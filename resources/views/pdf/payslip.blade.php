<!DOCTYPE html>
<html>
<head>
    <title>Payslip</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0; font-size: 12px; color: #666; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .label { font-weight: bold; width: 150px; }

        .salary-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .salary-table th, .salary-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .salary-table th { background-color: #f4f4f4; }
        .amount { text-align: right; font-family: monospace; font-size: 14px; }
        
        .total-row { background-color: #eee; font-weight: bold; }
        .footer { margin-top: 50px; text-align: right; font-size: 12px; }
        .sign { margin-top: 40px; border-top: 1px solid #333; width: 200px; display: inline-block; text-align: center; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>StaffSync Inc.</h1>
        <p>Official Monthly Payslip</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Employee Name:</td>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <td class="label">Position:</td>
            <td>{{ $user->position->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Period:</td>
            <td>{{ \Carbon\Carbon::createFromFormat('m-Y', $payroll->month)->format('F Y') }}</td>
        </tr>
    </table>

    <table class="salary-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount (IDR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td class="amount">{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Overtime Bonus</td>
                <td class="amount">{{ number_format($payroll->overtime_salary, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL TAKE HOME PAY</td>
                <td class="amount">Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Authorized Signature</p>
        <br><br>
        <div class="sign">HR Manager</div>
    </div>

</body>
</html>