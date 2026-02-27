<?php
declare(strict_types=1);

function getIcon(string $name): string
{
    $icons = [
        "rating_star" => '<svg class="ic-sm" viewBox="0 0 24 24" fill="#f4c430" stroke="#f4c430" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
        "rating_users" => '<svg class="ic-sm" viewBox="0 0 24 24" stroke="rgba(255,255,255,.7)" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        "rating_clock" => '<svg class="ic-sm" viewBox="0 0 24 24" stroke="rgba(255,255,255,.7)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',

        "highlight_oven" => '<svg width="28" height="28" viewBox="0 0 64 64" fill="none" stroke="#c2562a" stroke-width="2" stroke-linecap="round"><path d="M32 8 Q28 18 34 22 Q30 16 32 8 Z M20 22 Q14 32 20 42 Q22 48 32 52 Q46 52 50 40 Q54 28 44 20 Q42 30 36 28 Q40 18 32 8 Q28 18 20 22 Z" fill="rgba(194,86,42,0.15)"/></svg>',
        "highlight_fresh" => '<svg width="28" height="28" viewBox="0 0 64 64" fill="none" stroke="#3a7a45" stroke-width="2" stroke-linecap="round"><path d="M32 52 L32 24"/><path d="M32 36 Q22 30 18 18 Q28 16 34 28" fill="rgba(58,122,69,0.15)"/><path d="M32 28 Q42 22 46 10 Q36 8 30 22" fill="rgba(58,122,69,0.12)"/></svg>',
        "highlight_wine" => '<svg width="28" height="28" viewBox="0 0 64 64" fill="none" stroke="#880e4f" stroke-width="2" stroke-linecap="round"><path d="M18 8 L18 26 Q18 42 32 42 Q46 42 46 26 L46 8 Z" fill="rgba(136,14,79,0.12)"/><line x1="32" y1="42" x2="32" y2="54"/><line x1="22" y1="54" x2="42" y2="54"/><path d="M18 24 Q32 30 46 24" fill="rgba(136,14,79,0.15)"/></svg>',

        "offer_brunch" => '<svg width="30" height="30" viewBox="0 0 64 64" fill="none" stroke="rgba(180,100,30,0.7)" stroke-width="2" stroke-linecap="round"><path d="M8 44 Q32 20 56 44"/><line x1="32" y1="14" x2="32" y2="8"/><line x1="14" y1="20" x2="10" y2="16"/><line x1="50" y1="20" x2="54" y2="16"/><line x1="8" y1="44" x2="56" y2="44"/></svg>',
        "offer_candle" => '<svg width="30" height="30" viewBox="0 0 64 64" fill="none" stroke="rgba(180,60,60,0.7)" stroke-width="2" stroke-linecap="round"><rect x="24" y="24" width="16" height="36" rx="2" fill="rgba(255,220,180,0.5)"/><path d="M32 24 Q30 18 32 12 Q34 18 32 24"/><line x1="24" y1="44" x2="40" y2="44"/></svg>',
        "offer_coffee" => '<svg width="30" height="30" viewBox="0 0 64 64" fill="none" stroke="rgba(30,100,60,0.7)" stroke-width="2" stroke-linecap="round"><path d="M10 24 L12 48 Q12 52 32 52 Q52 52 52 48 L54 24 Z" fill="rgba(200,240,210,0.4)"/><path d="M10 24 L54 24"/><path d="M54 30 Q62 30 62 38 Q62 46 54 46"/></svg>',
        "offer_cake" => '<svg width="30" height="30" viewBox="0 0 64 64" fill="none" stroke="rgba(100,30,120,0.7)" stroke-width="2" stroke-linecap="round"><rect x="8" y="32" width="48" height="24" rx="3" fill="rgba(230,200,240,0.4)"/><path d="M8 32 Q12 24 32 24 Q52 24 56 32" fill="rgba(230,200,240,0.3)"/><line x1="22" y1="24" x2="22" y2="32"/><line x1="32" y1="22" x2="32" y2="30"/><line x1="42" y1="24" x2="42" y2="32"/></svg>',

        "transport_metro" => '<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
        "transport_parking" => '<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
        "transport_taxi" => '<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>',
        "transport_delivery" => '<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/></svg>',

        "social_instagram" => '<svg class="ic" viewBox="0 0 24 24" stroke-width="1.8"><path d="M17 2H7a5 5 0 0 0-5 5v10a5 5 0 0 0 5 5h10a5 5 0 0 0 5-5V7a5 5 0 0 0-5-5z"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
        "social_whatsapp" => '<svg class="ic" viewBox="0 0 24 24" stroke-width="1.8"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>',
        "social_youtube" => '<svg class="ic" viewBox="0 0 24 24" stroke-width="1.8"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#fff" stroke="none"/></svg>',
        "social_facebook" => '<svg class="ic" viewBox="0 0 24 24" stroke-width="1.8"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',

        "payment_card" => '<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="1.8"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
        "payment_upi" => '<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>',
        "payment_wallet" => '<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="1.8"><rect x="1" y="4" width="22" height="16" rx="2"/><circle cx="8" cy="12" r="3" fill="none"/><circle cx="14" cy="12" r="3" fill="none"/></svg>',
        "payment_cash" => '<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="1.8"><path d="M17 9V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><rect x="9" y="11" width="12" height="10" rx="2"/><circle cx="15" cy="16" r="1"/></svg>',

        "source_google" => '<svg viewBox="0 0 24 24" width="12" height="12" fill="#4285f4" stroke="none"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>',
        "source_zomato" => '<svg viewBox="0 0 24 24" width="12" height="12" fill="#e53935" stroke="none"><circle cx="12" cy="12" r="12"/></svg>',

        "ui_arrow_right" => '<polyline points="9 18 15 12 9 6"/>',
        "ui_check" => '<polyline points="20 6 9 17 4 12"/>',
        "ui_star" => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
        "ui_cart" => '<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
    ];

    return $icons[$name] ?? "";
}