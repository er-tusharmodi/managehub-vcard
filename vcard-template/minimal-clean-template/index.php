<?php
/**
 * Minimal Clean VCard Template
 * 
 * A simple, minimalist template perfect for startups and creative professionals.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimal VCard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #ffffff; }
        .container { max-width: 500px; margin: 40px auto; padding: 30px; }
        .header { border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .name { font-size: 24px; font-weight: 700; margin-bottom: 5px; }
        .title { font-size: 13px; color: #666; letter-spacing: 0.5px; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #000; margin-bottom: 12px; letter-spacing: 1px; }
        .item { font-size: 13px; margin-bottom: 8px; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="name">[Name]</div>
            <div class="title">[Title]</div>
        </div>
        <div class="section">
            <div class="section-title">Information</div>
            <div class="item">📧 [email]</div>
            <div class="item">📞 [phone]</div>
            <div class="item">🔗 [website]</div>
        </div>
    </div>
</body>
</html>
