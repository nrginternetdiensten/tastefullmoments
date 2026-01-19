<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('email-items.index');

    $component->assertSee('');
});
