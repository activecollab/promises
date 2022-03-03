<?php

/*
 * This file is part of the Active Collab Promises.
 *
 * (c) A51 doo <info@activecollab.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ActiveCollab\Promises\Test;

use ActiveCollab\DatabaseConnection\Connection\MysqliConnection;
use ActiveCollab\Promises\PromisesInterface;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var \mysqli
     */
    protected $link;

    /**
     * @var MysqliConnection
     */
    protected $connection;

    /**
     * @var Carbon
     */
    protected $now;

    public function setUp(): void
    {
        parent::setUp();

        $this->link = new \MySQLi('localhost', 'root', '', 'activecollab_promises_test');

        if ($this->link->connect_error) {
            throw new \RuntimeException('Failed to connect to database. MySQL said: ' . $this->link->connect_error);
        }

        $this->connection = new MysqliConnection($this->link);
        $this->connection->execute('DROP TABLE IF EXISTS `' . PromisesInterface::PROMISES_TABLE_NAME . '`');

        $this->now = new Carbon();
        Carbon::setTestNow($this->now);
    }

    public function tearDown(): void
    {
        Carbon::setTestNow(null);

        parent::tearDown();
    }
}
