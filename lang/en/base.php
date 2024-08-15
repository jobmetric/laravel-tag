<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base Tag Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during Tag for
    | various messages that we need to display to the user.
    |
    */

    "validation" => [
        "errors" => "Validation errors occurred."
    ],

    "messages" => [
        "found" => "Tag found.",
        "created" => "Tag created successfully.",
        "updated" => "Tag updated successfully.",
        "deleted" => "Tag deleted successfully.",
        "change_default_value" => "Default value changed successfully.",
        "used_in" => "Tag used in ':count' places.",
        "attached" => "Tag attached successfully.",
        "detached" => "Tag detached successfully.",
    ],

    "exceptions" => [
        "model_tag_contract_not_found" => "Model ':model' not implements 'JobMetric\Tag\Contracts\TagContract' interface!",
        "tag_not_found" => "Tag ':number' not found!",
    ],

    "tag_type" => [
        "product" => "Product",
        "post" => "Post"
    ],

];
