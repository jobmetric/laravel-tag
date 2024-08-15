<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    |
    | Table name in database
    */

    "tables" => [
        'tag' => 'tags',
        'tag_relation' => 'tag_relations'
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Image Size
    |--------------------------------------------------------------------------
    |
    | Default image size for media
    */

    "default_image_size" => [
        'width' => env('TAG_DEFAULT_IMAGE_SIZE_WIDTH', 100),
        'height' => env('TAG_DEFAULT_IMAGE_SIZE_HEIGHT', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Tag Type
    |--------------------------------------------------------------------------
    |
    | Default tag type
    */

    "default_tag_type" => [
        'product' => 'tag::base.tag_type.product',
        'post' => 'tag::base.tag_type.post',
    ],

];
