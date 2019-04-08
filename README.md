# PHPUnit Printer

Custom styled PHPUnit print output for testing our projects.

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
