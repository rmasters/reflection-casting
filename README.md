# Reflection casting

An experiment with combining PHPdoc comments and type-hinting.

At the moment, it allows for strict type checking for public properties and
methods. It could provide type casting for scalar values.

## Usage

> You probably shouldn't use this, it'll be slow.

Wrapping an object through CastWrapper proxies attribute-setters and method
calling through checks that inspect the doc-comment of the method or property.

```php
use Rmasters\ReflectionCast\CastWrapper;

class User {
    private $id;

    /** @var bool */
    public $enabled;

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }
}

$user = new CastWrapper(new User);
$user->setId(123);  // okay
$user->setId("42"); // throws InvalidArgumentException

$user->enabled = false; // okay
$user->enabled = "okay"; // throws InvalidArgumentException
```

## Todo

*   Make a better interface (traits?)
*   Test non-scalar hints, arrays (e.g. `stdClass[]`)
*   Support constructors
*   Cache doc-comments rather than parsing each time

## License

[MIT](LICENSE)
