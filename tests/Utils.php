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

class Utils
{
    /**
     * Returns the full path of the schema file.
     *
     * @param string $filename The filename of the schema file
     *
     * @return string
     */
    public static function getSchemaPath(string $filename): string
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, 'schemas', $filename]);
    }

    /**
     * Returns the full path of the schema file.
     *
     * @param string $filename The filename of the json file
     *
     * @return string
     */
    public static function getJsonPath(string $filename): string
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, 'json', $filename]);
    }
}
