@php
    $shop = is_array($data['shop'] ?? null) ? $data['shop'] : [];
    $messages = is_array($data['messages'] ?? null) ? $data['messages'] : [];
    $toast = is_array($data['toast'] ?? null) ? $data['toast'] : [];
    $cart = is_array($data['cart'] ?? null) ? $data['cart'] : [];
    $products = is_array($data['products'] ?? null) ? $data['products'] : [];
    $socialIcons = [
        'wa' => '<i class="bi bi-whatsapp ico" aria-hidden="true"></i>',
        'ig' => '<i class="bi bi-instagram ico" aria-hidden="true"></i>',
        'fb' => '<i class="bi bi-facebook ico" aria-hidden="true"></i>',
        'yt' => '<i class="bi bi-youtube ico" aria-hidden="true"></i>',
    ];
    $paymentIcons = [
        'upi' => '<i class="bi bi-phone-fill ico" aria-hidden="true"></i>',
        'card' => '<i class="bi bi-credit-card-2-front-fill ico" aria-hidden="true"></i>',
        'cash' => '<i class="bi bi-cash-stack ico" aria-hidden="true"></i>',
    ];
    $shareIcons = [
        'wa' => '<i class="bi bi-whatsapp ico" aria-hidden="true"></i>',
        'fb' => '<i class="bi bi-facebook ico" aria-hidden="true"></i>',
        'more' => '<i class="bi bi-three-dots ico" aria-hidden="true"></i>',
        'copy' => '<i class="bi bi-link-45deg ico" aria-hidden="true"></i>',
    ];
    $actionData = [
        'shop' => [
            'name' => (string) ($shop['name'] ?? ''),
            'tagline' => (string) ($shop['tagline'] ?? ''),
            'phone' => (string) ($shop['phone'] ?? ''),
            'whatsapp' => (string) ($shop['whatsapp'] ?? ''),
            'email' => (string) ($shop['email'] ?? ''),
            'website' => (string) ($shop['website'] ?? ''),
            'maps' => (string) ($shop['maps'] ?? ''),
            'address' => (string) ($shop['address'] ?? ''),
            'qrFileName' => (string) ($shop['qrFileName'] ?? ''),
            'vcardFileName' => (string) ($shop['vcardFileName'] ?? ''),
        ],
        'messages' => [
            'waEnquiry' => (string) ($messages['waEnquiry'] ?? ''),
            'shareText' => (string) ($messages['shareText'] ?? ''),
            'orderHeader' => (string) ($messages['orderHeader'] ?? ''),
            'orderConfirm' => (string) ($messages['orderConfirm'] ?? ''),
            'contactHeader' => (string) ($messages['contactHeader'] ?? ''),
            'fallbackEmail' => (string) ($messages['fallbackEmail'] ?? ''),
            'fallbackMessage' => (string) ($messages['fallbackMessage'] ?? ''),
        ],
        'toast' => $toast,
        'cart' => [
            'empty' => (string) ($cart['empty'] ?? ''),
            'emptySub' => (string) ($cart['emptySub'] ?? ''),
            'total' => (string) ($cart['total'] ?? ''),
            'order' => (string) ($cart['order'] ?? ''),
            'each' => (string) ($cart['each'] ?? ''),
        ],
        'products' => array_values(array_map(static function ($product): array {
            $row = is_array($product) ? $product : [];
            return [
                'id' => (int) ($row['id'] ?? 0),
                'name' => (string) ($row['name'] ?? ''),
                'author' => (string) ($row['author'] ?? ''),
                'price' => (int) ($row['price'] ?? 0),
            ];
        }, $products)),
    ];
    $actionDataJson = json_encode(
        $actionData,
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    );
    if ($actionDataJson === false) {
        $actionDataJson = '{}';
    }
