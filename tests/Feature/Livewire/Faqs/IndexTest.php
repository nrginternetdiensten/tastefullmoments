<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('faqs.index');

    $component->assertSee('');
});
