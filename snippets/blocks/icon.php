<?php    $class = '';
    if ($block->color()->value() === 'dark') {
        $class = 'text-dark dark:text-light';
    }
    if ($block->color()->value() === 'light') {
        $class = 'text-light dark:text-dark';
    }

    $size = 'h-auto ';
    if ($block->size()->value() === 'small') {
        $size .= 'w-24';
    }
    if ($block->size()->value() === 'medium') {
        $size .= 'w-48';
    }
    if ($block->size()->value() === 'large') {
        $size .= 'w-96';
    }
    if ($block->size()->value() === 'full') {
        $size .= 'w-full';
    }
?>

<div data-js-component="icon" class="{{ $block->classname()->value() }}">
    <?php svgIcon($block->icon()->toFile()->name(), 'fill fill-current ' . $class . $size) ?>
</div>
