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
    /**
     * @return PromiseInterface
     */
    public function create();

    /**
     * @param  PromiseInterface $promise
     * @return boolean
     */
    public function fulfill(PromiseInterface $promise);

    /**
     * @param  PromiseInterface $promise
     * @return boolean
     */
    public function reject(PromiseInterface $promise);

    /**
     * @param  PromiseInterface $promise
     * @return boolean
     */
    public function isFulfilled(PromiseInterface $promise);

    /**
     * @param  PromiseInterface $promise
     * @return boolean
     */
    public function isRejected(PromiseInterface $promise);

    /**
     * @param  PromiseInterface $promise
     * @return boolean
     */
    public function isSettled(PromiseInterface $promise);
}
