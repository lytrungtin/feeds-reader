<?php
namespace Tests\Unit;
use App\Item;
use Tests\TestCase;

class ListingGrabbedTest extends TestCase
{
    public function it_has_listing_grabbed_command()
    {
        $this->assertTrue(class_exists(\App\Console\Commands\ListingGrabbed::class));
    }

    public function test_valid_command()
    {
        factory(Item::class, 11)->create();
        $this->artisan('listing:grabbed')
            ->expectsOutput('Total items current page is: 8')
            ->expectsOutput('Total items is: 11')
            ->expectsOutput('The listing of grabbed items has been queried successfully!')
            ->assertExitCode(0);
    }

    public function test_page_valid()
    {
        factory(Item::class, 11)->create();
        $this->artisan('listing:grabbed --page=2')
            ->expectsOutput('Total items current page is: 3')
            ->expectsOutput('Total items is: 11')
            ->expectsOutput('The listing of grabbed items has been queried successfully!')
            ->assertExitCode(0);
    }

    public function test_page_empty()
    {
        factory(Item::class, 11)->create();
        $this->artisan('listing:grabbed --page=3')
            ->expectsOutput('Total items current page is: 0')
            ->expectsOutput('Total items is: 11')
            ->expectsOutput('The listing of grabbed items has been queried successfully!')
            ->assertExitCode(0);
    }
}
