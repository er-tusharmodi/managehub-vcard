<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $vcardName }} - vCard Editor</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background: #f8fafc; }
        .container { display: flex; flex-direction: column; height: 100vh; }
        .header { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 16px 24px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
        .header h1 { font-size: 18px; font-weight: 600; color: #0f172a; margin: 0; }
        .header p { font-size: 13px; color: #64748b; margin: 4px 0 0 0; }
        .iframe-container { flex: 1; overflow: hidden; }
        iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $vcardName }}'s vCard Editor</h1>
            <p>Logged in as: {{ Auth::user()->name ?? 'User' }}</p>
        </div>
        <div class="iframe-container">
            <iframe src="{{ $iframeUrl }}" title="vCard Editor"></iframe>
        </div>
    </div>
</body>
</html>