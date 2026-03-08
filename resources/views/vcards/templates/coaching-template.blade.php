@php
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
@endphp
<!doctype html>
<html lang="en">
  <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title>{{ v($data, 'meta.title') }}</title>
    <meta name="description" content="{{ v($data, 'meta.description') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ v($data, 'meta.ogTitle') }}" />
    <meta property="og:description" content="{{ v($data, 'meta.ogDescription') }}" />
    <meta property="og:url" content="{{ v($data, 'meta.ogUrl') }}" />
    <meta property="og:image" content="{{ v($data, 'meta.ogImage') }}" />
    <meta name="twitter:card" content="{{ v($data, 'meta.twitterCard') }}" />
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ $assetBase }}style.css" />
    @if(!empty($vcard->head_script))
    {!! $vcard->head_script !!}
    @endif
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
          <div class="banner-tagline">🎯 {{ v($data, 'banner.tagline') }}</div>
          <div class="banner-sub">{{ v($data, 'banner.sub') }}</div>
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
          <span>Share</span>
          <i class="bi bi-person-plus-fill"></i>
          <span>Save Contact</span>
        </button>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         2. STATUS BAR
    ══════════════════════════════════════════════════ -->
    <div class="status-bar">
      <div class="status-open">
        <div class="dot-pulse"></div>
        {{ v($data, 'status.openText') }}
      </div>
      <div class="next-batch">⚡ {{ v($data, 'status.nextBatch') }}</div>
    </div>

    <!-- ══════════════════════════════════════════════════
         3. PROFILE CARD
    ══════════════════════════════════════════════════ -->
    <div class="profile-card">
      <div class="profile-logo-wrap">
        <div class="profile-logo">
          @if(!empty(v($data, 'profile.logoImageUrl')))
            <img src="{{ v($data, 'profile.logoImageUrl') }}" alt="{{ v($data, 'profile.name') }}" />
          @else
            <i class="bi {{ v($data, 'profile.logoIconClass') }}"></i>
          @endif
        </div>
        <span class="est-tag">{{ v($data, 'profile.estTag') }}</span>
      </div>
      <div class="profile-name">{{ v($data, 'profile.name') }}</div>
      <div class="profile-role">{{ v($data, 'profile.role') }}</div>
      <div class="profile-qual">{{ v($data, 'profile.qual') }}</div>

      <div class="profile-stats">
        @foreach(a($data, 'stats') as $index => $stat)
          @continue(!is_array($stat))
          <div class="pstat">
            <div class="pstat-num" id="{{ $stat['id'] ?? '' }}">{{ $stat['static'] ?? '0' }}</div>
            <div class="pstat-lbl">{{ $stat['label'] ?? '' }}</div>
          </div>
          @if($index < count(a($data, 'stats')) - 1)
            <div class="stat-div"></div>
          @endif
        @endforeach
      </div>

      <div class="profile-action-btns">
        <button class="pab call" onclick="callInstitute()">
          <i class="bi bi-telephone-fill"></i>
          <span>Call</span>
        </button>
        <button class="pab whatsapp" onclick="openWA()">
          <i class="bi bi-whatsapp"></i>
          <span>WhatsApp</span>
        </button>
        <button class="pab save" onclick="saveContact()">
          <i class="bi bi-person-vcard-fill"></i>
          <span>Save</span>
        </button>
        <button class="pab email" onclick="emailInstitute()">
          <i class="bi bi-envelope-fill"></i>
          <span>Email</span>
        </button>
        <button class="pab direction" onclick="openMaps()">
          <i class="bi bi-geo-alt-fill"></i>
          <span>Directions</span>
        </button>
        <button class="pab share" onclick="openShare()">
          <i class="bi bi-share-fill"></i>
          <span>Share</span>
        </button>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         4. TRUST SIGNALS
    ══════════════════════════════════════════════════ -->
    <div class="trust-strip">
      @foreach(a($data, 'trust.items') as $item)
        @continue(!is_array($item))
        <div class="trust-item"><i class="bi {{ $item['iconClass'] ?? '' }}"></i>{{ $item['text'] ?? '' }}</div>
      @endforeach
    </div>

    <!-- ══════════════════════════════════════════════════
         5. DIRECTOR MESSAGE
    ══════════════════════════════════════════════════ -->
    <div class="sec" style="margin-top:.55rem;">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-person-badge"></i>
        </div>
        <div class="sec-title">Director's Message</div>
      </div>
      <div class="sec-body">
        <div style="display:flex;gap:.9rem;align-items:flex-start;">
          <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#4338ca,#6d28d9);display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:900;color:#fff;flex-shrink:0;">
            {{ v($data, 'director.initials') }}
          </div>
          <div>
            <div style="font-size:.9rem;font-weight:800;color:var(--text);">{{ v($data, 'director.name') }}</div>
            <div style="font-size:.71rem;color:var(--indigo);font-weight:600;margin-bottom:.5rem;">
              {{ v($data, 'director.role') }}
            </div>
            <div style="font-size:.78rem;color:#334155;line-height:1.62;">"{{ v($data, 'director.message') }}"</div>
          </div>
        </div>
        <div class="chip-row" style="margin-top:.88rem;">
          @foreach(a($data, 'director.badges') as $badge)
            @continue(!is_array($badge))
            <span class="chip {{ $badge['class'] ?? '' }}"><i class="bi {{ $badge['iconClass'] ?? '' }}"></i>{{ $badge['label'] ?? '' }}</span>
          @endforeach
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         6. WHY CHOOSE US
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'whyChoose'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-amber">
          <i class="bi bi-stars"></i>
        </div>
        <div class="sec-title">Why Choose Pinnacle?</div>
      </div>
      <div class="sec-body">
        <div class="why-grid">
          @foreach(a($data, 'whyChoose.items') as $item)
            @continue(!is_array($item))
            <div class="why-card">
              <div class="why-icon" style="background:{{ $item['gradient'] ?? '' }};">
                <i class="bi {{ $item['iconClass'] ?? '' }}" style="color:#fff;"></i>
              </div>
              <div class="why-title">{{ $item['title'] ?? '' }}</div>
              <div class="why-desc">{{ $item['description'] ?? '' }}</div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         7. COURSES OFFERED
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'courses'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-amber">
          <i class="bi bi-mortarboard"></i>
        </div>
        <div class="sec-title">Courses Offered</div>
        <div class="sec-subtitle">{{ v($data, 'courses.subtitle') }}</div>
      </div>
      <div class="sec-body" style="padding:.9rem .4rem .9rem 1.1rem;">
        <div class="hscroll">
          @foreach(a($data, 'courses.items') as $course)
            @continue(!is_array($course))
            <div class="course-card" onclick="enquireWA('{{ $course['enquiryText'] ?? '' }}')">
              <div class="course-banner" style="background:{{ $course['bannerGradient'] ?? '' }};">
                @if(!empty($course['imageUrl']))
                  <img src="{{ $course['imageUrl'] }}" alt="{{ $course['name'] ?? '' }}" />
                @else
                  <i class="bi {{ $course['iconClass'] ?? '' }}" style="color:rgba(255,255,255,.9);font-size:26px;"></i>
                @endif
                <span class="c-success-badge">{{ $course['successBadge'] ?? '' }}</span>
              </div>
              <div class="course-body">
                <div class="course-name">{{ $course['name'] ?? '' }}</div>
                <div class="course-tag">{{ $course['tag'] ?? '' }}</div>
                <div class="course-pills">
                  @foreach(($course['pills'] ?? []) as $pill)
                    <span class="c-pill">{{ $pill }}</span>
                  @endforeach
                </div>
                <button class="course-enroll" onclick="event.stopPropagation();enquireWA('{{ $course['enquiryText'] ?? '' }}')" style="background:{{ $course['buttonGradient'] ?? '' }};">
                  <i class="bi bi-check2"></i>
                  Enroll Now
                </button>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         8. UPCOMING BATCHES
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'batches'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-green">
          <i class="bi bi-calendar-event"></i>
        </div>
        <div class="sec-title">Upcoming Batches</div>
      </div>
      <div class="sec-body">
        <div class="batch-list">
          @foreach(a($data, 'batches.items') as $batch)
            @continue(!is_array($batch))
            <div class="batch-item {{ $batch['status'] ?? '' }}">
              <div class="batch-top">
                <div>
                  <div class="batch-name">{{ $batch['name'] ?? '' }}</div>
                  <div class="batch-fee-hint">{{ $batch['feeHint'] ?? '' }}</div>
                </div>
                <span class="batch-badge badge-{{ ($batch['status'] ?? '') === 'full' ? 'full' : (($batch['status'] ?? '') === 'starting' ? 'starting' : 'enroll') }}">{{ $batch['badge'] ?? '' }}</span>
              </div>
              <div class="batch-meta">
                @foreach(($batch['meta'] ?? []) as $meta)
                  <span class="bm"><i class="bi {{ $meta['iconClass'] ?? '' }}"></i>{{ $meta['text'] ?? '' }}</span>
                @endforeach
              </div>
              @if(!empty($batch['seatsFull']))
                <div class="batch-seats-full">⛔ {{ $batch['seatsFull'] }}</div>
              @else
                <div class="batch-seats"><i class="bi bi-check2"></i>{{ $batch['seats'] ?? '' }}</div>
              @endif
              <button class="batch-cta {{ $batch['ctaClass'] ?? '' }}" onclick="enquireWA('{{ $batch['enquiryText'] ?? '' }}')">
                <i class="bi bi-whatsapp"></i>
                {{ $batch['ctaText'] ?? '' }}
              </button>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         9. FREE DEMO REGISTRATION
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'demo'))
    <div class="sec" id="demoSection">
      <div class="sec-header">
        <div class="sec-icon g-red">
          <i class="bi bi-play-circle"></i>
        </div>
        <div class="sec-title" id="demoSectionTitle">{{ v($data, 'demo.title') }}</div>
      </div>
      <div class="sec-body">
        <div id="demoForm">
          <div class="demo-promo-bar">
            <div class="demo-promo-icon"><i class="bi bi-play-circle"></i></div>
            <div class="demo-promo-text">
              <h5>{{ v($data, 'demo.promoTitle') }} 🎯</h5>
              <p>{{ v($data, 'demo.promoText') }}</p>
            </div>
          </div>

          <div style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.4px;margin-bottom:.55rem;">
            {{ v($data, 'demo.slotTitle') }}
          </div>
          <div class="demo-slots" id="demoSlotGrid">
            {{-- slots rendered dynamically by renderDemoSlots() in script.js --}}
          </div>

          <div class="bf-row">
            <div class="bf-group"><label class="bf-label">{{ v($data, 'demo.form.nameLabel') }}</label><input class="bf-input" id="dName" placeholder="Full name" type="text"/></div>
            <div class="bf-group"><label class="bf-label">{{ v($data, 'demo.form.phoneLabel') }}</label><input class="bf-input" id="dPhone" placeholder="+91 XXXXX" type="tel"/></div>
          </div>
          <div class="bf-row">
            <div class="bf-group">
              <label class="bf-label">{{ v($data, 'demo.form.examLabel') }}</label>
              <select class="bf-input" id="dExam">
                @foreach(a($data, 'demo.examOptions') as $option)
                  <option>{{ $option }}</option>
                @endforeach
              </select>
            </div>
            <div class="bf-group">
              <label class="bf-label">{{ v($data, 'demo.form.educationLabel') }}</label>
              <select class="bf-input" id="dEdu">
                @foreach(a($data, 'demo.educationOptions') as $option)
                  <option>{{ $option }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="bf-group">
            <label class="bf-label">{{ v($data, 'demo.form.attemptLabel') }}</label>
            <input class="bf-input" id="dAttempt" placeholder="e.g. 1st attempt, cleared prelims once…" type="text"/>
          </div>
          <button class="bf-submit" onclick="bookDemo()">
            <i class="bi bi-play-circle"></i>
            Register for Free Demo via WhatsApp
          </button>
        </div>

        <div class="demo-success" id="demoSuccess">
          <div class="demo-success-icon"><i class="bi bi-check2-circle"></i></div>
          <h4>{{ v($data, 'demo.success.title') }}</h4>
          <p>{{ v($data, 'demo.success.text') }}</p>
          <button class="reset-btn" onclick="resetDemo()">Register Another</button>
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         10. FEE STRUCTURE
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'fees'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-amber">
          <i class="bi bi-cash"></i>
        </div>
        <div class="sec-title">Fee Structure</div>
      </div>
      <div class="sec-body">
        <div class="fees-list">
          @foreach(a($data, 'fees.items') as $fee)
            @continue(!is_array($fee))
            <div class="fee-item">
              <div class="fee-left">
                <div class="fee-icon" style="background:{{ $fee['bg'] ?? '' }};color:{{ $fee['color'] ?? '' }};"><i class="bi {{ $fee['iconClass'] ?? '' }}"></i></div>
                <div><div class="fee-name">{{ $fee['name'] ?? '' }}</div><div class="fee-note">{{ $fee['note'] ?? '' }}</div></div>
              </div>
              <div>
                <span class="fee-amount">{{ $fee['amount'] ?? '' }}</span>
                @if(!empty($fee['oldAmount']))
                  <span class="fee-old">{{ $fee['oldAmount'] }}</span>
                @endif
              </div>
            </div>
          @endforeach
        </div>
        <div class="emi-note">
          <i class="bi bi-check2-circle"></i>
          {{ v($data, 'fees.emiNote') }}
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         11. EXPERT FACULTY
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'faculty'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-people"></i>
        </div>
        <div class="sec-title">Expert Faculty</div>
        <div class="sec-subtitle">{{ v($data, 'faculty.subtitle') }}</div>
      </div>
      <div class="sec-body" style="padding:.9rem .4rem .9rem 1.1rem;">
        <div class="hscroll">
          @foreach(a($data, 'faculty.items') as $faculty)
            @continue(!is_array($faculty))
            <div class="faculty-card">
              <div class="faculty-avatar" style="background:{{ $faculty['gradient'] ?? '' }};">
                @if(!empty($faculty['imageUrl']))
                  <img src="{{ $faculty['imageUrl'] }}" alt="{{ $faculty['name'] ?? '' }}" />
                @else
                  {{ $faculty['initials'] ?? '' }}
                @endif
              </div>
              <div class="faculty-name">{{ $faculty['name'] ?? '' }}</div>
              <div class="faculty-subject">{{ $faculty['subject'] ?? '' }}</div>
              <div class="faculty-exp">{{ $faculty['experience'] ?? '' }}</div>
              <button class="faculty-wa" onclick="enquireWA('{{ $faculty['enquiryText'] ?? '' }}')"><i class="bi bi-whatsapp"></i> Connect</button>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         12. STUDY MATERIALS
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'materials'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-blue">
          <i class="bi bi-journal-bookmark"></i>
        </div>
        <div class="sec-title">Study Materials &amp; Resources</div>
      </div>
      <div class="sec-body">
        <div class="material-list">
          @foreach(a($data, 'materials.items') as $item)
            @continue(!is_array($item))
            <div class="material-item" onclick="enquireWA('{{ $item['enquiryText'] ?? '' }}')">
              <div class="mat-icon" style="background:{{ $item['bg'] ?? '' }};"><i class="bi {{ $item['iconClass'] ?? '' }}" style="color:{{ $item['iconColor'] ?? '' }};"></i></div>
              <div><div class="mat-name">{{ $item['name'] ?? '' }}</div><div class="mat-detail">{{ $item['detail'] ?? '' }}</div></div>
              <div class="mat-arrow"><i class="bi bi-chevron-right"></i></div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         15. LEARNING MODES
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'modes'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-green">
          <i class="bi bi-laptop"></i>
        </div>
        <div class="sec-title">Learning Modes</div>
      </div>
      <div class="sec-body">
        <div class="mode-grid">
          @foreach(a($data, 'modes.items') as $mode)
            @continue(!is_array($mode))
            <div class="mode-card">
              <div class="mode-icon" style="background:{{ $mode['gradient'] ?? '' }};"><i class="bi {{ $mode['iconClass'] ?? '' }}" style="color:#fff;"></i></div>
              <div class="mode-name">{{ $mode['name'] ?? '' }}</div>
              <div class="mode-desc">{{ $mode['description'] ?? '' }}</div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         16. FAQ & SCHOLARSHIP
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'faq'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-purple">
          <i class="bi bi-question-circle"></i>
        </div>
        <div class="sec-title">FAQs</div>
      </div>
      <div class="sec-body">
        <div class="faq-list">
          @foreach(a($data, 'faq.items') as $faq)
            @continue(!is_array($faq))
            <div class="faq-item{{ !empty($faq['open']) ? ' open' : '' }}">
              <div class="faq-q" onclick="toggleFaq(this)">
                {{ $faq['question'] ?? '' }}
                <div class="faq-arrow"><i class="bi bi-chevron-down"></i></div>
              </div>
              <div class="faq-a">{{ $faq['answer'] ?? '' }}</div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         16. SOCIAL MEDIA
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'socialLinks'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-share"></i>
        </div>
        <div class="sec-title">Follow &amp; Connect</div>
      </div>
      <div class="sec-body">
        <div class="social-list">
          @foreach(a($data, 'social.items') as $item)
            @continue(!is_array($item))
            @php
              $action = (string) ($item['action'] ?? '');
              $url = (string) ($item['url'] ?? '');
              $onclick = '';
              if ($action !== '') {
                  $onclick = $action . '()';
              } elseif ($url !== '') {
                  $onclick = "window.open('" . e($url) . "','_blank')";
              }
@endphp
            <div class="social-item"{{ $onclick !== '' ? ' onclick="' . e($onclick) . '"' : '' }}>
              <div class="s-ico {{ $item['iconClass'] ?? '' }}"><i class="bi {{ $socialIconMap[$item['iconClass'] ?? ''] ?? 'bi-link-45deg' }}"></i></div>
              <div><div class="s-name">{{ $item['label'] ?? '' }}</div><div class="s-val">{{ $item['value'] ?? '' }}</div></div>
              <div class="s-arrow"><i class="bi bi-chevron-right"></i></div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         17. LOCATION & MAP
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'location'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-geo-alt"></i>
        </div>
        <div class="sec-title">Find Us</div>
      </div>
      <div class="sec-body">
        <div class="map-frame">
          <iframe
            src="{{ v($data, 'location.mapEmbed') }}"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
            title="{{ v($data, 'shop.name') }} Location">
          </iframe>
        </div>
        <div class="map-address">
          <i class="bi bi-geo-alt-fill"></i>
          <span>{{ v($data, 'location.address') }}</span>
        </div>
        <button class="directions-btn" onclick="openMaps()">
          <i class="bi bi-signpost"></i>
          Get Directions on Google Maps
        </button>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         21. PAYMENT OPTIONS
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'payments'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon g-blue">
          <i class="bi bi-credit-card"></i>
        </div>
        <div class="sec-title">Payment Options</div>
      </div>
      <div class="sec-body">
        <div class="payment-list">
          @foreach(a($data, 'payment.items') as $item)
            @continue(!is_array($item))
            <div class="pay-item">
              <div class="pay-icon"><i class="bi {{ $item['iconClass'] ?? '' }}"></i></div>
              <div><div class="pay-name">{{ $item['name'] ?? '' }}</div><div class="pay-detail">{{ $item['detail'] ?? '' }}</div></div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         22. QR CODE
    ══════════════════════════════════════════════════ -->
    @if(vcard_section_enabled($data, 'qr'))
    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-qr-code"></i>
        </div>
        <div class="sec-title">Save &amp; Share Profile</div>
      </div>
      <div class="sec-body">
        <div class="qr-inner">
          <div style="font-size:.78rem;color:var(--muted);margin-bottom:.3rem;">
            {{ v($data, 'qr.intro') }}
          </div>
          <div id="instituteQR"></div>
          <div class="qr-actions">
            <button class="qr-btn" onclick="saveContact()">
              <i class="bi bi-person-vcard"></i>
              Save Contact
            </button>
            <button class="qr-btn" onclick="downloadQR()">
              <i class="bi bi-download"></i>
              Download QR
            </button>
          </div>
        </div>
      </div>
    </div>
    @endif

    <!-- ══════════════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════════════ -->
    <div class="site-footer">
      <span>{{ v($data, 'footer.line1') }}</span><br />
      <strong style="color:var(--indigo)">{{ v($data, 'footer.line2') }}</strong><br />
      <span>{{ v($data, 'footer.line3') }}</span>
      @if(v($data, 'footer.line4'))
        <br /><span style="font-size:.65rem;color:#aaa;">{{ v($data, 'footer.line4') }}</span>
      @endif
      <div style="font-size:.63rem;color:#aaa;margin-top:.55rem;">
        Powered by <a href="{{ config('app.url') }}" target="_blank" rel="noopener" style="color:var(--indigo);text-decoration:none;font-weight:600;">{{ config('app.name') }}</a>
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
      window.__ACTION_DATA__ = {!! $actionDataJson !!};
      window.__VCARD_SUBDOMAIN__ = {!! json_encode($subdomain) !!};
      window.__APP_URL__ = {!! json_encode('https://' . $vcard->subdomain . '.' . config('vcard.base_domain')) !!};
    </script>
    <script src="{{ $assetBase }}script.js"></script>
    @if(!empty($vcard->footer_script))
    {!! $vcard->footer_script !!}
    @endif
  </body>
</html>
