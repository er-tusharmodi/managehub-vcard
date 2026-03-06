{{--
 | _shared/_list_table.blade.php
 | Reusable Table + Add Modal + per-row Edit Modal for list sections.
 |
 | Required variables (pass via @include(..., [...])):
 |   $items        array   — the list of rows
 |   $addPath      string  — '' for root list, 'items' for nested {items:[…]}
 |   $modelBase    string  — wire:model prefix for rows ('form' or 'form.items')
 |   $sectionKey   string  — unique slug used in modal IDs (no spaces/dots)
 |   $itemLabel    string  — singular label e.g. 'Course', 'Product'
 |   $tableFields  array   — field keys to show as table columns
 |   $fields       array   — field definitions for Add / Edit modals:
 |                     ['key','label','type','placeholder','span','options','rows']
 |                     type: text | textarea | image | number | select | toggle
 |                     span: Bootstrap col class, default 'col-md-6'
--}}
@php
    $items       = $items       ?? [];
    $addPath     = $addPath     ?? '';
    $modelBase   = $modelBase   ?? 'form';
    $sectionKey  = $sectionKey  ?? 'sec';
    $itemLabel   = $itemLabel   ?? 'Item';
    $tableFields = $tableFields ?? [];
    $fields      = $fields      ?? [];
    $addRowCols  = array_column($fields, 'key');
    $addModalId  = 'lt-add-' . $sectionKey;
@endphp

{{-- ── Header ── --}}
<div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            {{ $itemLabel }}s
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($items) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                data-bs-toggle="modal" data-bs-target="#{{ $addModalId }}">
            <i class="mdi mdi-plus me-1"></i>Add {{ $itemLabel }}
        </button>
    </div>
</div>

{{-- ── Empty state ── --}}
@if(empty($items))
<div class="col-12">
    <div class="text-center py-5 rounded-3"
         style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px dashed #cbd5e1;">
        <i class="mdi mdi-table-plus fs-1 text-muted mb-2 d-block"></i>
        <p class="fw-semibold text-muted mb-1">No {{ strtolower($itemLabel) }}s added yet</p>
        <button type="button" class="btn btn-sm btn-primary mt-2"
                data-bs-toggle="modal" data-bs-target="#{{ $addModalId }}">
            <i class="mdi mdi-plus me-1"></i>Add First {{ $itemLabel }}
        </button>
    </div>
</div>

