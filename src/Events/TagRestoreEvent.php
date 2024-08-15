<?php

namespace JobMetric\Tag\Events;

use JobMetric\Tag\Models\Tag;

class TagRestoreEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly Tag $tag,
    )
    {
    }
}
