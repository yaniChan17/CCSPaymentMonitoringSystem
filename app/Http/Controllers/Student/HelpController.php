<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Display the help center.
     */
    public function index()
    {
        $faqs = [
            [
                'category' => 'Payments',
                'questions' => [
                    [
                        'question' => 'How do I make a payment?',
                        'answer' => 'Contact your block treasurer to record your payment. You can find your treasurer\'s contact information on your dashboard. Payments can be made via cash, GCash, Maya, or other approved methods.'
                    ],
                    [
                        'question' => 'When is the payment deadline?',
                        'answer' => 'Payment deadlines are displayed on your dashboard under "Active Collection Period". Make sure to pay before the due date to avoid late fees.'
                    ],
                    [
                        'question' => 'Can I pay in installments?',
                        'answer' => 'Yes, you can make partial payments. Your treasurer will record each payment, and the remaining balance will be displayed on your dashboard.'
                    ],
                ],
            ],
            [
                'category' => 'Account & Profile',
                'questions' => [
                    [
                        'question' => 'How do I update my profile information?',
                        'answer' => 'Click on your profile icon in the top right corner and select "Profile Settings". You can update your email, contact number, and other personal information there.'
                    ],
                    [
                        'question' => 'I forgot my password. What should I do?',
                        'answer' => 'Click on "Forgot Password?" on the login page. Enter your email address and you\'ll receive instructions to reset your password.'
                    ],
                ],
            ],
            [
                'category' => 'Receipts & Records',
                'questions' => [
                    [
                        'question' => 'How do I download my payment receipt?',
                        'answer' => 'Go to your Payment History on the dashboard and click the "Receipt" button next to any payment. This will download a PDF receipt for your records.'
                    ],
                    [
                        'question' => 'Where can I see my payment history?',
                        'answer' => 'Your complete payment history is displayed on your dashboard under the "Payment History" section. You can see all your past payments, amounts, and dates.'
                    ],
                ],
            ],
        ];

        return view('student.help.index', compact('faqs'));
    }

    /**
     * Display the contact support page.
     */
    public function contact()
    {
        return view('student.help.contact');
    }
}
