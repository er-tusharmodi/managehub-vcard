<?php

namespace App\Livewire\Concerns;

use Illuminate\Validation\ValidationException;

trait HandlesToastValidation
{
    protected function validateWithToast(array $rules, array $messages = [], array $attributes = [])
    {
        try {
            return $this->validate($rules, $messages, $attributes);
        } catch (ValidationException $e) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Please fix the highlighted errors and try again.'
            );
            throw $e;
        }
    }
}
