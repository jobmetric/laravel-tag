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
    ],

    "messages" => [
        "found" => "تگ یافت شد.",
        "created" => "تگ با موفقیت ایجاد شد.",
        "updated" => "تگ با موفقیت به‌روزرسانی شد.",
        "deleted" => "تگ با موفقیت حذف شد.",
        "restored" => "تگ با موفقیت بازیابی شد.",
        "permanently_deleted" => "تگ با موفقیت به طور دائمی حذف شد.",
        "used_in" => "تگ در ':count' مکان استفاده شده است.",
        "attached" => "تگ با موفقیت متصل شد.",
        "detached" => "تگ با موفقیت جدا شد.",
    ],

    "exceptions" => [
        "model_tag_contract_not_found" => "مدل ':model' اینترفیس 'JobMetric\Tag\Contracts\TagContract' را پیاده‌سازی نکرده است!",
        "tag_not_found" => "تگ ':number' یافت نشد!",
        "tag_type_used_in" => "تگ شماره ':tag_id' در ':number' مکان استفاده شده است!",
        "tag_collection_not_in_tag_allow_types" => "مجموعه تگ ':collection' در انواع مجاز تگ وجود ندارد!",
        "invalid_tag_type" => "نوع تگ ':type' نامعتبر است!",
    ],

];
