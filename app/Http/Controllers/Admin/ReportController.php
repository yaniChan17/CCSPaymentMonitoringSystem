<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * Display the reports page.
     */
    public function index()
    {
        $stats = [
            'total_students' => Student::where('status', 'active')->count(),
            'total_payments' => Payment::count(),
            'total_amount' => Payment::where('status', 'paid')->sum('amount'),
            'paid_count' => Payment::where('status', 'paid')->count(),
            'pending_count' => Payment::where('status', 'pending')->count(),
            'pending_amount' => Payment::where('status', 'pending')->sum('amount'),
            'this_month_collection' => Payment::where('status', 'paid')
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
        ];

        // Recent payments for preview
        $recentPayments = Payment::with(['student', 'recordedBy'])
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();

        // Payment by month (last 6 months)
        $monthlyData = Payment::selectRaw('
                MONTH(payment_date) as month,
                YEAR(payment_date) as year,
                COUNT(*) as payment_count,
                SUM(amount) as total
            ')
            ->where('status', 'paid')
            ->where('payment_date', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                $item->month_name = \Carbon\Carbon::create($item->year, $item->month, 1)->format('M Y');
                return $item;
            });

        // Use snake_case variable names for the view
        $recent_payments = $recentPayments;
        $monthly_data = $monthlyData;

        return view('admin.reports.index', compact('stats', 'recent_payments', 'monthly_data'));
    }

    /**
     * Export payments report to CSV.
     */
    public function exportPayments(Request $request)
    {
        $query = Payment::with(['student', 'recordedBy']);

        // Apply filters
        if ($request->filled('block')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('block', $request->block);
            });
        }
        
        if ($request->filled('year_level')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('year_level', $request->year_level);
            });
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('payment_date', 'desc')->get();

        // Build filename with filter information
        $filenameParts = ['payments'];
        
        if ($request->filled('block')) {
            $filenameParts[] = 'block_' . strtolower(str_replace(' ', '_', $request->block));
        }
        
        if ($request->filled('year_level')) {
            $yearLevel = strtolower(str_replace(['st', 'nd', 'rd', 'th', ' '], ['', '', '', '', '_'], $request->year_level));
            $filenameParts[] = 'year_' . $yearLevel;
        }
        
        $filenameParts[] = now()->format('Y-m-d_His');
        $filename = implode('_', $filenameParts) . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Payment ID',
                'Date',
                'Student ID',
                'Student Name',
                'Year Level',
                'Block',
                'Amount',
                'Payment Method',
                'Status',
                'Reference Number',
                'Recorded By',
                'Notes'
            ]);

            // CSV Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->payment_date->format('Y-m-d'),
                    $payment->student->student_id ?? 'N/A',
                    $payment->student->full_name ?? 'N/A',
                    $payment->student->year_level ?? 'N/A',
                    $payment->student->block ?? 'N/A',
                    number_format($payment->amount, 2),
                    ucfirst(str_replace('_', ' ', $payment->payment_method)),
                    ucfirst($payment->status),
                    $payment->reference_number ?? 'N/A',
                    $payment->recordedBy->name ?? 'System',
                    $payment->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export students report to CSV.
     */
    public function exportStudents(Request $request)
    {
        $query = Student::with(['payments']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('course')) {
            $query->where('course', $request->course);
        }

        $students = $query->orderBy('full_name')->get();

        $filename = 'students_report_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Student ID',
                'Full Name',
                'Email',
                'Course',
                'Year Level',
                'Status',
                'Total Payments',
                'Total Paid Amount',
                'Pending Amount',
                'Outstanding Balance'
            ]);

            // CSV Data
            foreach ($students as $student) {
                $totalPaid = $student->payments->where('status', 'paid')->sum('amount');
                $pendingAmount = $student->payments->where('status', 'pending')->sum('amount');
                
                fputcsv($file, [
                    $student->student_id,
                    $student->full_name,
                    $student->email,
                    $student->course,
                    $student->year_level,
                    ucfirst($student->status),
                    $student->payments->count(),
                    number_format($totalPaid, 2),
                    number_format($pendingAmount, 2),
                    number_format($student->balance ?? 0, 2)
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Generate summary report.
     */
    public function summary(Request $request)
    {
        $query = Payment::query();

        // Apply date filters
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Summary statistics
        $summary = [
            'total_payments' => (clone $query)->count(),
            'total_amount' => (clone $query)->where('status', 'paid')->sum('amount'),
            'pending_amount' => (clone $query)->where('status', 'pending')->sum('amount'),
        ];

        // Payment methods breakdown
        $payment_methods = (clone $query)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->where('status', 'paid')
            ->groupBy('payment_method')
            ->get();

        // Top collectors
        $top_collectors = (clone $query)
            ->selectRaw('recorded_by, COUNT(*) as payment_count, SUM(amount) as total_collected')
            ->where('status', 'paid')
            ->whereNotNull('recorded_by')
            ->groupBy('recorded_by')
            ->orderBy('total_collected', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $item->treasurer = User::find($item->recorded_by);
                return $item;
            });

        // Status breakdown
        $status_breakdown = [
            'paid_count' => (clone $query)->where('status', 'paid')->count(),
            'paid_amount' => (clone $query)->where('status', 'paid')->sum('amount'),
            'pending_count' => (clone $query)->where('status', 'pending')->count(),
            'pending_amount' => (clone $query)->where('status', 'pending')->sum('amount'),
            'cancelled_count' => (clone $query)->where('status', 'cancelled')->count(),
            'cancelled_amount' => (clone $query)->where('status', 'cancelled')->sum('amount'),
        ];

        return view('admin.reports.summary', compact('summary', 'payment_methods', 'top_collectors', 'status_breakdown'));
    }

    /**
     * Export summary report to CSV.
     */
    public function exportSummary(Request $request)
    {
        $query = Payment::with(['student', 'recordedBy']);

        // Apply date filters
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->orderBy('payment_date', 'desc')->get();

        // Calculate summary statistics
        $totalPaid = $payments->where('status', 'paid')->sum('amount');
        $totalPending = $payments->where('status', 'pending')->sum('amount');
        $totalCancelled = $payments->where('status', 'cancelled')->sum('amount');

        $filename = 'summary_report_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($payments, $totalPaid, $totalPending, $totalCancelled, $request) {
            $file = fopen('php://output', 'w');

            // Add summary header
            fputcsv($file, ['SUMMARY REPORT']);
            fputcsv($file, ['Generated:', now()->format('Y-m-d H:i:s')]);
            
            if ($request->filled('date_from') && $request->filled('date_to')) {
                fputcsv($file, ['Period:', $request->date_from . ' to ' . $request->date_to]);
            } elseif ($request->filled('date_from')) {
                fputcsv($file, ['Period:', 'From ' . $request->date_from]);
            } elseif ($request->filled('date_to')) {
                fputcsv($file, ['Period:', 'Until ' . $request->date_to]);
            } else {
                fputcsv($file, ['Period:', 'All Time']);
            }
            
            fputcsv($file, []);
            
            // Summary statistics
            fputcsv($file, ['SUMMARY STATISTICS']);
            fputcsv($file, ['Total Payments:', $payments->count()]);
            fputcsv($file, ['Paid Amount:', number_format($totalPaid, 2)]);
            fputcsv($file, ['Pending Amount:', number_format($totalPending, 2)]);
            fputcsv($file, ['Cancelled Amount:', number_format($totalCancelled, 2)]);
            fputcsv($file, []);

            // Payment details header
            fputcsv($file, ['PAYMENT DETAILS']);
            fputcsv($file, [
                'ID',
                'Date',
                'Student ID',
                'Student Name',
                'Amount',
                'Payment Method',
                'Status',
                'Reference Number',
                'Recorded By',
                'Notes'
            ]);

            // Payment details
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->payment_date->format('Y-m-d'),
                    $payment->student->student_id ?? 'N/A',
                    $payment->student->full_name ?? 'N/A',
                    number_format($payment->amount, 2),
                    ucfirst(str_replace('_', ' ', $payment->payment_method)),
                    ucfirst($payment->status),
                    $payment->reference_number ?? 'N/A',
                    $payment->recordedBy->name ?? 'System',
                    $payment->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
