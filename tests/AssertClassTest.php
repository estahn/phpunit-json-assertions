<?php

/*
 * This file is part of the phpunit-json-assertions package.
 *
 * (c) Enrico Stahn <enrico.stahn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EnricoStahn\JsonAssert\Tests;

use EnricoStahn\JsonAssert\AssertClass;
use PHPUnit\Framework\TestCase;

class AssertClassTest extends TestCase
{
    public function testClassInstance()
    {
        static::assertInstanceOf('EnricoStahn\JsonAssert\AssertClass', new AssertClass());
    }
}
