<?php
/**
 * Modern Professional VCard Template
 * 
 * This is a professional template suitable for corporate executives,
 * consultants, and business professionals.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional VCard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; }
        .name { font-size: 28px; font-weight: bold; margin-bottom: 5px; }
        .title { font-size: 14px; opacity: 0.9; }
        .content { padding: 30px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 12px; color: #667eea; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 1px; }
        .item { margin-bottom: 10px; font-size: 14px; }
        .label { font-weight: 600; color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="name">[Name]</div>
            <div class="title">[Title]</div>
        </div>
        <div class="content">
            <div class="section">
                <div class="section-title">Contact</div>
                <div class="item"><span class="label">Email:</span> [email]</div>
                <div class="item"><span class="label">Phone:</span> [phone]</div>
                <div class="item"><span class="label">Website:</span> [website]</div>
            </div>
        </div>
    </div>
</body>
</html>
