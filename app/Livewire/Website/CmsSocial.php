<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;

class CmsSocial extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
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
        unset($this->links[$index]);
        $this->links = array_values($this->links);
    }

    public function save()
    {
        $validated = $this->validateWithToast([
            'links' => ['required', 'array'],
            'links.*.platform' => ['required', 'string', 'in:' . implode(',', $this->socialPlatforms)],
            'links.*.url' => ['required', 'url'],
        ]);

        $data = $this->page->data ?? [];
        $data['social_links'] = $validated['links'];

        $this->page->update(['data' => $data]);

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
