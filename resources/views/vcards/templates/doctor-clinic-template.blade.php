@php
    require_once resource_path('views/vcards/icons/doctor-clinic-template.php');

    $bannerImage  = data_get($data, 'assets.bannerImage', '');
    $profileImage = data_get($data, 'assets.profileImage', data_get($data, 'assets.fallbackImage', ''));
    $profileAlt   = data_get($data, 'assets.profileAlt', data_get($data, 'doctor.name', ''));
    $serviceImage = data_get($data, 'assets.serviceImage', '');

    $slots = data_get($data, 'appointment.slots', []);
    if (!is_array($slots)) { $slots = []; }
    $selectedSlot = '';
    foreach ($slots as $slotItem) {
        if (!empty($slotItem['selected']) && empty($slotItem['full'])) { $selectedSlot = $slotItem['slot'] ?? ''; break; }
    }
    if (!$selectedSlot) { $selectedSlot = (string) data_get($data, 'appointment.defaultSlot', ''); }
    if (!$selectedSlot) {
        foreach ($slots as $slotItem) { if (empty($slotItem['full'])) { $selectedSlot = $slotItem['slot'] ?? ''; break; } }
    }
    $socialIconClasses = ['whatsapp'=>'ic-wa','facebook'=>'ic-fb','youtube'=>'ic-yt','website'=>'ic-web'];
