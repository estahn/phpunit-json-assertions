# phpunit-json-assertions

[![Latest Stable Version](https://poser.pugx.org/estahn/phpunit-json-assertions/version.png)](https://packagist.org/packages/estahn/phpunit-json-assertions)
[![Total Downloads](https://poser.pugx.org/estahn/phpunit-json-assertions/d/total.png)](https://packagist.org/packages/estahn/phpunit-json-assertions)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/581c093b-833a-49c2-a05c-d99aaf8f39c2/mini.png)](https://insight.sensiolabs.com/projects/581c093b-833a-49c2-a05c-d99aaf8f39c2)
[![CircleCI](https://circleci.com/gh/estahn/phpunit-json-assertions.svg?style=svg)](https://circleci.com/gh/estahn/phpunit-json-assertions)
[![StyleCI](https://styleci.io/repos/53177096/shield)](https://styleci.io/repos/53177096)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/estahn/phpunit-json-assertions.svg)](http://isitmaintained.com/project/estahn/phpunit-json-assertions "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/estahn/phpunit-json-assertions.svg)](http://isitmaintained.com/project/estahn/phpunit-json-assertions "Percentage of issues still open")

JSON assertions for PHPUnit includes traits/methods to help validate your JSON data through various methods.

## Features

* Validate your JSON data via JSON Schema
    * describes your existing data format
    * clear, human- and machine-readable documentation
    * complete structural validation, useful for
        * automated testing
        * validating client-submitted data
    * See more details [here](http://json-schema.org/)
* Access JSON data through expressions (e.g. `foo.bar[3]`)
    * See more details [here](http://jmespath.org/examples.html)

## Install

    $ composer require estahn/phpunit-json-assertions --dev

or in your `composer.json`:

```json
{
    "require-dev": {
        "estahn/phpunit-json-assertions": "@stable"
    }
}
```

## Asserts

| Assert                        | Description                                                                  | Available in |
| ----------------------------- | ---------------------------------------------------------------------------- | ------------ |
| [assertJsonMatchesSchema](https://github.com/estahn/phpunit-json-assertions/wiki/assertJsonMatchesSchema)       | Asserts that json content is valid according to the provided schema file     | All |
| assertJsonMatchesSchemaString | Asserts that json content is valid according to the provided schema string   | All |
| assertJsonValueEquals         | Asserts if the value retrieved with the expression equals the expected value | All |
| assertJsonValueEquals         | Asserts if the value retrieved with the expression equals the expected value | All |
| assertJsonResponse            | Asserts that a response is successful and of type json | Symfony |

## Usage

You can either use the `trait` or `class` version.

### Trait

```php
<?php

namespace EnricoStahn\JsonAssert\Tests;

use EnricoStahn\JsonAssert\Assert as JsonAssert;

class MyTestCase extends \PHPUnit_Framework_TestCase
{
    use JsonAssert;

    public function testJsonDocumentIsValid()
    {
        // my-schema.json
        //
        // {
        //   "type" : "object",
        //   "properties" : {
        //     "foo" : {
        //       "type" : "integer"
        //     }
        //   },
        //   "required" : [ "foo" ]
        // }

        $json = json_decode('{"foo":1}');

        $this->assertJsonMatchesSchema($json, './my-schema.json');
        $this->assertJsonValueEquals(1, '* | [0]', $json);
    }
}
```

### Class

In case you don't want to use the `trait` you can use the provided class wich extends from `\PHPUnit_Framework_TestCase`.
You can either extend your test case or use the static methods like below.

```php
<?php

namespace EnricoStahn\JsonAssert\Tests;

use EnricoStahn\JsonAssert\AssertClass as JsonAssert;

class MyTestCase extends \PHPUnit_Framework_TestCase
{
    public function testJsonDocumentIsValid()
    {
        // my-schema.json
        //
        // {
        //   "type" : "object",
        //   "properties" : {
        //     "foo" : {
        //       "type" : "integer"
        //     }
        //   },
        //   "required" : [ "foo" ]
        // }

        $json = json_decode('{"foo":1}');

        JsonAssert::assertJsonMatchesSchema($json, './my-schema.json');
        JsonAssert::assertJsonValueEquals(1, '* | [0]', $json);
    }
}
```

## Schema storage

The [schema storage](https://github.com/justinrainbow/json-schema/blob/master/src/JsonSchema/SchemaStorage.php) of `justinrainbow/json-schema` allows to register schemas which will effectively override the actual schema location.

Example:
```json
{"$ref" : "https://iglu.foobar.com/myschema.json#/definitions/positiveInteger"}
```

The resolver will fetch the schema from this endpoint and match the JSON document against it. Using schema storage you're able to override this behaviour.

```php
$schemastorage->addSchema('https://iglu.foobar.com/myschema.json', (object)['type' => 'string']);
```

With this in place the resolver will take the schema that is already in place without downloading it again.

```php
<?php

namespace EnricoStahn\JsonAssert\Tests;

use EnricoStahn\JsonAssert\AssertClass as JsonAssert;

class MyTestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        self::$schemaStorage = new SchemaStorage();
        
        self::$schemaStorage->addSchema('<id>', obj);
        ...
    }
    
    public function testJsonDocumentIsValid()
    {
        // my-schema.json
        //
        // {
        //   "type" : "object",
        //   "properties" : {
        //     "foo" : {
        //       "type" : "integer"
        //     }
        //   },
        //   "required" : [ "foo" ]
        // }

        $json = json_decode('{"foo":1}');

        JsonAssert::assertJsonMatchesSchema($json, './my-schema.json');
        JsonAssert::assertJsonValueEquals(1, '* | [0]', $json);
    }
}
```

## Extensions

`phpunit-json-assertions` provides extensions for simpler handling in different use cases.

### Symfony HttpFoundation Component

The extension `EnricoStahn\JsonAssert\Extension\Symfony` allows to pass in the actual response object generated
by the symfony framework and takes care of the decoding part.

BEFORE:
```php
use EnricoStahn\JsonAssert\Assert as JsonAssert;

// ...

$content = $response->getContent();
$json = json_decode($content);
JsonAssert::assertJsonMatchesSchemaString('./my-schema.json', $json);
```

AFTER:
```php
use EnricoStahn\JsonAssert\Extension\Symfony as JsonAssert;

// ...

JsonAssert::assertJsonMatchesSchemaString('./my-schema.json', $response);
```

## Tests

To run the test suite, you need [composer](http://getcomposer.org).

    $ composer install
    $ bin/phpunit

## Badge Mania
[![Build Status](https://scrutinizer-ci.com/g/estahn/phpunit-json-assertions/badges/build.png?b=master)](https://scrutinizer-ci.com/g/estahn/phpunit-json-assertions/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/estahn/phpunit-json-assertions/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/estahn/phpunit-json-assertions/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/estahn/phpunit-json-assertions/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/estahn/phpunit-json-assertions/?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/0bbc8fdeb4044287bbce009adc07ca39)](https://www.codacy.com/app/estahn/phpunit-json-assertions)
[![Codacy Badge](https://api.codacy.com/project/badge/coverage/0bbc8fdeb4044287bbce009adc07ca39)](https://www.codacy.com/app/estahn/phpunit-json-assertions)

## Alternatives
* https://github.com/martin-helmich/phpunit-json-assert - Doesn't support JSON Schema and uses [JSONPath](https://github.com/FlowCommunications/JSONPath) instead of [jmespath.php](https://github.com/jmespath/jmespath.php)

## License

The phpunit-json-assertions library is licensed under the [MIT](LICENSE).
