<?php
namespace Tests\Unit;
use Tests\TestCase;

class ScrapingFeedsTest extends TestCase
{
    public function it_has_scraping_feeds_command()
    {
        $this->assertTrue(class_exists(\App\Console\Commands\ScrapingFeeds::class));
    }

    public function test_urls_valid()
    {
        $this->artisan('feed:scrap https://www.feedforall.com/sample.xml')
            ->expectsOutput('Category ID: 1 has been saved successfully')
            ->expectsOutput('Channel ID: 1 has been saved successfully')
            ->expectsOutput('Image ID: 1 has been saved successfully')
            ->expectsOutput('Item ID: 1 has been saved successfully')
            ->expectsOutput('The scraping has been executed successfully')
            ->assertExitCode(0);

        $this->assertDatabaseHas('categories',
            ['content' => 'Computers/Software/Internet/Site Management/Content Management']);
        $this->assertDatabaseHas('channels', ['title' => 'FeedForAll Sample Feed']);
        $this->assertDatabaseHas('images', ['url' => 'http://www.feedforall.com/ffalogo48x48.gif']);
        $this->assertDatabaseHas('items', ['title' => 'RSS Solutions for Restaurants']);
        $this->assertDatabaseHas('comments', ['comments' => 'http://www.feedforall.com/forum']);
        $this->assertDatabaseHas('copyrights', ['copyright' => 'Copyright 2004 NotePage, Inc.']);
        $this->assertDatabaseHas('docs', ['docs' => 'http://blogs.law.harvard.edu/tech/rss']);
        $this->assertDatabaseHas('domains', ['domain' => 'www.dmoz.com']);
        $this->assertDatabaseHas('generators', ['generator' => 'FeedForAll Beta1 (0.0.1.8)']);
        $this->assertDatabaseHas('items', ['title' => 'RSS Solutions for Law Enforcement']);
        $this->assertDatabaseHas('languages', ['code' => 'en-us']);
        $this->assertDatabaseHas('links', ['link' => 'http://www.feedforall.com/industry-solutions.htm']);
        $this->assertDatabaseHas('people', ['email' => 'webmaster@feedforall.com']);
    }

    public function test_multi_urls_valid()
    {
        $this
        ->artisan
        ('feed:scrap https://www.feedforall.com/sample.xml,https://www.feedforall.com/sample-feed.xml,https://www.feedforall.com/blog-feed.xml,http://www.rss-specifications.com/blog-feed.xml')
        ->expectsOutput('Category ID: 1 has been saved successfully')
        ->expectsOutput('Channel ID: 1 has been saved successfully')
        ->expectsOutput('Image ID: 1 has been saved successfully')
        ->expectsOutput('Item ID: 1 has been saved successfully')
        ->expectsOutput('Channel ID: 2 has been saved successfully')
        ->expectsOutput('Image ID: 2 has been saved successfully')
        ->expectsOutput('The scraping has been executed successfully')
        ->assertExitCode(0);
    }

    public function test_urls_invalid()
    {
        $this
        ->artisan('feed:scrap https://www.feedforall.com/sample.xmlxml')
        ->expectsOutput
        ('URL: "https://www.feedforall.com/sample.xmlxml"" might be incorrect URL.')
        ->assertExitCode(0);
    }
}
