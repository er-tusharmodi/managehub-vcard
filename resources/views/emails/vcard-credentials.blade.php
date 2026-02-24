<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>vCard Credentials</title>
    </head>
    <body style="font-family: Arial, sans-serif; background: #f6f7fb; padding: 24px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 24px;">
            <tr>
                <td>
                    <h2 style="margin-top: 0; color: #111827;">Welcome {{ $user->name }}</h2>
                    <p style="color: #4b5563;">Your vCard panel is ready. Use the credentials below to log in and edit your vCard data.</p>
                    <p style="color: #111827; margin-bottom: 6px;"><strong>Username:</strong> {{ $user->username }}</p>
                    <p style="color: #111827; margin-top: 0;"><strong>Password:</strong> {{ $password }}</p>
                    <p style="color: #4b5563;">vCard URL: <a href="{{ $vcardUrl }}">{{ $vcardUrl }}</a></p>
                    <p style="color: #4b5563;">Login URL: <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
                </td>
            </tr>
        </table>
    </body>
</html>
