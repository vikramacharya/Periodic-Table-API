
# Periodic Table API
This is a RESTful API for the Periodic Table of Elements.
## Syntax

The basic syntax is:
`https://example.com/pt.php?mode={modes}&elements={element symbols}`

Certain options can be added to the GET parameters as well.

The `modes` field contains comma-seperated mode values. If the `modes` field is omitted, an error will be returned.

The `elements` field contains comma-seperated element symbols. If the `elements` field is omitted, **all** elements will be used.

### Modes
|Valid Modes|Purpose|Options|Purpose|Values|
|-----------|-------|-------|-------|------|
|`names`|Get the full name of an element given it's symbol.|`spelling`|Changes the spelling of the element names.|`uk`/`sci`|
|`orbitals` or `electrons`|Get the electronic configuration of an element given it's symbol.|`showBlocks`|Show the electron blocks of the element.|`true`|
|`numbers`|Get the atomic number of an element given it's symbol.|`mass`|Show the atomic mass of the element.|`true`|
|`dankmemes`|¯\\\_(ツ)_/¯|N/A|N/A|N/A|

### Response
The JSON response has a top-level `error` field, and a top-level field for each mode you select.

Example:

```json
{
	"error": null,
	"names": {
		"Nh": "Nihonium"
	},
	"numbers": {
		"Nh": {
			"atomic": 113
		}
	}
}
```