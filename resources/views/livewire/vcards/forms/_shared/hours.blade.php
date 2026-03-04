{{--
 | _shared/hours.blade.php
 | Business hours section — for templates with a dict containing a `rows` array.
 | Covers: doctor, electronics, mens-salon, restaurant patterns.
 |
 | Available: $form (array), section is a dict with optional header text + rows list.
 | Row shape varies: {day, time} or {day, time, open, closed} or {day, time, rowClass}
--}}

@php
    $rows        = $form['rows'] ?? [];
    $scalarFields = array_filter($form, fn($v) => !is_array($v));
@endphp

{{-- ── Header Text Fields ────────────────────────────────────────── --}}
@if(!empty($scalarFields))
    <div class="col-12 mb-2">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
            <i class="mdi mdi-clock-outline me-1"></i>Hours Header
        </h6>
    </div>
    @foreach($scalarFields as $hKey => $hVal)
        @php $isTextareaKey = preg_match('/(note|text|message|desc|suggest)/i', $hKey); @endphp
        <div class="{{ $isTextareaKey ? 'col-12' : 'col-lg-6' }} mb-3">
            <label class="form-label fw-semibold" for="hours-{{ $hKey }}">
                {{ \Illuminate\Support\Str::headline($hKey) }}
            </label>
            @if($isTextareaKey)
                <textarea id="hours-{{ $hKey }}"
                          class="form-control @error('form.' . $hKey) is-invalid @enderror"
                          wire:model="form.{{ $hKey }}"
                          rows="2"></textarea>
            @else
                <input type="text"
                       id="hours-{{ $hKey }}"
                       class="form-control @error('form.' . $hKey) is-invalid @enderror"
                       wire:model="form.{{ $hKey }}">
            @endif
            @error('form.' . $hKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endforeach
@endif

{{-- ── Hours Rows ────────────────────────────────────────────────── --}}
@if(!empty($rows))
    <div class="col-12 mb-2 mt-2">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
            <i class="mdi mdi-table me-1"></i>Weekly Schedule
        </h6>
    </div>
    <div class="col-12">
        @php
            $firstRow    = $rows[0] ?? [];
            $hasOpen     = array_key_exists('open', $firstRow);
            $hasClosed   = array_key_exists('closed', $firstRow);
            $hasRowClass = array_key_exists('rowClass', $firstRow);
        @endphp

        @foreach($rows as $ri => $row)
            <div class="border rounded-3 p-3 mb-2" style="background:#f8fafc;" wire:key="hours-row-{{ $ri }}">
                <div class="row g-2 align-items-center">
                    {{-- Day --}}
                    <div class="col-lg-3">
                        <label class="form-label mb-1 fw-semibold" style="font-size:.8rem;">Day</label>
                        <input type="text"
                               class="form-control form-control-sm @error('form.rows.' . $ri . '.day') is-invalid @enderror"
                               wire:model="form.rows.{{ $ri }}.day"
                               placeholder="Monday">
                        @error('form.rows.' . $ri . '.day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Time --}}
                    <div class="col-lg-4">
                        <label class="form-label mb-1 fw-semibold" style="font-size:.8rem;">Hours / Time</label>
                        <input type="text"
                               class="form-control form-control-sm @error('form.rows.' . $ri . '.time') is-invalid @enderror"
                               wire:model="form.rows.{{ $ri }}.time"
                               placeholder="10:00 AM – 8:00 PM or Closed">
                        @error('form.rows.' . $ri . '.time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- open / closed toggles (if present) --}}
                    @if($hasOpen)
                        <div class="col-lg-2">
                            <label class="form-label mb-1 fw-semibold d-block" style="font-size:.8rem;">Open?</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       wire:model="form.rows.{{ $ri }}.open"
                                       style="cursor:pointer;">
                            </div>
                        </div>
                    @endif
                    @if($hasClosed)
                        <div class="col-lg-2">
                            <label class="form-label mb-1 fw-semibold d-block" style="font-size:.8rem;">Closed?</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       wire:model="form.rows.{{ $ri }}.closed"
                                       style="cursor:pointer;">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-12 mb-1">
        <small class="text-muted">
            <i class="mdi mdi-information-outline me-1"></i>
            Edit hours directly in each row. Use <code>Closed</code> in the time field to mark a day off.
        </small>
    </div>
@endif
