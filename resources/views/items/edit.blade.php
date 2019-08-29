<!-- app/views/items/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Edit {{ $item->title }}</h1>

    <!-- if there are creation errors, they will show here -->
    {{ HTML::ul($errors->all()) }}

    {{ Form::model($item, array('route' => array('items.update', $item->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('title', 'Title') }}
        {{ Form::text('title', null,
            array('class' => 'form-control ' . ( $errors->has('title') ? 'is-invalid' : '' ))) }}
        @error('title')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        {{ Form::label('description', 'Description') }}
        {{ Form::textarea('description', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('channel_id', 'Channel') }}
        {{  Form::select('channel_id', $list['channels'], null, ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('category_id', 'Category') }}
        {{  Form::select('category_id', $list['categories'], null,
            ['placeholder' => 'Please Select', 'class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('link_id', 'Link') }}
        {{  Form::select('link_id', $list['links'], null,
            ['placeholder' => 'Please Select', 'class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('comments_id', 'Comment') }}
        {{  Form::select('comments_id', $list['comments'], null,
            ['placeholder' => 'Please Select', 'class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('publish_date', 'Publish date') }}
        {{ Form::input('dateTime-local', 'publish_date', null, ['class' => 'form-control']) }}
    </div>

    {{ Form::submit('Edit the Item!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@endsection
