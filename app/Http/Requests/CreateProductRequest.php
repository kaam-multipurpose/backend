<?php

namespace App\Http\Requests;

use App\Enum\PermissionsEnum;
use App\Enum\ProductVariantsTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can(PermissionsEnum::ADD_PRODUCT->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge([
            "name" => ["required", "string"],
            "category_id" => ["required", "integer", "exists:categories,id"],
            "has_variants" => ["required", "boolean"],
            "variant_type" => ["required_if:has_variants,true", "prohibited_if:has_variants,false", Rule::enum(ProductVariantsTypeEnum::class)],
            "cost_price" => ["required_if:has_variants,false", "prohibited_if:has_variants,true", "numeric"],
        ], $this->productUnitRules(), $this->variantRules());
    }

    public function productUnitRules(): array
    {
        return [
            "units" => ["required", "array", "min:1"],
            "units.*" => ["required", "array", "min:4", "max:6"],
            "units.*.unit_id" => ["required", "integer", "exists:units,id"],
            "units.*.conversion_rate" => ["required", "integer"],
            "units.*.percentage" => ["required", "integer"],
            "units.*.is_base" => ["boolean"],
            "units.*.is_max" => ["boolean"],
        ];
    }

    public function variantRules(): array
    {
        return [
            "variants" => ["required_if:has_variants,true", "array", "min:2"],
            "variants.*" => ["required_if:has_variants,true", "array"],
            "variants.*.name" => ["required_if:has_variants,true", "string"],
            "variants.*.cost_price" => ["required_if:has_variants,true", "numeric"],
        ];
    }

    public function messages(): array
    {
        return [
            // Core product fields
            'name.required' => 'Please provide a name for the product.',
            'category_id.required' => 'Product category is required.',
            'category_id.integer' => 'Category must be a valid numeric ID.',
            'category_id.exists' => 'Selected category does not exist in the system.',
            'has_variants.required' => 'Please specify if the product has variants.',
            'has_variants.boolean' => 'The variants flag must be true or false.',
            'variant_type.required_if' => 'Please select a variant type when variants are enabled.',
            'variant_type.prohibited_if' => 'You should not provide a variant type when the product has no variants.',
            'cost_price.required_if' => 'Cost price is required when the product has no variants.',
            'cost_price.prohibited_if' => 'Cost price should not be set when the product includes variants.',
            'cost_price.numeric' => 'Cost price must be a number.',

            // Product units
            'units.required' => 'Please provide at least one product unit.',
            'units.array' => 'Units must be submitted as an array.',
            'units.*.required' => 'Each unit must contain necessary details.',
            'units.*.array' => 'Each unit must be structured as an array.',
            'units.*.name.required' => 'Unit name is required.',
            'units.*.name.enum' => 'Unit name must be one of the predefined types.',
            'units.*.conversion_rate.required' => 'Please specify the conversion rate for each unit.',
            'units.*.conversion_rate.integer' => 'Conversion rate must be a whole number.',
            'units.*.percentage.required' => 'Percentage markup is required for each unit.',
            'units.*.percentage.integer' => 'Percentage must be a whole number.',
            'units.*.is_base.boolean' => 'The base unit indicator must be true or false.',
            'units.*.is_max.boolean' => 'The max unit indicator must be true or false.',

            // Product variants
            'variants.required_if' => 'Variants are required when "has_variants" is true.',
            'variants.array' => 'Variants must be submitted as an array.',
            'variants.*.required_with' => 'Each variant must contain the required details.',
            'variants.*.name.required_if' => 'Please provide a name for each variant.',
            'variants.*.name.string' => 'Variant name must be a text value.',
            'variants.*.cost_price.required_if' => 'Please provide cost price for each variant.',
            'variants.*.cost_price.numeric' => 'Cost price for variants must be numeric.',
        ];
    }
}
