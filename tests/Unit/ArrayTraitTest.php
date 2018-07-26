<?php
/**
 * Copyright 2016 Google Inc.
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

namespace Google\Cloud\Core\Tests\Unit;

use Google\Cloud\Core\ArrayTrait;
use Google\Cloud\Core\Testing\TestHelpers;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * @group core
 */
class ArrayTraitTest extends TestCase
{
    private $impl;

    public function setUp()
    {
        $this->impl = TestHelpers::impl(ArrayTrait::class);
    }

    public function testPluck()
    {
        $value = '123';
        $key = 'key';
        $array = [$key => $value];
        $actualValue = $this->impl->call('pluck', [$key, &$array]);

        $this->assertEquals($value, $actualValue);
        $this->assertEquals([], $array);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPluckThrowsExceptionWithInvalidKey()
    {
        $array = [];
        $this->impl->call('pluck', ['not_here', &$array]);
    }

    public function testPluckArray()
    {
        $keys = ['key1', 'key2'];
        $array = [
            'key1' => 'test',
            'key2' => 'test'
        ];
        $expectedArray = $array;

        $actualValues = $this->impl->call('pluckArray', [$keys, &$array]);

        $this->assertEquals($expectedArray, $actualValues);
        $this->assertEquals([], $array);
    }

    public function testIsAssocTrue()
    {
        $actual = $this->impl->call('isAssoc', [[
            'test' => 1,
            'test' => 2
        ]]);

        $this->assertTrue($actual);
    }

    public function testIsAssocFalse()
    {
        $actual = $this->impl->call('isAssoc', [[1, 2, 3]]);

        $this->assertFalse($actual);
    }

    public function testArrayFilterRemoveNull()
    {
        $input = [
            'null' => null,
            'false' => false,
            'zero' => 0,
            'float' => 0.0,
            'empty' => '',
            'array' => [],
        ];

        $res = $this->impl->call('arrayFilterRemoveNull', [$input]);
        $this->assertArrayNotHasKey('null', $res);
        $this->assertArrayHasKey('false', $res);
        $this->assertArrayHasKey('zero', $res);
        $this->assertArrayHasKey('float', $res);
        $this->assertArrayHasKey('empty', $res);
        $this->assertArrayHasKey('array', $res);
    }
}
