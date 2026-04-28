<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CityDistrictSelect extends Component
{
    public function __construct(
        public string $selectedCity = '',
        public string $selectedDistrict = ''
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.city-district-select');
    }
}