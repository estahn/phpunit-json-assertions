# phpunit-json-assertions

[![Latest Stable Version](https://poser.pugx.org/estahn/phpunit-json-assertions/version.png)](https://packagist.org/packages/estahn/phpunit-json-assertions)
[![Total Downloads](https://poser.pugx.org/estahn/phpunit-json-assertions/d/total.png)](https://packagist.org/packages/estahn/phpunit-json-assertions)
[![Dependency Status](https://www.versioneye.com/user/projects/56e8f6404e714c0035e760f3/badge.svg?style=flat)](https://www.versioneye.com/user/projects/56e8f6404e714c0035e760f3)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/581c093b-833a-49c2-a05c-d99aaf8f39c2/mini.png)](https://insight.sensiolabs.com/projects/581c093b-833a-49c2-a05c-d99aaf8f39c2)
[![Build Status](https://travis-ci.org/estahn/phpunit-json-assertions.png?branch=master)](https://travis-ci.org/estahn/phpunit-json-assertions)
[![StyleCI](https://styleci.io/repos/53177096/shield)](https://styleci.io/repos/53177096)

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
use PHPUnit\Framework\TestCase;

class MyTestCase extends TestCase
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
        
        $this->assertJsonMatchesSchema('./my-schema.json', $json);
        $this->assertJsonValueEquals(1, '* | [0]', $json);
    }
}
```

### Class

In case you don't want to use the `trait` you can use the provided class wich extends from `PHPUnit\Framework\TestCase`.
You can either extend your test case or use the static methods like below.

```php
<?php

namespace EnricoStahn\JsonAssert\Tests;

use EnricoStahn\JsonAssert\AssertClass as JsonAssert;
use PHPUnit\Framework\TestCase;

class MyTestCase extends TestCase
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
        
        JsonAssert::assertJsonMatchesSchema('./my-schema.json', $json);
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
