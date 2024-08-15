<?php

namespace JobMetric\Tag;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use JobMetric\Tag\Exceptions\InvalidTagTypeException;
use JobMetric\Tag\Exceptions\ModelTagContractNotFoundException;
use JobMetric\Tag\Exceptions\TagCollectionNotInTagAllowTypesException;
use JobMetric\Tag\Exceptions\TagNotFoundException;
use JobMetric\Tag\Http\Resources\TagResource;
use JobMetric\Tag\Models\Tag;
use JobMetric\Tag\Models\TagRelation;
use Throwable;

/**
 * Trait HasTag
 *
 * @package JobMetric\Tag
 *
 * @property Tag[] tags
 *
 * @method morphToMany(string $class, string $string, string $string1)
 */
trait HasTag
{
    /**
     * boot has tag
     *
     * @return void
     * @throws Throwable
     */
    public static function bootHasTag(): void
    {
        if (!in_array('JobMetric\Tag\Contracts\TagContract', class_implements(self::class))) {
            throw new ModelTagContractNotFoundException(self::class);
        }
    }

    /**
     * tag has many relationships
     *
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', config('tag.tables.tag_relation'))
            ->withPivot('collection')
            ->withTimestamps(['created_at']);
    }

    /**
     * attach tag
     *
     * @param int $tag_id
     * @param string $collection
     *
     * @return array
     * @throws Throwable
     */
    public function attachTag(int $tag_id, string $collection): array
    {
        /**
         * @var Tag $tag
         */
        $tag = Tag::find($tag_id);

        if (!$tag) {
            throw new TagNotFoundException($tag_id);
        }

        $tagAllowTypes = $this->tagAllowTypes();

        if (!array_key_exists($collection, $tagAllowTypes)) {
            throw new TagCollectionNotInTagAllowTypesException($collection);
        }

        if ($tag->type !== $tagAllowTypes[$collection]['type']) {
            throw new InvalidTagTypeException($tag->type);
        }

        TagRelation::query()->updateOrInsert([
            'taggable_id' => $this->id,
            'taggable_type' => get_class($this),
            'collection' => $collection
        ], [
            'tag_id' => $tag_id
        ]);

        return [
            'ok' => true,
            'message' => trans('tag::base.messages.attached'),
            'data' => TagResource::make($tag),
            'status' => 200
        ];
    }

    /**
     * detach tag
     *
     * @param int $tag_id
     *
     * @return array
     * @throws Throwable
     */
    public function detachTag(int $tag_id): array
    {
        foreach ($this->tags as $tag) {
            if ($tag->id == $tag_id) {
                $data = TagResource::make($tag);

                $this->tags()->detach($tag_id);

                return [
                    'ok' => true,
                    'message' => trans('tag::base.messages.detached'),
                    'data' => $data,
                    'status' => 200
                ];
            }
        }

        throw new TagNotFoundException($tag_id);
    }

    /**
     * Get tag by collection
     *
     * @param string $collection
     *
     * @return MorphToMany
     */
    public function getTagByCollection(string $collection): MorphToMany
    {
        return $this->tags()->wherePivot('collection', $collection);
    }
}
