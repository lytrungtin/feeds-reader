<?php

namespace Tests\Unit;

use App\Category;
use App\Channel;
use App\Comment;
use App\Item;
use App\Link;
use App\User;
use Tests\ModelTestCase;

class ItemTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Item(), [
            'title', 'description', 'publish_date',
        ]);
    }

    public function test_items_relation()
    {
        $model = new Item();
        $this->assertBelongsToRelation($model->channel(), $model, new Channel(), 'channel_id');
        $this->assertBelongsToRelation($model->category(), $model, new Category(), 'category_id');
        $this->assertBelongsToRelation($model->creator(), $model, new User(), 'created_by');
        $this->assertBelongsToRelation($model->editor(), $model, new User(), 'updated_by');
        $this->assertBelongsToRelation($model->link(), $model, new Link(), 'link_id');
        $this->assertBelongsToRelation($model->comments(), $model, new Comment(), 'comments_id');
    }

    public function an_item_can_be_created()
    {
        $channel = Channel::create([
            'title'     => 'FeedForAll Sample Feed',
            'description'    => 'RSS is a fascinating technology. The uses for RSS are expanding daily.',
        ]);

        // Given
        // create an item
        $item = new Item([
            'title'     => 'RSS Solutions for Restaurants',
            'description'    => "FeedForAll >helps Restaurant\'s communicate with customers.",
        ]);

        $item->channel()->associate($channel);
        $item->save();

        // When
        // get a latest item
        $latestItem = Item::latest()->first();

        // Then
        // assert equals: item is equal latest item
        $this->assertEquals($item->id, $latestItem->id);
        $this->assertEquals('RSS Solutions for Restaurants', $latestItem->title);
        $this->assertEquals("FeedForAll >helps Restaurant\'s communicate with customers.",
            $latestItem->description);
        $this->assertEquals($channel->id, $latestItem->channel_id);

        // see a channel in database
        $this->assertDatabaseHas('items', [
            'title'  => 'RSS Solutions for Restaurants',
            'description' => "FeedForAll >helps Restaurant\'s communicate with customers.",
            'channel_id' => $channel->id,
        ]);
    }
}
