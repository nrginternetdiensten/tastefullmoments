<?php

use Livewire\Volt\Component;

?>

<div>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('color-schemes.index');

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-856754190-1', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</div><?php /**PATH /Users/nickgroot/Sites/tastefullmoments/resources/views/livewire/pages/color-schemes/index.blade.php ENDPATH**/ ?>