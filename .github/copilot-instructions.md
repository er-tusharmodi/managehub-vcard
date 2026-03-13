# GitHub Copilot Instructions — vCard Builder (restaurant-cafe-template)

## Project Overview

Laravel + Livewire 3 vCard builder. Restaurant-cafe-template is the primary template.
All form partials in `resources/views/livewire/vcards/forms/restaurant-cafe-template/` are shared across three editors:

- **Template Editor**: `resources/views/livewire/admin/template-visual-editor.blade.php`
- **Admin/vCard Editor**: `resources/views/livewire/admin/admin-section-editor.blade.php`
- **Client Editor**: `resources/views/livewire/vcards/client-section-editor.blade.php`

> Always apply form-level changes to all three editors unless told otherwise.

---

## Stack

| Layer         | Tech                                                                              |
| ------------- | --------------------------------------------------------------------------------- |
| Backend       | PHP 8.x, Laravel 10, Livewire 3                                                   |
| Frontend      | Alpine.js (v3), Bootstrap 5, MDI Icons (`mdi-*`), FontAwesome 6 (`fa-solid fa-*`) |
| Build         | Vite + Tailwind (admin UI), vanilla CSS for vCard frontend                        |
| Data          | MySQL via Eloquent; vCard data stored as JSON in `data_content` column            |
| Public assets | `public/vcard-assets/restaurant-cafe-template/style.css` + `script.js`            |

---

## Key Patterns & Conventions

### Livewire

- Use `wire:key="section-form-{{ $section }}"` on all `<form>` tags in section editors — prevents Livewire DOM morphing from reusing stale inputs when switching sections.
- Use `wire:model.live` for reactive inputs; `wire:model.lazy` / `wire:model.blur` for color pickers and fields that should not fire on every keystroke.
- Call `addRowAndSave('key', ['fields'])` to add array rows; `removeRowWithConfirm($index, 'key')` to remove.
- PHP dispatches browser events with `$this->dispatch('event-name')` — Alpine listens with `x-on:event-name.window`.

### Alpine.js

- Use `x-data` per component with reactive `get` computed properties for things like platform headers (social links).
- Use `Js::from($phpArray)` to safely serialize PHP arrays for Alpine consumption.
- Avoid placing `wire:ignore` inside Alpine `x-data` scopes — it breaks `@entangle`.
- Never call `hideModal()` / `hideInstant()` **before** calling `$wire.call('saveX')` — always let PHP dispatch the `hide-*` event after saving to preserve `@entangle` sync.

### Bootstrap 5 Modals

- Add `data-bs-backdrop="static" data-bs-keyboard="false"` to any modal that has a file/image upload input — prevents the OS file picker close event from dismissing the modal.

### Section Forms

- Each section form file (e.g. `offers.blade.php`) starts with a Blade comment declaring its data shape: `{{-- section.blade.php — {field1, field2, items:[{...}]} --}}`.
- Variables available in form partials: `$form` (current section data), `$section` (section key string), `$vcard` (the VCard model).
- The `$formPartial` variable is resolved in each editor PHP component and passed to `@include($formPartial)`.

### Transport / Location

- Transport icon keys: `metro`, `parking`, `taxi`, `bus`, `walk`, `delivery`, `auto`, `bike`.
- Color preset swatches: `$preset` loop — use `wire:click="$set('form.transport.X.stroke', '#hex')"`.

### Story Highlights Icons

- Icon field stores FontAwesome class names (e.g. `fa-utensils`, `fa-burger`).
- Frontend `script.js` uses `resolveHighlightIcon(icon)` which returns `<i class="fa-solid {icon}">` for `fa-*` values.
- FontAwesome 6 CDN is loaded in `restaurant-cafe-template.blade.php` head.

### Social Links

- Platform type is stored in `form.X.name` (the platform key, e.g. `instagram`).
- Alpine `$jsPlatforms` object (serialized via `Js::from()`) drives reactive header color/icon without waiting for server re-render.

---

## File Map (restaurant-cafe-template)

```
resources/views/
  livewire/vcards/forms/restaurant-cafe-template/
    _common.blade.php        ← General info (name, phone, maps URL, etc.)
    location.blade.php       ← Address + transport options
    social.blade.php         ← Social links (Alpine reactive cards)
    story.blade.php          ← Our Story + Highlights (FA icons)
    offers.blade.php         ← Special offers with icon picker modal
    hours.blade.php          ← Business hours
    MENU.blade.php           ← Menu items with category tabs
    gallery.blade.php        ← Photo gallery
    banner.blade.php         ← Banner / hero section
  vcards/templates/
    restaurant-cafe-template.blade.php   ← Public vCard HTML (SSR + JS hydration)
public/vcard-assets/restaurant-cafe-template/
  script.js                 ← Client-side JS: renderAll(), openMaps(), etc.
  style.css                 ← Template styles
```

---

## Common Mistakes to Avoid

1. **Missing `wire:key` on forms** — causes blank fields when switching sections (fixed in all 3 editors).
2. **Calling `hideInstant()` before `$wire.call('save...')`** — destroys `@entangle` binding.
3. **No `data-bs-backdrop="static"`** on modals with file inputs — modal closes when OS file picker opens/closes.
4. **Forgetting FontAwesome CDN** in public template head — icons invisible on live vCard.
5. **Editing only one editor** when a form partial is shared — always update all 3.
