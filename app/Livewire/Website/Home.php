<?php

namespace App\Livewire\Website;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.website.home')
            ->title('Effortless International Shipping | ' . config('app.name'));
    }
}
