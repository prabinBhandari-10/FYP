<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Sora', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #3f51d9 0%, #10a6c6 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-body {
            padding: 30px 20px;
        }
        .email-body p {
            margin: 0 0 16px;
            font-size: 15px;
            line-height: 1.8;
        }
        .notification-content {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #3f51d9;
            margin: 20px 0;
        }
        .notification-content h2 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #3f51d9;
        }
        .notification-content p {
            margin: 0 0 8px;
            font-size: 14px;
            color: #666;
        }
        .cta-button {
            display: inline-block;
            background: #3f51d9;
            color: white !important;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
        }
        .cta-button:hover {
            background: #2d3ab3;
        }
        .email-footer {
            background: #f5f5f5;
            padding: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .email-footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🔔 FYP Lost & Found</h1>
        </div>

        <div class="email-body">
            <p>Hi {{ $user->name }},</p>

            <div class="notification-content">
                <h2>{{ $notification->title }}</h2>
                <p>{{ $notification->message }}</p>

                @if ($data && is_object($data))
                    @if (isset($data->title))
                        <p><strong>Item:</strong> {{ $data->title }}</p>
                    @endif
                    @if (isset($data->category))
                        <p><strong>Category:</strong> {{ $data->category }}</p>
                    @endif
                    @if (isset($data->location))
                        <p><strong>Location:</strong> {{ $data->location }}</p>
                    @endif
                @endif
            </div>

            <p>
                <a href="{{ route('home') }}" class="cta-button">View Details</a>
            </p>

            <p>Thank you for using FYP Lost & Found System. If you have any questions, please contact us.</p>

            <p>
                Best regards,<br>
                <strong>FYP Lost & Found Team</strong>
            </p>
        </div>

        <div class="email-footer">
            <p>© {{ now()->year }} FYP Lost & Found System. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
