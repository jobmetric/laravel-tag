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
        "restored" => "Tag restored successfully.",
        "permanently_deleted" => "Tag permanently deleted successfully.",
        "used_in" => "Tag used in ':count' places.",
        "attached" => "Tag attached successfully.",
        "detached" => "Tag detached successfully.",
    ],

    "exceptions" => [
        "model_tag_contract_not_found" => "Model ':model' not implements 'JobMetric\Tag\Contracts\TagContract' interface!",
        "tag_not_found" => "Tag ':number' not found!",
        "tag_type_used_in" => "Tag number ':tag_id' used in ':number' places!",
        "tag_collection_not_in_tag_allow_types" => "Tag collection ':collection' not in tag allow types!",
        "invalid_tag_type" => "Invalid tag type ':type'!",
    ],

];
