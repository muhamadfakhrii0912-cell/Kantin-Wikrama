<?php

namespace App\Livewire;

use App\Models\Canteen;
use App\Models\Menu;
use App\Models\Stand;
use Livewire\Component;
use Livewire\Attributes\On;

class StudentCatalog extends Component
{
    public $canteens = [];
    public $selectedCanteen = null;
    
    public $stands = [];
    public $selectedStand = null;

    public $menus = [];

    public function mount()
    {
        $this->canteens = Canteen::orderBy('sort_order')->get();
        if ($this->canteens->count() > 0) {
            $this->selectCanteen($this->canteens->first()->id);
        }
    }

    public function selectCanteen($canteenId)
    {
        $this->selectedCanteen = $canteenId;
        $this->stands = Stand::where('canteen_id', $canteenId)->orderBy('sort_order')->get();
        
        $this->selectedStand = null; // reset stand
        $this->menus = [];
    }

    public function selectStand($standId)
    {
        $this->selectedStand = $standId;
        // Ambil menu yang ada di stand ini dan is_available = true
        $this->menus = Menu::where('stand_id', $standId)
                            ->where('is_available', true)
                            ->with('category')
                            ->get();
    }

    public function addToCart($menuId)
    {
        // Panggil event untuk ditangkap oleh CartComponent
        $this->dispatch('menu-added-to-cart', menuId: $menuId);
    }

    public function render()
    {
        return view('livewire.student-catalog');
    }
}
