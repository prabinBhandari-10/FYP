<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Sora', sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; margin: 0; padding: 0; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .email-header { background: linear-gradient(135deg, #3f51d9 0%, #10a6c6 100%); color: white; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .email-body { padding: 30px 20px; }
        .email-body p { margin: 0 0 16px; font-size: 15px; line-height: 1.8; }
        .details-box { background: #f9f9f9; padding: 20px; border-radius: 10px; border-left: 4px solid #3f51d9; margin: 20px 0; }
        .email-footer { background: #f5f5f5; padding: 20px; text-align: center; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📝 Lost Item Report Received</h1>
        </div>
        <div class="email-body">
            <p>Hi {{ $report->reporter_name }},</p>
            <p>Thank you for submitting a lost item report. We have successfully received it.</p>
            <div class="details-box">
                <p><strong>Item Title:</strong> {{ $report->title }}</p>
                <p><strong>Category:</strong> {{ $report->category }}</p>
                <p><strong>Location:</strong> {{ $report->location }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($report->date)->format('F j, Y') }}</p>
            </div>
            <p>The system will actively try to match it with found items, and we will notify you if a possible match is discovered!</p>
            <p>Best regards,<br><strong>Lost & Found Management System Team</strong></p>
        </div>
        <div class="email-footer">
            <p>© {{ date('Y') }} Lost & Found Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
