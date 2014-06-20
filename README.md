# AVT Docs
AVT Docs is a drop in API doc explorer and documentation system.

[http://api.andrewvantassel.com/v1/docs](http://api.andrewvantassel.com/v1/docs)

## Setup
```bash
#install comoser
$ curl -sS https://getcomposer.org/installer | php

#if you want
$ sudo mv composer.phar /usr/bin/local/composer

#run composer install
$ composer install
```

Docs are created based on the [endpoints.json](endpoints.json) file.

Intended use is for this to live in a docs directory: /[version]/docs

## Tests
Tests use the env with attribute abbr:test and endpoints parameters attribute test:true

Tests are written with Behat [http://behat.org/](http://behat.org/)

Add specific data structure tests by editing [FeatureContext.php](tests/features/bootstrap/FeatureContext.php)

```bash
$ chmod +x tests/run.sh
$ tests/run.sh
```

## Config

### Endpoints
* name (use hyphen for directory, users-add will resolve to users/add)
* description
* method (GET or POST)
* perms_required (true or false)
* parameters (array)

### Parameters
Can have the following attributes:

* field
* desc
* value
* require (comma delimited list of fields whose values will be used in example)
* test (true or false)
