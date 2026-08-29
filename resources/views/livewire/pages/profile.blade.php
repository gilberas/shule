<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public function getListeners(): array
    {
        return ['profile-updated' => 'refreshName'];
    }

    public function refreshName(): void
    {
        $this->dispatch('profile-updated');
    }
}; ?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Profile Information') }}</h3>
                <div class="mt-6">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
