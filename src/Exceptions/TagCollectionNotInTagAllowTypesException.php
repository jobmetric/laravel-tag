<?php

namespace JobMetric\Tag\Exceptions;

use Exception;
use Throwable;

class TagCollectionNotInTagAllowTypesException extends Exception
{
    public function __construct(string $collection, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('tag::base.exceptions.tag_collection_not_in_tag_allow_types', [
            'collection' => $collection,
        ]), $code, $previous);
    }
}
