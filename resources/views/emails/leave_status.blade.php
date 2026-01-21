<!DOCTYPE html>
<html>
<head>
    <title>Leave Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    
    <h2>Leave Request Update 🔔</h2>
    
    <p>Hello {{ $leave->user->name }},</p>
    
    <p>Your leave request has been processed. Here is the status:</p>
    
    <div style="padding: 15px; border-radius: 5px; background-color: {{ $leave->status == 'approved' ? '#d1fae5' : '#fee2e2' }}; color: {{ $leave->status == 'approved' ? '#065f46' : '#991b1b' }}; text-align: center; font-weight: bold; font-size: 18px; margin: 20px 0;">
        {{ strtoupper($leave->status) }}
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr style="background-color: #f8f9fa;">
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Start Date</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $leave->start_date }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>End Date</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $leave->end_date }}</td>
        </tr>
        <tr style="background-color: #f8f9fa;">
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Reason</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $leave->reason }}</td>
        </tr>
    </table>

    <p>Thank you,<br>HRD Team - StaffSync</p>

</body>
</html>