@endphp
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
        <title>{{ data_get($data, 'meta.title') }}</title>
        <meta name="description" content="{{ data_get($data, 'meta.description', '') }}">
        <meta name="keywords" content="{{ data_get($data, 'meta.keywords', '') }}">
        <meta property="og:title" content="{{ data_get($data, 'meta.title', '') }}">
        <meta property="og:description" content="{{ data_get($data, 'meta.description', '') }}">
        @if(data_get($data, 'meta.og_image'))
        <meta property="og:image" content="{{ url(data_get($data, 'meta.og_image')) }}">
        @endif
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <link rel="stylesheet" href="{{ $assetBase }}style.css" />
    </head>
    <body>
        <main id="app-root" aria-live="polite" style="min-height:100vh">
            <div class="banner">
                <div class="banner-bg"@if($bannerImage) style="background:url('{{ $bannerImage }}') center/cover no-repeat"@endif>
                    <div class="banner-pattern"></div>
                    <div class="banner-ecg">
                        <svg viewBox="0 0 480 48" fill="none" preserveAspectRatio="none">
                            <polyline points="0,24 60,24 75,6 90,42 105,10 120,38 135,24 200,24 215,12 230,36 245,4 260,44 275,24 480,24" stroke="white" stroke-width="2.5" fill="none" />
                        </svg>
                    </div>
                </div>
                <div class="banner-top-bar">
                    <button class="share-btn" onclick="openShare()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#fff" stroke-width="2"><circle cx="18" cy="5" r="3" /><circle cx="6" cy="12" r="3" /><circle cx="18" cy="19" r="3" /><line x1="8.59" y1="13.51" x2="15.42" y2="17.49" /><line x1="15.41" y1="6.51" x2="8.59" y2="10.49" /></svg>
                        <span id="banner-share-label">Share</span>
                    </button>
                    <button class="save-btn-top" onclick="saveContact()">
                        <svg class="ic-sm" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span id="banner-save-label">Save Contact</span>
                    </button>
                </div>
            </div>

            <div class="status-bar">
                <div class="status-open">
                    <div class="dot-pulse"></div>
                    <span id="status-open-label">{{ data_get($data, 'status.openLabel') }}</span>
                </div>
                <div class="next-slot" id="status-next-slot">{{ data_get($data, 'status.nextSlot') }}</div>
            </div>

            <div class="profile-card">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">
                        <img id="profile-image" src="{{ $profileImage }}" alt="{{ $profileAlt }}" style="width:100%;height:100%;object-fit:cover" />
                    </div>
                    <span class="reg-tag" id="profile-reg-tag">{{ data_get($data, 'doctor.regTag') }}</span>
                </div>
                <div class="profile-name" id="profile-name">{{ data_get($data, 'doctor.name') }}</div>
                <div class="profile-role" id="profile-role">{{ data_get($data, 'doctor.role') }}</div>
                <div class="profile-qual" id="profile-qualification">{{ data_get($data, 'doctor.qualification') }}</div>
                <div class="profile-action-btns">
                    <button class="pab call" onclick="callClinic()">
                        <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.37 2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 5.09 5.09l1.32-1.32a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 14.92z" /></svg>
                        <span id="action-call">Call</span>
                    </button>
                    <button class="pab whatsapp" onclick="openWA()">
                        <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" /></svg>
                        <span id="action-whatsapp">WhatsApp</span>
                    </button>
                    <button class="pab save" onclick="saveContact()">
                        <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" /><polyline points="17 21 17 13 7 13 7 21" /><polyline points="7 3 7 8 15 8" /></svg>
                        <span id="action-save">Save</span>
                    </button>
                    <button class="pab email" onclick="emailClinic()">
                        <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" /><polyline points="22,6 12,13 2,6" /></svg>
                        <span id="action-email">Email</span>
                    </button>
                    <button class="pab direction" onclick="openMaps()">
                        <svg viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11" /></svg>
                        <span id="action-direction">Directions</span>
                    </button>
                    <button class="pab share" onclick="openShare()">
                        <svg viewBox="0 0 24 24"><circle cx="18" cy="5" r="3" /><circle cx="6" cy="12" r="3" /><circle cx="18" cy="19" r="3" /><line x1="8.59" y1="13.51" x2="15.42" y2="17.49" /><line x1="15.41" y1="6.51" x2="8.59" y2="10.49" /></svg>
                        <span id="action-share">Share</span>
                    </button>
                </div>
            </div>

            @if(vcard_section_enabled($data, 'specializations'))
            <div class="sec sec-top">
                <div class="sec-header">
                    <div class="sec-icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /></svg></div>
                    <div class="sec-title" id="sec-title-specializations">Specializations</div>
                </div>
                <div class="sec-body">
                    <div class="spec-chips" id="specializationChips">
                        @foreach(data_get($data, 'specializations', []) as $item)
                            @php $iconKey = 'chip_' . ($item['icon'] ?? ''); @endphp
                            <span class="chip {{ $item['tone'] ?? '' }}">{!! getIcon($iconKey) !!} {{ $item['name'] ?? '' }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'appointment'))
            <div class="sec" id="appointmentSection">
                <div class="sec-header">
                    <div class="sec-icon"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" /></svg></div>
                    <div class="sec-title" id="sec-title-appointment">Book Appointment</div>
                </div>
                <div class="sec-body">
                    <div id="apptForm">
                        <div class="appt-slots" id="slotGrid">
                            @foreach($slots as $slotItem)
                                @php
                                    $slotLabel  = $slotItem['slot'] ?? '';
                                    $isFull     = !empty($slotItem['full']);
                                    $isSelected = !$isFull && $selectedSlot && $slotLabel === $selectedSlot;
                                @endphp
                                @if($isFull)
                                    <div class="slot-card full" data-slot="{{ $slotLabel }}">
                                        <div class="slot-session">{{ $slotItem['session'] ?? '' }}</div>
                                        <div class="slot-time">{{ $slotItem['time'] ?? '' }}</div>
                                        <div class="slot-full">{{ $slotItem['fullLabel'] ?? '' }}</div>
                                    </div>
                                @else
                                    <div class="slot-card{{ $isSelected ? ' selected' : '' }}" onclick="selectSlot(this)" data-slot="{{ $slotLabel }}">
                                        <div class="slot-check"><svg viewBox="0 0 24 24">{!! getIcon('ui_check') !!}</svg></div>
                                        <div class="slot-session">{{ $slotItem['session'] ?? '' }}</div>
                                        <div class="slot-time">{{ $slotItem['time'] ?? '' }}</div>
                                        <div class="slot-avail"><svg viewBox="0 0 24 24">{!! getIcon('chip_info') !!}</svg> {{ $slotItem['availability'] ?? '' }}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="bf-row">
                            <div class="bf-group">
                                <label class="bf-label" id="label-name">{{ data_get($data, 'appointment.form.nameLabel') }}</label>
                                <input class="bf-input" id="aName" placeholder="{{ data_get($data, 'appointment.form.namePlaceholder') }}" type="text" />
                            </div>
                            <div class="bf-group">
                                <label class="bf-label" id="label-phone">{{ data_get($data, 'appointment.form.phoneLabel') }}</label>
                                <input class="bf-input" id="aPhone" placeholder="{{ data_get($data, 'appointment.form.phonePlaceholder') }}" type="tel" />
                            </div>
                        </div>
                        <div class="bf-row">
                            <div class="bf-group">
                                <label class="bf-label" id="label-age">{{ data_get($data, 'appointment.form.ageLabel') }}</label>
                                <input class="bf-input" id="aAge" placeholder="{{ data_get($data, 'appointment.form.agePlaceholder') }}" type="number" />
                            </div>
                            <div class="bf-group">
                                <label class="bf-label" id="label-visit-type">{{ data_get($data, 'appointment.form.visitTypeLabel') }}</label>
                                <select class="bf-input" id="aType">
                                    @foreach(data_get($data, 'appointment.form.visitTypes', []) as $item)
                                        <option value="{{ $item['value'] ?? '' }}">{{ $item['label'] ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="bf-group">
                            <label class="bf-label" id="label-complaint">{{ data_get($data, 'appointment.form.complaintLabel') }}</label>
                            <input class="bf-input" id="aComplaint" placeholder="{{ data_get($data, 'appointment.form.complaintPlaceholder') }}" type="text" />
                        </div>
                        <button class="bf-submit" onclick="bookAppointment()">
                            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" /></svg>
                            <span id="appointment-submit">Confirm Appointment via WhatsApp</span>
                        </button>
                    </div>
                    <div class="appt-success" id="apptSuccess">
                        <div class="appt-success-icon"><svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg></div>
                        <h4 id="appointment-success-title">{{ data_get($data, 'appointment.success.title') }}</h4>
                        <p id="appointment-success-text">{{ data_get($data, 'appointment.success.text') }}</p>
                        <button class="appt-reset" onclick="resetAppt()"><span id="appointment-success-button">Book Another</span></button>
                    </div>
                </div>
            </div>
            @endif

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon"><svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2" /></svg></div>
                    <div class="sec-title" id="sec-title-conditions">Conditions Treated</div>
                </div>
                <div class="sec-body">
                    <div class="services-grid" id="conditionsGrid">
                        @foreach(data_get($data, 'conditions', []) as $item)
                            @php $query = $item['query'] ?? $item['name'] ?? ''; $image = $item['image'] ?? $serviceImage; @endphp
                            <div class="svc-card" onclick="enquireWA({!! vcard_js_str($query) !!})">
                                <div class="svc-img" style="background:url('{{ $image }}') center/cover no-repeat;"></div>
                                <div class="svc-body">
                                    <div class="svc-name">{{ $item['name'] ?? '' }}</div>
                                    <div class="svc-desc">{{ $item['desc'] ?? '' }}</div>
                                    <div class="svc-wa">
                                        <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                        Enquire
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23" /><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" /></svg></div>
                    <div class="sec-title" id="sec-title-fees">Consultation Fees</div>
                </div>
                <div class="sec-body">
                    <div class="fees-list" id="feesList">
                        @foreach(data_get($data, 'fees.items', []) as $item)
                            <div class="fee-item">
                                <div class="fee-left"><div><div class="fee-name">{{ $item['name'] ?? '' }}</div><div class="fee-note">{{ $item['note'] ?? '' }}</div></div></div>
                                <div>
                                    <span class="fee-amount">{{ $item['amount'] ?? '' }}</span>
                                    @if(!empty($item['oldAmount']))<span class="fee-old">{{ $item['oldAmount'] }}</span>@endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="insurance-note">
                        <svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /></svg>
                        <span id="fees-insurance-note">{{ data_get($data, 'fees.insuranceNote') }}</span>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg></div>
                    <div class="sec-title" id="sec-title-hours">Clinic Hours</div>
                </div>
                <div class="sec-body">
                    <div class="today-badge">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
                        <span id="hours-today">{{ data_get($data, 'hours.todayLabel') }}</span>
                    </div>
                    <table class="hours-table" id="hoursTable">
                        @foreach(data_get($data, 'hours.rows', []) as $row)
                            <tr class="{{ $row['rowClass'] ?? '' }}">
                                <td class="day">{{ $row['day'] ?? '' }}</td>
                                <td class="session">{{ $row['session'] ?? '' }}</td>
                                <td class="time {{ $row['timeClass'] ?? '' }}">{{ $row['time'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="suggest-link" style="display:flex;align-items:center;gap:0.4rem;color:var(--teal);font-size:0.78rem;font-weight:600;cursor:pointer;margin-top:0.7rem;padding-top:0.6rem;border-top:1px solid var(--border);" onclick="openWA()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" /></svg>
                        <span id="hours-suggest-link">{{ data_get($data, 'hours.suggestLink') }}</span>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="6" /><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11" /></svg></div>
                    <div class="sec-title" id="sec-title-awards">Achievements &amp; Certifications</div>
                </div>
                <div class="sec-body">
                    <div class="awards-list" id="awardsList">
                        @foreach(data_get($data, 'awards', []) as $item)
                            <div class="award-item">
                                <div class="award-icon">{!! getIcon('award_medal') !!}</div>
                                <div><div class="award-name">{{ $item['name'] ?? '' }}</div><div class="award-desc">{{ $item['desc'] ?? '' }}</div></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" /><circle cx="12" cy="10" r="3" /></svg></div>
                    <div class="sec-title" id="sec-title-location">Clinic Location</div>
                </div>
                <div class="sec-body">
                    <a class="address-link" href="#" onclick="return (openMaps(), !1);">
                        <div class="addr-icon-wrap"><svg class="ic" viewBox="0 0 24 24" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" /><circle cx="12" cy="10" r="3" /></svg></div>
                        <div class="addr-text">
                            <strong id="location-clinic-name">{{ data_get($data, 'location.clinicName') }}</strong>
                            <span id="location-line1">{{ data_get($data, 'location.line1') }}</span><br />
                            <span id="location-line2">{{ data_get($data, 'location.line2') }}</span>
                            <span class="map-btn">
                                <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11" /></svg>
                                <span id="location-map-label">Get Directions</span>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><line x1="2" y1="12" x2="22" y2="12" /><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" /></svg></div>
                    <div class="sec-title" id="sec-title-social">Connect Online</div>
                </div>
                <div class="sec-body">
                    <div class="social-list" id="socialList">
                        @foreach(data_get($data, 'social', []) as $item)
                            @php
                                $type      = $item['type'] ?? 'website';
                                $iconKey   = 'social_' . $type;
                                $iconClass = $socialIconClasses[$type] ?? $socialIconClasses['website'];
                                $onclick   = ($item['action'] ?? '') === 'openWA' ? 'openWA()' : (!empty($item['url']) ? 'openExternal(' . vcard_js_str($item['url']) . ')' : '');
                            @endphp
                            <div class="social-item"@if($onclick) onclick="{{ $onclick }}"@endif>
                                <div class="s-ico {{ $iconClass }}">{!! getIcon($iconKey) !!}</div>
                                <div><div class="s-name">{{ $item['name'] ?? '' }}</div><div class="s-val">{{ $item['value'] ?? '' }}</div></div>
                                <div class="s-arrow"><svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2.5"><polyline points="9 18 15 12 9 6" /></svg></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon"><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2" /><line x1="1" y1="10" x2="23" y2="10" /></svg></div>
                    <div class="sec-title" id="sec-title-payments">Payment Modes</div>
                </div>
                <div class="sec-body">
                    <div class="payment-list" id="paymentList">
                        @foreach(data_get($data, 'payments', []) as $item)
                            @php
                                $pIcon = $item['icon'] ?? '';
                                if ($pIcon === '') {
                                    $lbl = strtolower((string)($item['name'] ?? ''));
                                    $pIcon = str_contains($lbl,'upi')||str_contains($lbl,'qr') ? 'upi' : (str_contains($lbl,'card') ? 'card' : (str_contains($lbl,'bank') ? 'bank' : 'cash'));
                                }
                            @endphp
                            <div class="pay-item">
                                <div class="pay-icon-wrap"><span style="display:flex;color:{{ $item['stroke'] ?? '#0d9488' }}">{!! getIcon('pay_' . $pIcon) !!}</span></div>
                                <div><div class="pay-name">{{ $item['name'] ?? '' }}</div><div class="pay-detail">{{ $item['detail'] ?? '' }}</div></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1" /><rect x="14" y="3" width="7" height="7" rx="1" /><rect x="3" y="14" width="7" height="7" rx="1" /><rect x="14" y="14" width="3" height="3" /><rect x="18" y="14" width="3" height="3" /><rect x="14" y="18" width="3" height="3" /><rect x="18" y="18" width="3" height="3" /></svg></div>
                    <div class="sec-title" id="sec-title-contact-save">Save Doctor's Contact</div>
                </div>
                <div class="sec-body">
                    <div class="qr-card-inner">
                        <div style="font-size:0.79rem;color:var(--muted);margin-bottom:0.3rem;" id="qr-note">{{ data_get($data, 'qr.note') }}</div>
                        <div id="vcardQR"></div>
                        <div class="qr-actions">
                            <button class="qr-btn" onclick="saveContact()">
                                <svg viewBox="0 0 24 24" width="15" height="15"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" /><polyline points="17 21 17 13 7 13 7 21" /><polyline points="7 3 7 8 15 8" /></svg>
                                <span id="qr-save-label">Save Contact</span>
                            </button>
                            <button class="qr-btn" onclick="downloadQR()">
                                <svg viewBox="0 0 24 24" width="15" height="15"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="7 10 12 15 17 10" /><line x1="12" y1="15" x2="12" y2="3" /></svg>
                                <span id="qr-download-label">Download QR</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div style="text-align:center;padding:1.4rem 1rem 1rem;font-size:0.72rem;color:var(--muted);">
                <span id="footer-line1">{{ data_get($data, 'footer.line1') }}</span><br />
                <strong style="color:var(--teal)" id="footer-line2">{{ data_get($data, 'footer.line2') }}</strong><br />
                <span id="footer-line3">{{ data_get($data, 'footer.line3') }}</span><br />
                <span style="font-size:0.65rem;color:#aaa" id="footer-line4">{{ data_get($data, 'footer.line4') }}</span>
            </div>

            <div class="float-bar">
                <button class="fab call-fab" onclick="callClinic()">
                    <svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.9"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.37 2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 5.09 5.09l1.32-1.32a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 14.92z" /></svg>
                    <span id="fab-call-label">Call</span>
                </button>
                <button class="fab wa-fab" onclick="openWA()">
                    <svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.9"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" /></svg>
                    <span id="fab-whatsapp-label">WhatsApp</span>
                </button>
                <button class="fab appt-fab" onclick="openAppointment()">
                    <svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.9"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" /></svg>
                    <span id="fab-appointment-label">Book Appt.</span>
                </button>
                <button class="fab save-fab" onclick="saveContact()">
                    <svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.9"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" /><polyline points="17 21 17 13 7 13 7 21" /><polyline points="7 3 7 8 15 8" /></svg>
                    <span id="fab-save-label">Save</span>
                </button>
            </div>

            <div class="modal-overlay" id="shareModal" onclick="closeShare(event)">
                <div class="modal-box">
                    <div class="modal-title" id="share-title">Share</div>
                    <div class="share-options">
                        <div class="sh-opt" onclick="shareWA()" style="color:#128c7e">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="#128c7e" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" /></svg>
                            <span id="share-whatsapp-label">WhatsApp</span>
                        </div>
                        <div class="sh-opt" onclick="copyLink()" style="color:var(--teal)">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="var(--teal)" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2" /><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" /></svg>
                            <span id="share-copy-label">Copy Link</span>
                        </div>
                        <div class="sh-opt" onclick="shareNative()" style="color:var(--blue)">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="var(--blue)" stroke-width="2"><circle cx="18" cy="5" r="3" /><circle cx="6" cy="12" r="3" /><circle cx="18" cy="19" r="3" /><line x1="8.59" y1="13.51" x2="15.42" y2="17.49" /><line x1="15.41" y1="6.51" x2="8.59" y2="10.49" /></svg>
                            <span id="share-more-label">More…</span>
                        </div>
                        <div class="sh-opt" onclick="shareFB()" style="color:#1877f2">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="#1877f2" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" /></svg>
                            <span id="share-facebook-label">Facebook</span>
                        </div>
                    </div>
                    <button class="modal-cancel" onclick="closeShareModal()"><span id="share-cancel-label">Cancel</span></button>
                </div>
            </div>

            <div class="promo-overlay" id="promoOverlay" onclick="closePromo(event)">
                <div class="promo-box" onclick="event.stopPropagation()">
                    <button class="promo-close" onclick="closePromo()"><svg viewBox="0 0 24 24" width="16" height="16"><line x1="18" y1="6" x2="6" y2="18" stroke-width="2" /><line x1="6" y1="6" x2="18" y2="18" stroke-width="2" /></svg></button>
                    <div class="promo-icon"><svg viewBox="0 0 24 24" width="28" height="28">{!! getIcon('social_whatsapp') !!}</svg></div>
                    <h3 id="promo-title">{{ data_get($data, 'promo.title') }}</h3>
                    <p id="promo-text">{{ data_get($data, 'promo.text') }}</p>
                    <button class="promo-cta" onclick="promoAction()">
                        <svg viewBox="0 0 24 24" width="18" height="18">{!! getIcon('social_whatsapp') !!}</svg>
                        <span id="promo-cta-label">{{ data_get($data, 'promo.ctaLabel') }}</span>
                    </button>
                </div>
            </div>

            <div class="toast" id="toast">
                <svg viewBox="0 0 24 24" stroke-width="2" width="15" height="15">{!! getIcon('ui_check') !!}</svg>
                <span id="toastMsg">{{ data_get($data, 'messages.defaultToast') }}</span>
            </div>

            <div class="icon-templates" style="display:none;">
                @foreach(['chip_pulse','chip_heart','chip_info','chip_respiratory','chip_home','chip_search','chip_preventive','tip_sun','tip_heart','tip_drop','tip_cup','award_medal','award_pulse','award_book','award_patients'] as $ic)
                    <span id="icon-{{ str_replace('_','-',$ic) }}"><svg viewBox="0 0 24 24">{!! getIcon($ic) !!}</svg></span>
                @endforeach
                @foreach(['social_whatsapp','social_facebook','social_youtube','social_website'] as $ic)
                    <span id="icon-{{ str_replace('_','-',$ic) }}"><svg class="ic" viewBox="0 0 24 24" stroke-width="2">{!! getIcon($ic) !!}</svg></span>
                @endforeach
                @foreach(['pay_cash','pay_card','pay_shield'] as $ic)
                    <span id="icon-{{ str_replace('_','-',$ic) }}"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="2">{!! getIcon($ic) !!}</svg></span>
                @endforeach
                <span id="icon-ui-star"><svg viewBox="0 0 24 24">{!! getIcon('ui_star') !!}</svg></span>
                <span id="icon-ui-check"><svg viewBox="0 0 24 24">{!! getIcon('ui_check') !!}</svg></span>
            </div>
        </main>
        <script>
            window.__APP__ = {!! vcard_js_str($data) !!};
            window.__VCARD_SUBDOMAIN__ = {!! vcard_js_str($subdomain) !!};
        </script>
        <script src="{{ $assetBase }}script.js"></script>
    </body>
</html>