@else
{{-- ── Table ── --}}
<div class="col-12">
    <div class="table-responsive rounded-3" style="border:1px solid #e2e8f0;">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:32px;"></th>
                    @foreach($tableFields as $tf)
                        @php $tfDef = collect($fields)->firstWhere('key',$tf); $tfType = $tfDef['type'] ?? 'text'; @endphp
                        <th class="small fw-semibold text-muted" style="min-width:{{ $tfType==='image'?'60px':'90px' }}">
                            {{ $tfType==='image' ? 'Image' : ($tfDef['label'] ?? \Illuminate\Support\Str::headline($tf)) }}
                        </th>
                    @endforeach
                    <th class="text-center small fw-semibold text-muted" style="width:136px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($items as $i => $row)
                @php $editId = 'lt-edit-'.$sectionKey.'-'.$i; @endphp
                <tr wire:key="lt-{{ $sectionKey }}-{{ $i }}">
                    <td class="text-center text-muted" style="cursor:grab;"><i class="mdi mdi-drag-vertical"></i></td>
                    @foreach($tableFields as $tf)
                        @php
                            $tfDef   = collect($fields)->firstWhere('key',$tf);
                            $tfType  = $tfDef['type'] ?? 'text';
                            $cellVal = $row[$tf] ?? '';
                        @endphp
                        <td>
                            @if($tfType === 'image' && !empty($cellVal))
                                @php
                                    $iSrc = $cellVal;
                                    // Strip CSS url() wrapper if present
                                    if (preg_match('/url\([\'"]?(.*?)[\'"]?\)/i', $iSrc, $_m)) { $iSrc = $_m[1]; }
                                    if (isset($assetBaseUrl) && !preg_match('~^(https?:)?//|data:|/~', $iSrc)) { $iSrc = rtrim($assetBaseUrl, '/') . '/' . $iSrc; }
                                @endphp
                                <img src="{{ $iSrc }}" style="width:44px;height:44px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;" alt="">
                            @elseif($tfType === 'toggle')
                                <span class="badge {{ $cellVal ? 'bg-success-subtle text-success':'bg-secondary-subtle text-secondary' }}">{{ $cellVal?'On':'Off' }}</span>
                            @else
                                <span class="small">
                                    @if(is_array($cellVal)) <span class="text-muted">{{ count($cellVal) }} items</span>
                                    @elseif($cellVal !== '' && $cellVal !== null) {{ is_string($cellVal) && strlen($cellVal)>45 ? substr($cellVal,0,45).'…' : $cellVal }}
                                    @else <span class="text-muted">—</span>
                                    @endif
                                </span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-primary p-0 rounded-circle"
                                    style="width:28px;height:28px;" title="Edit"
                                    data-bs-toggle="modal" data-bs-target="#{{ $editId }}">
                                <i class="mdi mdi-pencil" style="font-size:12px;"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary p-0 rounded-circle"
                                    style="width:28px;height:28px;" title="Move Up"
                                    wire:click.stop="moveRow('{{ $addPath }}',{{ $i }},-1)"
                                    {{ $i===0?'disabled':'' }}>
                                <i class="mdi mdi-arrow-up" style="font-size:12px;"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary p-0 rounded-circle"
                                    style="width:28px;height:28px;" title="Move Down"
                                    wire:click.stop="moveRow('{{ $addPath }}',{{ $i }},1)"
                                    {{ $i===count($items)-1?'disabled':'' }}>
                                <i class="mdi mdi-arrow-down" style="font-size:12px;"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                    style="width:28px;height:28px;" title="Delete"
                                    wire:click.stop="confirmRemoveRow('{{ $addPath }}',{{ $i }})">
                                <i class="mdi mdi-delete" style="font-size:12px;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ── Per-row Edit Modals ── --}}
@foreach($items as $i => $row)
@php
    $editId  = 'lt-edit-'.$sectionKey.'-'.$i;
    $upxEdit = $addPath ? 'uploads.'.$addPath.'.'.$i : 'uploads.'.$i;
    $rowTitle = '';
    foreach(['name','title','label','question','slot','day'] as $nk){ if(!empty($row[$nk])&&is_string($row[$nk])){$rowTitle=substr($row[$nk],0,40);break;} }
    $rowTitle = $rowTitle ?: ($itemLabel.' #'.($i+1));
