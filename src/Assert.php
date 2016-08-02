<?php

/*
 * This file is part of the phpunit-json-assertions package.
 *
 * (c) Enrico Stahn <enrico.stahn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EnricoStahn\JsonAssert;

use JsonSchema\RefResolver;
use JsonSchema\Uri\UriResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;

/**
 * Asserts to validate JSON data.
 *
 * - All assert methods expect deserialised JSON data (an actual object or array)
 *   since the deserialisation method should be up to the user.
 * - We provide a convenience method to transfer whatever into a JSON object (see ::getJsonObject(mixed))
 */
trait Assert
{
    /**
     * Asserts that json content is valid according to the provided schema file.
     *
     * Example:
     *
     *   static::assertJsonMatchesSchema('./schema.json', json_decode('{"foo":1}'))
     *
     * @param string       $schema  Path to the schema file
     * @param array|object $content JSON array or object
     */
    public static function assertJsonMatchesSchema($schema, $content)
    {
        // Assume references are relative to the current file
        // Create an issue or pull request if you need more complex use cases
        $refResolver = new RefResolver(new UriRetriever(), new UriResolver());
        $schemaObj = $refResolver->resolve('file://'.realpath($schema));

        $validator = new Validator();
        $validator->check($content, $schemaObj);

        $message = '- Property: %s, Contraint: %s, Message: %s';
        $messages = array_map(function ($exception) use ($message) {
            return sprintf($message, $exception['property'], $exception['constraint'], $exception['message']);
        }, $validator->getErrors());
        $messages[] = '- Response: '.json_encode($content);

        \PHPUnit_Framework_Assert::assertTrue($validator->isValid(), implode("\n", $messages));
    }

    /**
     * Asserts that json content is valid according to the provided schema string.
     *
     * @param string       $schema  Schema data
     * @param array|object $content JSON content
     */
    public static function assertJsonMatchesSchemaString($schema, $content)
    {
        $file = tempnam(sys_get_temp_dir(), 'json-schema-');
        file_put_contents($file, $schema);

        self::assertJsonMatchesSchema($file, $content);
    }

    /**
     * Asserts if the value retrieved with the expression equals the expected value.
     *
     * Example:
     *
     *     static::assertJsonValueEquals(33, 'foo.bar[0]', $json);
     *
     * @param mixed        $expected   Expected value
     * @param string       $expression Expression to retrieve the result
     *                                 (e.g. locations[?state == 'WA'].name | sort(@))
     * @param array|object $json       JSON Content
     */
    public static function assertJsonValueEquals($expected, $expression, $json)
    {
        $result = \JmesPath\Env::search($expression, $json);

        \PHPUnit_Framework_Assert::assertEquals($expected, $result);
        \PHPUnit_Framework_Assert::assertInternalType(gettype($expected), $result);
    }

    /**
     * Helper method to deserialise a JSON string into an object.
     *
     * @param mixed $data The JSON string
     *
     * @return array|object
     */
    public static function getJsonObject($data)
    {
        return (is_array($data) || is_object($data)) ? $data : json_decode($data);
    }
}
