<?php
declare(strict_types=1);

$dataPath = __DIR__ . "/default.json";
$rawData = @file_get_contents($dataPath);
$data = $rawData ? json_decode($rawData, true) : [];

if (!is_array($data)) {
    $data = [];
}

require_once __DIR__ . "/icons.php";

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function data_get(array $data, string $path, $default = "")
{
    $segments = explode(".", $path);
    $value = $data;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function data_list(array $data, string $path): array
{
    $value = data_get($data, $path, []);
    return is_array($value) ? $value : [];
}

function js_str($value): string
{
    return json_encode($value ?? "", JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}

$bannerImage = data_get($data, "assets.bannerImage", "");
$profileImage = data_get($data, "assets.profileImage", data_get($data, "assets.fallbackImage", ""));
$profileAlt = data_get($data, "assets.profileAlt", data_get($data, "doctor.name", ""));
$serviceImage = data_get($data, "assets.serviceImage", "");

$slots = data_list($data, "appointment.slots");
$selectedSlot = "";
foreach ($slots as $slotItem) {
    if (!empty($slotItem["selected"]) && empty($slotItem["full"])) {
        $selectedSlot = $slotItem["slot"] ?? "";
        break;
    }
}

if (!$selectedSlot) {
    $selectedSlot = (string) data_get($data, "appointment.defaultSlot", "");
}

if (!$selectedSlot) {
    foreach ($slots as $slotItem) {
        if (empty($slotItem["full"])) {
            $selectedSlot = $slotItem["slot"] ?? "";
            break;
        }
    }
}

$socialIconClasses = [
    "whatsapp" => "ic-wa",
    "facebook" => "ic-fb",
    "youtube" => "ic-yt",
    "website" => "ic-web",
];
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
        <title><?= e(data_get($data, "meta.title")); ?></title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <main id="app-root" aria-live="polite" style="min-height:100vh">
            <div class="banner">
                <div class="banner-bg"<?= $bannerImage ? " style=\"background:url('" . e($bannerImage) . "') center/cover no-repeat\"" : ""; ?>>
                    <div class="banner-pattern"></div>
                    <div class="banner-ecg">
                        <svg viewBox="0 0 480 48" fill="none" preserveAspectRatio="none">
                            <polyline points="0,24 60,24 75,6 90,42 105,10 120,38 135,24 200,24 215,12 230,36 245,4 260,44 275,24 480,24" stroke="white" stroke-width="2.5" fill="none" />
                        </svg>
                    </div>
                </div>
                <div class="banner-top-bar">
                    <button class="share-btn" onclick="openShare()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="banner-share-label"><?= e(data_get($data, "banner.shareLabel")); ?></span>
                    </button>
                    <div class="verified-badge">
                        <svg width="13" height="13" viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <span id="banner-verified-label"><?= e(data_get($data, "banner.verifiedLabel")); ?></span>
                    </div>
                </div>
            </div>

            <div class="status-bar">
                <div class="status-open">
                    <div class="dot-pulse"></div>
                    <span id="status-open-label"><?= e(data_get($data, "status.openLabel")); ?></span>
                </div>
                <div class="next-slot" id="status-next-slot"><?= e(data_get($data, "status.nextSlot")); ?></div>
            </div>

            <div class="profile-card">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">
                        <img id="profile-image" src="<?= e($profileImage); ?>" alt="<?= e($profileAlt); ?>" style="width:100%;height:100%;object-fit:cover" />
                    </div>
                    <span class="reg-tag" id="profile-reg-tag"><?= e(data_get($data, "doctor.regTag")); ?></span>
                </div>
                <div class="profile-name" id="profile-name"><?= e(data_get($data, "doctor.name")); ?></div>
                <div class="profile-role" id="profile-role"><?= e(data_get($data, "doctor.role")); ?></div>
                <div class="profile-qual" id="profile-qualification"><?= e(data_get($data, "doctor.qualification")); ?></div>
                <div class="profile-stats" id="profileStats">
                    <?php $stats = data_list($data, "profile.stats"); ?>
                    <?php foreach ($stats as $index => $item): ?>
                        <div class="pstat">
                            <div class="pstat-num"><?= e($item["value"] ?? ""); ?></div>
                            <div class="pstat-lbl"><?= e($item["label"] ?? ""); ?></div>
                        </div>
                        <?php if ($index < count($stats) - 1): ?>
                            <div class="stat-divider"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="profile-action-btns">
                    <button class="pab call" onclick="callClinic()">
                        <svg viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.37 2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 5.09 5.09l1.32-1.32a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 14.92z" />
                        </svg>
                        <span id="action-call"><?= e(data_get($data, "profile.actions.call")); ?></span>
                    </button>
                    <button class="pab whatsapp" onclick="openWA()">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="action-whatsapp"><?= e(data_get($data, "profile.actions.whatsapp")); ?></span>
                    </button>
                    <button class="pab save" onclick="saveContact()">
                        <svg viewBox="0 0 24 24">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        <span id="action-save"><?= e(data_get($data, "profile.actions.save")); ?></span>
                    </button>
                    <button class="pab email" onclick="emailClinic()">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <span id="action-email"><?= e(data_get($data, "profile.actions.email")); ?></span>
                    </button>
                    <button class="pab direction" onclick="openMaps()">
                        <svg viewBox="0 0 24 24">
                            <polygon points="3 11 22 2 13 21 11 13 3 11" />
                        </svg>
                        <span id="action-direction"><?= e(data_get($data, "profile.actions.direction")); ?></span>
                    </button>
                    <button class="pab share" onclick="openShare()">
                        <svg viewBox="0 0 24 24">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="action-share"><?= e(data_get($data, "profile.actions.share")); ?></span>
                    </button>
                </div>
            </div>

            <div class="sec sec-top">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-specializations"><?= e(data_get($data, "sections.specializations")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="spec-chips" id="specializationChips">
                        <?php foreach (data_list($data, "specializations") as $item): ?>
                            <?php
                            $tone = $item["tone"] ?? "";
                            $iconKey = "chip_" . ($item["icon"] ?? "");
                            ?>
                            <span class="chip <?= e($tone); ?>">
                                <?= getIcon($iconKey); ?>
                                <?= e($item["name"] ?? ""); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec" id="appointmentSection">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-appointment"><?= e(data_get($data, "sections.appointment")); ?></div>
                </div>
                <div class="sec-body">
                    <div id="apptForm">
                        <div class="appt-slots" id="slotGrid">
                            <?php foreach ($slots as $slotItem): ?>
                                <?php
                                $slotLabel = $slotItem["slot"] ?? "";
                                $isFull = !empty($slotItem["full"]);
                                $isSelected = !$isFull && $selectedSlot && $slotLabel === $selectedSlot;
                                ?>
                                <?php if ($isFull): ?>
                                    <div class="slot-card full" data-slot="<?= e($slotLabel); ?>">
                                        <div class="slot-session"><?= e($slotItem["session"] ?? ""); ?></div>
                                        <div class="slot-time"><?= e($slotItem["time"] ?? ""); ?></div>
                                        <div class="slot-full"><?= e($slotItem["fullLabel"] ?? ""); ?></div>
                                    </div>
                                <?php else: ?>
                                    <div class="slot-card<?= $isSelected ? " selected" : ""; ?>" onclick="selectSlot(this)" data-slot="<?= e($slotLabel); ?>">
                                        <div class="slot-check">
                                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12" /></svg>
                                        </div>
                                        <div class="slot-session"><?= e($slotItem["session"] ?? ""); ?></div>
                                        <div class="slot-time"><?= e($slotItem["time"] ?? ""); ?></div>
                                        <div class="slot-avail">
                                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            <?= e($slotItem["availability"] ?? ""); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="bf-row">
                            <div class="bf-group">
                                <label class="bf-label" id="label-name"><?= e(data_get($data, "appointment.form.nameLabel")); ?></label>
                                <input class="bf-input" id="aName" placeholder="<?= e(data_get($data, "appointment.form.namePlaceholder")); ?>" type="text" />
                            </div>
                            <div class="bf-group">
                                <label class="bf-label" id="label-phone"><?= e(data_get($data, "appointment.form.phoneLabel")); ?></label>
                                <input class="bf-input" id="aPhone" placeholder="<?= e(data_get($data, "appointment.form.phonePlaceholder")); ?>" type="tel" />
                            </div>
                        </div>
                        <div class="bf-row">
                            <div class="bf-group">
                                <label class="bf-label" id="label-age"><?= e(data_get($data, "appointment.form.ageLabel")); ?></label>
                                <input class="bf-input" id="aAge" placeholder="<?= e(data_get($data, "appointment.form.agePlaceholder")); ?>" type="number" />
                            </div>
                            <div class="bf-group">
                                <label class="bf-label" id="label-visit-type"><?= e(data_get($data, "appointment.form.visitTypeLabel")); ?></label>
                                <select class="bf-input" id="aType">
                                    <?php foreach (data_list($data, "appointment.form.visitTypes") as $item): ?>
                                        <option value="<?= e($item["value"] ?? ""); ?>"><?= e($item["label"] ?? ""); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="bf-group">
                            <label class="bf-label" id="label-complaint"><?= e(data_get($data, "appointment.form.complaintLabel")); ?></label>
                            <input class="bf-input" id="aComplaint" placeholder="<?= e(data_get($data, "appointment.form.complaintPlaceholder")); ?>" type="text" />
                        </div>
                        <button class="bf-submit" onclick="bookAppointment()">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            <span id="appointment-submit"><?= e(data_get($data, "appointment.form.submitLabel")); ?></span>
                        </button>
                    </div>
                    <div class="appt-success" id="apptSuccess">
                        <div class="appt-success-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                        </div>
                        <h4 id="appointment-success-title"><?= e(data_get($data, "appointment.success.title")); ?></h4>
                        <p id="appointment-success-text"><?= e(data_get($data, "appointment.success.text")); ?></p>
                        <button class="appt-reset" onclick="resetAppt()">
                            <span id="appointment-success-button"><?= e(data_get($data, "appointment.success.buttonLabel")); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-conditions"><?= e(data_get($data, "sections.conditions")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="services-grid" id="conditionsGrid">
                        <?php foreach (data_list($data, "conditions") as $item): ?>
                            <?php
                            $query = $item["query"] ?? $item["name"] ?? "";
                            $image = $item["image"] ?? $serviceImage;
                            ?>
                            <div class="svc-card" onclick="enquireWA(<?= js_str($query); ?>)">
                                <div class="svc-img" style="background:url('<?= e($image); ?>') center/cover no-repeat;"></div>
                                <div class="svc-body">
                                    <div class="svc-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="svc-desc"><?= e($item["desc"] ?? ""); ?></div>
                                    <div class="svc-wa">
                                        <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                        <?= e(data_get($data, "labels.enquire", "Enquire")); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-fees"><?= e(data_get($data, "sections.fees")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="fees-list" id="feesList">
                        <?php foreach (data_list($data, "fees.items") as $item): ?>
                            <?php
                            $feeIconKey = "fee_" . ($item["icon"] ?? "");
                            $feeBg = $item["bg"] ?? "#e0f2fe";
                            $feeColor = $item["color"] ?? "#0369a1";
                            ?>
                            <div class="fee-item">
                                <div class="fee-left">
                                    <div class="fee-icon" style="background:<?= e($feeBg); ?>;color:<?= e($feeColor); ?>">
                                        <?= getIcon($feeIconKey); ?>
                                    </div>
                                    <div>
                                        <div class="fee-name"><?= e($item["name"] ?? ""); ?></div>
                                        <div class="fee-note"><?= e($item["note"] ?? ""); ?></div>
                                    </div>
                                </div>
                                <div>
                                    <span class="fee-amount"><?= e($item["amount"] ?? ""); ?></span>
                                    <?php if (!empty($item["oldAmount"])): ?>
                                        <span class="fee-old"><?= e($item["oldAmount"]); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="insurance-note">
                        <svg viewBox="0 0 24 24" width="16" height="16">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        <span id="fees-insurance-note"><?= e(data_get($data, "fees.insuranceNote")); ?></span>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-hours"><?= e(data_get($data, "sections.hours")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="today-badge">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <span id="hours-today"><?= e(data_get($data, "hours.todayLabel")); ?></span>
                    </div>
                    <table class="hours-table" id="hoursTable">
                        <?php foreach (data_list($data, "hours.rows") as $row): ?>
                            <tr class="<?= e($row["rowClass"] ?? ""); ?>">
                                <td class="day"><?= e($row["day"] ?? ""); ?></td>
                                <td class="session"><?= e($row["session"] ?? ""); ?></td>
                                <td class="time <?= e($row["timeClass"] ?? ""); ?>"><?= e($row["time"] ?? ""); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <div class="suggest-link" style="display:flex;align-items:center;gap:0.4rem;color:var(--teal);font-size:0.78rem;font-weight:600;cursor:pointer;margin-top:0.7rem;padding-top:0.6rem;border-top:1px solid var(--border);" onclick="openWA()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="hours-suggest-link"><?= e(data_get($data, "hours.suggestLink")); ?></span>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="6" />
                            <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-awards"><?= e(data_get($data, "sections.awards")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="awards-list" id="awardsList">
                        <?php foreach (data_list($data, "awards") as $item): ?>
                            <?php $awardIconKey = "award_" . ($item["icon"] ?? ""); ?>
                            <div class="award-item">
                                <div class="award-icon"><?= getIcon($awardIconKey); ?></div>
                                <div>
                                    <div class="award-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="award-desc"><?= e($item["desc"] ?? ""); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-location"><?= e(data_get($data, "sections.location")); ?></div>
                </div>
                <div class="sec-body">
                    <a class="address-link" href="#" onclick="return (openMaps(), !1);">
                        <div class="addr-icon-wrap">
                            <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div class="addr-text">
                            <strong id="location-clinic-name"><?= e(data_get($data, "location.clinicName")); ?></strong>
                            <span id="location-line1"><?= e(data_get($data, "location.line1")); ?></span><br />
                            <span id="location-line2"><?= e(data_get($data, "location.line2")); ?></span>
                            <span class="map-btn">
                                <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2">
                                    <polygon points="3 11 22 2 13 21 11 13 3 11" />
                                </svg>
                                <span id="location-map-label"><?= e(data_get($data, "location.mapLabel")); ?></span>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="2" y1="12" x2="22" y2="12" />
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-social"><?= e(data_get($data, "sections.social")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="social-list" id="socialList">
                        <?php foreach (data_list($data, "social") as $item): ?>
                            <?php
                            $type = $item["type"] ?? "website";
                            $iconKey = "social_" . $type;
                            $iconClass = $socialIconClasses[$type] ?? $socialIconClasses["website"];
                            $action = "";
                            if (($item["action"] ?? "") === "openWA") {
                                $action = "openWA()";
                            } elseif (!empty($item["url"])) {
                                $action = "openExternal(" . js_str($item["url"]) . ")";
                            }
                            ?>
                            <div class="social-item"<?= $action ? " onclick=\"{$action}\"" : ""; ?>>
                                <div class="s-ico <?= e($iconClass); ?>"><?= getIcon($iconKey); ?></div>
                                <div>
                                    <div class="s-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="s-val"><?= e($item["value"] ?? ""); ?></div>
                                </div>
                                <div class="s-arrow">
                                    <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2.5">
                                        <polyline points="9 18 15 12 9 6" />
                                    </svg>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-payments"><?= e(data_get($data, "sections.payments")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="payment-list" id="paymentList">
                        <?php foreach (data_list($data, "payments") as $item): ?>
                            <?php $payIconKey = "pay_" . ($item["icon"] ?? ""); ?>
                            <div class="pay-item">
                                <div class="pay-icon-wrap">
                                    <span style="display:flex;color:<?= e($item["stroke"] ?? "#0d9488"); ?>"><?= getIcon($payIconKey); ?></span>
                                </div>
                                <div>
                                    <div class="pay-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="pay-detail"><?= e($item["detail"] ?? ""); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="3" height="3" />
                            <rect x="18" y="14" width="3" height="3" />
                            <rect x="14" y="18" width="3" height="3" />
                            <rect x="18" y="18" width="3" height="3" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-contact-save"><?= e(data_get($data, "sections.contactSave")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="qr-card-inner">
                        <div style="font-size:0.79rem;color:var(--muted);margin-bottom:0.3rem;" id="qr-note"><?= e(data_get($data, "qr.note")); ?></div>
                        <div id="vcardQR"></div>
                        <div class="qr-actions">
                            <button class="qr-btn" onclick="saveContact()">
                                <svg viewBox="0 0 24 24" width="15" height="15">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                    <polyline points="17 21 17 13 7 13 7 21" />
                                    <polyline points="7 3 7 8 15 8" />
                                </svg>
                                <span id="qr-save-label"><?= e(data_get($data, "qr.saveLabel")); ?></span>
                            </button>
                            <button class="qr-btn" onclick="downloadQR()">
                                <svg viewBox="0 0 24 24" width="15" height="15">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                <span id="qr-download-label"><?= e(data_get($data, "qr.downloadLabel")); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div style="text-align:center;padding:1.4rem 1rem 1rem;font-size:0.72rem;color:var(--muted);">
                <span id="footer-line1"><?= e(data_get($data, "footer.line1")); ?></span><br />
                <strong style="color:var(--teal)" id="footer-line2"><?= e(data_get($data, "footer.line2")); ?></strong><br />
                <span id="footer-line3"><?= e(data_get($data, "footer.line3")); ?></span><br />
                <span style="font-size:0.65rem;color:#aaa" id="footer-line4"><?= e(data_get($data, "footer.line4")); ?></span>
            </div>

            <div class="float-bar">
                <button class="fab call-fab" onclick="callClinic()">
                    <svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.9">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.37 2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 5.09 5.09l1.32-1.32a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 14.92z" />
                    </svg>
                    <span id="fab-call-label"><?= e(data_get($data, "floatBar.call")); ?></span>
                </button>
                <button class="fab wa-fab" onclick="openWA()">
                    <svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.9">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                    </svg>
                    <span id="fab-whatsapp-label"><?= e(data_get($data, "floatBar.whatsapp")); ?></span>
                </button>
                <button class="fab appt-fab" onclick="openAppointment()">
                    <svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.9">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span id="fab-appointment-label"><?= e(data_get($data, "floatBar.appointment")); ?></span>
                </button>
                <button class="fab save-fab" onclick="saveContact()">
                    <svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.9">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    <span id="fab-save-label"><?= e(data_get($data, "floatBar.save")); ?></span>
                </button>
            </div>

            <div class="modal-overlay" id="shareModal" onclick="closeShare(event)">
                <div class="modal-box">
                    <div class="modal-title" id="share-title"><?= e(data_get($data, "share.title")); ?></div>
                    <div class="share-options">
                        <div class="sh-opt" onclick="shareWA()" style="color:#128c7e">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="#128c7e" stroke-width="2">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="share-whatsapp-label"><?= e(data_get($data, "share.whatsappLabel")); ?></span>
                        </div>
                        <div class="sh-opt" onclick="copyLink()" style="color:var(--teal)">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="var(--teal)" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                            </svg>
                            <span id="share-copy-label"><?= e(data_get($data, "share.copyLabel")); ?></span>
                        </div>
                        <div class="sh-opt" onclick="shareNative()" style="color:var(--blue)">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="var(--blue)" stroke-width="2">
                                <circle cx="18" cy="5" r="3" />
                                <circle cx="6" cy="12" r="3" />
                                <circle cx="18" cy="19" r="3" />
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                            </svg>
                            <span id="share-more-label"><?= e(data_get($data, "share.moreLabel")); ?></span>
                        </div>
                        <div class="sh-opt" onclick="shareFB()" style="color:#1877f2">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="#1877f2" stroke-width="2">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                            <span id="share-facebook-label"><?= e(data_get($data, "share.facebookLabel")); ?></span>
                        </div>
                    </div>
                    <button class="modal-cancel" onclick="closeShareModal()">
                        <span id="share-cancel-label"><?= e(data_get($data, "share.cancelLabel")); ?></span>
                    </button>
                </div>
            </div>

            <div class="promo-overlay" id="promoOverlay" onclick="closePromo(event)">
                <div class="promo-box" onclick="event.stopPropagation()">
                    <button class="promo-close" onclick="closePromo()">
                        <svg viewBox="0 0 24 24" width="16" height="16">
                            <line x1="18" y1="6" x2="6" y2="18" stroke-width="2" />
                            <line x1="6" y1="6" x2="18" y2="18" stroke-width="2" />
                        </svg>
                    </button>
                    <div class="promo-icon">
                        <svg viewBox="0 0 24 24" width="28" height="28">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                    </div>
                    <h3 id="promo-title"><?= e(data_get($data, "promo.title")); ?></h3>
                    <p id="promo-text"><?= e(data_get($data, "promo.text")); ?></p>
                    <button class="promo-cta" onclick="promoAction()">
                        <svg viewBox="0 0 24 24" width="18" height="18">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="promo-cta-label"><?= e(data_get($data, "promo.ctaLabel")); ?></span>
                    </button>
                </div>
            </div>

            <div class="toast" id="toast">
                <svg viewBox="0 0 24 24" stroke-width="2" width="15" height="15">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <span id="toastMsg"><?= e(data_get($data, "messages.defaultToast")); ?></span>
            </div>
        </main>
        <script>
            window.__APP__ = <?= json_encode($data ?: [], JSON_UNESCAPED_SLASHES); ?>;
        </script>
        <script src="script.js"></script>
    </body>
</html>