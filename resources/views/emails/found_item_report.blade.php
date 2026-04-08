<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Sora', sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; margin: 0; padding: 0; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .email-header { background: linear-gradient(135deg, #10a6c6 0%, #3f51d9 100%); color: white; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .email-body { padding: 30px 20px; }
        .email-body p { margin: 0 0 16px; font-size: 15px; line-height: 1.8; }
        .details-box { background: #f9f9f9; padding: 20px; border-radius: 10px; border-left: 4px solid #10a6c6; margin: 20px 0; }
        .email-footer { background: #f5f5f5; padding: 20px; text-align: center; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🤝 Found Item Report Received</h1>
        </div>
        <div class="email-body">
            <p>Hi {{ $report->reporter_name }},</p>
            <p>Thank you for submitting a found item report. Your honesty is highly appreciated!</p>
            <div class="details-box">
                <p><strong>Item Details:</strong> {{ $report->title }}</p>
                <p><strong>Category:</strong> {{ $report->category }}</p>
                <p><strong>Location:</strong> {{ $report->location }}</p>
            </div>
            <p>Our admin might review this report if necessary before making it visible to everyone. The system will alert potential owners about this item.</p>
            <p>Best regards,<br><strong>Lost & Found Management System Team</strong></p>
        </div>
        <div class="email-footer">
            <p>© {{ date('Y') }} Lost & Found Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
