<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Password Reset</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
    <div
        style="background-color: white; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">Hello {{ $fullname }},</h2>
        <p style="font-size: 16px; color: #555;">
            You have requested to reset your password. Below is your new password:
        </p>
        <p
            style="font-size: 20px; font-weight: bold; color: #000; background-color: #f0f0f0; padding: 10px; display: inline-block;">
            {{ $password }}
        </p>
        <p style="font-size: 16px; color: #555;">
            Please log in using this password and change it as soon as possible from your profile settings.
        </p>
        <br>
        <p style="font-size: 14px; color: #999;">If you did not request this, please contact our team immediately.
        </p>
        <p style="font-size: 14px; color: #999;">Regards,<br>SOC Team</p>
    </div>
</body>

</html>