<?php

use App\Models\ColorScheme;
use Livewire\Volt\Component;

new class extends Component {
    public ColorScheme $colorScheme;

    public function mount(ColorScheme $colorScheme): void
    {
        $this->colorScheme = $colorScheme;
    }
}; ?>

<div>
    @livewire('color-schemes.form', ['colorScheme' => $colorScheme])
</div>
