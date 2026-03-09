<div class="row g-4">

    {{-- robots.txt --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0"><i class="fas fa-robot me-2 text-secondary"></i>robots.txt</h5>
                <small class="text-muted">Served at <code>/robots.txt</code> — controls search engine crawling</small>
            </div>
            <div class="card-body">
                <textarea wire:model="robots_txt" class="form-control font-monospace" rows="12"
                    placeholder="User-agent: *&#10;Allow: /&#10;Disallow: /admin/&#10;&#10;Sitemap: https://yourdomain.com/sitemap.xml"></textarea>
                @error('robots_txt') <span class="text-danger small">{{ $message }}</span> @enderror
                <div class="mt-3 d-flex gap-2 flex-wrap">
                    <button wire:click="saveRobots" wire:loading.attr="disabled" type="button" class="btn btn-primary">
                        <span wire:loading wire:target="saveRobots" class="spinner-border spinner-border-sm me-2"></span>
                        <span wire:loading.remove wire:target="saveRobots"><i class="fas fa-save me-1"></i>Save robots.txt</span>
                        <span wire:loading wire:target="saveRobots">Saving...</span>
                    </button>
                    <button wire:click="autoGenerateRobots" wire:loading.attr="disabled" type="button" class="btn btn-outline-secondary">
                        <span wire:loading wire:target="autoGenerateRobots" class="spinner-border spinner-border-sm me-2"></span>
                        <span wire:loading.remove wire:target="autoGenerateRobots"><i class="fas fa-magic me-1"></i>Auto Generate</span>
                        <span wire:loading wire:target="autoGenerateRobots">Generating...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- sitemap.xml --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0"><i class="fas fa-sitemap me-2 text-secondary"></i>sitemap.xml</h5>
                <small class="text-muted">Served at <code>/sitemap.xml</code> — helps search engines index your site</small>
            </div>
            <div class="card-body">
                @php $sitemapPlaceholder = '<' . '?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n" . '  <url>' . "\n" . '    <loc>https://yourdomain.com/</loc>' . "\n" . '    <priority>1.0</priority>' . "\n" . '  </url>' . "\n" . '</urlset>'; @endphp
                <textarea wire:model="sitemap_xml" class="form-control font-monospace" rows="16"
                    placeholder="{{ $sitemapPlaceholder }}"></textarea>
                @error('sitemap_xml') <span class="text-danger small">{{ $message }}</span> @enderror
                <div class="mt-3 d-flex gap-2 flex-wrap">
                    <button wire:click="saveSitemap" wire:loading.attr="disabled" type="button" class="btn btn-primary">
                        <span wire:loading wire:target="saveSitemap" class="spinner-border spinner-border-sm me-2"></span>
                        <span wire:loading.remove wire:target="saveSitemap"><i class="fas fa-save me-1"></i>Save sitemap.xml</span>
                        <span wire:loading wire:target="saveSitemap">Saving...</span>
                    </button>
                    <button wire:click="autoGenerateSitemap" wire:loading.attr="disabled" type="button" class="btn btn-outline-secondary">
                        <span wire:loading wire:target="autoGenerateSitemap" class="spinner-border spinner-border-sm me-2"></span>
                        <span wire:loading.remove wire:target="autoGenerateSitemap"><i class="fas fa-magic me-1"></i>Auto Generate</span>
                        <span wire:loading wire:target="autoGenerateSitemap">Generating...</span>
                    </button>
                    <small class="text-muted align-self-center">Includes homepage + all active vCard URLs</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <a href="{{ route('admin.website-cms', $pageSlug) }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left me-1"></i>Back to CMS
        </a>
    </div>

</div>
