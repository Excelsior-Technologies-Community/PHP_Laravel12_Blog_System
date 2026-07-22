@extends('layouts.app')

@section('title', $post->title . ' - BlogSphere')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- Featured Image --}}
                @if($post->image)
                    <img src="{{ Str::startsWith($post->image, 'http')
                        ? $post->image
                        : asset('storage/' . $post->image) }}"
                        class="card-img-top"
                        alt="{{ $post->title }}"
                        style="height:450px; width:100%; object-fit:contain; background:#f8f9fa;">
                @endif

                <div class="card-body p-5">

                    {{-- Title --}}
                    <h1 class="fw-bold mb-3">
                        {{ $post->title }}
                    </h1>

                    {{-- Meta --}}
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">

                        <span class="badge bg-{{ $post->status == 'published' ? 'success' : 'warning' }}">
                            {{ ucfirst($post->status) }}
                        </span>

                        @if($post->is_featured)
                            <span class="badge bg-danger">
                                ⭐ Featured
                            </span>
                        @endif

                        <span class="text-muted">
                            <i class="bi bi-calendar-event"></i>
                            {{ $post->created_at->format('F d, Y') }}
                        </span>

                        <span class="text-muted">
                            <i class="bi bi-clock"></i>
                            {{ $post->reading_time }} min read
                        </span>

                    </div>

                    <hr>

                    {{-- Content --}}
                    <div class="fs-5 lh-lg">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                </div>

                {{-- Footer --}}
                <div class="card-footer bg-light py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <a href="{{ route('posts.index') }}"
                           class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left"></i>
                            Back to Posts
                        </a>

                        <div>

                            <a href="{{ route('posts.edit', $post) }}"
                               class="btn btn-warning rounded-pill">
                                <i class="bi bi-pencil-square"></i>
                                Edit
                            </a>

                            <form action="{{ route('posts.destroy', $post) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to move this post to Trash?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger rounded-pill">
                                    <i class="bi bi-trash"></i>
                                    Delete
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection