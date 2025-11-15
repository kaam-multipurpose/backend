# DTO Standards

This document outlines the standards for Data Transfer Objects (DTOs) in the application.

## Directory Structure

- `app/Dtos/`: Main directory for all DTOs
  - `Abstract/`: Contains abstract base classes for DTOs
  - `Contract/`: Contains interfaces for DTOs
  - `Mail/`: Contains DTOs specific to mail functionality

## Base Classes

### AbstractDto

The `AbstractDto` class is the base class for all DTOs. It implements the `DtoContract` interface and provides common functionality:

- A default implementation of `toArray()` that uses reflection to convert all public properties to an array
- A static method `fromValidated()` to create a new instance from validated data

```php
abstract readonly class AbstractDto implements DtoContract
{
    public static function fromValidated(array $data): static
    {
        return new static(...$data);
    }

    public function toArray(): array
    {
        // Implementation that uses reflection to convert properties to array
    }
}
```

### AbstractPaginationDto

The `AbstractPaginationDto` class extends `AbstractDto` and provides pagination-specific functionality:

- Properties for `page` and `row`
- A constructor that initializes these properties from an array
- A static method `defaultKeys()` that returns the pagination keys
- Implementations of `fromValidated()` and `toArray()` specific to pagination

```php
abstract readonly class AbstractPaginationDto extends AbstractDto implements DtoContract
{
    public int $page;
    public int $row;

    public function __construct(?array $array = null)
    {
        $this->page = (int) ($array['page'] ?? 1);
        $this->row = (int) ($array['row'] ?? 5);
    }

    // Other methods...
}
```

## Creating New DTOs

### Regular DTOs

For regular DTOs, extend the `AbstractDto` class:

```php
final readonly class MyDto extends AbstractDto
{
    public function __construct(
        public string $property1,
        public int $property2,
        // ...
    ) {}

    // Override fromValidated() if needed
    // Override toArray() if needed
}
```

### Pagination DTOs

For pagination DTOs, extend the `AbstractPaginationDto` class:

```php
final readonly class GetPaginatedItemsDto extends AbstractPaginationDto
{
    public function __construct(
        ?array $defaultPaginationProps = null,
        // Additional properties if needed
    ) {
        parent::__construct($defaultPaginationProps);
        // Initialize additional properties if needed
    }

    // Override methods if needed
}
```

## Best Practices

1. Always use `readonly` classes for DTOs to ensure immutability
2. Use `final` for concrete DTO classes to prevent inheritance
3. Use public properties with type declarations
4. Add proper docblocks to all classes, methods, and properties
5. Override `fromValidated()` and `toArray()` only when necessary
6. Use named parameters in `fromValidated()` for better readability
7. Ensure return type declarations are compatible with parent classes