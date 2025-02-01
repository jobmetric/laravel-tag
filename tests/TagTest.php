<?php

namespace JobMetric\Tag\Tests;

use JobMetric\Tag\Exceptions\TagNotFoundException;
use JobMetric\Tag\Exceptions\TagTypeUsedInException;
use JobMetric\Tag\Facades\Tag;
use JobMetric\Tag\Http\Resources\TagRelationResource;
use JobMetric\Tag\Http\Resources\TagResource;
use Throwable;

class TagTest extends BaseTag
{
    /**
     * @throws Throwable
     */
    public function test_store()
    {
        // store tag
        $tag = $this->create_tag();

        $this->assertIsArray($tag);
        $this->assertTrue($tag['ok']);
        $this->assertEquals($tag['message'], trans('tag::base.messages.created'));
        $this->assertInstanceOf(TagResource::class, $tag['data']);
        $this->assertEquals(201, $tag['status']);

        $this->assertDatabaseHas('tags', [
            'type' => 'product',
            'ordering' => 1,
            'status' => true,
        ]);

        $this->assertDatabaseHas('translations', [
            'translatable_type' => 'JobMetric\Tag\Models\Tag',
            'translatable_id' => $tag['data']->id,
            'locale' => app()->getLocale(),
            'key' => 'name',
            'value' => 'tag name',
        ]);

        // store duplicate name
        $tag = $this->create_tag();

        $this->assertIsArray($tag);
        $this->assertFalse($tag['ok']);
        $this->assertEquals($tag['message'], trans('package-core::base.validation.errors'));
        $this->assertEquals(422, $tag['status']);
    }

