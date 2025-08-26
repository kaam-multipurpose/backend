<?php

use App\Dto\GetPaginatedCategoriesDto;

test('default values are set when none provided', function () {
    $dto = new GetPaginatedCategoriesDto([]);
    expect($dto->page)->toBe(1)
        ->and($dto->row)->toBe(5);
});

test('custom values are respected', function () {
    $dto = new GetPaginatedCategoriesDto(['page' => 2, 'row' => 10]);
    expect($dto->page)->toBe(2)
        ->and($dto->row)->toBe(10);
});

test('from validated sets defaults', function () {
    $dto = GetPaginatedCategoriesDto::fromValidated([]);
    expect($dto->page)->toBe(1)
        ->and($dto->row)->toBe(5);
});

test('from validated sets custom values', function () {
    $dto = GetPaginatedCategoriesDto::fromValidated(['page' => 4, 'row' => 25]);
    expect($dto->page)->toBe(4)
        ->and($dto->row)->toBe(25);
});

test('search is included in output', function () {
    $dto = GetPaginatedCategoriesDto::fromValidated(['search' => 'notebooks']);
    expect($dto->search)->toBe('notebooks');

    $expected = [
        'search' => 'notebooks',
        'page' => 1,
        'row' => 5,
    ];

    expect($dto->toArray())->toBe($expected);
});
