<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                Dashboard Admin Stan: <span class="text-blue-600">{{ Auth::user()->stand->name ?? 'Stan' }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:admin-order-list />
        </div>
    </div>
</x-app-layout>
