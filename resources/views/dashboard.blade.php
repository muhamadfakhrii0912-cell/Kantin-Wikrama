<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Pesan Makan Sekarang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Komponen Livewire Katalog Siswa -->
            <livewire:student-catalog />
        </div>
    </div>
</x-app-layout>
