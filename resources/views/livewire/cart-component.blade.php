<div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 sticky top-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z" />
            </svg> 
            Keranjang
        </h3>
        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-full">{{ count($cart) }} Item</span>
    </div>

    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
    @endif

    @if ($errorMessage)
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <span class="font-medium">Gagal!</span> {{ $errorMessage }}
        </div>
    @endif

    @if(empty($cart))
        <div class="text-center py-8 text-slate-400">
            <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <p>Belum ada makanan yang dipilih</p>
        </div>
    @else
        <div class="space-y-4 mb-6 max-h-[40vh] overflow-y-auto pr-2">
            @foreach($cart as $menuId => $item)
                <div class="flex justify-between items-start border-b border-slate-100 pb-4">
                    <div class="flex-1">
                        <h4 class="font-semibold text-slate-800 text-sm">{{ $item['menu']->name }}</h4>
                        <p class="text-xs text-slate-500 mb-2">Rp {{ number_format($item['menu']->price, 0, ',', '.') }}</p>
                        
                        <div class="flex items-center gap-2">
                            <button wire:click="decreaseQuantity({{ $menuId }})" class="w-6 h-6 flex items-center justify-center bg-slate-100 rounded text-slate-600 hover:bg-slate-200 transition-colors">-</button>
                            <span class="text-sm font-semibold w-4 text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="increaseQuantity({{ $menuId }})" class="w-6 h-6 flex items-center justify-center bg-slate-100 rounded text-slate-600 hover:bg-slate-200 transition-colors">+</button>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="block font-bold text-slate-800 text-sm mb-2">Rp {{ number_format($item['menu']->price * $item['quantity'], 0, ',', '.') }}</span>
                        <button wire:click="removeItem({{ $menuId }})" class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Form Pemesanan -->
        <div class="space-y-4 pt-4 border-t border-slate-200">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Rencana Pengambilan</label>
                <input type="datetime-local" wire:model="pickupTime" class="w-full text-sm border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                <input type="text" wire:model="note" placeholder="Misal: Jangan pedas" class="w-full text-sm border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="bg-slate-50 p-4 rounded-lg flex justify-between items-center mt-2">
                <span class="text-sm font-bold text-slate-700">Total Pembayaran</span>
                <span class="text-xl font-black text-blue-600">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>

            <button 
                wire:click="checkout"
                wire:loading.attr="disabled"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-all duration-200 flex justify-center items-center gap-2">
                <span wire:loading.remove>Pesan Sekarang</span>
                <span wire:loading>Memproses...</span>
            </button>
        </div>
    @endif
</div>
