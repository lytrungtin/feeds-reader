<!-- app/views/items/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>All the Items</h1>

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <div class="form-group">
        <form action="/items" method="GET">
            <select name="category_id" class="form-control" onchange="this.form.submit()">
                <option value="">Please select category</option>
                <option value="0" {{  isset($_GET["category_id"]) && $_GET["category_id"] == 0 ? 'selected' : '' }}>All</option>
                @foreach($list['categories'] as $category_id =>  $category_name)
                    <option value="{{ $category_id }}" {{  isset($_GET["category_id"]) && $_GET["category_id"] == $category_id ? 'selected' : '' }}>{{ $category_name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <td>ID</td>
            <td>Title</td>
            <td>Created by</td>
            <td>Updated by</td>
            <td>Actions</td>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $key => $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td>{{ $value->title }}</td>
                <td>{{ $value->creator['name'] }}</td>
                <td>{{ $value->editor['name'] }}</td>
                <!-- we will also add show, edit, and delete buttons -->
                <td>
                    <!-- delete the item (uses the destroy method DESTROY /items/{id} -->
                    <!-- we will add this later since its a little more complicated than the other two buttons -->
                    {{ Form::open(array('url' => 'items/' . $value->id, 'class' => 'pull-right',
                                        'style' => 'display: inline-block;')) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete this Item', array('class' => 'btn btn-warning')) }}
                    {{ Form::close() }}
                    <!-- edit this item (uses the edit method found at GET /items/{id}/edit -->
                    <a class="btn btn-small btn-info" href="{{ URL::to('items/' . $value->id . '/edit') }}">Edit this item</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! $items->render() !!}
@endsection
