<?php

namespace JobMetric\Tag\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JobMetric\Location\Models\LocationCountry;
use JobMetric\Location\Rules\CheckExistNameRule;
use JobMetric\Tag\Models\Tag;
use JobMetric\Translation\Rules\TranslationFieldExistRule;
use JobMetric\Unit\Enums\UnitTypeEnum;
use JobMetric\Unit\Models\Unit;

class StoreTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string|in:' . implode(',', getTagType('key')),
            'ordering' => 'numeric|nullable',
            'status' => 'boolean|nullable',

            'translation' => 'required|array',
            'translation.name' => [
                'string',
                new TranslationFieldExistRule(Tag::class, 'name'),
            ],
            'translation.description' => 'string|nullable',
            'translation.meta_title' => 'string|nullable',
            'translation.meta_description' => 'string|nullable',
            'translation.meta_keywords' => 'string|nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'ordering' => 0,
            'status' => $this->status ?? true,
        ]);
    }
}
