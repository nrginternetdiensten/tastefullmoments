<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('faq-categories.form');

    $component->assertSee('');
});
