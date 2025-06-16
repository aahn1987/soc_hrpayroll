<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Evaluation Request</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
    <div
        style="background-color: white; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">New Evaluation Request</h2>
        <p style="font-size: 16px; color: #555;">From: <strong>{{ $data['fullname'] }}
                ({{ $data['soc_reference'] }})</strong></p>
        <p style="font-size: 16px; color: #555;">For Month: {{ $data['request_for_month'] }}</p>
        <p style="font-size: 16px; color: #555;">For Year: {{ $data['request_for_year'] }}</p>
        <p style="font-size: 16px; color: #555;">Objective:</p>
        <div style="background-color: #f0f0f0; padding: 10px; border-left: 4px solid #007BFF; margin-bottom: 20px;">
            {!! $objective_text !!}
        </div>
        <p style="text-align: center;">
            <a href="{{ $approveurl }}"
                style="background-color: #28a745; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px;">Approve
                Leave</a>
        </p>
        <p style="font-size: 14px; color: #999;">Regards,<br>SOC Team</p>
    </div>
</body>

</html>
