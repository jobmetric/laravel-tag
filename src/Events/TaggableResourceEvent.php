<?php

namespace JobMetric\Tag\Events;

class TaggableResourceEvent
{
    /**
     * The taggable model instance.
     *
     * @var mixed
     */
    public mixed $taggable;

    /**
     * The resource to be filled by the listener.
     *
     * @var mixed|null
     */
    public mixed $resource;

    /**
     * Create a new event instance.
     *
     * @param mixed $taggable
     */
    public function __construct(mixed $taggable)
    {
        $this->taggable = $taggable;
        $this->resource = null;
    }
}
