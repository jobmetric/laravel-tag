<?php

namespace JobMetric\Tag\Contracts;

interface TagContract
{
    /**
     * tag allows the type.
     *
     * @return array
     */
    public function tagAllowTypes(): array;
}
