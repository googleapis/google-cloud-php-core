<?php
/**
 * Copyright 2017 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Core\Tests\Unit\Lock;

use Google\Cloud\Core\Lock\FlockLock;
use Google\Cloud\Core\Testing\Lock\MockValues;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @group core
 * @group lock
 */
class FlockLockTest extends TestCase
{
    use CommonLockTrait;

    const LOCK_NAME = 'test';

    public function setUp(): void
    {
        MockValues::initialize();
        $this->setLock(new FlockLock(self::LOCK_NAME));
    }

    public function testThrowsExceptionWithInvalidFileName()
    {
        $this->expectException(InvalidArgumentException::class);

        new FlockLock(123);
    }

    public function testThrowsExceptionWhenFlockFailsOnAcquire()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to acquire lock.');

        MockValues::$flockReturnValue = false;
        $this->lock->acquire();
    }

    public function testThrowsExceptionWhenFlockFailsOnRelease()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to release lock.');

        $this->lock->acquire();
        MockValues::$flockReturnValue = false;
        $this->lock->release();
    }

    public function testThrowsExceptionWhenFopenFails()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to open lock file.');

        MockValues::$fopenReturnValue = false;
        $this->lock->acquire();
    }
}
