<?php

/*
 * This file is part of the Active Collab Promises.
 *
 * (c) A51 doo <info@activecollab.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ActiveCollab\Promises;

use ActiveCollab\DatabaseConnection\ConnectionInterface;
use ActiveCollab\Promises\Promise\PromiseInterface;
use Psr\Log\LoggerInterface;

/**
 * @package ActiveCollab\Promises
 */
class Promises implements PromisesInterface
{
    const PROMISES_TABLE_NAME = 'promises';

    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * @param ConnectionInterface  $connection
     * @param bool                 $create_tables_if_missing
     * @param LoggerInterface|null $log
     */
    public function __construct(ConnectionInterface &$connection, $create_tables_if_missing = true, LoggerInterface &$log = null)
    {
        $this->connection = $connection;
        $this->log = $log;

        if ($create_tables_if_missing && !$this->connection->tableExists(self::PROMISES_TABLE_NAME)) {
            $this->connection->execute('CREATE TABLE IF NOT EXISTS `' . self::PROMISES_TABLE_NAME . "` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(191) NOT NULL DEFAULT '',
                `jobs_count` int(10) unsigned NOT NULL DEFAULT '0',
                `created_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    /**
     * @return PromiseInterface
     */
    public function create()
    {
    }

    /**
     * @param  PromiseInterface $promise
     * @return boolean
     */
    public function fulfill(PromiseInterface $promise)
    {
    }

    /**
     * @param  PromiseInterface $promise
     * @return boolean
     */
    public function reject(PromiseInterface $promise)
    {
    }

    /**
     * @param  PromiseInterface $promise
     * @return boolean
     */
    public function isFulfilled(PromiseInterface $promise)
    {
    }

    /**
     * @param  PromiseInterface $promise
     * @return boolean
     */
    public function isRejected(PromiseInterface $promise)
    {
    }

    /**
     * @param  PromiseInterface $promise
     * @return boolean
     */
    public function isSettled(PromiseInterface $promise)
    {
    }
}
