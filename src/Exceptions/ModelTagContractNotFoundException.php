<?php

namespace JobMetric\Tag\Exceptions;

use Exception;
use Throwable;

class ModelTagContractNotFoundException extends Exception
{
    public function __construct(string $model, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('unit::base.exceptions.model_tag_contract_not_found', [
            'model' => $model
        ]), $code, $previous);
    }
}
