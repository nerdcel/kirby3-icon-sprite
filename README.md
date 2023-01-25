# Kirby3 plugin: Icon sprite

This plugin provides a custom panel for your kirby installation to manage svg icons which can be used in your template to provide a SVG sprite.
To set custom options, please see the [Options](#options) section in this README file.

> This plugin is completely free and published under the MIT license. However, if you are using it in a commercial project and want to help me keep up with maintenance, please consider [making a donation of your choice](https://www.paypal.me/nerdcel).

## Installation

### Download

Download and copy this repository to `/site/plugins/icon-sprite`.

### Git submodule

```
git submodule add https://github.com/nerdcel/kirby3-icon-sprite.git site/plugins/icon-sprite
```

### Composer

```
composer require nerdcel/kirby3-icon-sprite
```

## Setup

![screencast-responsive-images-panel](demo.gif)

### Template

```php
<?php svgSprite();  ?>
<?php svgIcon('slug', 'css-classes', [ /** Additional attributes as key => value */]);  ?>
<?php inlineIcon('icon-path', 'css-classes');  ?>
```

### Output

```html
// Tbd.
```

## Options

The following options are available to be set using your site/config/config.php

```php
'nerdcel.icon-sprite' => [
    // Tbd.
]
```

## Development

Frontend components are based on kirby's internal UI Kit. Development works using the kirbyup npm module.
To start developing simply run the following cmd from the plugin root:
```shell
npm run dev
```

If that doesn't work, rund ```npm install``` first.

## License

MIT

## Credits

- [Marcel Hieke](https://github.com/nerdcel)
