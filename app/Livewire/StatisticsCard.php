<?php

namespace App\Livewire;

use Livewire\Component;

class StatisticsCard extends Component
{
    public $value;

    public $label;

    public $isPercentage = false;

    public $currentValue = 0;

    public $targetValue;

    public $duration = 1500;

    public $animated = false;

    public function mount($value, $label, $isPercentage = false, $duration = 2000)
    {
        $this->targetValue = (int) $value;
        $this->value = $value;
        $this->label = $label;
        $this->isPercentage = $isPercentage;
        $this->duration = $duration;
        $this->currentValue = 0;
    }

    public function render()
    {
        return view('livewire.statistics-card');
    }
}
