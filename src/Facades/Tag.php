<?php

namespace JobMetric\Tag\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Spatie\QueryBuilder\QueryBuilder query(array $filter = [], array $with = [], string $mode = null)
 * @method static \Illuminate\Http\Resources\Json\AnonymousResourceCollection paginate(array $filter = [], int $page_limit = 15, array $with = [], string $mode = null)
 * @method static \Illuminate\Http\Resources\Json\AnonymousResourceCollection all(array $filter = [], array $with = [], string $mode = null)
 * @method static array get(int $tag_id, array $with = [], string $locale = null)
 * @method static array store(array $data)
 * @method static array update(int $tag_id, array $data)
 * @method static array delete(int $tag_id)
 * @method static array restore(int $tag_id)
 * @method static array forceDelete(int $tag_id)
 * @method static array usedIn(int $tag_id)
 * @method static bool hasUsed(int $tag_id)
 *
 * @see \JobMetric\Tag\Tag
 */
class Tag extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \JobMetric\Tag\Tag::class;
    }
}
