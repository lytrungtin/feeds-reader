<?php

namespace App\Http\Controllers;

use App\Category;
use App\Channel;
use App\Comment;
use App\Item;
use App\Link;
use App\User;
use Exception;
use Illuminate\Http\Response;
use Validator, Input, Redirect, Session, View, Auth;


class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     *
     * @return Response
     */
    public function index()
    {
        $list = [
            'channels'  => Channel::pluck('title', 'id'),
            'categories'  => Category::pluck('content', 'id'),
            'links'  => Link::pluck('link', 'id'),
            'comments'  => Comment::pluck('comments', 'id'),
        ];

        // filter items by category name
        $items = Item::categoryId(Input::get('category_id'))->orderBy('id', 'desc')->paginate(8);

        // load the view and pass the items
        return View::make('items.index')
            ->with('items', $items)
            ->with('list', $list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $list = [
            'channels'  => Channel::pluck('title', 'id'),
            'categories'  => Category::pluck('content', 'id'),
            'links'  => Link::pluck('link', 'id'),
            'comments'  => Comment::pluck('comments', 'id'),
        ];

        // load the create form (app/views/items/create.blade.php)
        return View::make('items.create')
            ->with('list', $list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        // validate
        $rules = array(
            'title'      => 'required',
            'channel_id' => 'required',
        );
        $messages = [
            'channel_id.required' => 'Please select channel for new item!',
        ];
        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::to('items/create')
                ->withErrors($validator)
                ->withInput(Input::all());
        } else {
            // store
            $item = new Item(Input::all());
            $item->creator()->associate(User::find(Auth::id()));
            $item->channel()->associate(Channel::find(Input::get('channel_id')));
            $item->category()->associate(Category::find(Input::get('category_id')));
            $item->link()->associate(Link::find(Input::get('link_id')));
            $item->comments()->associate(Comment::find(Input::get('comments_id')));
            $item->save();

            // redirect
            Session::flash('message', 'Item with ID: ' . $item->id . ' has been created successfully!');
            return Redirect::to('items');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Item $item
     * @return Response
     */
    public function edit(Item $item)
    {
        $list = [
            'channels'  => Channel::pluck('title', 'id'),
            'categories'  => Category::pluck('content', 'id'),
            'links'  => Link::pluck('link', 'id'),
            'comments'  => Comment::pluck('comments', 'id'),
        ];

        // show the edit form and pass the item
        $item->publish_date = str_replace(" ", "T", $item->publish_date);
        return View::make('items.edit')
            ->with('item', $item)
            ->with('list', $list);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Item $item
     * @return Response
     */
    public function update(Item $item)
    {
        // validate
        $rules = array(
            'title' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('items/' . $item->id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::all());
        } else {
            // store
            $item->update(Input::all());
            $item->editor()->associate(User::find(Auth::id()));
            if (Input::get('channel_id')) {
                $item->channel()->associate(Channel::find(Input::get('channel_id')));
            }
            $item->category()->associate(Category::find(Input::get('category_id')));
            $item->link()->associate(Link::find(Input::get('link_id')));
            $item->comments()->associate(Comment::find(Input::get('comments_id')));
            $item->save();

            // redirect
            Session::flash('message', 'Item with ID: ' . $item->id . ' has been updated successfully!');
            return Redirect::to('items');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Item $item
     * @return Response
     * @throws Exception
     */
    public function destroy(Item $item)
    {
        // delete
        $item->delete();

        // redirect
        Session::flash('message', 'Item with Title: ' . $item->title . ' has been deleted successfully!');
        return Redirect::to('items');
    }
}
