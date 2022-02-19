[![Latest Version on Packagist](https://img.shields.io/packagist/v/elfsundae/laravel-hashid-uuid.svg?style=flat-square)](https://packagist.org/packages/elfsundae/laravel-hashid-uuid)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![tests](https://github.com/ElfSundae/laravel-hashid-uuid/actions/workflows/tests.yml/badge.svg)](https://github.com/ElfSundae/laravel-hashid-uuid/actions/workflows/tests.yml)
[![StyleCI](https://styleci.io/repos/110262872/shield)](https://styleci.io/repos/110262872)
[![SymfonyInsight Grade](https://img.shields.io/symfony/i/grade/57a207e1-f852-42c4-8260-a078b7dff9df?style=flat-square)](https://insight.symfony.com/projects/57a207e1-f852-42c4-8260-a078b7dff9df)
[![Quality Score](https://img.shields.io/scrutinizer/g/ElfSundae/laravel-hashid-uuid.svg?style=flat-square)](https://scrutinizer-ci.com/g/ElfSundae/laravel-hashid-uuid)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ElfSundae/laravel-hashid-uuid/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/ElfSundae/laravel-hashid-uuid/?branch=master)

This is a plugin package for [Laravel Hashid][hashid], it provides an `uuid` hashid driver to shorten your [UUID] encoding.

## Installation

You can install this package using the [Composer](https://getcomposer.org) manager:

```sh
$ composer require elfsundae/laravel-hashid-uuid
```

For Lumen or earlier Laravel than v5.5, you need to register the service provider manually:

```php
ElfSundae\Laravel\Hashid\UuidServiceProvider::class,
```

## Configuration

- Driver name: `uuid`
- Configuration:
    - `connection` : The hashid connection name for encoding and decoding.

**Example:**

```php
'connections' => [

    'uuid_base62' => [
        'driver' => 'uuid',
        'connection' => 'base62',
    ],

    'uuid_hashids' => [
        'driver' => 'uuid',
        'connection' => 'hashids_string',
    ],

    // ...
],
```

## Usage

- `encode($data)` accepts a [`Ramsey\Uuid\Uuid`][uuid] instance or an UUID string.
- `decode($data)` returns a [`Ramsey\Uuid\Uuid`][uuid] instance.

**Example:**

```php
use Ramsey\Uuid\Uuid;

$string = 'cd79e206-c715-11e7-891d-8bf37117635e';
$uuid = Uuid::fromString($string);
$hex = $uuid->getHex();                 // "cd79e206c71511e7891d8bf37117635e"

// Encode using the original connections:
hashid_encode($uuid, 'base62');         // "1mUcj8TfpKB7vEBlRecZ4PADhnE1UbBg2L9n3PQOSFqzYZHwj"
hashid_encode($uuid, 'hashids_string'); // "Wr3xrA2WWEh4K1LBBV6vhXL592VVQoSKWnpQB5vkt9DkZxDp6Lsjz945vnRl"

// Encode using the "uuid" driver:
hashid_encode($uuid, 'uuid_base62');    // "6Fj7unqIaNKkq5zbJo1HJ8"
hashid_encode($string, 'uuid_base62');  // "6Fj7unqIaNKkq5zbJo1HJ8"
hashid_encode($hex, 'uuid_base62');     // "6Fj7unqIaNKkq5zbJo1HJ8"
hashid_encode($uuid, 'uuid_hashids');   // "kp2wZkXOBzSMNA3nPxNzSOZJ701"

// Decode
$decoded = hashid_decode('6Fj7unqIaNKkq5zbJo1HJ8', 'uuid_base62');
(string) $decoded;                               // "cd79e206-c715-11e7-891d-8bf37117635e"
$decoded->getDateTime()->format('Y-m-d H:i:s');  // "2017-11-11 19:23:31"

// Decoding failure
(string) hashid_decode('foobar', 'uuid_base62'); // "00000000-0000-0000-0000-000000000000"
```

## Testing

```sh
$ composer test
```

## License

This package is open-sourced software licensed under the [MIT License](LICENSE.md).

[hashid]: https://github.com/ElfSundae/laravel-hashid
[uuid]: https://github.com/ramsey/uuid
