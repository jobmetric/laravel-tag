<?php

namespace JobMetric\Tag\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JobMetric\Tag\Models\Tag;
use JobMetric\Translation\Rules\TranslationFieldExistRule;

class UpdateTagRequest extends FormRequest
{
    public int|null $tag_id = null;

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
        if (is_null($this->tag_id)) {
            $tag_id = $this->route()->parameter('tag')?->id;
        } else {
            $tag_id = $this->tag_id;
        }

        return [
            'ordering' => 'sometimes|numeric',
            'status' => 'sometimes|boolean|nullable',

            'translation' => 'sometimes|array',
            'translation.name' => [
                'sometimes',
                'string',
                new TranslationFieldExistRule(Tag::class, 'name', object_id: $tag_id),
            ],
            'translation.description' => 'sometimes|string|nullable',
            'translation.meta_title' => 'sometimes|string|nullable',
            'translation.meta_description' => 'sometimes|string|nullable',
            'translation.meta_keywords' => 'sometimes|string|nullable',
        ];
    }

    /**
     * Set tag id for validation
     *
     * @param int $tag_id
     * @return static
     */
    public function setTagId(int $tag_id): static
    {
        $this->tag_id = $tag_id;

        return $this;
    }
}
