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

use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

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
     * @var SchemaStorage
     */
    private static $schemaStorage = null;

    /**
     * Asserts that json content is valid according to the provided schema file.
     *
     * Example:
     *
     *   static::assertJsonMatchesSchema(json_decode('{"foo":1}'), './schema.json')
     *
     * @param string|null  $schema  Path to the schema file
     * @param array|object $content JSON array or object
     */
    public static function assertJsonMatchesSchema($content, $schema = null)
    {
        if (self::$schemaStorage === null) {
            self::$schemaStorage = new SchemaStorage();
        }

        if ($schema !== null && !file_exists($schema)) {
            throw new FileNotFoundException($schema);
        }

        $schemaObject = null;

        if ($schema !== null) {
            $schemaObject = json_decode(file_get_contents($schema));
            self::$schemaStorage->addSchema( 'file://' . $schema, $schemaObject);
        }

        $validator = new Validator(new Factory(self::$schemaStorage));
        $validator->validate($content, $schemaObject);

        $message = '- Property: %s, Contraint: %s, Message: %s';
        $messages = array_map(function ($exception) use ($message) {
            return sprintf($message, $exception['property'], $exception['constraint'], $exception['message']);
        }, $validator->getErrors());
        $messages[] = '- Response: '.json_encode($content);

        \PHPUnit\Framework\Assert::assertTrue($validator->isValid(), implode("\n", $messages));
    }

    /**
     * Asserts that json content is valid according to the provided schema file.
     *
     * Example:
     *
     *   static::assertJsonMatchesSchema(json_decode('{"foo":1}'), './schema.json')
     *
     * @param string|null  $schema  Path to the schema file
     * @param array|object $content JSON array or object
     * @deprecated This will be removed in the next major version (4.x).
     */
    public static function assertJsonMatchesSchemaDepr($schema, $content)
    {
        self::assertJsonMatchesSchema($content, $schema);
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

        self::assertJsonMatchesSchema($content, $file);
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

        \PHPUnit\Framework\Assert::assertEquals($expected, $result);
        \PHPUnit\Framework\Assert::assertInternalType(strtolower(gettype($expected)), $result);
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
