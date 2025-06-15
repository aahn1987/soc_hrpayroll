<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Objective Update Notification</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
    <div
        style="background-color: white; padding: 25px 30px; border-radius: 8px; max-width: 650px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">

        <h2 style="color: #333; margin-bottom: 20px;">New Objective Update Request</h2>

        <p style="font-size: 16px; color: #555; line-height: 1.6;">
            Hello {{ $supervisor_name }},
        </p>

        <p style="font-size: 16px; color: #555; line-height: 1.6;">
            You have received an objective update request from:
        </p>

        <p style="font-size: 16px; color: #000;">
            <strong>{{ $fullname }}</strong> ({{ $soc_reference }})
        </p>

        <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">

        <h3 style="font-size: 18px; color: #333;">Objective Content:</h3>
        <div
            style="font-size: 15px; color: #444; background-color: #f9f9f9; padding: 15px; border-left: 4px solid #3490dc; margin-bottom: 20px;">
            {!! $objective_text !!}
        </div>

        <p style="font-size: 16px; color: #555;">
            To review and respond to this objective update, please click the button below:
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $objlink }}"
                style="background-color: #3490dc; color: white; padding: 12px 24px; border-radius: 5px; text-decoration: none; font-size: 16px;">
                Review Objective
            </a>
        </div>


        <p style="font-size: 14px; color: #999;">
            Regards,<br>
            <strong>SOC Team</strong>
        </p>
    </div>
</body>

</html>
