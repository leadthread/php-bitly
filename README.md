# php-bitly
[![Latest Version](https://img.shields.io/github/release/leadthread/php-bitly.svg?style=flat-square)](https://github.com/leadthread/php-bitly/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/leadthread/php-bitly.svg?branch=master)](https://travis-ci.org/leadthread/php-bitly)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/leadthread/php-bitly/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/leadthread/php-bitly/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/leadthread/php-bitly/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/leadthread/php-bitly/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/56f3252c35630e0029db0187/badge.svg?style=flat)](https://www.versioneye.com/user/projects/56f3252c35630e0029db0187)
[![Total Downloads](https://img.shields.io/packagist/dt/leadthread/php-bitly.svg?style=flat-square)](https://packagist.org/packages/leadthread/php-bitly)

Version 3 now uses OAuth2 as required by Bitly. [Get your developer access token here](https://bitly.com/a/oauth_apps)

## Installation

Install via [composer](https://getcomposer.org/) - In the terminal:
```bash
composer require leadthread/php-bitly
```

## Usage
```php
use LeadThread\Bitly\Bitly;
$c = new Bitly("access token");
$result = $c->shorten("https://www.google.com/");
var_dump($result);
// string(21) "http://bit.ly/1SvUIo8"
```
