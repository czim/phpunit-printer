# PHPUnit Printer

Custom styled PHPUnit print output for testing our projects.

### Compatibility

| PHPUnit version | Package version |
|-----------------|-----------------|
| < 9             | 0.9             |
| 9               | 1.0             |
| 10              | 2.0             |


### Install

```bash
composer require czim/phpunit-printer --dev
```


### Usage

You can add the package extension to your project's `phpunit.xml` file:

```xml
<phpunit>
    <!-- ... -->

    <extensions>
        <bootstrap class="Czim\PHPUnitPrinter\PrintExtension"/>
    </extensions>
    
    <!-- ... -->
</phpunit>
```

Then you can run with only the new custom output by hiding the default progress:

```bash
vendor/bin/phpunit --no-progress
```

You can also run it with normal progress. The custom formatting will be displayed directly after
the normal progress indicators when all tests are finished.
