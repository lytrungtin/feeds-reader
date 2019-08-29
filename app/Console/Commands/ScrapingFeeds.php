<?php

/*
 * This file is part of Submission for test base-php.
 *
 * (c) Tin Ly <trungtin.sotech@gmail.com>
 */

namespace App\Console\Commands;

use Log;
Use Exception;
use App\Category;
use App\Channel;
use App\Comment;
use App\Copyright;
use App\Domain;
use App\Generator;
use App\Item;
use App\Language;
use App\Link;
use App\Docs;
use App\Person;
use App\Image;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Console\Command;

/**
 * Command to run the scraping feed from URLs
 *
 * @author Tin Ly <trungtin.sotech@gmail.com>
 */
class ScrapingFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:scrap {urls}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scraping RSS feeds from URLs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $logFileName = env('LOG_COMMAND_FILE');
        $log = new Logger('scraping_feeds');
        $log->pushHandler(new StreamHandler(storage_path('logs/' . $logFileName)));

        $urls = explode(",", $this->argument('urls'));

        foreach ($urls as $url) {
            $result = @file_get_contents($url);
            if ($result === false) {
                $this->logWarn(
                    'URL: "'.  $url . '"" might be incorrect URL.',
                    $log
                );
                continue;
            }

            $xml = $this->xmlToArray($result);

            $xml_channel = $xml['channel'];

            $channel = $this->findOrCreateChannel($xml_channel, $log);

            $xml_image = $xml_channel['image'];

            $this->findOrCreateImage($xml_image, $channel, $log);

            $xml_items = $xml_channel['item'];

            foreach ($xml_items as $xml_item) {
                $this->createNewItem($xml_item, $channel, $log);
            }
        }
        $this->logInfo('The scraping has been executed successfully', $log);
    }


    /**
     * Create new image with relations and log.
     *
     * @param array $xml_image Params to create new image.
     * @param Channel $channel Channel for image associate to.
     * @param string $log Log file name.
     *
     * @return void $image new image has been created
     */
    protected function findOrCreateImage($xml_image, $channel, $log)
    {
        $image = Image::firstOrNew(
            ['title' => $xml_image['title'],
            'description' => $xml_image['description'],
            'url' => $xml_image['url'],
            'width' => $xml_image['width'],
            'height' => $xml_image['height']]
        );
        $image->link()->associate(
            Link::firstOrCreate(['link' => $xml_image['link']])
        );
        $image->channel()->associate($channel);
        $this->saveModel($image, 'Image', $log);
    }

    /**
     * Create new channel with relations and log.
     *
     * @param array  $xml_channel Params to create new channel.
     * @param string $log         Log file name.
     *
     * @return Channel $channel new channel has been created
     */
    protected function findOrCreateChannel($xml_channel, $log)
    {
        $channel = Channel::firstOrNew(
            [
                'title' => $xml_channel['title'],
                'description' => $xml_channel['description'],
                'last_build_date' => date(
                    "Y-m-d H:i:s",
                    strtotime($xml_channel['lastBuildDate'])
                ),
                'publish_date' => date(
                    "Y-m-d H:i:s",
                    strtotime($xml_channel['pubDate'])
                ),
            ]
        );

        if (isset($xml_channel['link']) !== false) {
            $channel->link()->associate(
                Link::firstOrCreate(['link' => $xml_channel['link']])
            );
        }
        if (isset($xml_channel['docs']) !== false) {
            $channel->docs()->associate(
                Docs::firstOrCreate(['docs' => $xml_channel['docs']])
            );
        }
        if (isset($xml_channel['language']) !== false) {
            $channel->language()->associate(
                Language::firstOrCreate(['code' => $xml_channel['language']])
            );
        }
        if (isset($xml_channel['webMaster']) !== false) {
            $channel->webmaster()->associate(
                Person::firstOrCreate(['email' => $xml_channel['webMaster']])
            );
        }
        if (isset($xml_channel['managingEditor']) !== false) {
            $channel->editor()->associate(
                Person::firstOrCreate(['email' => $xml_channel['managingEditor']])
            );
        }
        if (isset($xml_channel['managingEditor']) !== false) {
            $channel->copyright()->associate(
                Copyright::firstOrCreate(['copyright' => $xml_channel['copyright']])
            );
        }
        if (isset($xml_channel['generator']) !== false) {
            $channel->generator()->associate(
                Generator::firstOrCreate(['generator' => $xml_channel['generator']])
            );
        }
        if (isset($xml_channel['category']) !== false) {
            $channel->category()->associate(
                $this->firstOrCreateCategory($xml_channel['category'], $log)
            );
        }

        $this->saveModel($channel, 'Channel', $log);

        return $channel;
    }

    /**
     * Create new item with relations domain and log.
     *
     * @param array $xml_item Params to create new item.
     * @param Channel $channel Channel for item associate to.
     * @param string $log Log file name.
     *
     * @return void $item new item has been created
     */
    protected function createNewItem($xml_item, $channel, $log)
    {
        $item = new Item(
            ['title' => $xml_item['title'],
            'description' => $xml_item['description'],
            'publish_date' => date(
                "Y-m-d H:i:s",
                strtotime($xml_item['pubDate'])
            )
                ]
        );
        if (isset($xml_item['category']) !== false) {
            $item->category()->associate(
                $this->firstOrCreateCategory($xml_item['category'], $log)
            );
        }
        if (isset($xml_item['comments']) !== false) {
            $item->comments()->associate(
                Comment::firstOrCreate(['comments' => $xml_item['comments']])
            );
        }
        if (isset($xml_item['link']) !== false) {
            $item->link()->associate(
                Link::firstOrCreate(['link' => $xml_item['link']])
            );
        }
        $item->channel()->associate($channel);
        $this->saveModel($item, 'Item', $log);
    }

    /**
     * Create new category with associate domain and log.
     *
     * @param array  $category_attributes Attributes to create new category
     * @param string $log                 Log file name
     *
     * @return Category $category new category has been created
     */
    protected function firstOrCreateCategory($category_attributes, $log)
    {
        $category = Category::firstOrNew(
            ['content' => $category_attributes['@content']]
        );
        $domain = Domain::firstOrCreate(
            ['domain' => $category_attributes['@attributes']['domain']]
        );
        $category->domain()->associate($domain);
        $this->saveModel($category, 'Category', $log);
        return $category;
    }

    /**
     * Print and save warning message to log
     *
     * @param string $message This is string get from URL
     * @param string $log     Log file name
     *
     * @return void message to console
     */
    protected function logWarn($message, $log)
    {
        $log->warn($message);
        $this->warn($message);
    }

    /**
     * Print and save info message to log
     *
     * @param string $message This is string get from URL
     * @param string $log     Log file name
     *
     * @return void message to console
     */
    protected function logInfo($message, $log)
    {
        $log->info($message);
        $this->info($message);
    }

    /**
     * Print and save error message to log
     *
     * @param string $message This is string get from URL
     * @param string $log     Log file name
     *
     * @return void message to console
     */
    private function _logError($message, $log)
    {
        $log->error($message);
        $this->error($message);
    }


    /**
     * Convert from DOM to array
     *
     * @param Model  $object Model object
     * @param string $name   Model object
     * @param string $log    Log file name
     *
     * @return void output result after converting from xml string
     */
    protected function saveModel($object, $name, $log)
    {
        $success_msg = ' has been saved successfully';
        $failed_msg =' has not been saved successfully';
        try {
            $object->save();
            $this->logInfo($name . ' ID: ' . $object->id . $success_msg, $log);
        } catch (Exception $e) {
            $this->_logError($name.' ID: '. $object->title . $failed_msg, $log);
            $this->_logError($e->getMessage(), $log);
        }
    }

    /**
     * Convert from DOM to array
     *
     * @param xml string $xml_str This is string get from URL
     *
     * @return array output result after converting from xml string
     */
    protected function xmlToArray($xml_str)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml_str);
        $root = $doc->documentElement;
        $output = $this->domToArray($root);
        $output['@root'] = $root->tagName;
        return $output;
    }

    /**
     * Convert from DOM to array
     *
     * @param DOMDocument $node This is node load from XML String
     *
     * @return array output result after converting from DOM
     */
    protected function domToArray($node)
    {
        $output = array();
        switch ($node->nodeType) {
        case XML_CDATA_SECTION_NODE:
        case XML_TEXT_NODE:
            $output = trim($node->textContent);
            break;
        case XML_ELEMENT_NODE:
            for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                $child = $node->childNodes->item($i);
                $v = $this->domToArray($child);
                if (isset($child->tagName)) {
                    $t = $child->tagName;
                    if (!isset($output[$t])) {
                        $output[$t] = array();
                    }
                    $output[$t][] = $v;
                } elseif ($v || $v === '0') {
                    $output = (string) $v;
                }
            }
            if ($node->attributes->length && !is_array($output)) { //Has attributes but isn't an array
                $output = array('@content'=>$output); //Change output into an array.
            }
            if (is_array($output)) {
                if ($node->attributes->length) {
                    $a = array();
                    foreach ($node->attributes as $attrName => $attrNode) {
                        $a[$attrName] = (string) $attrNode->value;
                    }
                    $output['@attributes'] = $a;
                }
                foreach ($output as $t => $v) {
                    if (is_array($v) && count($v)==1 && $t!='@attributes') {
                        $output[$t] = $v[0];
                    }
                }
            }
            break;
        }
        return $output;
    }
}
