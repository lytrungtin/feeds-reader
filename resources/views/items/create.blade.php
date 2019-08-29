<!-- app/views/items/create.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Create a Item</h1>

    <!-- if there are creation errors, they will show here -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ Form::open(array('url' => 'items')) }}

    <div class="form-group">
        {{ Form::label('title', 'Title') }}
        {{ Form::text('title', Input::old('title'),
            array('class' => 'form-control ' . ( $errors->has('title') ? 'is-invalid' : '' ))) }}
        @error('title')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        {{ Form::label('description', 'Description') }}
        {{ Form::textarea('description', Input::old('description'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('channel_id', 'Channel') }}
        {{ Form::select('channel_id', $list['channels'], Input::old('channel_id'),
            ['placeholder' => 'Please Select',
            'class' => 'form-control ' . ( $errors->has('channel_id') ? 'is-invalid' : '' ) ]) }}
        @error('channel_id')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        {{ Form::label('category_id', 'Category') }}
        {{ Form::select('category_id', $list['categories'], Input::old('category_id'),
            ['placeholder' => 'Please Select', 'class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('link_id', 'Link') }}
        {{  Form::select('link_id', $list['links'], Input::old('link_id'),
            ['placeholder' => 'Please Select', 'class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('comments_id', 'Comments') }}
        {{ Form::select('comments_id', $list['comments'], Input::old('comments_id'),
            ['placeholder' => 'Please Select', 'class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('publish_date', 'Publish date') }}
        {{ Form::input('dateTime-local', 'publish_date', Input::old('publish_date'), ['class' => 'form-control']) }}
    </div>

    {{ Form::submit('Create the Item!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@endsection
