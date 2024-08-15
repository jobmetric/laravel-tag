<?php

namespace JobMetric\Tag\Events;

use JobMetric\Tag\Models\Tag;

class TagStoreEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly Tag   $tag,
        public readonly array $data
    )
    {
    }
}
