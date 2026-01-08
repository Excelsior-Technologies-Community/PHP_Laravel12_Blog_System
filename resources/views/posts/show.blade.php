@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="{{ $post->title }}">
                @endif
                <div class="card-body">
                    <h1 class="card-title">{{ $post->title }}</h1>
                    <p class="text-muted">Posted on {{ $post->created_at->format('F d, Y') }}</p>
                    <hr>
                    <p class="card-text">{{ $post->content }}</p>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back to Posts</a>
                        <div>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection