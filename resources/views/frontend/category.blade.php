@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <div class="container">
        <div class="jumbotron">
            <div class="container">
                <h1>{{ $category->name }}</h1>
                <p>{{ $description }}</p>
                <p>Change this text in frontend/view/category.php or change data in controllers/category.php</p>
                <p>Add / edit categories in admin -> category.</p>
                <p class="text-success">Name of this category is : {{ $category->name }}.</p>
                <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more »</a></p>
            </div>
        </div>
    </div>
@endsection

@include('partials.script_file')
