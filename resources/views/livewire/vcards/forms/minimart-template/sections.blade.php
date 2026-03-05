{{-- minimart-template/sections.blade.php — sub-section editor with Bootstrap tabs --}}
@php
    $tabDefs = [
        'location'  => ['icon'=>'mdi-map-marker-outline',   'label'=>'Location'],
        'hours'     => ['icon'=>'mdi-clock-outline',         'label'=>'Hours'],
        'social'    => ['icon'=>'mdi-share-variant-outline', 'label'=>'Social'],
        'categories'=> ['icon'=>'mdi-tag-multiple-outline',  'label'=>'Categories'],
        'picks'     => ['icon'=>'mdi-star-outline',          'label'=>'Picks'],
        'deals'     => ['icon'=>'mdi-sale-outline',          'label'=>'Deals'],
        'gallery'   => ['icon'=>'mdi-image-multiple-outline','label'=>'Gallery'],
        'payments'  => ['icon'=>'mdi-credit-card-outline',   'label'=>'Payments'],
        'qr'        => ['icon'=>'mdi-qrcode',                'label'=>'QR'],
        'contact'   => ['icon'=>'mdi-email-outline',         'label'=>'Contact'],
        'cart'      => ['icon'=>'mdi-cart-outline',          'label'=>'Cart'],
        'share'     => ['icon'=>'mdi-share-outline',         'label'=>'Share'],
    ];
    $activeKeys = array_intersect_key($tabDefs, $form);
    $firstKey   = array_key_first($activeKeys) ?? 'location';
    $hoursRows  = $form['hours']['rows'] ?? [];
@endphp

