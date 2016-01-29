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

use InvalidArgumentException;

/**
 * @package ActiveCollab\Promises\Promise
 */
class Promise implements PromiseInterface
{
    /**
     * @var string
     */
    private $signature;

    /**
     * @param string $signature
     */
    public function __construct($signature)
    {
        if (empty($signature)) {
            throw new InvalidArgumentException("Promise signature can't be empty");
        }

        $this->signature = (string) $signature;
    }

    /**
     * {@inheritdoc}
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->signature;
    }
}
