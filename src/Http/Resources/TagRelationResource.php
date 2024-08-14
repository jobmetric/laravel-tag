<?php

namespace JobMetric\Tag\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JobMetric\Tag\Models\Tag;

/**
 * @property mixed tag_id
 * @property mixed taggable_id
 * @property mixed taggable_type
 * @property mixed collection
 * @property mixed created_at
 *
 * @property Tag tag
 * @property mixed taggable
 * @property mixed taggable_resource
 */
class TagRelationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tag_id' => $this->tag_id,
            'taggable_id' => $this->taggable_id,
            'taggable_type' => $this->taggable_type,
            'collection' => $this->collection,
            'created_at' => $this->created_at,

            'tag' => $this->whenLoaded('tag', function () {
                return new TagResource($this->tag);
            }),

            'taggable' => $this?->taggable_resource
        ];
    }
}
