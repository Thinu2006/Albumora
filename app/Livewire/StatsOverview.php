<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Album;
use App\Models\User;
use App\Models\Order;

class StatsOverview extends Component
{
    public $albumCount;
    public $userCount;
    public $orderCount;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->albumCount = Album::count();
        $this->userCount = User::count();
        $this->orderCount = Order::count();
    }

    public function render()
    {
        return view('livewire.stats-overview');
    }
}