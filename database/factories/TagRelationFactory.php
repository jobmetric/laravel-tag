<?php

namespace JobMetric\Tag\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JobMetric\Tag\Models\TagRelation;

/**
 * @extends Factory<TagRelation>
 */
class TagRelationFactory extends Factory
{
    protected $model = TagRelation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tag_id' => null,
            'taggable_type' => null,
            'taggable_id' => null
        ];
    }

    /**
     * set tag_id
     *
     * @param int $tag_id
     *
     * @return static
     */
    public function setTagId(int $tag_id): static
    {
        return $this->state(fn(array $attributes) => [
            'tag_id' => $tag_id
        ]);
    }

    /**
     * set taggable
     *
     * @param string $taggable_type
     * @param int $taggable_id
     *
     * @return static
     */
    public function setTaggable(string $taggable_type, int $taggable_id): static
    {
        return $this->state(fn(array $attributes) => [
            'taggable_type' => $taggable_type,
            'taggable_id' => $taggable_id
        ]);
    }
}
