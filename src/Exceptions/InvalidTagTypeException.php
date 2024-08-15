<?php

namespace JobMetric\Tag\Exceptions;

use Exception;
use Throwable;

class InvalidTagTypeException extends Exception
{
    public function __construct(string $type, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('tag::base.exceptions.invalid_tag_type', [
            'type' => $type
        ]), $code, $previous);
    }
}
