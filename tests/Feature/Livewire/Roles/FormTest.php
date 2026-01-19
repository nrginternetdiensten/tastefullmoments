<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('roles.form');

    $component->assertSee('');
});
