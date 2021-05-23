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
use JsonSchema\SchemaStorage;
use PHPUnit\Framework\TestCase;

class AssertTraitImpl extends TestCase
{
    use JsonAssert;

    public function setUp(): void
    {
        self::$schemaStorage = new SchemaStorage();
    }

    /**
     * @param string       $id
     * @param array|object $schema
     *
     * @return SchemaStorage
     */
    public function testWithSchemaStore(string $id, $schema): SchemaStorage
    {
        self::$schemaStorage->addSchema($id, $schema);

        return self::$schemaStorage;
    }
}
