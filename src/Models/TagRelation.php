<?php

namespace JobMetric\Tag\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property mixed tag_id
 * @property mixed taggable_type
 * @property mixed taggable_id
 *
 * @property Tag tag
 * @property mixed taggable
 */
class TagRelation extends Pivot
{
    use HasFactory;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'tag_id',
        'taggable_type',
        'taggable_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tag_id' => 'integer',
        'taggable_type' => 'string',
        'taggable_id' => 'integer'
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
}
