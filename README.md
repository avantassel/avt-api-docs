# API Docs

Docs are created based on the [endpoints.json](endpoints.json) file.

API Docs supports methods GET and POST

Intended use is for this to live in a docs directory: /[version]/docs

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