@extends('layouts.app')

@section('content')

    <div class="container">

        <form action="{{route('admin.posts.store')}}" method="POST">
            @csrf
            
            <label for="name" class="form-label">Name</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Title" name="name" value="{{old('name')}}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <label for="content" class="form-label">Content</label>
            <div class="input-group mb-3">
                <textarea placeholder="Post content" class="form-control @error('content') is-invalid @enderror" aria-label="With textarea" name="content">{{old('content')}}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <label for="tag" class="form-label">Tag</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control @error('tag') is-invalid @enderror" placeholder="enter the words separated by spaces" name="tag" value="{{old('tag')}}">
                @error('tag')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        
    </div>

@endsection