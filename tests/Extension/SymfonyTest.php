<?php

/*
 * This file is part of the phpunit-json-assertions package.
 *
 * (c) Enrico Stahn <enrico.stahn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EnricoStahn\JsonAssert\Tests\Extension;

use EnricoStahn\JsonAssert\Extension\Symfony;
use EnricoStahn\JsonAssert\Tests\Utils;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class SymfonyTest extends TestCase
{
    public function testAssertJsonMatchesSchema()
    {
        $schema = Utils::getSchemaPath('test.schema.json');
        $response = new Response(file_get_contents(Utils::getJsonPath('simple.json')));

        Symfony::assertJsonMatchesSchema($schema, $response);
    }

    public function testAssertJsonMatchesSchemaString()
    {
        $schema = file_get_contents(Utils::getSchemaPath('test.schema.json'));
        $response = new Response(file_get_contents(Utils::getJsonPath('simple.json')));

        Symfony::assertJsonMatchesSchemaString($schema, $response);
    }

    public function testAssertJsonValueEquals()
    {
        $response = new Response(file_get_contents(Utils::getJsonPath('simple.json')));

        Symfony::assertJsonValueEquals(123, 'foo', $response);
    }

    public function testAssertJsonResponse()
    {
        $response = new Response('{}', 200, ['Content-Type' => 'application/json']);

        Symfony::assertJsonResponse($response);
    }
}
