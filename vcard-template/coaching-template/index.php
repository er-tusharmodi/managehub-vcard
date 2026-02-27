<?php

declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function v(array $data, string $path, mixed $default = ''): mixed
{
    $segments = explode('.', $path);
    $current = $data;

    foreach ($segments as $segment) {
        if (!is_array($current) || !array_key_exists($segment, $current)) {
            return $default;
        }
        $current = $current[$segment];
    }

    return $current;
}

function a(array $data, string $path): array
{
    $value = v($data, $path, []);
    return is_array($value) ? $value : [];
}

$jsonPath = __DIR__ . '/default.json';

try {
    if (!is_file($jsonPath)) {
        throw new RuntimeException('default.json file not found.');
    }

    $jsonRaw = file_get_contents($jsonPath);
    if ($jsonRaw === false) {
        throw new RuntimeException('Unable to read default.json file.');
    }

    $data = json_decode($jsonRaw, true, 512, JSON_THROW_ON_ERROR);
    if (!is_array($data)) {
        throw new RuntimeException('default.json must decode to an object.');
    }
} catch (Throwable $exception) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Configuration error: ' . $exception->getMessage();
    exit;
}

$actionData = [
    'shop' => [
        'name' => (string) v($data, 'shop.name'),
        'tagline' => (string) v($data, 'shop.tagline'),
        'phone' => (string) v($data, 'shop.phone'),
        'whatsapp' => (string) v($data, 'shop.whatsapp'),
        'email' => (string) v($data, 'shop.email'),
        'address' => (string) v($data, 'shop.address'),
        'maps' => (string) v($data, 'shop.maps'),
        'website' => (string) v($data, 'shop.website'),
        'registrationId' => (string) v($data, 'shop.registrationId'),
        'vcardFileName' => (string) v($data, 'shop.vcardFileName'),
        'qrFileName' => (string) v($data, 'shop.qrFileName'),
        'established' => (string) v($data, 'shop.established'),
    ],
    'messages' => [
        'waEnquiry' => (string) v($data, 'messages.waEnquiry'),
        'shareText' => (string) v($data, 'messages.shareText'),
        'demoHeader' => (string) v($data, 'messages.demoHeader'),
        'demoConfirm' => (string) v($data, 'messages.demoConfirm'),
    ],
    'toast' => a($data, 'toast'),
    'counters' => a($data, 'counters'),
    'demo' => a($data, 'demo'),
    'promo' => a($data, 'promo'),
];

$actionDataJson = json_encode(
    $actionData,
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
);

if ($actionDataJson === false) {
    $actionDataJson = '{}';
}

