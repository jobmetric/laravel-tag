<?php

namespace JobMetric\Tag\Events;

class TagAllowMemberCollectionEvent
{
    /**
     * The tay allow the member collection to be filled by the listener.
     *
     * @var array
     */
    public array $allowMemberCollection = [];

    /**
     * Create a new event instance.
     *
     * @param array $defaultTagAllowMemberCollection
     */
    public function __construct(array $defaultTagAllowMemberCollection = [])
    {
        $this->allowMemberCollection = $defaultTagAllowMemberCollection;
    }

    /**
     * Add an allowed member collection.
     *
     * @param array $allowMemberCollection Example: ['members' => 'multiple'] or ['owner' => 'single']
     *
     * @return static
     */
    public function AddAllowMemberCollection(array $allowMemberCollection): static
    {
        if (!in_array($allowMemberCollection, $this->allowMemberCollection)) {
            $this->allowMemberCollection = array_merge($this->allowMemberCollection, $allowMemberCollection);
        }

        return $this;
    }
}
