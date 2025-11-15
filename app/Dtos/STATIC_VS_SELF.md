# Understanding `static` vs `self` Return Types in PHP

This document explains the difference between using `static` and `self` as return type declarations in PHP, particularly in the context of our DTO classes.

## The Issue

In our codebase, we have:

1. The `DtoContract` interface defines:
   ```php
   public static function fromValidated(array $data): self;
   ```

2. But the implementations in `AbstractDto` and concrete DTOs use:
   ```php
   public static function fromValidated(array $data): static
   ```

## What's the Difference?

### `self` Return Type

- `self` refers to the class in which the method is defined
- It's a fixed reference to the current class
- When used in a parent class, it always refers to that parent class, even when the method is called on a child class

### `static` Return Type (Late Static Binding)

- `static` refers to the class on which the method was called (the "late static binding" class)
- It's a dynamic reference that changes based on the calling context
- When used in a parent class, it refers to the child class when the method is called on a child class

## Why `static` is Better Than `self` for Factory Methods

In our DTO hierarchy:

```
DtoContract (interface)
    ↑
AbstractDto (abstract class)
    ↑
ConcreteDto (final class)
```

When we call `ConcreteDto::fromValidated()`, we want it to return a `ConcreteDto` instance, not an `AbstractDto` instance.

### With `self`:

```php
// In AbstractDto
public static function fromValidated(array $data): self
{
    return new self(...$data); // Always creates an AbstractDto
}
```

If a child class doesn't override this method, calling `ConcreteDto::fromValidated()` would try to instantiate `AbstractDto`, which is abstract and can't be instantiated.

### With `static`:

```php
// In AbstractDto
public static function fromValidated(array $data): static
{
    return new static(...$data); // Creates an instance of the calling class
}
```

When called as `ConcreteDto::fromValidated()`, this creates a `ConcreteDto` instance, which is what we want.

## The Contract vs. Implementation Discrepancy

The interface uses `self` while the implementations use `static`. This is technically a violation of the Liskov Substitution Principle, as the return type in the implementation should be compatible with the return type in the interface.

However, PHP allows this because `static` is considered compatible with `self` in this context (covariant return types).

## Recommendation

We should update the `DtoContract` interface to use `static` instead of `self` to make it consistent with the implementations:

```php
public static function fromValidated(array $data): static;
```

This would make our code more consistent and better reflect our intention that factory methods should return instances of the calling class, not the class where the method is defined.