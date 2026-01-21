<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('faq-categories.index');

    $component->assertSee('');
});
