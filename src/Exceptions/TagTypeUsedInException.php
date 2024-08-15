<?php

namespace JobMetric\Tag\Exceptions;

use Exception;
use Throwable;

class TagTypeUsedInException extends Exception
{
    public function __construct(int $tag_id, int $number, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('tag::base.exceptions.tag_type_used_in', [
            'tag_id' => $tag_id,
            'number' => $number,
        ]), $code, $previous);
    }
}
