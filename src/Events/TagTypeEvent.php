<?php

namespace JobMetric\Tag\Events;

class TagTypeEvent
{
    /**
     * The tag type to be filled by the listener.
     *
     * @var array
     */
    public array $tagType = [];

    /**
     * Add a type.
     *
     * @param array $type Example: ['course' => 'base.tag_type.course']
     *
     * @return static
     */
    public function AddType(array $type): static
    {
        if (!in_array($type, $this->tagType)) {
            $this->tagType = array_merge($this->tagType, $type);
        }

        return $this;
    }
}
