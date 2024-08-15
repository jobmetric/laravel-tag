<?php

namespace JobMetric\Tag\Exceptions;

use Exception;
use Throwable;

class TagNotFoundException extends Exception
{
    public function __construct(int $number, int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct(trans('tag::base.exceptions.tag_not_found', [
            'number' => $number,
        ]), $code, $previous);
    }
}