$socialIconMap = [
  'ic-wa' => 'bi-whatsapp',
  'ic-yt' => 'bi-youtube',
  'ic-tg' => 'bi-telegram',
  'ic-ig' => 'bi-instagram',
  'ic-fb' => 'bi-facebook',
  'ic-web' => 'bi-globe',
];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title><?= e(v($data, 'meta.title')) ?></title>
    <meta name="description" content="<?= e(v($data, 'meta.description')) ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= e(v($data, 'meta.ogTitle')) ?>" />
    <meta property="og:description" content="<?= e(v($data, 'meta.ogDescription')) ?>" />
    <meta property="og:url" content="<?= e(v($data, 'meta.ogUrl')) ?>" />
    <meta property="og:image" content="<?= e(v($data, 'meta.ogImage')) ?>" />
    <meta name="twitter:card" content="<?= e(v($data, 'meta.twitterCard')) ?>" />
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>

    <!-- ══════════════════════════════════════════════════
         1. BANNER
    ══════════════════════════════════════════════════ -->
    <div class="banner">
      <div class="banner-bg">
        <div class="banner-pattern"></div>
        <div class="banner-shapes">
          <div class="bshape" style="width:90px;height:90px;top:-20px;left:-20px;animation-delay:0s;"></div>
          <div class="bshape" style="width:60px;height:60px;top:30px;right:10px;animation-delay:1.5s;"></div>
          <div class="bshape" style="width:40px;height:40px;bottom:40px;left:60px;animation-delay:0.8s;"></div>
        </div>
        <div class="banner-shapes">
          <div class="star" style="top:14%;left:8%;animation-delay:0s"></div>
          <div class="star" style="top:30%;left:25%;animation-delay:.4s"></div>
          <div class="star" style="top:10%;left:55%;animation-delay:.8s"></div>
          <div class="star" style="top:50%;left:72%;animation-delay:.2s"></div>
          <div class="star" style="top:25%;left:88%;animation-delay:1.1s"></div>
          <div class="star" style="top:65%;left:42%;animation-delay:.6s"></div>
          <div class="star" style="top:18%;left:96%;animation-delay:1.4s"></div>
          <div class="star" style="top:75%;left:15%;animation-delay:.9s"></div>
          <div class="star" style="top:55%;left:35%;animation-delay:1.7s"></div>
        </div>
        <div class="banner-center">
          <div class="banner-tagline">🎯 <?= e(v($data, 'banner.tagline')) ?></div>
          <div class="banner-sub"><?= e(v($data, 'banner.sub')) ?></div>
        </div>
        <div class="banner-wave">
          <svg viewBox="0 0 480 52" fill="none" preserveAspectRatio="none">
            <path d="M0,32 C80,52 160,12 240,32 C320,52 400,16 480,32 L480,52 L0,52 Z" fill="#f1f5f9"/>
          </svg>
        </div>
      </div>
      <div class="banner-top-bar">
        <button class="share-btn" onclick="openShare()">
          <i class="bi bi-share-fill"></i>
          <span><?= e(v($data, 'profile.actions.share')) ?></span>
        </button>
        <div class="verified-badge">
          <i class="bi bi-patch-check"></i>
          <?= e(v($data, 'trust.items.0.text', 'Govt. Registered')) ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         2. STATUS BAR
    ══════════════════════════════════════════════════ -->
    <div class="status-bar">
      <div class="status-open">
        <div class="dot-pulse"></div>
        <?= e(v($data, 'status.openText')) ?>
      </div>
      <div class="next-batch">⚡ <?= e(v($data, 'status.nextBatch')) ?></div>
    </div>

    <!-- ══════════════════════════════════════════════════
         3. PROFILE CARD
    ══════════════════════════════════════════════════ -->
    <div class="profile-card">
      <div class="profile-logo-wrap">
        <div class="profile-logo">
          <?php if (!empty(v($data, 'profile.logoImageUrl'))): ?>
            <img src="<?= e(v($data, 'profile.logoImageUrl')) ?>" alt="<?= e(v($data, 'profile.name')) ?>" />
          <?php else: ?>
            <i class="bi <?= e(v($data, 'profile.logoIconClass')) ?>"></i>
          <?php endif; ?>
        </div>
        <span class="est-tag"><?= e(v($data, 'profile.estTag')) ?></span>
      </div>
      <div class="profile-name"><?= e(v($data, 'profile.name')) ?></div>
      <div class="profile-role"><?= e(v($data, 'profile.role')) ?></div>
      <div class="profile-qual"><?= e(v($data, 'profile.qual')) ?></div>

      <div class="profile-stats">
        <?php foreach (a($data, 'stats') as $index => $stat): ?>
          <?php if (!is_array($stat)) { continue; } ?>
          <div class="pstat">
            <div class="pstat-num" id="<?= e($stat['id'] ?? '') ?>"><?= e($stat['static'] ?? '0') ?></div>
            <div class="pstat-lbl"><?= e($stat['label'] ?? '') ?></div>
          </div>
          <?php if ($index < count(a($data, 'stats')) - 1): ?>
            <div class="stat-div"></div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <div class="profile-action-btns">
        <button class="pab call" onclick="callInstitute()">
          <i class="bi bi-telephone-fill"></i>
          <span><?= e(v($data, 'profile.actions.call')) ?></span>
        </button>
        <button class="pab whatsapp" onclick="openWA()">
          <i class="bi bi-whatsapp"></i>
          <span><?= e(v($data, 'profile.actions.whatsapp')) ?></span>
        </button>
        <button class="pab save" onclick="saveContact()">
          <i class="bi bi-person-vcard-fill"></i>
          <span><?= e(v($data, 'profile.actions.save')) ?></span>
        </button>
        <button class="pab email" onclick="emailInstitute()">
          <i class="bi bi-envelope-fill"></i>
          <span><?= e(v($data, 'profile.actions.email')) ?></span>
        </button>
        <button class="pab direction" onclick="openMaps()">
          <i class="bi bi-geo-alt-fill"></i>
          <span><?= e(v($data, 'profile.actions.directions')) ?></span>
        </button>
        <button class="pab share" onclick="openShare()">
          <i class="bi bi-share-fill"></i>
          <span><?= e(v($data, 'profile.actions.share')) ?></span>
        </button>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         4. TRUST SIGNALS
    ══════════════════════════════════════════════════ -->
    <div class="trust-strip">
      <?php foreach (a($data, 'trust.items') as $item): ?>
        <?php if (!is_array($item)) { continue; } ?>
        <div class="trust-item"><i class="bi <?= e($item['iconClass'] ?? '') ?>"></i><?= e($item['text'] ?? '') ?></div>
      <?php endforeach; ?>
    </div>

    <!-- ══════════════════════════════════════════════════
         5. DIRECTOR MESSAGE
    ══════════════════════════════════════════════════ -->
    <div class="sec" style="margin-top:.55rem;">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-person-badge"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'director.title')) ?></div>
      </div>
      <div class="sec-body">
        <div style="display:flex;gap:.9rem;align-items:flex-start;">
          <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#4338ca,#6d28d9);display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:900;color:#fff;flex-shrink:0;">
            <?= e(v($data, 'director.initials')) ?>
          </div>
          <div>
            <div style="font-size:.9rem;font-weight:800;color:var(--text);"><?= e(v($data, 'director.name')) ?></div>
            <div style="font-size:.71rem;color:var(--indigo);font-weight:600;margin-bottom:.5rem;">
              <?= e(v($data, 'director.role')) ?>
            </div>
            <div style="font-size:.78rem;color:#334155;line-height:1.62;">"<?= e(v($data, 'director.message')) ?>"</div>
          </div>
        </div>
        <div class="chip-row" style="margin-top:.88rem;">
          <?php foreach (a($data, 'director.badges') as $badge): ?>
            <?php if (!is_array($badge)) { continue; } ?>
            <span class="chip <?= e($badge['class'] ?? '') ?>"><i class="bi <?= e($badge['iconClass'] ?? '') ?>"></i><?= e($badge['label'] ?? '') ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         6. WHY CHOOSE US
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-amber">
          <i class="bi bi-stars"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'whyChoose.title')) ?></div>
      </div>
      <div class="sec-body">
        <div class="why-grid">
          <?php foreach (a($data, 'whyChoose.items') as $item): ?>
            <?php if (!is_array($item)) { continue; } ?>
            <div class="why-card">
              <div class="why-icon" style="background:<?= e($item['gradient'] ?? '') ?>;">
                <i class="bi <?= e($item['iconClass'] ?? '') ?>" style="color:#fff;"></i>
              </div>
              <div class="why-title"><?= e($item['title'] ?? '') ?></div>
              <div class="why-desc"><?= e($item['description'] ?? '') ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         7. COURSES OFFERED
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-amber">
          <i class="bi bi-mortarboard"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'courses.title')) ?></div>
        <div class="sec-subtitle"><?= e(v($data, 'courses.subtitle')) ?></div>
      </div>
      <div class="sec-body" style="padding:.9rem .4rem .9rem 1.1rem;">
        <div class="hscroll">
          <?php foreach (a($data, 'courses.items') as $course): ?>
            <?php if (!is_array($course)) { continue; } ?>
            <div class="course-card" onclick="enquireWA('<?= e($course['enquiryText'] ?? '') ?>')">
              <div class="course-banner" style="background:<?= e($course['bannerGradient'] ?? '') ?>;">
                <?php if (!empty($course['imageUrl'])): ?>
                  <img src="<?= e($course['imageUrl']) ?>" alt="<?= e($course['name'] ?? '') ?>" />
                <?php else: ?>
                  <i class="bi <?= e($course['iconClass'] ?? '') ?>" style="color:rgba(255,255,255,.9);font-size:26px;"></i>
                <?php endif; ?>
                <span class="c-success-badge"><?= e($course['successBadge'] ?? '') ?></span>
              </div>
              <div class="course-body">
                <div class="course-name"><?= e($course['name'] ?? '') ?></div>
                <div class="course-tag"><?= e($course['tag'] ?? '') ?></div>
                <div class="course-pills">
                  <?php foreach (($course['pills'] ?? []) as $pill): ?>
                    <span class="c-pill"><?= e($pill) ?></span>
                  <?php endforeach; ?>
                </div>
                <button class="course-enroll" onclick="event.stopPropagation();enquireWA('<?= e($course['enquiryText'] ?? '') ?>')" style="background:<?= e($course['buttonGradient'] ?? '') ?>;">
                  <i class="bi bi-check2"></i>
                  Enroll Now
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         8. UPCOMING BATCHES
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-green">
          <i class="bi bi-calendar-event"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'batches.title')) ?></div>
      </div>
      <div class="sec-body">
        <div class="batch-list">
          <?php foreach (a($data, 'batches.items') as $batch): ?>
            <?php if (!is_array($batch)) { continue; } ?>
            <div class="batch-item <?= e($batch['status'] ?? '') ?>">
              <div class="batch-top">
                <div>
                  <div class="batch-name"><?= e($batch['name'] ?? '') ?></div>
                  <div class="batch-fee-hint"><?= e($batch['feeHint'] ?? '') ?></div>
                </div>
                <span class="batch-badge badge-<?= e(($batch['status'] ?? '') === 'full' ? 'full' : (($batch['status'] ?? '') === 'starting' ? 'starting' : 'enroll')) ?>"><?= e($batch['badge'] ?? '') ?></span>
              </div>
              <div class="batch-meta">
                <?php foreach (($batch['meta'] ?? []) as $meta): ?>
                  <span class="bm"><i class="bi <?= e($meta['iconClass'] ?? '') ?>"></i><?= e($meta['text'] ?? '') ?></span>
                <?php endforeach; ?>
              </div>
              <?php if (!empty($batch['seatsFull'])): ?>
                <div class="batch-seats-full">⛔ <?= e($batch['seatsFull']) ?></div>
              <?php else: ?>
                <div class="batch-seats"><i class="bi bi-check2"></i><?= e($batch['seats'] ?? '') ?></div>
              <?php endif; ?>
              <button class="batch-cta <?= e($batch['ctaClass'] ?? '') ?>" onclick="enquireWA('<?= e($batch['enquiryText'] ?? '') ?>')">
                <i class="bi bi-whatsapp"></i>
                <?= e($batch['ctaText'] ?? '') ?>
              </button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         9. FREE DEMO REGISTRATION
    ══════════════════════════════════════════════════ -->
    <div class="sec" id="demoSection">
      <div class="sec-header">
        <div class="sec-icon g-red">
          <i class="bi bi-play-circle"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'demo.title')) ?></div>
      </div>
      <div class="sec-body">
        <div id="demoForm">
          <div class="demo-promo-bar">
            <div class="demo-promo-icon"><i class="bi bi-play-circle"></i></div>
            <div class="demo-promo-text">
              <h5><?= e(v($data, 'demo.promoTitle')) ?> 🎯</h5>
              <p><?= e(v($data, 'demo.promoText')) ?></p>
            </div>
          </div>

          <div style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.4px;margin-bottom:.55rem;">
            <?= e(v($data, 'demo.slotTitle')) ?>
          </div>
          <div class="demo-slots" id="demoSlotGrid">
            <?php foreach (a($data, 'demo.slots') as $slot): ?>
              <?php if (!is_array($slot)) { continue; } ?>
              <div class="demo-slot<?= !empty($slot['selected']) ? ' selected' : '' ?>" onclick="selectDemo(this)" data-slot="<?= e($slot['slot'] ?? '') ?>">
                <div class="demo-slot-check"><i class="bi bi-check2"></i></div>
                <div class="demo-slot-day"><?= e($slot['day'] ?? '') ?></div>
                <div class="demo-slot-time"><?= e($slot['time'] ?? '') ?></div>
                <div class="demo-slot-topic">📖 <?= e($slot['topic'] ?? '') ?></div>
                <div class="demo-slot-mode">🏛 <?= e($slot['mode'] ?? '') ?></div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="bf-row">
            <div class="bf-group"><label class="bf-label"><?= e(v($data, 'demo.form.nameLabel')) ?></label><input class="bf-input" id="dName" placeholder="Full name" type="text"/></div>
            <div class="bf-group"><label class="bf-label"><?= e(v($data, 'demo.form.phoneLabel')) ?></label><input class="bf-input" id="dPhone" placeholder="+91 XXXXX" type="tel"/></div>
          </div>
          <div class="bf-row">
            <div class="bf-group">
              <label class="bf-label"><?= e(v($data, 'demo.form.examLabel')) ?></label>
              <select class="bf-input" id="dExam">
                <?php foreach (a($data, 'demo.examOptions') as $option): ?>
                  <option><?= e($option) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="bf-group">
              <label class="bf-label"><?= e(v($data, 'demo.form.educationLabel')) ?></label>
              <select class="bf-input" id="dEdu">
                <?php foreach (a($data, 'demo.educationOptions') as $option): ?>
                  <option><?= e($option) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="bf-group">
            <label class="bf-label"><?= e(v($data, 'demo.form.attemptLabel')) ?></label>
            <input class="bf-input" id="dAttempt" placeholder="e.g. 1st attempt, cleared prelims once…" type="text"/>
          </div>
          <button class="bf-submit" onclick="bookDemo()">
            <i class="bi bi-play-circle"></i>
            <?= e(v($data, 'demo.form.submitText')) ?>
          </button>
        </div>

        <div class="demo-success" id="demoSuccess">
          <div class="demo-success-icon"><i class="bi bi-check2-circle"></i></div>
          <h4><?= e(v($data, 'demo.success.title')) ?></h4>
          <p><?= e(v($data, 'demo.success.text')) ?></p>
          <button class="reset-btn" onclick="resetDemo()"><?= e(v($data, 'demo.success.resetText')) ?></button>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         10. FEE STRUCTURE
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-amber">
          <i class="bi bi-cash"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'fees.title')) ?></div>
      </div>
      <div class="sec-body">
        <div class="fees-list">
          <?php foreach (a($data, 'fees.items') as $fee): ?>
            <?php if (!is_array($fee)) { continue; } ?>
            <div class="fee-item">
              <div class="fee-left">
                <div class="fee-icon" style="background:<?= e($fee['bg'] ?? '') ?>;color:<?= e($fee['color'] ?? '') ?>;"><i class="bi <?= e($fee['iconClass'] ?? '') ?>"></i></div>
                <div><div class="fee-name"><?= e($fee['name'] ?? '') ?></div><div class="fee-note"><?= e($fee['note'] ?? '') ?></div></div>
              </div>
              <div>
                <span class="fee-amount"><?= e($fee['amount'] ?? '') ?></span>
                <?php if (!empty($fee['oldAmount'])): ?>
                  <span class="fee-old"><?= e($fee['oldAmount']) ?></span>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="emi-note">
          <i class="bi bi-check2-circle"></i>
          <?= e(v($data, 'fees.emiNote')) ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         11. EXPERT FACULTY
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-people"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'faculty.title')) ?></div>
        <div class="sec-subtitle"><?= e(v($data, 'faculty.subtitle')) ?></div>
      </div>
      <div class="sec-body" style="padding:.9rem .4rem .9rem 1.1rem;">
        <div class="hscroll">
          <?php foreach (a($data, 'faculty.items') as $faculty): ?>
            <?php if (!is_array($faculty)) { continue; } ?>
            <div class="faculty-card">
              <div class="faculty-avatar" style="background:<?= e($faculty['gradient'] ?? '') ?>;">
                <?php if (!empty($faculty['imageUrl'])): ?>
                  <img src="<?= e($faculty['imageUrl']) ?>" alt="<?= e($faculty['name'] ?? '') ?>" />
                <?php else: ?>
                  <?= e($faculty['initials'] ?? '') ?>
                <?php endif; ?>
              </div>
              <div class="faculty-name"><?= e($faculty['name'] ?? '') ?></div>
              <div class="faculty-subject"><?= e($faculty['subject'] ?? '') ?></div>
              <div class="faculty-exp"><?= e($faculty['experience'] ?? '') ?></div>
              <button class="faculty-wa" onclick="enquireWA('<?= e($faculty['enquiryText'] ?? '') ?>')"><i class="bi bi-whatsapp"></i> Connect</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         12. STUDY MATERIALS
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-blue">
          <i class="bi bi-journal-bookmark"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'materials.title')) ?></div>
      </div>
      <div class="sec-body">
        <div class="material-list">
          <?php foreach (a($data, 'materials.items') as $item): ?>
            <?php if (!is_array($item)) { continue; } ?>
            <div class="material-item" onclick="enquireWA('<?= e($item['enquiryText'] ?? '') ?>')">
              <div class="mat-icon" style="background:<?= e($item['bg'] ?? '') ?>;"><i class="bi <?= e($item['iconClass'] ?? '') ?>" style="color:<?= e($item['iconColor'] ?? '') ?>;"></i></div>
              <div><div class="mat-name"><?= e($item['name'] ?? '') ?></div><div class="mat-detail"><?= e($item['detail'] ?? '') ?></div></div>
              <div class="mat-arrow"><i class="bi bi-chevron-right"></i></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         15. LEARNING MODES
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-green">
          <i class="bi bi-laptop"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'modes.title')) ?></div>
      </div>
      <div class="sec-body">
        <div class="mode-grid">
          <?php foreach (a($data, 'modes.items') as $mode): ?>
            <?php if (!is_array($mode)) { continue; } ?>
            <div class="mode-card">
              <div class="mode-icon" style="background:<?= e($mode['gradient'] ?? '') ?>;"><i class="bi <?= e($mode['iconClass'] ?? '') ?>" style="color:#fff;"></i></div>
              <div class="mode-name"><?= e($mode['name'] ?? '') ?></div>
              <div class="mode-desc"><?= e($mode['description'] ?? '') ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         16. FAQ & SCHOLARSHIP
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-purple">
          <i class="bi bi-question-circle"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'faq.title')) ?></div>
      </div>
      <div class="sec-body">
        <div class="faq-list">
          <?php foreach (a($data, 'faq.items') as $faq): ?>
            <?php if (!is_array($faq)) { continue; } ?>
            <div class="faq-item<?= !empty($faq['open']) ? ' open' : '' ?>">
              <div class="faq-q" onclick="toggleFaq(this)">
                <?= e($faq['question'] ?? '') ?>
                <div class="faq-arrow"><i class="bi bi-chevron-down"></i></div>
              </div>
              <div class="faq-a"><?= e($faq['answer'] ?? '') ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         16. SOCIAL MEDIA
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-share"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'social.title')) ?></div>
      </div>
      <div class="sec-body">
        <div class="social-list">
          <?php foreach (a($data, 'social.items') as $item): ?>
            <?php if (!is_array($item)) { continue; } ?>
            <?php
              $action = (string) ($item['action'] ?? '');
              $url = (string) ($item['url'] ?? '');
              $onclick = '';
              if ($action !== '') {
                  $onclick = $action . '()';
              } elseif ($url !== '') {
                  $onclick = "window.open('" . e($url) . "','_blank')";
              }
            ?>
            <div class="social-item"<?= $onclick !== '' ? ' onclick="' . e($onclick) . '"' : '' ?>>
              <div class="s-ico <?= e($item['iconClass'] ?? '') ?>"><i class="bi <?= e($socialIconMap[$item['iconClass'] ?? ''] ?? 'bi-link-45deg') ?>"></i></div>
              <div><div class="s-name"><?= e($item['label'] ?? '') ?></div><div class="s-val"><?= e($item['value'] ?? '') ?></div></div>
              <div class="s-arrow"><i class="bi bi-chevron-right"></i></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         17. LOCATION & MAP
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-geo-alt"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'location.title')) ?></div>
      </div>
      <div class="sec-body">
        <div class="map-frame">
          <iframe
            src="<?= e(v($data, 'location.mapEmbed')) ?>"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
            title="<?= e(v($data, 'shop.name')) ?> Location">
          </iframe>
        </div>
        <div class="map-address">
          <i class="bi bi-geo-alt-fill"></i>
          <span><?= e(v($data, 'location.address')) ?></span>
        </div>
        <button class="directions-btn" onclick="openMaps()">
          <i class="bi bi-signpost"></i>
          <?= e(v($data, 'location.directionsText')) ?>
        </button>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         21. PAYMENT OPTIONS
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-blue">
          <i class="bi bi-credit-card"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'payment.title')) ?></div>
      </div>
      <div class="sec-body">
        <div class="payment-list">
          <?php foreach (a($data, 'payment.items') as $item): ?>
            <?php if (!is_array($item)) { continue; } ?>
            <div class="pay-item">
              <div class="pay-icon"><i class="bi <?= e($item['iconClass'] ?? '') ?>"></i></div>
              <div><div class="pay-name"><?= e($item['name'] ?? '') ?></div><div class="pay-detail"><?= e($item['detail'] ?? '') ?></div></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         22. QR CODE
    ══════════════════════════════════════════════════ -->
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-qr-code"></i>
        </div>
        <div class="sec-title"><?= e(v($data, 'qr.title')) ?></div>
      </div>
      <div class="sec-body">
        <div class="qr-inner">
          <div style="font-size:.78rem;color:var(--muted);margin-bottom:.3rem;">
            <?= e(v($data, 'qr.intro')) ?>
          </div>
          <div id="instituteQR"></div>
          <div class="qr-actions">
            <button class="qr-btn" onclick="saveContact()">
              <i class="bi bi-person-vcard"></i>
              <?= e(v($data, 'qr.saveText')) ?>
            </button>
            <button class="qr-btn" onclick="downloadQR()">
              <i class="bi bi-download"></i>
              <?= e(v($data, 'qr.downloadText')) ?>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════════════ -->
    <div class="site-footer">
      <?= e(v($data, 'footer.line1')) ?><br/>
      <strong><?= e(v($data, 'shop.name')) ?></strong> · <?= e(v($data, 'footer.line2')) ?><br/>
      <span style="font-size:.65rem;color:#aaa;"><?= e(v($data, 'footer.line3')) ?></span>
      <div class="footer-links">
        <?php foreach (a($data, 'footer.links') as $link): ?>
          <?php if (!is_array($link)) { continue; } ?>
          <span onclick="enquireWA('<?= e($link['enquiryText'] ?? '') ?>')"><?= e($link['label'] ?? '') ?></span>
        <?php endforeach; ?>
      </div>
      <div style="font-size:.63rem;color:#bbb;margin-top:.5rem;">
        <?= e(v($data, 'footer.copyright')) ?>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         FLOATING BOTTOM BAR
    ══════════════════════════════════════════════════ -->
    <div class="float-bar">
      <button class="fab call-fab" onclick="callInstitute()">
        <i class="bi bi-telephone"></i>
        Call
      </button>
      <button class="fab wa-fab" onclick="openWA()">
        <i class="bi bi-whatsapp"></i>
        WhatsApp
      </button>
      <button class="fab demo-fab" onclick="document.getElementById('demoSection').scrollIntoView({behavior:'smooth'})">
        <i class="bi bi-play-circle"></i>
        Free Demo
      </button>
      <button class="fab save-fab" onclick="saveContact()">
        <i class="bi bi-person-vcard"></i>
        Save
      </button>
    </div>

    <!-- ══════════════════════════════════════════════════
         SHARE MODAL
    ══════════════════════════════════════════════════ -->
    <div class="modal-overlay" id="shareModal">
      <div class="modal-box">
        <div class="modal-title">Share Coaching Profile</div>
        <div class="share-opts">
          <div class="sh-opt" onclick="shareWA()" style="color:#128c7e;">
            <i class="bi bi-whatsapp"></i>
            WhatsApp
          </div>
          <div class="sh-opt" onclick="copyLink()" style="color:var(--indigo);">
            <i class="bi bi-link-45deg"></i>
            Copy Link
          </div>
          <div class="sh-opt" onclick="shareNative()" style="color:var(--blue);">
            <i class="bi bi-share"></i>
            More…
          </div>
          <div class="sh-opt" onclick="shareFB()" style="color:#1877f2;">
            <i class="bi bi-facebook"></i>
            Facebook
          </div>
        </div>
        <button class="modal-cancel" onclick="closeShare()">Cancel</button>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         TOAST
    ══════════════════════════════════════════════════ -->
    <div class="toast" id="toast">
      <i class="bi bi-check2-circle"></i>
      Done!
    </div>

    <script>
      window.__ACTION_DATA__ = <?= $actionDataJson ?>;
    </script>
    <script src="script.js"></script>
  </body>
</html>
