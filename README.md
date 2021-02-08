# PHPUnit Printer

Custom styled PHPUnit print output for testing our projects.

### Compatibility

Use `^0.9` for PHPUnit < 9; and `^1.0` PHPUnit 9+.

### Install

```bash
composer require czim/phpunit-printer --dev
```

### Usage

You can specify the printer to use on the phpunit command line:

```bash
php vendor/bin/phpunit --printer 'Czim\PHPUnitPrinter\PrettyPrinter' tests/
```

Optionally, you can add it to your project's `phpunit.xml` file instead:

```xml
<phpunit
    ...
    printerClass="Czim\PHPUnitPrinter\PrettyPrinter">
    ...
```
