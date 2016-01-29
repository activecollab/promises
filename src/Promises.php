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
use ActiveCollab\Promises\Promise\Promise;
use ActiveCollab\Promises\Promise\PromiseInterface;
use Carbon\Carbon;
use Psr\Log\LoggerInterface;

/**
 * @package ActiveCollab\Promises
 */
class Promises implements PromisesInterface
{
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
            if ($this->log) {
                $this->log->info('Creating {table_name} table', ['table_name' => self::PROMISES_TABLE_NAME]);
            }

            $this->connection->execute('CREATE TABLE IF NOT EXISTS `' . self::PROMISES_TABLE_NAME . "` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `signature` varchar(191) NOT NULL DEFAULT '',
                `created_at` datetime DEFAULT NULL,
                `settlement` enum(?, ?) DEFAULT NULL,
                `settled_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE (`signature`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;", PromiseInterface::FULFILLED, PromiseInterface::REJECTED);
        }
    }

    /**
     * @return PromiseInterface
     */
    public function create()
    {
        do {
            $signature = $this->createSignature();
        } while ($this->connection->count(self::PROMISES_TABLE_NAME, ['`signature` = ?', $signature]));

        $this->connection->execute('INSERT INTO ' . self::PROMISES_TABLE_NAME . ' (signature, created_at) VALUES (?, ?)', $signature, (new Carbon())->format('Y-m-d H:i:s'));

        return new Promise($signature);
    }

    /**
     * @return string
     */
    private function createSignature()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $result = '';
        for ($i = 0; $i < 64; ++$i) {
            $result .= $characters[rand(0, $characters_length - 1)];
        }

        return $result;
    }

    /**
     * @param  PromiseInterface $promise
     * @return bool
     */
    public function fulfill(PromiseInterface $promise)
    {
        if ($this->isSettled($promise)) {
            throw new \LogicException("Settled promises can't be fulfilled");
        }

        $this->connection->execute('UPDATE ' . self::PROMISES_TABLE_NAME . ' SET `settlement` = ?, `settled_at` = UTC_TIMESTAMP() WHERE `signature` = ?', PromiseInterface::FULFILLED, $promise->getSignature());
    }

    /**
     * @param  PromiseInterface $promise
     * @return bool
     */
    public function reject(PromiseInterface $promise)
    {
        if ($this->isSettled($promise)) {
            throw new \LogicException("Settled promises can't be rejected");
        }

        $this->connection->execute('UPDATE ' . self::PROMISES_TABLE_NAME . ' SET `settlement` = ?, `settled_at` = UTC_TIMESTAMP() WHERE `signature` = ?', PromiseInterface::REJECTED, $promise->getSignature());
    }

    /**
     * @param  PromiseInterface $promise
     * @return bool
     */
    public function isFulfilled(PromiseInterface $promise)
    {
        return (boolean) $this->connection->executeFirstCell('SELECT COUNT(`id`) AS "row_count" FROM ' . self::PROMISES_TABLE_NAME . ' WHERE `settlement` = ? AND `settled_at` IS NOT NULL', PromiseInterface::FULFILLED);
    }

    /**
     * @param  PromiseInterface $promise
     * @return bool
     */
    public function isRejected(PromiseInterface $promise)
    {
        return (boolean) $this->connection->executeFirstCell('SELECT COUNT(`id`) AS "row_count" FROM ' . self::PROMISES_TABLE_NAME . ' WHERE `settlement` = ? AND `settled_at` IS NOT NULL', PromiseInterface::REJECTED);
    }

    /**
     * @param  PromiseInterface $promise
     * @return bool
     */
    public function isSettled(PromiseInterface $promise)
    {
        return (boolean) $this->connection->executeFirstCell('SELECT COUNT(`id`) AS "row_count" FROM ' . self::PROMISES_TABLE_NAME . ' WHERE `settlement` IS NOT NULL AND `settled_at` IS NOT NULL');
    }
}
