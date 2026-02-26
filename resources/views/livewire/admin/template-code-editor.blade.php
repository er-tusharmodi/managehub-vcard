<div>
    <!-- Page Header -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Code Editor: {{ $templateName }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.templates.index') }}">Templates</a></li>
                            <li class="breadcrumb-item active">Code Editor</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Code Editor Panel -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <!-- File Tabs -->
                        <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'php' ? 'active' : '' }}" 
                                   wire:click="switchTab('php')" 
                                   style="cursor: pointer;">
                                    <i class="mdi mdi-language-php me-1"></i> index.php
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'css' ? 'active' : '' }}" 
                                   wire:click="switchTab('css')" 
                                   style="cursor: pointer;">
                                    <i class="mdi mdi-language-css3 me-1"></i> style.css
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'js' ? 'active' : '' }}" 
                                   wire:click="switchTab('js')" 
                                   style="cursor: pointer;">
                                    <i class="mdi mdi-language-javascript me-1"></i> script.js
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'json' ? 'active' : '' }}" 
                                   wire:click="switchTab('json')" 
                                   style="cursor: pointer;">
                                    <i class="mdi mdi-code-json me-1"></i> default.json
                                </a>
                            </li>
                        </ul>

                        <!-- Editor Content -->
                        <div class="tab-content">
                            @if($activeTab === 'php')
                            <div class="mb-3">
                                <label class="form-label">index.php</label>
                                <textarea 
                                    wire:model="phpContent" 
                                    class="form-control font-monospace" 
                                    rows="20" 
                                    style="font-size: 13px; white-space: pre; overflow-x: auto;"
                                ></textarea>
                                @error('phpContent') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            @endif

                            @if($activeTab === 'css')
                            <div class="mb-3">
                                <label class="form-label">style.css</label>
                                <textarea 
                                    wire:model="cssContent" 
                                    class="form-control font-monospace" 
                                    rows="20" 
                                    style="font-size: 13px; white-space: pre; overflow-x: auto;"
                                ></textarea>
                                @error('cssContent') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            @endif

                            @if($activeTab === 'js')
                            <div class="mb-3">
                                <label class="form-label">script.js</label>
                                <textarea 
                                    wire:model="jsContent" 
                                    class="form-control font-monospace" 
                                    rows="20" 
                                    style="font-size: 13px; white-space: pre; overflow-x: auto;"
                                ></textarea>
                                @error('jsContent') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            @endif

                            @if($activeTab === 'json')
                            <div class="mb-3">
                                <label class="form-label">default.json</label>
                                <textarea 
                                    wire:model="jsonContent" 
                                    class="form-control font-monospace" 
                                    rows="20" 
                                    style="font-size: 13px; white-space: pre; overflow-x: auto;"
                                ></textarea>
                                @error('jsonContent') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="button" wire:click="save" class="btn btn-primary">
                                <i class="mdi mdi-content-save me-1"></i> Save Changes
                            </button>
                            <button type="button" wire:click="refreshPreview" class="btn btn-info">
                                <i class="mdi mdi-refresh me-1"></i> Refresh Preview
                            </button>
                            <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i> Back to Templates
                            </a>
                            <a href="{{ route('admin.templates.edit.visual', $templateKey) }}" class="btn btn-outline-primary">
                                <i class="mdi mdi-view-dashboard-outline me-1"></i> Visual Editor
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Panel -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Live Preview</h5>
                        <div class="border rounded bg-light" style="height: 600px; overflow: hidden;">
                            <iframe 
                                id="template-preview-iframe"
                                src="{{ route('admin.templates.preview', $templateKey) }}?t={{ $previewKey }}" 
                                class="w-100 h-100 border-0"
                                style="background: white;"
                            ></iframe>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                <i class="mdi mdi-information-outline"></i>
                                Preview shows template with sample data from default.json
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Watch for previewKey changes and reload iframe
    document.addEventListener('livewire:updated', () => {
        const iframe = document.getElementById('template-preview-iframe');
        if (iframe) {
            const baseUrl = iframe.src.split('?')[0];
            iframe.src = baseUrl + '?t=' + Date.now();
        }
    });
</script>
@endpush
