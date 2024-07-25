<?php

namespace JobMetric\Tag\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JobMetric\Comment\Contracts\CommentContract;
use JobMetric\Comment\HasComment;
use JobMetric\Layout\Contracts\LayoutContract;
use JobMetric\Layout\HasLayout;
use JobMetric\Like\HasLike;
use JobMetric\Metadata\Contracts\MetaContract;
use JobMetric\Metadata\HasMeta;
use JobMetric\Metadata\Metaable;
use JobMetric\Star\HasStar;
use JobMetric\Translation\Contracts\TranslationContract;
use JobMetric\Translation\HasTranslation;
use JobMetric\Url\Urlable;

/**
 * @property mixed type
 * @property mixed ordering
 */
class Tag extends Model implements TranslationContract, MetaContract, CommentContract, LayoutContract
{
    use HasFactory,
        HasTranslation,
        HasMeta,
        Metaable,
        HasComment,
        HasLike,
        HasStar,
        HasLayout,
        Urlable;

    protected $fillable = [
        'type',
        'ordering'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => 'string',
        'ordering' => 'integer'
    ];

    public function getTable()
    {
        return config('tag.tables.tag', parent::getTable());
    }

    public function translationAllowFields(): array
    {
        return [
            'name'
        ];
    }

    /**
     * Check if a comment for a specific model needs to be approved.
     *
     * @return bool
     */
    public function needsCommentApproval(): bool
    {
        return true;
    }

    /**
     * Layout page type.
     *
     * @return string
     */
    public function layoutPageType(): string
    {
        return 'tag';
    }

    /**
     * Layout collection field.
     *
     * @return string|null
     */
    public function layoutCollectionField(): ?string
    {
        return null;
    }

    /**
     * scope type
     *
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type)->orderBy('ordering');
    }
}
