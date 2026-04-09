<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .code-section {
            background-color: #f9f9f9;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 5px;
            font-family: monospace;
        }
        .expiry {
            color: #666;
            font-size: 14px;
            margin-top: 20px;
        }
        .message {
            color: #444;
            line-height: 1.6;
            margin: 20px 0;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #ddd;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verify Your Email Address</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hi {{ $user->name }},
            </div>
            
            <div class="message">
                Thank you for registering with <strong>Lost & Found Management System</strong>. 
                To complete your registration and verify your email address, please use the verification code below:
            </div>
            
            <div class="code-section">
                <div class="code">{{ $verificationCode }}</div>
                <div class="expiry">This code will expire in 15 minutes</div>
            </div>
            
            <div class="message">
                If you didn't create this account, please ignore this email. Your account will not be fully activated until your email is verified.
            </div>
            
            <div class="warning">
                <strong>Security Note:</strong> Never share this code with anyone. We will never ask you for this code via email or phone.
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Lost & Found Management System. All rights reserved.</p>
            <p>If you have any questions, please contact us at support@lostandfound.local</p>
        </div>
    </div>
</body>
</html>
