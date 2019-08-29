<?php

namespace App\Console\Commands;

use App\Item;
use Illuminate\Console\Command;

class ListingGrabbed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listing:grabbed {--page=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The list of items which were grabbed by run console';

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
     */
    public function handle()
    {
        $headers = ['ID', 'Title'];

        $data = [];

        $page = $this->option('page');

        $items = Item::orderBy('id', 'desc')->paginate(8, ['*'], 'page', $page);

        foreach ($items as $item):
            array_push($data, [$item->id, $item->title]);
        endforeach;

        $this->table($headers, $data);

        $headers = ['Current page', 'Last page'];

        $data = [
            [
                $items->currentPage(),
                $items->lastPage(),
            ]
        ];

        $this->table($headers, $data);

        $this->line('Total items current page is: ' . $items->count());
        $this->line('Total items is: ' . $items->total());
        $this->line('The listing of grabbed items has been queried successfully!');
    }
}
