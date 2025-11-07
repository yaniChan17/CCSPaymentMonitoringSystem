<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PaymentController extends Controller
{
    /**
     * Display a listing of all payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'recordedBy']);

        // Search by student name or ID (FIRST)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }

        // Filter by block
        if ($request->filled('block')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('block', $request->block);
            });
        }

        // Filter by year level
        if ($request->filled('year_level')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('year_level', $request->year_level);
            });
        }

        // Filter by course
        if ($request->filled('course')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('course', $request->course);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'payment_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $payments = $query->paginate(20);

        // Statistics - apply same filters
        $statsQuery = Payment::query();

        if ($request->filled('block')) {
            $statsQuery->whereHas('student', function ($q) use ($request) {
                $q->where('block', $request->block);
            });
        }

        if ($request->filled('year_level')) {
            $statsQuery->whereHas('student', function ($q) use ($request) {
                $q->where('year_level', $request->year_level);
            });
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'total_amount' => (clone $statsQuery)->where('status', 'paid')->sum('amount'),
            'pending_count' => (clone $statsQuery)->where('status', 'pending')->count(),
            'pending_amount' => (clone $statsQuery)->where('status', 'pending')->sum('amount'),
            'today' => (clone $statsQuery)->where('status', 'paid')
                ->whereDate('payment_date', today())
                ->sum('amount'),
        ];

        // Get available blocks, year levels, and courses for filters
        $blocks = Student::whereNotNull('block')->distinct()->pluck('block')->sort();
        $yearLevels = Student::distinct()->pluck('year_level')->sort();
        $courses = Student::distinct()->pluck('course')->sort();

        return view('admin.payments.index', compact('payments', 'stats', 'blocks', 'yearLevels', 'courses'));
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['student', 'recordedBy']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        $payment->load('student');
        $students = Student::where('status', 'active')->orderBy('full_name')->get();
        $treasurers = User::where('role', 'treasurer')->orderBy('name')->get();

        return view('admin.payments.edit', compact('payment', 'students', 'treasurers'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,check,bank_transfer,online'],
            'status' => ['required', 'in:paid,pending,cancelled'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully!');
    }

    /**
     * Export payments to Excel.
     */
    public function export(Request $request)
    {
        try {
            $query = Payment::with(['student', 'recordedBy']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('student_id', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                });
            }

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

            if ($request->filled('course')) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('course', $request->course);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('payment_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('payment_date', '<=', $request->date_to);
            }

            $payments = $query->orderBy('payment_date', 'desc')->get();

            // Create new Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = [
                'Payment ID',
                'Payment Date',
                'Student Number',
                'Student Name',
                'Course',
                'Year Level',
                'Block Number',
                'Amount',
                'Payment Type',
                'Status',
                'Reference Number',
                'Recorded By',
                'Notes',
            ];

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col.'1', $header);
                $sheet->getStyle($col.'1')->getFont()->setBold(true);
                $col++;
            }

            // Add data
            $row = 2;
            foreach ($payments as $payment) {
                $sheet->setCellValue('A'.$row, $payment->id);
                $sheet->setCellValue('B'.$row, $payment->payment_date->format('Y-m-d'));
                $sheet->setCellValue('C'.$row, $payment->student->student_id ?? 'N/A');
                $sheet->setCellValue('D'.$row, $payment->student->full_name ?? 'N/A');
                $sheet->setCellValue('E'.$row, $payment->student->course ?? 'N/A');
                $sheet->setCellValue('F'.$row, $payment->student->year_level ?? 'N/A');
                $sheet->setCellValue('G'.$row, $payment->student->block ?? 'N/A');
                $sheet->setCellValue('H'.$row, number_format($payment->amount, 2));
                $sheet->setCellValue('I'.$row, ucfirst(str_replace('_', ' ', $payment->payment_method)));
                $sheet->setCellValue('J'.$row, ucfirst($payment->status));
                $sheet->setCellValue('K'.$row, $payment->reference_number ?? 'N/A');
                $sheet->setCellValue('L'.$row, $payment->recordedBy->name ?? 'System');
                $sheet->setCellValue('M'.$row, $payment->notes ?? '');
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'M') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Generate filename
            $filename = 'payments_export_'.now()->format('Y-m-d_His').'.xlsx';

            // Create writer and output
            $writer = new Xlsx($spreadsheet);

            // Set headers for download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return redirect()->route('admin.payments.index')
                ->with('error', 'Export failed: '.$e->getMessage());
        }
    }
}
