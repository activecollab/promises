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

use ActiveCollab\Promises\Promise\Promise;
use ActiveCollab\Promises\Promise\PromiseInterface;
use ActiveCollab\Promises\Promises;
use ActiveCollab\Promises\PromisesInterface;

/**
 * @package ActiveCollab\Promises\Test
 */
class PromisesTest extends TestCase
{
    /**
     * Test if promises table is created by default.
     */
    public function testPromisesTableIsCreatedByDefault()
    {
        $this->assertFalse($this->connection->tableExists(PromisesInterface::PROMISES_TABLE_NAME));
        new Promises($this->connection);
        $this->assertTrue($this->connection->tableExists(PromisesInterface::PROMISES_TABLE_NAME));
    }

    /**
     * Test if promises table creation can be skipped.
     */
    public function testPromisesTableCreationCanBeSkipped()
    {
        $this->assertFalse($this->connection->tableExists(PromisesInterface::PROMISES_TABLE_NAME));
        new Promises($this->connection, false);
        $this->assertFalse($this->connection->tableExists(PromisesInterface::PROMISES_TABLE_NAME));
    }

    /**
     * Test make a promise.
     */
    public function testCreate()
    {
        $promises = new Promises($this->connection);

        $this->assertEquals(0, $this->connection->count(PromisesInterface::PROMISES_TABLE_NAME));
        $promise = $promises->create();
        $this->assertEquals(1, $this->connection->count(PromisesInterface::PROMISES_TABLE_NAME));
        $this->assertInstanceOf(PromiseInterface::class, $promise);
        $this->assertNotEmpty($promise->getSignature());
    }

    /**
     * Test project fulfillement call.
     */
    public function testFulfill()
    {
        $promises = new Promises($this->connection);

        $promise = $promises->create();

        $this->assertFalse($promises->isFulfilled($promise));
        $promises->fulfill($promise);

        $this->assertTrue($promises->isFulfilled($promise));
        $this->assertFalse($promises->isRejected($promise));
        $this->assertTrue($promises->isSettled($promise));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Promise 'not found' not found
     */
    public function testFulfillExceptionWhenPromiseCantBeFound()
    {
        (new Promises($this->connection))->fulfill(new Promise('not found'));
    }

    /**
     * Test reject promise call.
     */
    public function testReject()
    {
        $promises = new Promises($this->connection);

        $promise = $promises->create();

        $this->assertFalse($promises->isRejected($promise));
        $promises->reject($promise);

        $this->assertFalse($promises->isFulfilled($promise));
        $this->assertTrue($promises->isRejected($promise));
        $this->assertTrue($promises->isSettled($promise));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Promise 'not found' not found
     */
    public function testRejectExceptionWhenPromiseCantBeFound()
    {
        (new Promises($this->connection))->reject(new Promise('not found'));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Settled promises can't be fulfilled
     */
    public function testSettledPromiseCantBeFulfilled()
    {
        $promises = new Promises($this->connection);
        $promise = $promises->create();
        $promises->fulfill($promise);
        $this->assertTrue($promises->isSettled($promise));

        $promises->fulfill($promise);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Settled promises can't be rejected
     */
    public function testSettledPromiseCantBeRejected()
    {
        $promises = new Promises($this->connection);
        $promise = $promises->create();
        $promises->fulfill($promise);
        $this->assertTrue($promises->isSettled($promise));

        $promises->reject($promise);
    }
}