@endphp
<!doctype html>
<html lang="en">
  <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
    <title>{{ v($data, 'meta.title') }}</title>
    <meta name="description" content="{{ v($data, 'meta.description') }}">
    <meta name="keywords" content="{{ v($data, 'meta.keywords') }}">
    <meta property="og:title" content="{{ v($data, 'meta.title') }}">
    <meta property="og:description" content="{{ v($data, 'meta.description') }}">
    @if(v($data, 'meta.og_image'))
    <meta property="og:image" content="{{ url(v($data, 'meta.og_image')) }}">
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ $assetBase }}style.css" />
  </head>
  <body>
    <div class="banner">
      <img class="cover" id="coverImage" src="{{ v($data, 'shop.coverImage') }}" alt="{{ v($data, 'shop.coverAlt') }}" />
      <div class="banner-overlay"></div>
      <div class="banner-top-bar">
        <button class="share-btn" onclick="openShare()">
          <i class="bi bi-share-fill ico-sm" aria-hidden="true"></i>
          <span>{{ v($data, 'hero.buttons.share') }}</span>
        </button>
        <button class="save-btn-top" onclick="saveContact()">
          <i class="bi bi-person-vcard-fill ico-sm" aria-hidden="true"></i>
          <span>{{ v($data, 'hero.buttons.saveContact') }}</span>
        </button>
      </div>
    </div>

    <div class="profile-card">
      <div class="profile-avatar-wrap">
        <div class="profile-avatar">
          <img id="avatarImage" src="{{ v($data, 'shop.avatarImage') }}" alt="{{ v($data, 'shop.name') }}" />
        </div>
      </div>
      <div class="profile-name">{{ v($data, 'hero.profile.name') }}</div>
      <div class="profile-role">{{ v($data, 'hero.profile.role') }}</div>
      <div class="profile-bio">{{ v($data, 'hero.profile.bio') }}</div>

      <div class="profile-action-btns">
        <button class="pab call" onclick="callShop()">
          <i class="bi bi-telephone-fill ico" aria-hidden="true"></i>
          <span>{{ v($data, 'hero.actions.call') }}</span>
        </button>

        <button class="pab whatsapp" onclick="openWA()">
          <i class="bi bi-whatsapp ico" aria-hidden="true"></i>
          <span>{{ v($data, 'hero.actions.whatsapp') }}</span>
        </button>

        <button class="pab save" onclick="saveContact()">
          <i class="bi bi-person-vcard-fill ico" aria-hidden="true"></i>
          <span>{{ v($data, 'hero.actions.save') }}</span>
        </button>

        <button class="pab email" onclick="emailShop()">
          <i class="bi bi-envelope-fill ico" aria-hidden="true"></i>
          <span>{{ v($data, 'hero.actions.email') }}</span>
        </button>

        <button class="pab direction" onclick="openMaps()">
          <i class="bi bi-geo-alt-fill ico" aria-hidden="true"></i>
          <span>{{ v($data, 'hero.actions.directions') }}</span>
        </button>

        <button class="pab share" onclick="openShare()">
          <i class="bi bi-share-fill ico" aria-hidden="true"></i>
          <span>{{ v($data, 'hero.actions.share') }}</span>
        </button>
      </div>
    </div>

    <div class="section-space"></div>

    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-stars ico" aria-hidden="true"></i>
        </div>
        <div class="sec-title">{{ v($data, 'sections.categories.title') }}</div>
      </div>
      <div class="sec-body">
        <div class="cat-scroll">
          @foreach(a($data, 'sections.categories.items') as $item)
            @continue(!is_array($item))
            <div class="cat-chip{{ !empty($item['active']) ? ' active' : '' }}">{{ $item['label'] ?? '' }}</div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-stars ico" aria-hidden="true"></i>
        </div>
        <div class="sec-title">{{ v($data, 'sections.location.title') }}</div>
      </div>
      <div class="sec-body">
        <a class="address-link" href="#" onclick="return (openMaps(), false);">
          <div class="addr-icon-wrap">
            <i class="bi bi-stars ico" aria-hidden="true"></i>
          </div>
          <div class="addr-text">
            <strong>{{ v($data, 'sections.location.primary') }}</strong>
            <span>{{ v($data, 'sections.location.secondary') }}</span>
            <span class="map-btn"><i class="bi bi-geo-alt-fill ico-sm" aria-hidden="true"></i>
              <span>{{ v($data, 'sections.location.mapButton') }}</span>
            </span>
          </div>
        </a>
      </div>
    </div>

    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-stars ico" aria-hidden="true"></i>
        </div>
        <div class="sec-title">{{ v($data, 'sections.social.title') }}</div>
      </div>
      <div class="sec-body">
        <div class="social-list">
          @foreach(a($data, 'sections.social.items') as $item)
            @continue(!is_array($item))
            @php
                $action = (string) ($item['action'] ?? '');
                $iconKey = (string) ($item['icon'] ?? '');
                $iconClass = (string) ($item['iconClass'] ?? '');
            @endphp
            <div class="social-item"{{ $action !== '' ? ' onclick="' . e($action . '()') . '"' : '' }}>
              <div class="s-ico {{ $iconClass }}"><span class="social-icon">{!! $socialIcons[$iconKey] ?? '' !!}</span></div>
              <div>
                <div class="s-name">{{ $item['name'] ?? '' }}</div>
                <div class="s-val">{{ $item['value'] ?? '' }}</div>
              </div>
              <div class="s-arrow">
                <i class="bi bi-chevron-right ico-sm" aria-hidden="true"></i>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-stars ico" aria-hidden="true"></i>
        </div>
        <div class="sec-title">{{ v($data, 'sections.services.title') }}</div>
      </div>
      <div class="sec-body">
        <div class="services-grid">
          @foreach(a($data, 'sections.services.items') as $item)
            @continue(!is_array($item))
            <div class="svc-card">
              <div class="svc-img">
                <div class="svc-img-placeholder" style="background:url('{{ $item['image'] ?? '' }}') center/cover no-repeat"></div>
              </div>
              <div class="svc-body">
                <div class="svc-name">{{ $item['name'] ?? '' }}</div>
                <div class="svc-desc">{{ $item['description'] ?? '' }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-stars ico" aria-hidden="true"></i>
        </div>
        <div class="sec-title">{{ v($data, 'sections.products.title') }}</div>
      </div>
      <div class="sec-body">
        <div class="products-grid" id="productsGrid">
          @foreach ($products as $product)
            @continue(!is_array($product))
            @php
                $productId = (int) ($product['id'] ?? 0);
                $hasTag = (string) ($product['tag'] ?? '') !== '';
                $oldPrice = (int) ($product['oldPrice'] ?? 0);
            @endphp
            <div class="prod-card">
              <div class="prod-img">
                <div class="prod-img-placeholder" style="background:url('{{ $product['image'] ?? '' }}');height:100%;background-size:cover;background-position:center"></div>
                @if($hasTag)
                  <span class="prod-tag" style="background:{{ $product['tagColor'] ?? '#1a2744' }};color:#fff">{{ $product['tag'] ?? '' }}</span>
                @endif
              </div>
              <div class="prod-body">
                <div class="prod-name">{{ $product['name'] ?? '' }}</div>
                <div class="prod-desc">{{ ($product['author'] ?? '') . ' · ' . ($product['desc'] ?? '') }}</div>
                <div class="prod-footer">
                  <div>
                    <span class="prod-price">₹{{ $product['price'] ?? '' }}</span>
                    @if($oldPrice > 0)
                      <span class="prod-old">₹{{ $oldPrice }}</span>
                    @endif
                  </div>
                  <div class="qty-ctrl">
                    <button class="qty-btn" onclick="changeQty({{ $productId }},-1)"><i class="bi bi-dash-lg ico-sm" aria-hidden="true"></i></button>
                    <span class="qty-num" id="qty-{{ $productId }}">0</span>
                    <button class="qty-btn" onclick="changeQty({{ $productId }},1)"><i class="bi bi-plus-lg ico-sm" aria-hidden="true"></i></button>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-stars ico" aria-hidden="true"></i>
        </div>
        <div class="sec-title">{{ v($data, 'sections.gallery.title') }}</div>
      </div>
      <div class="sec-body">
        <div class="gallery-grid">
          @foreach(a($data, 'sections.gallery.images') as $image)
            <div class="g-item"><div style="height:100%;background:url('{{ $image }}') center/cover no-repeat"></div></div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-clock-fill ico" aria-hidden="true"></i>
        </div>
        <div class="sec-title">{{ v($data, 'sections.hours.title') }}</div>
      </div>
      <div class="sec-body">
        <div class="today-badge">
          <i class="bi bi-clock-fill ico" aria-hidden="true"></i>
          <span>{{ v($data, 'sections.hours.badge') }}</span>
        </div>
        <table class="hours-table">
          <tbody>
            @foreach(a($data, 'sections.hours.rows') as $item)
              @continue(!is_array($item))
              @php
                $openRow = !empty($item['open']) ? ' open-row' : '';
                $closedClass = !empty($item['closed']) ? ' closed' : '';
              @endphp
              <tr class="{{ trim($openRow) }}">
                <td class="day">{{ $item['day'] ?? '' }}</td>
                <td class="time{{ $closedClass }}">{{ $item['time'] ?? '' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <div class="suggest-link" onclick="emailShop()">
          <i class="bi bi-info-circle-fill ico-sm" aria-hidden="true"></i>
          <span>{{ v($data, 'sections.hours.suggest') }}</span>
        </div>
      </div>
    </div>

    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-qr-code-scan ico" aria-hidden="true"></i>
        </div>
        <div class="sec-title">{{ v($data, 'sections.qr.title') }}</div>
      </div>
      <div class="sec-body">
        <div class="qr-card-inner">
          <p style="font-size:0.79rem;color:var(--muted);margin-bottom:0.2rem;">{{ v($data, 'sections.qr.description') }}</p>
          <div id="vcardQR"></div>
          <div class="qr-actions">
            <button class="qr-btn" onclick="downloadQR()">
              <i class="bi bi-download ico-sm" aria-hidden="true"></i>
              <span>{{ v($data, 'sections.qr.download') }}</span>
            </button>
            <button class="qr-btn" onclick="copyLink()">
              <i class="bi bi-link-45deg ico-sm" aria-hidden="true"></i>
              <span>{{ v($data, 'sections.qr.copy') }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-stars ico" aria-hidden="true"></i>
        </div>
        <div class="sec-title">{{ v($data, 'sections.payments.title') }}</div>
      </div>
      <div class="sec-body">
        <div class="payment-list">
          @foreach (a($data, 'sections.payments.items') as $item)
            @continue(!is_array($item))
            @php $type = (string) ($item['type'] ?? ''); @endphp
            <div class="pay-item">
              <div class="pay-icon-wrap"><span class="pay-icon">{!! $paymentIcons[$type] ?? '' !!}</span></div>
              <div>
                <div class="pay-name">{{ $item['name'] ?? '' }}</div>
                <div class="pay-detail">{{ $item['detail'] ?? '' }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="sec">
      <div class="sec-header">
        <div class="sec-icon">
          <i class="bi bi-stars ico" aria-hidden="true"></i>
        </div>
        <div class="sec-title">{{ v($data, 'sections.contact.title') }}</div>
      </div>
      <div class="sec-body">
        <div id="contactForm">
          <div class="bf-row">
            <div class="bf-group">
              <label class="bf-label">{{ v($data, 'sections.contact.labels.name') }}</label>
              <input class="bf-input" id="cName" placeholder="{{ v($data, 'sections.contact.placeholders.name') }}" />
            </div>
            <div class="bf-group">
              <label class="bf-label">{{ v($data, 'sections.contact.labels.mobile') }}</label>
              <input class="bf-input" id="cPhone" placeholder="{{ v($data, 'sections.contact.placeholders.mobile') }}" type="tel" />
            </div>
          </div>
          <div class="bf-group">
            <label class="bf-label">{{ v($data, 'sections.contact.labels.email') }}</label>
            <input class="bf-input" id="cEmail" placeholder="{{ v($data, 'sections.contact.placeholders.email') }}" />
          </div>
          <div class="bf-group">
            <label class="bf-label">{{ v($data, 'sections.contact.labels.message') }}</label>
            <textarea class="bf-input" id="cMsg" placeholder="{{ v($data, 'sections.contact.placeholders.message') }}"></textarea>
          </div>
          <button class="cf-submit" onclick="submitContact()">
            <i class="bi bi-send-fill ico" aria-hidden="true"></i>
            <span>{{ v($data, 'sections.contact.submit') }}</span>
          </button>
        </div>
        <div class="contact-success" id="contactSuccess">
          <i class="bi bi-check-circle-fill ico-lg" aria-hidden="true"></i>
          <h4>{{ v($data, 'sections.contact.successTitle') }}</h4>
          <p>{{ v($data, 'sections.contact.successText') }}</p>
          <button style="margin-top:1rem;background:0 0;border:none;color:var(--navy);font-weight:700;cursor:pointer;font-size:0.84rem;" onclick="resetContact()">
            {{ v($data, 'sections.contact.successAction') }}
          </button>
        </div>
      </div>
    </div>

    <div class="vcard-footer">
      <span>{{ v($data, 'footer.prefix') }}</span>
      <strong>{{ v($data, 'shop.name') }}</strong>
      <span>{{ v($data, 'footer.suffix') }}</span>
    </div>

    <div class="float-bar">
      <button class="fab call-fab" onclick="callShop()">
        <i class="bi bi-telephone-fill ico-lg" aria-hidden="true"></i>
        <span>{{ v($data, 'floatingBar.call') }}</span>
      </button>
      <button class="fab wa-fab" onclick="openWA()">
        <i class="bi bi-whatsapp ico-lg" aria-hidden="true"></i>
        <span>{{ v($data, 'floatingBar.whatsapp') }}</span>
      </button>
      <button class="fab save-fab" onclick="saveContact()">
        <i class="bi bi-person-vcard-fill ico-lg" aria-hidden="true"></i>
        <span>{{ v($data, 'floatingBar.save') }}</span>
      </button>
      <div class="fab-wrap" onclick="openCart()">
        <span class="cart-badge" id="cartBadge"></span>
        <i class="bi bi-cart3 ico-lg" aria-hidden="true"></i>
        <span>{{ v($data, 'floatingBar.cart') }}</span>
      </div>
    </div>

    <div class="cart-overlay" id="cartOverlay" onclick="closeCartOutside(event)">
      <div class="cart-box">
        <div class="cart-header">
          <div class="cart-title">
            <i class="bi bi-cart3 ico" aria-hidden="true"></i>
            <span>{{ v($data, 'cart.title') }}</span>
          </div>
          <button class="cart-close" onclick="closeCart()">
            <i class="bi bi-x-lg ico-sm" aria-hidden="true"></i>
          </button>
        </div>
        <div id="cartBody"></div>
      </div>
    </div>

    <div class="modal-overlay" id="shareModal" onclick="closeShare(event)">
      <div class="modal-box">
        <div class="modal-title">{{ v($data, 'share.title') }}</div>
        <div class="share-options">
          @foreach(a($data, 'share.options') as $item)
            @php
            if (!is_array($item)) { continue; }
            $key = (string) ($item['key'] ?? '');
            $action = (string) ($item['action'] ?? '');
@endphp
            <div class="share-opt" onclick="{{ $action }}()">
              <span class="share-icon">{!! $shareIcons[$key] ?? '' !!}</span>
              <span class="share-label">{{ $item['label'] ?? '' }}</span>
            </div>
          @endforeach
        </div>
        <button class="modal-close-btn" onclick="document.getElementById('shareModal').classList.remove('show')">
          {{ v($data, 'share.cancel') }}
        </button>
      </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
      window.__ACTION_DATA__ = {!! $actionDataJson !!};
      window.__VCARD_SUBDOMAIN__ = {!! json_encode($subdomain) !!};
    </script>
    <script src="{{ $assetBase }}script.js"></script>
  </body>
</html>
