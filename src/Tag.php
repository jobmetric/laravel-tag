<?php

namespace JobMetric\Tag;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JobMetric\Tag\Events\TagDeleteEvent;
use JobMetric\Tag\Events\TagForceDeleteEvent;
use JobMetric\Tag\Events\TagRestoreEvent;
use JobMetric\Tag\Events\TagStoreEvent;
use JobMetric\Tag\Events\TagUpdateEvent;
use JobMetric\Tag\Exceptions\TagNotFoundException;
use JobMetric\Tag\Exceptions\TagTypeUsedInException;
use JobMetric\Tag\Http\Requests\StoreTagRequest;
use JobMetric\Tag\Http\Requests\UpdateTagRequest;
use JobMetric\Tag\Http\Resources\TagRelationResource;
use JobMetric\Tag\Http\Resources\TagResource;
use JobMetric\Tag\Models\Tag as TagModel;
use JobMetric\Tag\Models\TagRelation;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class Tag
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Create a new Translation instance.
     *
     * @param Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the object tag.
     *
     * @param int $tag_id
     *
     * @return Builder|Model
     * @throws Throwable
     */
    public function getObject(int $tag_id): Builder|Model
    {
        $tag = TagModel::withTrashed()->where('id', $tag_id)->first();

        if (!$tag) {
            throw new TagNotFoundException($tag_id);
        }

        return $tag;
    }

    /**
     * Get the specified tag.
     *
     * @param array $filter
     * @param array $with
     * @param string|null $mode
     *
     * @return QueryBuilder
     */
    public function query(array $filter = [], array $with = [], string $mode = null): QueryBuilder
    {
        $fields = [
            'id',
            'type',
            'ordering',
            'status',
            'created_at',
            'updated_at'
        ];

        $query = QueryBuilder::for(TagModel::class);

        if ($mode === 'withTrashed') {
            $query->withTrashed();
        }

        if ($mode === 'onlyTrashed') {
            $query->onlyTrashed();
        }

        $query->allowedFields($fields)
            ->allowedSorts($fields)
            ->allowedFilters($fields)
            ->defaultSort('-id')
            ->where($filter);

        if (!empty($with)) {
            $query->with($with);
        }

        return $query;
    }

    /**
     * Paginate the specified tag.
     *
     * @param array $filter
     * @param int $page_limit
     * @param array $with
     * @param string|null $mode
     *
     * @return AnonymousResourceCollection
     */
    public function paginate(array $filter = [], int $page_limit = 15, array $with = [], string $mode = null): AnonymousResourceCollection
    {
        return TagResource::collection(
            $this->query($filter, $with, $mode)->paginate($page_limit)
        );
    }

    /**
     * Get all tags.
     *
     * @param array $filter
     * @param array $with
     * @param string|null $mode
     *
     * @return AnonymousResourceCollection
     */
    public function all(array $filter = [], array $with = [], string $mode = null): AnonymousResourceCollection
    {
        return TagResource::collection(
            $this->query($filter, $with, $mode)->get()
        );
    }

    /**
     * Get the specified tag.
     *
     * @param int $tag_id
     * @param array $with
     * @param string|null $locale
     *
     * @return array
     * @throws Throwable
     */
    public function get(int $tag_id, array $with = [], string $locale = null): array
    {
        $query = TagModel::withTrashed()
            ->where('id', $tag_id);

        if (!empty($with)) {
            $query->with($with);
        }

        if (!in_array('translations', $with)) {
            $query->with('translations');
        }

        $tag = $query->first();

        if (!$tag) {
            throw new TagNotFoundException($tag_id);
        }

        global $translationLocale;
        if (!is_null($locale)) {
            $translationLocale = $locale;
        }

        return [
            'ok' => true,
            'message' => trans('tag::base.messages.found'),
            'data' => TagResource::make($tag),
            'status' => 200
        ];
    }

    /**
     * Store the specified tag.
     *
     * @param array $data
     *
     * @return array
     * @throws Throwable
     */
    public function store(array $data): array
    {
        $validator = Validator::make($data, (new StoreTagRequest)->rules());
        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return [
                'ok' => false,
                'message' => trans('package-core::base.validation.errors'),
                'errors' => $errors,
                'status' => 422
            ];
        } else {
            $data = $validator->validated();
        }

        return DB::transaction(function () use ($data) {
            $tag = new TagModel;
            $tag->type = $data['type'];
            $tag->ordering = $data['ordering'] ?? 0;
            $tag->status = $data['status'] ?? true;
            $tag->save();

            $tag->translate(app()->getLocale(), [
                'name' => $data['translation']['name'],
                'description' => $data['translation']['description'] ?? null,
                'meta_title' => $data['translation']['meta_title'] ?? null,
                'meta_description' => $data['translation']['meta_description'] ?? null,
                'meta_keywords' => $data['translation']['meta_keywords'] ?? null,
            ]);

            event(new TagStoreEvent($tag, $data));

            return [
                'ok' => true,
                'message' => trans('tag::base.messages.created'),
                'data' => TagResource::make($tag),
                'status' => 201
            ];
        });
    }

    /**
     * Update the specified tag.
     *
     * @param int $tag_id
     * @param array $data
     *
     * @return array
     * @throws Throwable
     */
    public function update(int $tag_id, array $data): array
    {
        $validator = Validator::make($data, (new UpdateTagRequest)->setTagId($tag_id)->rules());
        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return [
                'ok' => false,
                'message' => trans('package-core::base.validation.errors'),
                'errors' => $errors,
                'status' => 422
            ];
        } else {
            $data = $validator->validated();
        }

        return DB::transaction(function () use ($tag_id, $data) {
            /**
             * @var TagModel $tag
             */
            $tag = TagModel::find($tag_id);

            if (!$tag) {
                throw new TagNotFoundException($tag_id);
            }

            if (array_key_exists('ordering', $data)) {
                $tag->ordering = $data['ordering'];
            }

            if (array_key_exists('status', $data)) {
                $tag->status = $data['status'];
            }

            if (array_key_exists('translation', $data)) {
                $trnas = [];
                if (array_key_exists('name', $data['translation'])) {
                    $trnas['name'] = $data['translation']['name'];
                }

                if (array_key_exists('description', $data['translation'])) {
                    $trnas['description'] = $data['translation']['description'];
                }

                if (array_key_exists('meta_title', $data['translation'])) {
                    $trnas['meta_title'] = $data['translation']['meta_title'];
                }

                if (array_key_exists('meta_description', $data['translation'])) {
                    $trnas['meta_description'] = $data['translation']['meta_description'];
                }

                if (array_key_exists('meta_keywords', $data['translation'])) {
                    $trnas['meta_keywords'] = $data['translation']['meta_keywords'];
                }

                $tag->translate(app()->getLocale(), $trnas);
            }

            $tag->save();

            event(new TagUpdateEvent($tag, $data));

            return [
                'ok' => true,
                'message' => trans('tag::base.messages.updated'),
                'data' => TagResource::make($tag),
                'status' => 200
            ];
        });
    }

    /**
     * Delete the specified tag.
     *
     * @param int $tag_id
     *
     * @return array
     * @throws Throwable
     */
    public function delete(int $tag_id): array
    {
        return DB::transaction(function () use ($tag_id) {
            /**
             * @var TagModel $tag
             */
            $tag = TagModel::find($tag_id);

            if (!$tag) {
                throw new TagNotFoundException($tag_id);
            }

            $check_used = $this->hasUsed($tag_id);

            if ($check_used) {
                $count = TagRelation::query()->where([
                    'tag_id' => $tag_id
                ])->count();

                throw new TagTypeUsedInException($tag_id, $count);
            }

            event(new TagDeleteEvent($tag));

            $data = TagResource::make($tag);

            $tag->translations()->delete();

            $tag->delete();

            return [
                'ok' => true,
                'data' => $data,
                'message' => trans('tag::base.messages.deleted'),
                'status' => 200
            ];
        });
    }

    /**
     * Restore the specified tag.
     *
     * @param int $tag_id
     *
     * @return array
     */
    public function restore(int $tag_id): array
    {
        return DB::transaction(function () use ($tag_id) {
            /**
             * @var TagModel $tag
             */
            $tag = TagModel::onlyTrashed()->where('id', $tag_id)->first();

            if (!$tag) {
                throw new TagNotFoundException($tag_id);
            }

            event(new TagRestoreEvent($tag));

            $data = TagResource::make($tag);

            $tag->restore();

            return [
                'ok' => true,
                'data' => $data,
                'message' => trans('tag::base.messages.restored'),
                'status' => 200
            ];
        });
    }

    /**
     * Force delete the specified tag.
     *
     * @param int $tag_id
     *
     * @return array
     */
    public function forceDelete(int $tag_id): array
    {
        return DB::transaction(function () use ($tag_id) {
            /**
             * @var TagModel $tag
             */
            $tag = TagModel::onlyTrashed()->where('id', $tag_id)->first();

            if (!$tag) {
                throw new TagNotFoundException($tag_id);
            }

            event(new TagForceDeleteEvent($tag));

            $data = TagResource::make($tag);

            $tag->forceDelete();

            return [
                'ok' => true,
                'data' => $data,
                'message' => trans('tag::base.messages.permanently_deleted'),
                'status' => 200
            ];
        });
    }

    /**
     * Used In tag
     *
     * @param int $tag_id
     *
     * @return array
     * @throws Throwable
     */
    public function usedIn(int $tag_id): array
    {
        /**
         * @var TagModel $tag
         */
        $tag = TagModel::find($tag_id);

        if (!$tag) {
            throw new TagNotFoundException($tag_id);
        }

        $tag_relations = TagRelation::query()->where([
            'tag_id' => $tag_id
        ])->get();

        return [
            'ok' => true,
            'message' => trans('tag::base.messages.used_in', [
                'count' => $tag_relations->count()
            ]),
            'data' => TagRelationResource::collection($tag_relations),
            'status' => 200
        ];
    }

    /**
     * Has Used tag
     *
     * @param int $tag_id
     *
     * @return bool
     * @throws Throwable
     */
    public function hasUsed(int $tag_id): bool
    {
        /**
         * @var TagModel $tag
         */
        $tag = TagModel::find($tag_id);

        if (!$tag) {
            throw new TagNotFoundException($tag_id);
        }

        return TagRelation::query()->where([
            'tag_id' => $tag_id
        ])->exists();
    }
}
