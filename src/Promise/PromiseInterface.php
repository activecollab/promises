<?php

/*
 * This file is part of the Active Collab Promises.
 *
 * (c) A51 doo <info@activecollab.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ActiveCollab\Promises\Promise;

/**
 * @package ActiveCollab\Promises\Promise
 */
interface PromiseInterface
{
    const FULFILLED = 'fulfilled';
    const REJECTED = 'rejected';

    /**
     * Return promise signature. This signature is unique to all all promises given by the application components.
     *
     * @return string
     */
    public function getSignature();
}