@endphp
<div class="modal fade" id="{{ $editId }}" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h5 class="modal-title fw-semibold fs-6">
                    <i class="mdi mdi-pencil me-2 text-primary"></i>{{ $rowTitle }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-3">
                    @foreach($fields as $fd)
                    @php
                        $fKey  = $fd['key']; $fLbl=$fd['label']??\Illuminate\Support\Str::headline($fKey);
                        $fType = $fd['type']??'text'; $fPh=$fd['placeholder']??'';
                        $fSpan = $fd['span']??'col-md-6'; $fRows=$fd['rows']??3; $fOpts=$fd['options']??[];
                        $fModel  = $modelBase.'.'.$i.'.'.$fKey;
                        $fUpload = $upxEdit.'.'.$fKey;
                        $curVal  = $row[$fKey] ?? '';
                    @endphp
                    <div class="{{ $fSpan }}">
                        <label class="form-label small fw-semibold mb-1">{{ $fLbl }}</label>
                        @if($fType==='image')
                            @if(!empty($curVal))
                                @php
                                    $iSrc2 = $curVal;
                                    // Strip CSS url() wrapper if present
                                    if (preg_match('/url\([\'"]?(.*?)[\'"]?\)/i', $iSrc2, $_m2)) { $iSrc2 = $_m2[1]; }
                                    if (isset($assetBaseUrl) && !preg_match('~^(https?:)?//|data:|/~', $iSrc2)) { $iSrc2 = rtrim($assetBaseUrl, '/') . '/' . $iSrc2; }
                                @endphp
                                <div class="mb-1"><img src="{{ $iSrc2 }}" style="max-height:80px;border-radius:6px;border:1px solid #e2e8f0;" alt=""></div>
                            @endif
                            <div wire:loading wire:target="{{ $fUpload }}" class="text-muted small mb-1"><i class="mdi mdi-loading mdi-spin me-1"></i>Uploading…</div>
                            @php
                                $_upKey = last(explode('.', $fUpload));
                                $_upReady = $addPath
                                    ? (isset($uploads[$addPath][$i][$_upKey]))
                                    : (isset($uploads[$i][$_upKey]));
                            @endphp
                            @if($_upReady)
                                <div class="text-success small mb-1"><i class="mdi mdi-check-circle me-1"></i>Image selected — click Save Changes</div>
                            @endif
                            <input type="file" class="form-control form-control-sm" wire:model.live="{{ $fUpload }}" accept="image/*">
                        @elseif($fType==='textarea')
                            <textarea class="form-control form-control-sm" rows="{{ $fRows }}" wire:model="{{ $fModel }}" placeholder="{{ $fPh }}"></textarea>
                        @elseif($fType==='toggle')
                            <div class="form-check form-switch mt-1">
                                <input type="checkbox" class="form-check-input" id="{{ $editId }}-{{ $fKey }}" wire:model="{{ $fModel }}">
                                <label class="form-check-label small" for="{{ $editId }}-{{ $fKey }}">{{ $fPh ?: 'Enabled' }}</label>
                            </div>
                        @elseif($fType==='select')
                            <select class="form-select form-select-sm" wire:model="{{ $fModel }}">
                                <option value="">— Select —</option>
                                @foreach($fOpts as $opt)<option value="{{ $opt['value'] ?? $opt['key'] ?? '' }}">{{ $opt['label'] }}</option>@endforeach
                            </select>
                        @elseif($fType==='number')
                            <input type="number" class="form-control form-control-sm" wire:model="{{ $fModel }}" placeholder="{{ $fPh }}">
                        @elseif($fType==='color')
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" class="form-control form-control-color p-1 border" wire:model="{{ $fModel }}"
                                       style="width:44px;height:34px;cursor:pointer;flex-shrink:0;" title="Pick color">
                                <input type="text" class="form-control form-control-sm" wire:model="{{ $fModel }}" placeholder="{{ $fPh ?: '#000000' }}">
                            </div>
                        @elseif($fType==='datalist')
                            @php $dlId = $editId.'-'.$fKey.'-dl'; @endphp
                            <input type="text" class="form-control form-control-sm" wire:model="{{ $fModel }}" placeholder="{{ $fPh }}" list="{{ $dlId }}">
                            <datalist id="{{ $dlId }}">@foreach($fOpts as $opt)<option value="{{ is_array($opt)?($opt['value']??$opt['key']??''):$opt }}">@endforeach</datalist>
                        @else
                            @if(!empty($fd['prefix']) || !empty($fd['suffix']))
                            <div class="input-group input-group-sm">
                                @if(!empty($fd['prefix']))<span class="input-group-text">{{ $fd['prefix'] }}</span>@endif
                                <input type="text" class="form-control form-control-sm" wire:model="{{ $fModel }}" placeholder="{{ $fPh }}">
                                @if(!empty($fd['suffix']))<span class="input-group-text">{{ $fd['suffix'] }}</span>@endif
                            </div>
                            @else
                            <input type="text" class="form-control form-control-sm" wire:model="{{ $fModel }}" placeholder="{{ $fPh }}">
                            @endif
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer" style="background:#f8fafc;border-top:1px solid #e2e8f0;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary" wire:click="save" data-bs-dismiss="modal">
                    <i class="mdi mdi-content-save me-1"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

{{-- ── Add Modal ── --}}
<div class="modal fade" id="{{ $addModalId }}" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h5 class="modal-title fw-semibold fs-6">
                    <i class="mdi mdi-plus-circle-outline me-2 text-success"></i>Add {{ $itemLabel }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-3">
                    @foreach($fields as $fd)
                    @php
                        $fKey=$fd['key']; $fLbl=$fd['label']??\Illuminate\Support\Str::headline($fKey);
                        $fType=$fd['type']??'text'; $fPh=$fd['placeholder']??'';
                        $fSpan=$fd['span']??'col-md-6'; $fRows=$fd['rows']??3; $fOpts=$fd['options']??[];
                        $niModel='newItem.'.$fKey; $niUpload='uploads.newItem.'.$fKey;
                    @endphp
                    <div class="{{ $fSpan }}">
                        <label class="form-label small fw-semibold mb-1">{{ $fLbl }}</label>
                        @if($fType==='image')
                            <div wire:loading wire:target="{{ $niUpload }}" class="text-muted small mb-1"><i class="mdi mdi-loading mdi-spin me-1"></i>Uploading…</div>
                            <input type="file" class="form-control form-control-sm" wire:model.live="{{ $niUpload }}" accept="image/*">
                        @elseif($fType==='textarea')
                            <textarea class="form-control form-control-sm" rows="{{ $fRows }}" wire:model="{{ $niModel }}" placeholder="{{ $fPh }}"></textarea>
                        @elseif($fType==='toggle')
                            <div class="form-check form-switch mt-1">
                                <input type="checkbox" class="form-check-input" id="{{ $addModalId }}-{{ $fKey }}" wire:model="{{ $niModel }}">
                                <label class="form-check-label small" for="{{ $addModalId }}-{{ $fKey }}">{{ $fPh ?: 'Enabled' }}</label>
                            </div>
                        @elseif($fType==='select')
                            <select class="form-select form-select-sm" wire:model="{{ $niModel }}">
                                <option value="">— Select —</option>
                                @foreach($fOpts as $opt)<option value="{{ $opt['value'] ?? $opt['key'] ?? '' }}">{{ $opt['label'] }}</option>@endforeach
                            </select>
                        @elseif($fType==='number')
                            <input type="number" class="form-control form-control-sm" wire:model="{{ $niModel }}" placeholder="{{ $fPh }}">
                        @elseif($fType==='color')
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" class="form-control form-control-color p-1 border" wire:model="{{ $niModel }}"
                                       style="width:44px;height:34px;cursor:pointer;flex-shrink:0;" title="Pick color">
                                <input type="text" class="form-control form-control-sm" wire:model="{{ $niModel }}" placeholder="{{ $fPh ?: '#000000' }}">
                            </div>
                        @elseif($fType==='datalist')
                            @php $dlId2 = $addModalId.'-'.$fKey.'-dl'; @endphp
                            <input type="text" class="form-control form-control-sm" wire:model="{{ $niModel }}" placeholder="{{ $fPh }}" list="{{ $dlId2 }}">
                            <datalist id="{{ $dlId2 }}">@foreach($fOpts as $opt)<option value="{{ is_array($opt)?($opt['value']??$opt['key']??''):$opt }}">@endforeach</datalist>
                        @else
                            @if(!empty($fd['prefix']) || !empty($fd['suffix']))
                            <div class="input-group input-group-sm">
                                @if(!empty($fd['prefix']))<span class="input-group-text">{{ $fd['prefix'] }}</span>@endif
                                <input type="text" class="form-control form-control-sm" wire:model="{{ $niModel }}" placeholder="{{ $fPh }}">
                                @if(!empty($fd['suffix']))<span class="input-group-text">{{ $fd['suffix'] }}</span>@endif
                            </div>
                            @else
                            <input type="text" class="form-control form-control-sm" wire:model="{{ $niModel }}" placeholder="{{ $fPh }}">
                            @endif
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer" style="background:#f8fafc;border-top:1px solid #e2e8f0;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-success"
                        wire:click="addRowAndSave('{{ $addPath }}', {{ json_encode($addRowCols) }})"
                        data-bs-dismiss="modal">
                    <i class="mdi mdi-plus me-1"></i>Add {{ $itemLabel }}
                </button>
            </div>
        </div>
    </div>
</div>
