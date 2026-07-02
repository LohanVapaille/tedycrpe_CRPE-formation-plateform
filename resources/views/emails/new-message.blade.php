<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body style="font-family: sans-serif; color: #333; line-height: 1.6;">
    <h2>Nouveau message reçu sur votre site</h2>
    <hr>
    <p><strong>De :</strong> {{ $contactMessage->email }}</p>
    <p><strong>Message :</strong></p>
    <div style="background: #f4f4f4; padding: 15px; border-radius: 5px; border-left: 4px solid #386b73;">
        {{ $contactMessage->message }}
    </div>
    <br>
    <p><a href="{{ route('admin.messages.index') }}"
            style="background: #386b73; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">Voir
            sur le dashboard</a></p>
</body>

</html>