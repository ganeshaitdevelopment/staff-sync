<!DOCTYPE html>
<html>
<head>
    <title>New Leave Request</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    
    <h2>New Leave Request Received 📝</h2>
    
    <p>Hello Admin,</p>
    
    <p>An employee has submitted a new leave request. Here are the details:</p>
    
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr style="background-color: #f8f9fa;">
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Employee Name</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $leave->user->name }}</td>
        </tr>
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

    <p>Please login to the StaffSync dashboard to Approve or Reject this request.</p>
    
    <p>Thank you,<br>StaffSync System</p>

</body>
</html>