# Aeon

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)

Time Management Framework for PHP

> The word aeon /ˈiːɒn/, also spelled eon (in American English), originally meant "life", "vital force" or "being", 
> "generation" or "a period of time", though it tended to be translated as "age" in the sense of "ages", "forever", 
> "timeless" or "for eternity".

[Source: Wikipedia](https://en.wikipedia.org/wiki/Aeon) 

## Website

Documentation of Aeon PHP project. 

### Development 

```bash
composer install
symfony server:start
```

[http://127.0.0.1:8000/](http://127.0.0.1:8000/)

### Generate static content

```bash
php -d memory_limit=-1 bin/console dump-static-site
```