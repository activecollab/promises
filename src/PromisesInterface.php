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

use ActiveCollab\Promises\Promise\PromiseInterface;

/**
 * @package ActiveCollab\Promises
 */
interface PromisesInterface
{
    const PROMISES_TABLE_NAME = 'promises';

    /**
     * @return PromiseInterface
     */
    public function create();

    /**
     * @param  PromiseInterface $promise
     * @return bool
     */
    public function fulfill(PromiseInterface $promise);

    /**
     * @param  PromiseInterface $promise
     * @return bool
     */
    public function reject(PromiseInterface $promise);

    /**
     * @param  PromiseInterface $promise
     * @return bool
     */
    public function isFulfilled(PromiseInterface $promise);

    /**
     * @param  PromiseInterface $promise
     * @return bool
     */
    public function isRejected(PromiseInterface $promise);

    /**
     * @param  PromiseInterface $promise
     * @return bool
     */
    public function isSettled(PromiseInterface $promise);
}
