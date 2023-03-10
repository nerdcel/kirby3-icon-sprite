<?php

namespace Nerdcel\IconSprite;

use Kirby\Cms\File;
use DOMDocument;
use stdClass;

class SvgIcons
{
    protected bool $withStyles = false;
    protected string $width;
    protected string $fill;
    protected string $aspectRatio;
    protected static $instance;
    protected static array $icons = [];

    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function __construct()
    {
        $options = kirby()->option('nerdcel.icon-sprite');
        $this->withStyles = $options['withStyles'] ?? false;
        $this->width = $options['width'] ?? '24px';
        $this->fill = $options['fill'] ?? '#0a0a0a';
        $this->aspectRatio = $options['aspectRatio'] ?? '1';
    }

    /**
     * Add icon to sprite array
     *
     * @param  File  $file
     *
     * @return void
     */
    public function add(File $file): void
    {
        if (! array_key_exists($file->filename(), self::$icons)) {
            self::$icons[] = $this->checkFile($file);
        }
    }

    /**
     * @param  File  $file
     *
     * @return bool|string
     */
    private function checkFile(File $file): bool|string
    {
        $dom = new DOMDocument();
        $dom->load($file->root());

        if (! $dom->documentElement->hasAttribute('id')) {
            $dom->documentElement->setAttribute('id', 'icon-'.$file->name());
        }

        return $dom->saveHTML();
    }

    /**
     * Transform SVG to Symbol
     *
     * @param $icon
     *
     * @return stdClass
     */
    private function transform($icon): stdClass
    {
        $dom = new DOMDocument();
        $dom->loadXML($icon);
        $svg = $dom->documentElement;
        $reg = "/<svg.*?>(?<code>.*?)<\/svg>/s";

        preg_match($reg, $icon, $matches);

        $id = $svg->getAttribute('id');
        $viewBox = $svg->getAttribute('viewBox');

        $tmp = new stdClass();
        $tmp->id = $id;
        $tmp->viewbox = $viewBox;
        $tmp->code = '!! invalid svg !!';

        if ($matches) {
            $tmp->code = '<symbol id="'.$id.'" viewBox="'.$viewBox.'">'.$matches['code'].'</symbol>';
        }

        return $tmp;
    }

    /**
     * Return the SVG sprite
     * @return string
     */
    public function sprite(): string
    {
        $output = '<svg xmlns="http://www.w3.org/2000/svg" style="position:absolute; width: 0; height: 0;">';

        if ($this->withStyles) {
            $output = '<style>svg.icon { width: '.$this->width.'; fill: '.$this->fill.'; aspect-ratio: '.$this->aspectRatio.'; }</style>'.$output;
        }

        foreach (self::$icons as $icon) {
            $symbol = $this->transform($icon);

            $output .= $symbol->code;
        }

        $output .= '</svg>';

        return $output;
    }
}
