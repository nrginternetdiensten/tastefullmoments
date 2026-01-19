<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('users.edit-role');

    $component->assertSee('');
});
