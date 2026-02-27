<?php
function get_icon($name, $class = "", $stroke = "currentColor")
{
    $icons = [
        "share" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}"><circle cx="18" cy="5" r="3" /><circle cx="6" cy="12" r="3" /><circle cx="18" cy="19" r="3" /><line x1="8.59" y1="13.51" x2="15.42" y2="17.49" /><line x1="15.41" y1="6.51" x2="8.59" y2="10.49" /></svg>',
        "save" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" /><polyline points="17 21 17 13 7 13 7 21" /><polyline points="7 3 7 8 15 8" /></svg>',
        "phone" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z" /></svg>',
        "whatsapp" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" /></svg>',
        "email" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" /><polyline points="22,6 12,13 2,6" /></svg>',
        "directions" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" /><circle cx="12" cy="10" r="3" /></svg>',
        "location" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" /><circle cx="12" cy="10" r="3" /></svg>',
        "globe" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><circle cx="12" cy="12" r="10" /><line x1="2" y1="12" x2="22" y2="12" /><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" /></svg>',
        "services" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" /></svg>',
        "products" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" /><line x1="3" y1="6" x2="21" y2="6" /><path d="M16 10a4 4 0 0 1-8 0" /></svg>',
        "gallery" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2" /><circle cx="8.5" cy="8.5" r="1.5" /><polyline points="21 15 16 10 5 21" /></svg>',
        "clock" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>',
        "qr" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><rect x="3" y="3" width="5" height="5" /><rect x="16" y="3" width="5" height="5" /><rect x="3" y="16" width="5" height="5" /><path d="M21 16h-3a2 2 0 0 0-2 2v3" /><path d="M21 21v.01" /><path d="M12 7v3a2 2 0 0 1-2 2H7" /><path d="M3 12h.01" /><path d="M12 3h.01" /><path d="M12 16v.01" /><path d="M16 12h1" /><path d="M21 12v.01" /><path d="M12 21v-1" /></svg>',
        "payment" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2" /><line x1="1" y1="10" x2="23" y2="10" /></svg>',
        "mail" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" /><polyline points="22,6 12,13 2,6" /></svg>',
        "map-arrow" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11" /></svg>',
        "info" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>',
        "download" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="7 10 12 15 17 10" /><line x1="12" y1="15" x2="12" y2="3" /></svg>',
        "link" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" /><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" /></svg>',
        "send" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13" /><polygon points="22 2 15 22 11 13 2 9 22 2" /></svg>',
        "check" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="1.8"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg>',
        "check-large" => '<svg class="{{class}}" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="1.8" style="display: block; margin: 0 auto 0.8rem"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg>',
        "reset" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><polyline points="1 4 1 10 7 10" /><path d="M3.51 15a9 9 0 1 0 .49-3.51" /></svg>',
        "cart" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><circle cx="9" cy="21" r="1" /><circle cx="20" cy="21" r="1" /><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" /></svg>',
        "cart-thin" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="1.5"><circle cx="9" cy="21" r="1" /><circle cx="20" cy="21" r="1" /><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" /></svg>',
        "close" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg>',
        "facebook" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" /></svg>',
        "instagram" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" /><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" /><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" /></svg>',
        "youtube" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z" /><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" /></svg>',
        "chevron-right" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2.5"><polyline points="9 18 15 12 9 6" /></svg>',
        "plus" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>',
        "minus" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12" /></svg>',
        "ui_arrow_right" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><polyline points="9 18 15 12 9 6" /></svg>',
        "ui_check" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><polyline points="20 6 9 17 4 12" /></svg>',
        "ui_star" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>',
        "ui_cart" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" /></svg>',
        "payment-upi" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2" /><line x1="12" y1="18" x2="12.01" y2="18" /></svg>',
        "payment-bank" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><line x1="3" y1="22" x2="21" y2="22" /><line x1="6" y1="18" x2="6" y2="11" /><line x1="10" y1="18" x2="10" y2="11" /><line x1="14" y1="18" x2="14" y2="11" /><line x1="18" y1="18" x2="18" y2="11" /><polygon points="12 2 20 7 4 7" /></svg>',
        "payment-cash" => '<svg class="{{class}}" viewBox="0 0 24 24" fill="none" stroke="{{stroke}}" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23" /><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" /></svg>',
    ];

    if (!isset($icons[$name])) {
        return "";
    }

    $svg = $icons[$name];
    $safeClass = $class !== "" ? htmlspecialchars($class, ENT_QUOTES, "UTF-8") : "";
    $safeStroke = htmlspecialchars($stroke, ENT_QUOTES, "UTF-8");

    $svg = str_replace("{{class}}", $safeClass, $svg);
    $svg = str_replace("{{stroke}}", $safeStroke, $svg);

    if ($safeClass === "") {
        $svg = str_replace(' class=""', "", $svg);
    }

    return $svg;
}
