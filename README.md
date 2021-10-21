# Nova Inline Text Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/optimistdigital/nova-inline-text-field.svg?style=flat-square)](https://packagist.org/packages/optimistdigital/nova-inline-text-field)
[![Total Downloads](https://img.shields.io/packagist/dt/optimistdigital/nova-inline-text-field.svg?style=flat-square)](https://packagist.org/packages/optimistdigital/nova-inline-text-field)

This [Laravel Nova](https://nova.laravel.com/) package adds an inline text field to Nova's arsenal of fields.

## Requirements

- `php: >=7.2`
- `laravel/nova: ^3.0`

## Features

A simple text field that allows the user to edit the value on Index and Detail views in addition to the Form view.

## Screenshots

![Index page](./docs/index.gif)

## Installation

Install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require optimistdigital/nova-inline-text-field
```

## Usage

### General

```php
use OptimistDigital\NovaInlineTextField\InlineText;

public function fields(Request $request) {
    InlineText::make('Name'),
}
```

## Credits

- [Tarvo Reinpalu](https://github.com/tarpsvo)

## License

Nova Inline Text Field is open-sourced software licensed under the [MIT license](LICENSE.md).
