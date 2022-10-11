@extends('layouts.app')

@section('content')

    <div class="container">

        <a class="btn btn-primary my-4" href="{{route('admin.posts.index')}}">< Back</a>

        <h2 class="text-center">{{$data->name}}</h2>

        <div class="card">
            <div class="card-header">
                {{$data->slug}}
            </div>
            <div class="card-body">
                <p class="card-text">{{$data->content}}</p>
                <div class="card-footer text-muted">
                    {{$data->tag}}
                </div>
            </div>
        </div>    
    </div>

@endsection