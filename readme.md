# Kirby Components

Build your site with components. A low level plugin completely without UI.

- **Version:** 0.1 beta
- **Requirement:** Kirby 3
- [Changelog](docs/changelog.md)
- [Disclaimer](https://devonera.se/docs/disclaimer/?user=jenstornell&plugin=kirby-components)
- [Donate](https://devonera.se/docs/donate/?user=jenstornell&plugin=kirby-components)

## How it works

Templates, controllers, blueprints and snippets are automatically registered when the components for them are created.

For example, you can do something like `snippet('footer')` when having the folder setup below.

### Folders

Place files in template or snippet components.

```text
site/components/
  --about/
    blueprint.yml
    component.php
  --home/
  --projects/
    menu1/
    menu2/
  header/
  footer/
    component.php
    image.jpg
    style.scss
```

- `--` prefixed folders are template components.
- Non prefixed folders are snippet components.
- You can place snippet components inside template components like `snippet(--my-template/my-snippet)`

### Files

The supported files will be used by the plugin. Additionally you can add your own files like images, css and js.

#### Template component files

These files are allowed in a template component.

- `autoload.php` - Will be loaded instantly when found.
- `blueprint.yml` - Blueprint file. Only the `yml` extension is supported.
- `component.php` - The template file.
- `controller.php` - The template controller file.

#### Snippet component files

- `autoload.php` - Will be loaded instantly when found.
- `component.php` - The snippet file.

## Options

```php
return [
  'jenstornell.components.roots' => $roots
];
```

### roots

You can send a path to your components, or an array with multiple paths. By default it will use `../site/components`.