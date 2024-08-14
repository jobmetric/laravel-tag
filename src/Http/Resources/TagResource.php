<?php

namespace JobMetric\Tag\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JobMetric\Tag\Models\TagRelation;
use JobMetric\Unit\Models\UnitRelation;

/**
 * @property mixed id
 * @property mixed type
 * @property mixed ordering
 * @property mixed status
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @property mixed translations
 * @property TagRelation[] tagRelations
 */
class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        global $translationLocale;

        return [
            'id' => $this->id,
            'type' => $this->type,
            'ordering' => $this->ordering,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'translations' => translationResourceData($this->translations, $translationLocale),

            'tagRelations' => $this->whenLoaded('tagRelations', function () {
                return TagRelationResource::collection($this->tagRelations);
            }),
        ];
    }
}
