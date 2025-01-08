<?php

namespace Nerdcel\IconSprite;

use Kirby\Cms\File;
use DOMDocument;
use stdClass;

class SvgIcons
{
    protected static array $icons = [];
    protected bool $withStyles = false;
    protected string $width;
    protected string $fill;
    protected string $aspectRatio;
    protected static $instance;

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
     * @param  null  $name
     *
     * @return void
     */
    public function add(File $file, $name = null): void
    {
        if (! $this->exists($name ?? $file->name())) {
            self::$icons[$name ?? $file->name()]['icon'] = $this->checkFile($file, $name);
            self::$icons[$name ?? $file->name()]['info'] = $this->transform(self::$icons[$name ?? $file->name()]['icon'], $name ?? $file->name());
        }
    }

    /**
     * @param  File  $file
     * @param $name
     *
     * @return array
     */
    public function get(File $file, $name = null): array
    {
        return self::$icons[$name ?? $file->name()];
    }

    /**
     * @param  string  $name
     *
     * @return bool
     */
    public function exists(string $name): bool
    {
        return array_key_exists($name, self::$icons);
    }

    /**
     * @param  File  $file
     *
     * @return bool|string
     */
    private function checkFile(File $file, $name = null): bool|string
    {
        $dom = new DOMDocument();
        $dom->load($file->root());

        if (! $dom->documentElement->hasAttribute('id')) {
            $dom->documentElement->setAttribute('id', 'icon-'.($name ?? $file->name()));
        }

        // Get all elements with an ID
        $ids = [];
        $elements = $dom->getElementsByTagName('*');

        foreach ($elements as $element) {
            if ($element->hasAttribute('id')) {
                $ids[] = $element->getAttribute('id');
            }
        }

        // Transform all ids and there references in the SVG
        $textRep = $dom->saveHTML();
        foreach ($ids as $id) {
            $newId = 'icon-'. $this->transformPath($id);
            // Search and replace only if it starts with # or "
            $textRep = preg_replace('/(?<=#)'.$id.'/', $newId, $textRep);
            $textRep = preg_replace('/(?<=\")'.$id.'/', $newId, $textRep);
        }

        return $textRep;
    }

    public function transformPath($path): string
    {
        return md5($path);
    }

    /**
     * Transform SVG to Symbol
     *
     * @param $icon
     *
     * @return stdClass
     */
    private function transform($icon, $id): stdClass
    {
        $dom = new DOMDocument();
        $dom->loadXML(html_entity_decode($icon, ENT_QUOTES, 'UTF-8'));
        $svg = $dom->documentElement;
        $reg = "/<svg.*?>(?<code>.*?)<\/svg>/s";

        preg_match($reg, $icon, $matches);

        $viewBox = $svg->getAttribute('viewBox');

        $tmp = new stdClass();
        $tmp->id = 'icon-' . $id;
        $tmp->viewbox = $viewBox;
        $tmp->code = '!! invalid svg !!';

        if ($matches) {
            $tmp->code = '<symbol id="'.$tmp->id.'" viewBox="'.$tmp->viewbox.'">'.$matches['code'].'</symbol>';
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
            $symbol = $icon['info'];

            $output .= $symbol->code;
        }

        $output .= '</svg>';

        return $output;
    }
}
