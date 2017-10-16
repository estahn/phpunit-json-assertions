<?php

/*
 * This file is part of the phpunit-json-assertions package.
 *
 * (c) Enrico Stahn <enrico.stahn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EnricoStahn\JsonAssert\Extension;

use EnricoStahn\JsonAssert\Assert;
use Symfony\Component\HttpFoundation\Response;

trait Symfony
{
    /**
     * Asserts that json content is valid according to the provided schema file.
     *
     * Example:
     *
     *   static::assertJsonMatchesSchema(json_decode('{"foo":1}'), './schema.json')
     *
     * @param string   $schema   Path to the schema file
     * @param Response $response JSON array or object
     */
    public static function assertJsonMatchesSchema($schema, Response $response)
    {
        Assert::assertJsonMatchesSchema($schema, json_decode($response->getContent()));
    }

    /**
     * Asserts that json content is valid according to the provided schema string.
     *
     * @param string   $schema   Schema data
     * @param Response $response JSON content
     */
    public static function assertJsonMatchesSchemaString($schema, Response $response)
    {
        Assert::assertJsonMatchesSchemaString($schema, json_decode($response->getContent()));
    }

    /**
     * Asserts if the value retrieved with the expression equals the expected value.
     *
     * Example:
     *
     *     static::assertJsonValueEquals(33, 'foo.bar[0]', $json);
     *
     * @param mixed    $expected   Expected value
     * @param string   $expression Expression to retrieve the result
     *                             (e.g. locations[?state == 'WA'].name | sort(@))
     * @param Response $response   JSON Content
     */
    public static function assertJsonValueEquals($expected, $expression, $response)
    {
        Assert::assertJsonValueEquals($expected, $expression, json_decode($response->getContent()));
    }

    /**
     * Asserts that a response is successful and of type json.
     *
     * @param Response $response   Response object
     * @param int      $statusCode Expected status code (default 200)
     *
     * @see \Bazinga\Bundle\RestExtraBundle\Test\WebTestCase::assertJsonResponse()
     */
    public static function assertJsonResponse(Response $response, $statusCode = 200)
    {
        \PHPUnit\Framework\Assert::assertEquals(
            $statusCode,
            $response->getStatusCode(),
            $response->getContent()
        );
        \PHPUnit\Framework\Assert::assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }
}