    /**
     * @throws Throwable
     */
    public function test_update()
    {
        // tag not found
        try {
            $tag = Tag::update(1000, [
                'ordering' => 1000,
                'status' => true,
                'translation' => [
                    'name' => 'tag name',
                    'description' => 'tag description',
                    'meta_title' => 'tag meta title',
                    'meta_description' => 'tag meta description',
                    'meta_keywords' => 'tag meta keywords',
                ],
            ]);

            $this->assertIsArray($tag);
        } catch (Throwable $e) {
            $this->assertInstanceOf(TagNotFoundException::class, $e);
        }

        // store a tag
        $tagStore = $this->create_tag();

        // update with another name
        $tag = Tag::update($tagStore['data']->id, [
            'status' => true,
            'translation' => [
                'name' => 'tag name 2',
                'description' => 'tag description',
                'meta_title' => 'tag meta title',
                'meta_description' => 'tag meta description',
                'meta_keywords' => 'tag meta keywords',
            ],
        ]);

        $this->assertIsArray($tag);
        $this->assertTrue($tag['ok']);
        $this->assertEquals($tag['message'], trans('tag::base.messages.updated'));
        $this->assertInstanceOf(TagResource::class, $tag['data']);
        $this->assertEquals(200, $tag['status']);

        $this->assertDatabaseHas('tags', [
            'id' => $tag['data']->id,
            'type' => 'product',
            'ordering' => 1,
            'status' => true,
        ]);

        $this->assertDatabaseHas('translations', [
            'translatable_type' => 'JobMetric\Tag\Models\Tag',
            'translatable_id' => $tag['data']->id,
            'locale' => app()->getLocale(),
            'key' => 'name',
            'value' => 'tag name 2',
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_get()
    {
        // store a tag
        $tagStore = $this->create_tag();

        // get the tag
        $tag = Tag::get($tagStore['data']->id);

        $this->assertIsArray($tag);
        $this->assertTrue($tag['ok']);
        $this->assertEquals($tag['message'], trans('tag::base.messages.found'));
        $this->assertInstanceOf(TagResource::class, $tag['data']);
        $this->assertEquals(200, $tag['status']);

        $this->assertEquals($tag['data']->id, $tagStore['data']->id);
        $this->assertEquals('product', $tag['data']->type);
        $this->assertEquals(1, $tag['data']->ordering);
        $this->assertTrue($tag['data']->status);

        // get the tag with a wrong id
        try {
            $tag = Tag::get(1000);

            $this->assertIsArray($tag);
        } catch (Throwable $e) {
            $this->assertInstanceOf(TagNotFoundException::class, $e);
        }
    }

    /**
     * @throws Throwable
     */
    public function test_delete()
    {
        // store tag
        $tagStore = $this->create_tag();

        // delete the tag
        $tag = Tag::delete($tagStore['data']->id);

        $this->assertIsArray($tag);
        $this->assertTrue($tag['ok']);
        $this->assertEquals($tag['message'], trans('tag::base.messages.deleted'));
        $this->assertEquals(200, $tag['status']);

        $this->assertSoftDeleted('tags', [
            'id' => $tagStore['data']->id,
        ]);

        // delete the tag again
        try {
            $tag = Tag::delete($tagStore['data']->id);

            $this->assertIsArray($tag);
        } catch (Throwable $e) {
            $this->assertInstanceOf(TagNotFoundException::class, $e);
        }

        // attach the tag to the product
        $product = $this->create_product();

        // Store tag
        $tagStore = $this->create_tag();

        $product->attachTag($tagStore['data']->id, 'product_tag');

        // delete the tag
        try {
            $tag = Tag::delete($tagStore['data']->id);

            $this->assertIsArray($tag);
        } catch (Throwable $e) {
            $this->assertInstanceOf(TagTypeUsedInException::class, $e);
        }
    }

    /**
     * @throws Throwable
     */
    public function test_restore()
    {
        // store tag
        $tagStore = $this->create_tag();

        // delete the tag
        Tag::delete($tagStore['data']->id);

        // restore the tag
        $tag = Tag::restore($tagStore['data']->id);

        $this->assertIsArray($tag);
        $this->assertTrue($tag['ok']);
        $this->assertEquals($tag['message'], trans('tag::base.messages.restored'));
        $this->assertEquals(200, $tag['status']);

        $this->assertDatabaseHas('tags', [
            'id' => $tagStore['data']->id,
        ]);

        // restore the tag again
        try {
            $tag = Tag::restore($tagStore['data']->id);

            $this->assertIsArray($tag);
        } catch (Throwable $e) {
            $this->assertInstanceOf(TagNotFoundException::class, $e);
        }
    }

    /**
     * @throws Throwable
     */
    public function test_force_delete()
    {
        // store tag
        $tagStore = $this->create_tag();

        // delete the tag
        Tag::delete($tagStore['data']->id);

        // force delete tag
        $tag = Tag::forceDelete($tagStore['data']->id);

        $this->assertIsArray($tag);
        $this->assertTrue($tag['ok']);
        $this->assertEquals($tag['message'], trans('tag::base.messages.permanently_deleted'));
        $this->assertEquals(200, $tag['status']);

        $this->assertDatabaseMissing('tags', [
            'id' => $tagStore['data']->id,
        ]);

        // force delete tag again
        try {
            $tag = Tag::forceDelete($tagStore['data']->id);

            $this->assertIsArray($tag);
        } catch (Throwable $e) {
            $this->assertInstanceOf(TagNotFoundException::class, $e);
        }
    }

    /**
     * @throws Throwable
     */
    public function test_all()
    {
        // Store a tag
        $this->create_tag();

        // Get the tags
        $getTags = Tag::all();

        $this->assertCount(1, $getTags);

        $getTags->each(function ($tag) {
            $this->assertInstanceOf(TagResource::class, $tag);
        });
    }

    /**
     * @throws Throwable
     */
    public function test_pagination()
    {
        // Store a tag
        $this->create_tag();

        // Paginate the tags
        $paginateTags = Tag::paginate();

        $this->assertCount(1, $paginateTags);

        $paginateTags->each(function ($tag) {
            $this->assertInstanceOf(TagResource::class, $tag);
        });

        $this->assertIsInt($paginateTags->total());
        $this->assertIsInt($paginateTags->perPage());
        $this->assertIsInt($paginateTags->currentPage());
        $this->assertIsInt($paginateTags->lastPage());
        $this->assertIsArray($paginateTags->items());
    }

    /**
     * @throws Throwable
     */
    public function test_used_in()
    {
        $product = $this->create_product();

        // Store a tag
        $tagStore = $this->create_tag();

        // Attach the tag to the product
        $attachTag = $product->attachTag($tagStore['data']->id, 'product_tag');

        $this->assertIsArray($attachTag);
        $this->assertTrue($attachTag['ok']);
        $this->assertEquals($attachTag['message'], trans('tag::base.messages.attached'));
        $this->assertInstanceOf(TagResource::class, $attachTag['data']);
        $this->assertEquals(200, $attachTag['status']);

        // Get the tag used in the product
        $usedIn = Tag::usedIn($tagStore['data']->id);

        $this->assertIsArray($usedIn);
        $this->assertTrue($usedIn['ok']);
        $this->assertEquals($usedIn['message'], trans('tag::base.messages.used_in', [
            'count' => 1
        ]));
        $usedIn['data']->each(function ($dataUsedIn) {
            $this->assertInstanceOf(TagRelationResource::class, $dataUsedIn);
        });
        $this->assertEquals(200, $usedIn['status']);

        // Get the tag used in the product with a wrong tag id
        try {
            $usedIn = Tag::usedIn(1000);

            $this->assertIsArray($usedIn);
        } catch (Throwable $e) {
            $this->assertInstanceOf(TagNotFoundException::class, $e);
        }
    }

    /**
     * @throws Throwable
     */
    public function test_has_used()
    {
        $product = $this->create_product();

        // Store a tag
        $tagStore = $this->create_tag();

        // Attach the tag to the product
        $attachTag = $product->attachTag($tagStore['data']->id, 'product_tag');

        $this->assertIsArray($attachTag);
        $this->assertTrue($attachTag['ok']);
        $this->assertEquals($attachTag['message'], trans('tag::base.messages.attached'));
        $this->assertInstanceOf(TagResource::class, $attachTag['data']);
        $this->assertEquals(200, $attachTag['status']);

        // check has used in
        $usedIn = Tag::hasUsed($tagStore['data']->id);

        $this->assertTrue($usedIn);

        // check with wrong tag id
        try {
            $usedIn = Tag::hasUsed(1000);

            $this->assertIsArray($usedIn);
        } catch (Throwable $e) {
            $this->assertInstanceOf(TagNotFoundException::class, $e);
        }
    }
}
