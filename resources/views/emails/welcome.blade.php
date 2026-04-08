<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Sora', sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; margin: 0; padding: 0; }
        .email-container { max-width: 600px; margin: 20px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .email-header { background: linear-gradient(135deg, #10a6c6 0%, #3f51d9 100%); color: white; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 28px; font-weight: 700; }
        .email-body { padding: 30px 20px; }
        .email-body p { margin: 0 0 16px; font-size: 15px; line-height: 1.8; }
        .cta-button { display: inline-block; background: #3f51d9; color: white !important; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; margin: 20px 0; }
        .email-footer { background: #f5f5f5; padding: 20px; text-align: center; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🎉 Welcome Aboard!</h1>
        </div>
        <div class="email-body">
            <p>Hi {{ $user->name }},</p>
            <p>Welcome to the Lost & Found Management System!</p>
            <p>We're thrilled to have you here. Our platform helps you securely report lost items and confidently find matches for found belongings.</p>
            <p>You can get started by browsing available items or making your first report right away.</p>
            <p>
                <a href="{{ url('/') }}" class="cta-button">Go to your Dashboard</a>
            </p>
            <p>Best regards,<br><strong>Lost & Found Management System Team</strong></p>
        </div>
        <div class="email-footer">
            <p>© {{ date('Y') }} Lost & Found Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
