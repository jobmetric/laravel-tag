<?php

namespace JobMetric\Tag\Events;

class TagTypeEvent
{
    /**
     * The tay type to be filled by the listener.
     *
     * @var array
     */
    public array $tagType;

    /**
     * Create a new event instance.
     *
     * @param array $defaultTagType
     */
    public function __construct(array $defaultTagType)
    {
        $this->tagType = $defaultTagType;
    }

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
