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

use EnricoStahn\JsonAssert\Assert as JsonAssert;
use PHPUnit\Framework\TestCase;

class AssertTraitImpl extends TestCase
{
    use JsonAssert;
}
