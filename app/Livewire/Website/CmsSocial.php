<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\Attributes\Locked;

class CmsSocial extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $links = [];

    protected $socialPlatforms = [
        'Facebook',
        'Twitter',
        'Instagram',
        'LinkedIn',
        'YouTube',
        'TikTok',
        'Pinterest',
        'Snapchat',
        'WhatsApp',
        'Telegram',
        'Discord',
        'Slack',
        'GitHub',
        'GitLab',
        'Medium',
        'Behance',
        'Dribbble',
        'Figma',
        'Twitch',
        'WeChat',
    ];

    public function getSocialPlatforms()
    {
        return $this->socialPlatforms;
    }

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->links = $this->page->data['social_links'] ?? [];
    }

    public function addLink()
    {
        $this->links[] = ['platform' => '', 'url' => ''];
    }

    public function removeLink($index)
    {
        $this->dispatch(
            'confirm-delete',
            id: $this->getId(),
            index: $index,
            method: 'removeLinkConfirmed',
            message: 'Delete this social link?'
        );
    }

    public function removeLinkConfirmed($index)
    {
        if (!isset($this->links[$index])) {
            return;
        }

        unset($this->links[$index]);
        $this->links = array_values($this->links);
    }

    public function save()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        
        $validated = $this->validateWithToast([
            'links' => ['required', 'array'],
            'links.*.platform' => ['required', 'string', 'in:' . implode(',', $this->socialPlatforms)],
            'links.*.url' => ['required', 'url'],
        ]);

        $data = $this->page->data ?? [];
        $data['social_links'] = $validated['links'];

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;

        $this->dispatch('notify',
            type: 'success',
            message: 'Social links saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-social');
    }
}
