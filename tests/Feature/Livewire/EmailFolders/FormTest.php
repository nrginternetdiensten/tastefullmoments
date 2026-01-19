<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('email-folders.form');

    $component->assertSee('');
});
