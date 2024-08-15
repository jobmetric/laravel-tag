<?php

namespace JobMetric\Tag\Tests;

use App\Models\Product;
use JobMetric\Tag\Facades\Tag;
use JobMetric\Tag\Models\Tag as TagModels;
use Tests\BaseDatabaseTestCase as BaseTestCase;

class BaseTag extends BaseTestCase
{
    /**
     * create a fake product
     *
     * @return Product
     */
    public function create_product(): Product
    {
        return Product::factory()->create();
    }

    /**
     * create a fake tag
     *
     * @return TagModels
     */
    public function create_tag_for_has(): TagModels
    {
        Tag::store([
            'type' => 'product',
            'ordering' => 1,
            'status' => true,
            'translation' => [
                'name' => 'tag name',
                'description' => 'tag description',
                'meta_title' => 'tag meta title',
                'meta_description' => 'tag meta description',
                'meta_keywords' => 'tag meta keywords',
            ],
        ]);

        return TagModels::find(1);
    }

    /**
     * create a fake tag
     *
     * @return array
     */
    public function create_tag(): array
    {
        return Tag::store([
            'type' => 'product',
            'ordering' => 1,
            'status' => true,
            'translation' => [
                'name' => 'tag name',
                'description' => 'tag description',
                'meta_title' => 'tag meta title',
                'meta_description' => 'tag meta description',
                'meta_keywords' => 'tag meta keywords',
            ],
        ]);
    }
}
