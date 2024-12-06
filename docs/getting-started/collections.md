# Collections

Collections are like an array, but with builtin functions. They implement the `ArrayAccess` and `Iterator` interfaces, which means it's possible to use a collection like you would use an array.

The following methods are builtin:

## get
Get any value from the collection. Return an optional `$default` value if the `$key` does not exist.

```php 
$collection->get($key, $default = null)
```

## set

Set any value in the collection.

```php 
$collection->set($key, $value)
```

## has

Check if the given `$key` exists.

```php 
$collection->has($key)
```

## all

Returns all data.

```php 
$collection->all(): iterable
```

## take

Returns entries up to the given limit.

```php 
$collection->take($limit): iterable
```

## push

Appends the given item to the collection.

```php 
$collection->push($item): CollectionInterface
```

## count

Well.. what do you think this does?

```php 
$collection->count()
```

## is (not) empty

```php 
$collection->isEmpty(): bool

$collection->isNotEmpty(): bool
```

## first/last/nth

```php 
$collection->first()

$collection->last()
```

Returns the nth item or the `$default` value if it does not exist.

```php 
$collection->nth(int $index, $default)
```

## keys

```php 
$collection->keys(): CollectionInterface
```

## filter

```php 
$collection->filter(Closure $predicate): CollectionInterface
```

Filters the collection elements using a callback function. Returns a new collection.

## map

```php 
$collection->map(Closure $callback): CollectionInterface
```

Applies the callback to the collection elements. Returns a new collection.

## mapWithKeys

```php 
$collection->mapWithKeys(Closure $callback): CollectionInterface
```

Applies the callback to the collection elements. Injects the collection keys as second argument to the callback. Returns a new collection.

## flatten

```php 
$collection->flatten(Closure $callback, $initial = null)
```

## flattenAndAssign

```php 
$collection->flattenAndAssign(Closure $callback, $initial = null)
```


## groupBy

```php 
$collection->groupBy(Closure $callback): CollectionInterface
```

Group the collection by any value. For example group a collection of Zaken by their Zaaktype identificatie:

## sorting

```php 
$collection->sort(?Closure $callback = null, bool $reverse = false)

$collection->asort(?Closure $callback = null, bool $reverse = false)

$collection->ksort(?Closure $callback = null, bool $reverse = false)

$collection->sortByAttribute(string $attribute, bool $reverse = false)
```
