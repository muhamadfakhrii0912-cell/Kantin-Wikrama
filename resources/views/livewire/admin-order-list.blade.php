<div class="space-y-6">
    <!-- Filter & Pencarian PIN -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-2 w-full md:w-auto">
            <input 
                type="text" 
                wire:model.live="searchPin" 
                placeholder="Cari PIN Pengambilan..." 
                class="w-full md:w-64 text-sm border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto">
            @foreach(['all' => 'Semua', 'pending' => 'Menunggu', 'in_progress' => 'Diproses', 'ready_for_pickup' => 'Siap Diambil', 'picked_up' => 'Selesai'] as $key => $label)
                <button 
                    wire:click="$set('statusFilter', '{{ $key }}')"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors
                    {{ $statusFilter === $key ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    @if (session()->has('success'))
        <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel Pesanan -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3">PIN / Order ID</th>
                    <th class="px-6 py-3">Pemesan</th>
                    <th class="px-6 py-3">Item Pesanan</th>
                    <th class="px-6 py-3">Rencana Ambil</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orderStands as $os)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4 font-medium text-slate-900">
                            <span class="block font-mono text-base font-bold text-blue-600">PIN: {{ $os->order->pin }}</span>
                            <span class="text-xs text-slate-400">{{ $os->order->order_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="block font-semibold text-slate-800">{{ $os->order->user->name }}</span>
                            <span class="text-xs text-slate-500">NIS: {{ $os->order->user->nis ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <ul class="space-y-1">
                                @foreach($os->orderItems as $item)
                                    <li class="text-xs text-slate-700">
                                        <span class="font-bold">{{ $item->quantity }}x</span> {{ $item->menu->name }}
                                    </li>
                                @endforeach
                            </ul>
                            @if($os->order->note)
                                <p class="text-xs text-amber-600 mt-1 italic">Ket: "{{ $os->order->note }}"</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs">
                            {{ \Carbon\Carbon::parse($os->order->pickup_time)->format('H:i, d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badgeClasses = [
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'ready_for_pickup' => 'bg-purple-100 text-purple-800',
                                    'picked_up' => 'bg-emerald-100 text-emerald-800',
                                    'canceled' => 'bg-rose-100 text-rose-800',
                                ][$os->status] ?? 'bg-slate-100 text-slate-800';
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClasses }}">
                                {{ strtoupper(str_replace('_', ' ', $os->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            @if($os->status === 'pending')
                                <button wire:click="updateStatus({{ $os->id }}, 'in_progress')" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-colors">
                                    Proses
                                </button>
                            @elseif($os->status === 'in_progress')
                                <button wire:click="updateStatus({{ $os->id }}, 'ready_for_pickup')" class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-semibold transition-colors">
                                    Siap Ambil
                                </button>
                            @elseif($os->status === 'ready_for_pickup')
                                <button wire:click="updateStatus({{ $os->id }}, 'picked_up')" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors">
                                    Selesai (Diambil)
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                            Belum ada pesanan masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