<div class="col-12">
    {{-- Tab Nav --}}
    <ul class="nav nav-tabs flex-nowrap overflow-auto mb-0" id="mmSectionTabs" role="tablist"
        style="border-bottom:2px solid #e2e8f0;-webkit-overflow-scrolling:touch;scrollbar-width:none;">
        @foreach($activeKeys as $sk => $sMeta)
        <li class="nav-item flex-shrink-0" role="presentation">
            <button class="nav-link px-3 py-2 {{ $sk === $firstKey ? 'active' : '' }}"
                    id="mm-tab-{{ $sk }}"
                    data-bs-toggle="tab"
                    data-bs-target="#mm-pane-{{ $sk }}"
                    type="button" role="tab"
                    style="font-size:.75rem;white-space:nowrap;border-radius:6px 6px 0 0;">
                <i class="mdi {{ $sMeta['icon'] }} me-1"></i>{{ $sMeta['label'] }}
            </button>
        </li>
        @endforeach
    </ul>

    {{-- Tab Panes --}}
    <div class="tab-content border border-top-0 rounded-bottom p-3" style="background:#fff;">

        {{-- ── LOCATION ── --}}
        @if(isset($form['location']))
        <div class="tab-pane fade {{ $firstKey === 'location' ? 'show active' : '' }}"
             id="mm-pane-location" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.location.title" placeholder="Our Location">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold mb-1">Address Line 1</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.location.addressLine1" placeholder="42 Green Park Market, Sector 5">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold mb-1">Address Line 2</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.location.addressLine2" placeholder="New Delhi, Delhi – 110016">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold mb-1">Map Button Label</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.location.mapLabel" placeholder="Open in Maps">
                </div>
            </div>
        </div>
        @endif

        {{-- ── HOURS ── --}}
        @if(isset($form['hours']))
        <div class="tab-pane fade {{ $firstKey === 'hours' ? 'show active' : '' }}"
             id="mm-pane-hours" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.hours.title" placeholder="Business Hours">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold mb-1">Today Label</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.hours.todayLabel" placeholder="Open Now · Closes 10 PM">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold mb-1">Suggest Label</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.hours.suggestLabel" placeholder="Suggest new hours">
                </div>
                {{-- Rows header --}}
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
                            <i class="mdi mdi-table me-1"></i>Weekly Rows
                            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($hoursRows) }}</span>
                        </span>
                        <button type="button" class="btn btn-sm btn-primary px-3"
                                wire:click="addRowAndSave('hours.rows',['day','time','status'])">
                            <i class="mdi mdi-plus me-1"></i>Add Row
                        </button>
                    </div>
                </div>
                {{-- Rows list --}}
                @if(empty($hoursRows))
                <div class="col-12">
                    <div class="text-center py-4 rounded-3"
                         style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px dashed #cbd5e1;">
                        <i class="mdi mdi-clock-plus fs-1 text-muted mb-2 d-block"></i>
                        <p class="fw-semibold text-muted mb-2 small">No rows added yet</p>
                        <button type="button" class="btn btn-sm btn-primary"
                                wire:click="addRowAndSave('hours.rows',['day','time','status'])">
                            <i class="mdi mdi-plus me-1"></i>Add First Row
                        </button>
                    </div>
                </div>
                @else
                @foreach($hoursRows as $ri => $row)
                <div class="col-12" wire:key="mmhr-{{ $ri }}">
                    <div class="border rounded-3 p-2" style="background:#f8fafc;">
                        <div class="row g-2 align-items-center">
                            <div class="col-sm-3">
                                <label class="form-label small mb-1 fw-semibold" style="font-size:.75rem;">Day</label>
                                <input type="text" class="form-control form-control-sm"
                                       wire:model="form.hours.rows.{{ $ri }}.day" placeholder="Monday">
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label small mb-1 fw-semibold" style="font-size:.75rem;">Status</label>
                                <select class="form-select form-select-sm"
                                        wire:model="form.hours.rows.{{ $ri }}.status">
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small mb-1 fw-semibold" style="font-size:.75rem;">Time</label>
                                <input type="text" class="form-control form-control-sm"
                                       wire:model="form.hours.rows.{{ $ri }}.time"
                                       placeholder="7:00 am – 10:00 pm">
                            </div>
                            <div class="col-sm-1 d-flex align-items-end pb-1">
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                        style="width:28px;height:28px;"
                                        wire:click="removeRow('hours.rows',{{ $ri }})"
                                        wire:confirm="Delete this row?">
                                    <i class="mdi mdi-delete" style="font-size:12px;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        @endif

        {{-- ── SOCIAL ── --}}
        @if(isset($form['social']))
        <div class="tab-pane fade {{ $firstKey === 'social' ? 'show active' : '' }}"
             id="mm-pane-social" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.social.title" placeholder="Social & Connect">
                </div>
            </div>
        </div>
        @endif

        {{-- ── CATEGORIES ── --}}
        @if(isset($form['categories']))
        <div class="tab-pane fade {{ $firstKey === 'categories' ? 'show active' : '' }}"
             id="mm-pane-categories" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.categories.title" placeholder="Product Categories">
                </div>
            </div>
        </div>
        @endif

        {{-- ── PICKS ── --}}
        @if(isset($form['picks']))
        <div class="tab-pane fade {{ $firstKey === 'picks' ? 'show active' : '' }}"
             id="mm-pane-picks" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.picks.title" placeholder="Today's Picks">
                </div>
            </div>
        </div>
        @endif

        {{-- ── DEALS ── --}}
        @if(isset($form['deals']))
        <div class="tab-pane fade {{ $firstKey === 'deals' ? 'show active' : '' }}"
             id="mm-pane-deals" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.deals.title" placeholder="Today's Special Deals">
                </div>
            </div>
        </div>
        @endif

        {{-- ── GALLERY ── --}}
        @if(isset($form['gallery']))
        <div class="tab-pane fade {{ $firstKey === 'gallery' ? 'show active' : '' }}"
             id="mm-pane-gallery" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.gallery.title" placeholder="Store Gallery">
                </div>
            </div>
        </div>
        @endif

        {{-- ── PAYMENTS ── --}}
        @if(isset($form['payments']))
        <div class="tab-pane fade {{ $firstKey === 'payments' ? 'show active' : '' }}"
             id="mm-pane-payments" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.payments.title" placeholder="Payment Methods">
                </div>
            </div>
        </div>
        @endif

        {{-- ── QR ── --}}
        @if(isset($form['qr']))
        <div class="tab-pane fade {{ $firstKey === 'qr' ? 'show active' : '' }}"
             id="mm-pane-qr" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.qr.title" placeholder="Scan & Save Our Contact">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Help Text</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.qr.helpText" placeholder="Scan QR code to visit this page & save contact">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold mb-1">Download Button</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.qr.download" placeholder="Download QR">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold mb-1">Copy Button</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.qr.copy" placeholder="Copy Link">
                </div>
            </div>
        </div>
        @endif

        {{-- ── CONTACT ── --}}
        @if(isset($form['contact']))
        <div class="tab-pane fade {{ $firstKey === 'contact' ? 'show active' : '' }}"
             id="mm-pane-contact" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.contact.title" placeholder="Contact Us">
                </div>
                <div class="col-12">
                    <p class="small fw-semibold text-muted mb-1 text-uppercase" style="font-size:.68rem;letter-spacing:.06em;">Form Labels &amp; Placeholders</p>
                </div>
                @foreach([
                    ['name','Name'],['phone','Mobile'],['email','Email'],['message','Message']
                ] as [$fk,$fl])
                <div class="col-md-3">
                    <label class="form-label small mb-1 fw-semibold">{{ $fl }} Placeholder</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.contact.form.{{ $fk }}Placeholder"
                           placeholder="{{ $fl }} placeholder">
                </div>
                @endforeach
                <div class="col-md-4">
                    <label class="form-label small mb-1 fw-semibold">Submit Button</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.contact.form.submit" placeholder="Send Message">
                </div>
                <div class="col-12">
                    <p class="small fw-semibold text-muted mb-1 text-uppercase" style="font-size:.68rem;letter-spacing:.06em;">Success State</p>
                </div>
                <div class="col-md-4">
                    <label class="form-label small mb-1 fw-semibold">Success Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.contact.success.title" placeholder="Message Sent!">
                </div>
                <div class="col-md-5">
                    <label class="form-label small mb-1 fw-semibold">Success Text</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.contact.success.text" placeholder="We'll get back to you soon.">
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1 fw-semibold">Success Button</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.contact.success.button" placeholder="Send Another">
                </div>
            </div>
        </div>
        @endif

        {{-- ── CART ── --}}
        @if(isset($form['cart']))
        <div class="tab-pane fade {{ $firstKey === 'cart' ? 'show active' : '' }}"
             id="mm-pane-cart" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.cart.title" placeholder="Your Cart">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold mb-1">Total Label</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.cart.totalLabel" placeholder="Total">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold mb-1">Order Button Label</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.cart.orderLabel" placeholder="Order via WhatsApp">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Empty Cart HTML</label>
                    <textarea class="form-control form-control-sm" rows="2"
                              wire:model="form.cart.emptyHtml"
                              placeholder="Your cart is empty.&lt;br /&gt;Add items!"></textarea>
                </div>
            </div>
        </div>
        @endif

        {{-- ── SHARE ── --}}
        @if(isset($form['share']))
        <div class="tab-pane fade {{ $firstKey === 'share' ? 'show active' : '' }}"
             id="mm-pane-share" role="tabpanel">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Section Title</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.share.title" placeholder="Share This Card">
                </div>
                @foreach([
                    ['whatsapp','WhatsApp'],['facebook','Facebook'],
                    ['copy','Copy Link'],['more','More'],['cancel','Cancel']
                ] as [$bk,$bl])
                <div class="col-md-3">
                    <label class="form-label small mb-1 fw-semibold">{{ $bl }} Label</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.share.{{ $bk }}" placeholder="{{ $bl }}">
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>{{-- /.tab-content --}}
</div>
