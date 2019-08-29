<?php

namespace Tests\Feature;

use App\Channel;
use App\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\WithStubUser;

class ItemsTest extends TestCase
{
    use DatabaseTransactions, WithStubUser;

    public function test_index_authentication()
    {
        $this->assertAuthenticationRequired('/items');
        $this->assertAuthenticationRequired('/items/create');
        $this->assertAuthenticationRequired('/items', 'post');
        $this->assertAuthenticationRequired('/items/1/edit');
        $this->assertAuthenticationRequired('/items/1', 'put');
        $this->assertAuthenticationRequired('/items/1', 'delete');
    }

    public function test_index_view()
    {
        $user = $this->createStubUser();
        $response = $this->actingAs($user)->get('/items');

        $response->assertStatus(200);
        $response->assertViewHas('items');
        $response->assertSee('<h1>All the Items</h1>');
        $response->assertSee('<option value="">Please select category</option>');
    }

    public function test_authenticated_user_can_create_new_item()
    {
        $this->actingAs($this->createStubUser());

        $this->get('/items/create')
            ->assertStatus(200)
            ->assertViewIs('items.create')
            ->assertViewHas('list', null);

        $channel = factory(Channel::class)->create();

        $this->post('/items', ['title' => 'Hanoi', 'channel_id' => $channel->id])
            ->assertRedirect('/items/')
            ->assertSessionHas('message',
                'Item with ID: ' . Item::all()->last()->id . ' has been created successfully!');
    }

    public function test_it_checks_for_invalid_item()
    {
        $this->actingAs($this->createStubUser());

        $channel = factory(Channel::class)->create();

        $this->post('/items', ['title' => '', 'channel_id' => $channel->id])
            ->assertRedirect('/items/create')
            ->assertSessionHasErrors('title', 'The title field is required.');

        $this->post('/items', ['title' => 'Hanoi'])
            ->assertRedirect('/items/create')
            ->assertSessionHasErrors('channel_id', 'Please select channel for new item!');
    }

    public function test_authenticated_user_can_edit_an_existing_item()
    {
        $item = $this->createItem();

        $this->get("/items/{$item->id}/edit")
            ->assertStatus(200)
            ->assertViewIs('items.edit')
            ->assertViewHas('list');

        $this->put("/items/{$item->id}", ['title' => 'London'])
            ->assertRedirect('/items')
            ->assertSessionHas('message',
                'Item with ID: ' . $item->id . ' has been updated successfully!');
    }

    public function test_authenticated_user_can_delete_an_existing_item()
    {
        $item = $this->createItem();

        $this->delete("/items/{$item->id}")
            ->assertRedirect('/items')
            ->assertSessionHas('message',
                'Item with Title: ' . $item->title . ' has been deleted successfully!');
    }

    private function createItem($authenticated = true)
    {
        $item = factory(Item::class)->create();

        if ($authenticated) {
            $this->actingAs($this->createStubUser());
        }

        return $item;
    }
}
