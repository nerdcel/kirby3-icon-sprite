<?php

use Nerdcel\IconSprite\SvgIcons;

if (! function_exists('arr2attr')) {
    function arr2attr(array $input): string
    {
        return implode(' ', array_map(static function ($key) use ($input) {
            if (is_bool($input[$key])) {
                return $input[$key] ? $key : '';
            }

            return $key.'="'.$input[$key].'"';
        }, array_keys($input)));
    }
}

if (! function_exists('svgIcon')) {
    function svgIcon($icon = '', $class = 'w-6 h-6', $additionalAttr = [], $viewBox = "0 0 24 24"): string
    {
        return '<svg '.arr2attr($additionalAttr).' viewBox="'.$viewBox.'" class="'.$class.' icon icon-'.$icon.'"><use xlink:href="#icon-'.$icon.'"></use></svg>';
    }
}

if (! function_exists('svgSprite')) {
    function svgSprite(): string
    {
        $icons = site()->files()->filter('template', 'svgicon');
        $svgIcons = SvgIcons::getInstance();

        foreach ($icons as $icon) {
            $svgIcons->add($icon);
        }

        return $svgIcons->sprite();
    }
}

if (! function_exists('inlineSvg')) {
    function inlineSvg($icon = '', $class = 'w-6 h-6'): void
    {
        // If filepath is not existent, try to find the right file on page or site
        if (! file_exists($icon)) {
            if ($pageIcon = page()->files()->filterBy('template', 'svgicon')->filterBy('name', $icon)->first()) {
                $icon = $pageIcon->read();
            } elseif ($siteIcon = site()->files()->filterBy('template', 'svgicon')->filterBy('name', $icon)->first()) {
                $icon = $siteIcon->read();
            }
        } else {
            $icon = file_get_contents($icon);
        }
        echo "<div class=\"icon $class\">".$icon."</div>";
    }
}
