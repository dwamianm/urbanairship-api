Urban Airship PHP Library (Beta)
================================
PHP library for use with the Urban Airship API for sending push notifications. Supports iOS, Android, and Blackberry.

Adding in this not official version : Multi push in single HTTP Call

Requirements
------------

PHP >= 7.0

**Dependencies**

- Composer
- Httpful
- Monolog

**Development Dependencies**

PHPUnit

Example Usage
-------------

```php
<?php

require_once 'vendor/autoload.php';

use UrbanAirship\Airship;
use UrbanAirship\AirshipException;
use UrbanAirship\UALog;
use UrbanAirship\Push as P;
use UrbanAirship\Push\MultiPushRequest;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

UALog::setLogHandlers(array(new StreamHandler("php://stdout", Logger::DEBUG)));

$airship = new Airship("<app key>", "<master secret>");

// Single push
try {
    $response = $airship->push()
        ->setAudience(P\all)
        ->setNotification(P\notification("Hello from php"))
        ->setDeviceTypes(P\all)
        ->send();
} catch (AirshipException $e) {
    print_r($e);
}

// Multi push in single call
try {
    $multiPushRequest = new MultiPushRequest($airship);
    $multiPushRequest->addPushRequest(
        $airship->push()
            ->setAudience(P\all)
            ->setNotification(P\notification("Hello from php"))
            ->setDeviceTypes(P\all)
    );
    $response = $multiPushRequest->send();
} catch (AirshipException $e) {
    print_r($e);
}

```

Resources
---------

- [Home page](http://docs.urbanairship.com/reference/libraries/php/)
- [Official Source](https://github.com/urbanairship/php-library2)
- [Support](http://support.urbanairship.com/)
