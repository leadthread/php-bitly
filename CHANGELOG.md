# Changelog

All notable changes to `php-bitly` will be documented in this file.

### 4.0.0
- Changed ownership from zenapply to leadthread
- Namespace changes

### 3.0.0
- Correctly using OAuth2 now by using a developer token instead of a username and password combination

### 2.0.0
- Now using OAuth2 for bitly

### 1.2.0
- Added two new exceptions. BitlyRateLimitException and BitlyErrorException.

### 1.1.1
- Fixes URLs that dont have a protocol by adding `http://` onto the front of them

### 1.1.0
- Changed dependency from zenapply/php-request to guzzlehttp/guzzle

### 1.0.1
- Fixed bug where status code was ignored on errors

### 1.0.0
- Initial release and connected with packagist
