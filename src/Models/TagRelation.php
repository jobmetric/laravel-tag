<?php

namespace JobMetric\Tag\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use JobMetric\Tag\Events\TaggableResourceEvent;

/**
 * @property mixed tag_id
 * @property mixed taggable_type
 * @property mixed taggable_id
 * @property mixed collection
 *
 * @property Tag tag
 * @property mixed taggable
 * @property mixed taggable_resource
 */
class TagRelation extends Pivot
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'tag_id',
        'taggable_type',
        'taggable_id',
        'collection'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tag_id' => 'integer',
        'taggable_type' => 'string',
        'taggable_id' => 'integer',
        'collection' => 'string'
    ];

    public function getTable()
    {
        return config('tag.tables.tag_relation', parent::getTable());
    }

    /**
     * tag relation
     *
     * @return BelongsTo
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * taggable relation
     *
     * @return MorphTo
     */
    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include categories of a given collection.
     *
     * @param Builder $query
     * @param string $collection
     *
     * @return Builder
     */
    public function scopeOfCollection(Builder $query, string $collection): Builder
    {
        return $query->where('collection', $collection);
    }

    /**
     * Get the taggable resource attribute.
     */
    public function getTaggableResourceAttribute()
    {
        $event = new TaggableResourceEvent($this->taggable);
        event($event);

        return $event->resource;
    }
}
