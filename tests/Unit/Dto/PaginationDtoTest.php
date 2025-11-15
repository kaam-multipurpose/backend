<?php

declare(strict_types=1);

use App\Dtos\GetPaginatedCategoriesDto;

test('default values are set when none provided', function (): void {
    $dto = new GetPaginatedCategoriesDto([]);
    expect($dto->page)->toBe(1)
        ->and($dto->row)->toBe(5);
});

test('custom values are respected', function (): void {
    $dto = new GetPaginatedCategoriesDto(['page' => 2, 'row' => 10]);
    expect($dto->page)->toBe(2)
        ->and($dto->row)->toBe(10);
});

test('from validated sets defaults', function (): void {
    $dto = GetPaginatedCategoriesDto::fromValidated([]);
    expect($dto->page)->toBe(1)
        ->and($dto->row)->toBe(5);
});

test('from validated sets custom values', function (): void {
    $dto = GetPaginatedCategoriesDto::fromValidated(['page' => 4, 'row' => 25]);
    expect($dto->page)->toBe(4)
        ->and($dto->row)->toBe(25);
});
