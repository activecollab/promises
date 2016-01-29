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

/**
 * @package ActiveCollab\Promises\Test
 */
class PromiseTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Promise signature can't be empty
     */
    public function testPromiseSignatureIsRequired()
    {
        new Promise('');
    }

    /**
     * Test getSignature() method.
     */
    public function testGetSignature()
    {
        $this->assertSame('123', (new Promise(123))->getSignature());
    }

    /**
     * Test __toString() method.
     */
    public function testToString()
    {
        $this->assertSame('123', (string) new Promise(123));
    }
}
