<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Kolom Katalog Kiri (2/3) -->
    <div class="md:col-span-2 space-y-6">
        
        <!-- Pilihan Kantin -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Pilih Kantin</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($canteens as $canteen)
                    <button 
                        wire:click="selectCanteen({{ $canteen->id }})"
                        class="px-5 py-2 rounded-lg font-semibold transition-colors duration-200 
                        {{ $selectedCanteen == $canteen->id ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $canteen->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Pilihan Stan -->
        @if(count($stands) > 0)
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Pilih Stan</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($stands as $stand)
                    <button 
                        wire:click="selectStand({{ $stand->id }})"
                        class="flex flex-col items-center p-4 rounded-xl border-2 transition-all duration-200
                        {{ $selectedStand == $stand->id ? 'border-blue-500 bg-blue-50' : 'border-transparent bg-slate-50 hover:bg-slate-100' }}">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm text-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0v-4m0 4h4" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-center text-slate-700">{{ $stand->name }}</span>
                    </button>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Daftar Menu -->
        @if($selectedStand)
        <div>
            <h3 class="text-xl font-bold text-slate-800 mb-4">Menu Tersedia</h3>
            @if(count($menus) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($menus as $menu)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-slate-800 text-lg">{{ $menu->name }}</h4>
                                    <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-500 rounded-md">{{ $menu->category->name }}</span>
                                </div>
                                <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ $menu->description }}</p>
                            </div>
                            <div class="flex justify-between items-center mt-4 pt-4 border-t border-slate-100">
                                <span class="text-lg font-bold text-blue-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                <button 
                                    wire:click="addToCart({{ $menu->id }})"
                                    class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-white rounded-xl border border-dashed border-slate-300">
                    <p class="text-slate-500">Belum ada menu di stan ini.</p>
                </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Kolom Keranjang Kanan (1/3) -->
    <div class="md:col-span-1">
        <livewire:cart-component />
    </div>
</div>
