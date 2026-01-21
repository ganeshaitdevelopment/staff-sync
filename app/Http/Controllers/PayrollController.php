<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf; // <-- Panggil Library PDF
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function download(Payroll $payroll)
    {
        if (auth()->user()->role === 'user' && $payroll->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this payslip.');
        }
        
        $pdf = Pdf::loadView('pdf.payslip', [
            'payroll' => $payroll,
            'user' => $payroll->user
        ]);

        // 3. Download file dengan nama otomatis
        $filename = 'Payslip-' . $payroll->user->name . '-' . $payroll->month . '.pdf';
        
        return $pdf->download($filename);
    }
}