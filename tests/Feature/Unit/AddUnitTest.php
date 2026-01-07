<?php

declare(strict_types=1);

use App\Models\Unit;
use Symfony\Component\HttpFoundation\Response;

describe('Add Unit', function (): void {
    beforeEach(function (): void {
        $this->unitName = 'Test Unit';
        $this->unitSymbol = 'TU';
        $this->unitQuantity = 10;

        $this->validData = [
            'name' => $this->unitName,
            'symbol' => $this->unitSymbol,
            'quantity' => $this->unitQuantity,
        ];
    });

    describe('Authorization', function (): void {
        it('allows super admin to create a unit', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->postJson('/api/units', $this->validData);

            $response->assertStatus(Response::HTTP_CREATED);
            $response->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'symbol',
                ],
                'message',
            ]);

            // Verify the unit was created
            $unit = Unit::where('name', $this->unitName)->first();
            expect($unit)->not->toBeNull()
                ->and($unit->symbol)->toBe($this->unitSymbol)
                ->and($unit->quantity)->toBe($this->unitQuantity);
        });

        it('prevents unauthorized users from creating units', function (): void {
            $this->actingAs($this->salesRepUser, 'sanctum'); // Assuming sales rep doesn't have ADD_UNIT

            $response = $this->postJson('/api/units', $this->validData);

            $response->assertStatus(Response::HTTP_FORBIDDEN);
        });

        it('requires authentication', function (): void {
            $response = $this->postJson('/api/units', $this->validData);

            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        });
    });

    describe('Validation', function (): void {
        beforeEach(function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');
        });

        it('requires a name', function (): void {
            $data = $this->validData;
            unset($data['name']);

            $response = $this->postJson('/api/units', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['name']);
        });

        it('requires name to be at least 3 characters', function (): void {
            $data = array_merge($this->validData, ['name' => 'ab']);

            $response = $this->postJson('/api/units', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['name']);
        });

        it('requires name to be at most 30 characters', function (): void {
            $data = array_merge($this->validData, ['name' => str_repeat('a', 31)]);

            $response = $this->postJson('/api/units', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['name']);
        });

        it('requires a symbol', function (): void {
            $data = $this->validData;
            unset($data['symbol']);

            $response = $this->postJson('/api/units', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['symbol']);
        });

        it('requires symbol to be at least 2 characters', function (): void {
            $data = array_merge($this->validData, ['symbol' => 'a']);

            $response = $this->postJson('/api/units', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['symbol']);
        });

        it('requires symbol to be at most 5 characters', function (): void {
            $data = array_merge($this->validData, ['symbol' => 'abcdef']);

            $response = $this->postJson('/api/units', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['symbol']);
        });

        it('validates quantity is integer', function (): void {
            $data = array_merge($this->validData, ['quantity' => 'not-an-integer']);

            $response = $this->postJson('/api/units', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['quantity']);
        });

        it('validates quantity min value', function (): void {
            // Request says min:3
            $data = array_merge($this->validData, ['quantity' => 2]);

            $response = $this->postJson('/api/units', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['quantity']);
        });
    });
});
