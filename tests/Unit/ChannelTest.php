<?php

namespace Tests\Unit;

use App\Category;
use App\Channel;
use App\Copyright;
use App\Docs;
use App\Generator;
use App\Image;
use App\Item;
use App\Language;
use App\Link;
use App\Person;
use Tests\ModelTestCase;

class ChannelTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Channel(), [
            'title', 'description', 'last_build_date', 'publish_date'
        ]);
    }

    public function test_items_relation()
    {
        $model = new Channel();
        $relation = $model->items();
        $this->assertHasManyRelation($relation, $model, new Item());
    }

    public function test_image_relation()
    {
        $model = new Channel();
        $relation = $model->image();
        $this->assertHasOneRelation($relation, $model, new Image());
    }

    public function test_channels_relation()
    {
        $model = new Channel();
        $this->assertBelongsToRelation($model->category(), $model, new Category(), 'category_id');
        $this->assertBelongsToRelation($model->link(), $model, new Link(), 'link_id');
        $this->assertBelongsToRelation($model->docs(), $model, new Docs(), 'docs_id');
        $this->assertBelongsToRelation($model->language(), $model, new Language(), 'language_id');
        $this->assertBelongsToRelation($model->copyright(), $model, new Copyright(), 'copyright_id');
        $this->assertBelongsToRelation($model->generator(), $model, new Generator(), 'generator_id');
        $this->assertBelongsToRelation($model->editor(), $model, new Person(), 'managing_editor_id');
        $this->assertBelongsToRelation($model->webmaster(), $model, new Person(), 'web_master_id');
    }

    public function a_channel_can_be_created()
    {
        // Given
        // create a channel
        $channel = Channel::create([
            'title'     => 'FeedForAll Sample Feed',
            'description'    => 'RSS is a fascinating technology. The uses for RSS are expanding daily.',
        ]);

        // When
        // get a latest channel
        $latestChannel = Channel::latest()->first();

        // Then
        // assert equals: channel is equal latest channel
        $this->assertEquals($channel->id, $latestChannel->id);
        $this->assertEquals('FeedForAll Sample Feed', $latestChannel->title);
        $this->assertEquals('RSS is a fascinating technology. The uses for RSS are expanding daily.',
            $latestChannel->description);

        // see a channel in database
        $this->assertDatabaseHas('channels', [
            'title'  => 'FeedForAll Sample Feed',
            'description' => 'RSS is a fascinating technology. The uses for RSS are expanding daily.',
        ]);
    }

    public function a_channel_can_be_added_an_item()
    {
        // Given
        // create a channel
        $channel = Channel::latest()->first();

        // add item to channel
        $items = factory(Item::class, 2)->create();
        $channel->addItem($items);

        // see an item in channel
        $this->assertTrue($channel->hasItem($items));
    }
}
