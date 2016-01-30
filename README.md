# Promises Library

Purpose of this library is to provide persisted server side promises. We built it to power our multi-service system, where one service issues a job requests and expect that antoher service executes it (and fulfulls or rejects a promise that it gives when it accepts a job).

Example:

```php
<?php

use ActiveCollab\DatabaseConnection\Connection\MysqliConnection;

$mysqli_link = new \MySQLi('localhost', 'root', '', 'activecollab_promises_test');

if ($mysqli_link->connect_error) {
    throw new \RuntimeException('Failed to connect to database. MySQL said: ' . $mysqli_link->connect_error);
}

$mysqli_connection = new MysqliConnection($mysqli_link);

$promises = new Promises($this->connection);

// Print promise signature
$promise = $promises->create();

print $promise->getSinature() . "\n";
print (string) $promise . "\n"; // __toString() is available

// Default promise status
$promise = $promises->create();

$promises->isFulfilled($promise); // false
$promises->isRejected($promise);  // false
$promises->isSettled($promise);   // false

// Promise fulfillment
$promise = $promises->create();

$promises->fulfill($promise);

$promises->isFulfilled($promise); // true
$promises->isRejected($promise);  // false
$promises->isSettled($promise);   // true

// Promise rejection
$promise = $promises->create();

$promises->reject($promise);

$promises->isFulfilled($promise); // false
$promises->isRejected($promise);  // true
$promises->isSettled($promise);   // true
```

## Running tests

To run tests, `cd` to this directory and run:

```bash
phpunit -c test
```
