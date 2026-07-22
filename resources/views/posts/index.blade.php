@extends('layouts.app')

@section('title', 'Blog Dashboard')

@section('content')

    <!-- Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                <i class="bi bi-journal-richtext me-2"></i>
                Blog Dashboard
            </h2>

            <p class="text-muted mb-0">
                Manage all blog posts from one place.
            </p>

        </div>

        <div>

            <a href="{{ route('posts.trash') }}"
                class="btn btn-outline-danger me-2">

                <i class="bi bi-trash"></i>

                Trash

                <span class="badge bg-danger">

                    {{ $statistics['trash'] }}

                </span>

            </a>

            <a href="{{ route('posts.create') }}"
                class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>

                Create Post

            </a>

        </div>

    </div>

    <!-- Dashboard Statistics -->

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-primary">

                        {{ $statistics['total'] }}

                    </h3>

                    <p class="mb-0">

                        Total Posts

                    </p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-success">

                        {{ $statistics['published'] }}

                    </h3>

                    <p class="mb-0">

                        Published

                    </p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-warning">

                        {{ $statistics['draft'] }}

                    </h3>

                    <p class="mb-0">

                        Draft

                    </p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center">

                    <h3 class="fw-bold text-danger">

                        {{ $statistics['featured'] }}

                    </h3>

                    <p class="mb-0">

                        Featured

                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Search + Filters -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                action="{{ route('posts.index') }}">

                <div class="row g-2">

                    <!-- Search -->
                    <div class="col-md-4">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search title or content..."
                            value="{{ request('search') }}">
                    </div>

                    <!-- Status -->
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>

                            <option value="published"
                                {{ request('status') == 'published' ? 'selected' : '' }}>
                                Published
                            </option>

                            <option value="draft"
                                {{ request('status') == 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>
                        </select>
                    </div>

                    <!-- Featured -->
                    <div class="col-md-2">
                        <select name="featured" class="form-select">
                            <option value="">Featured?</option>

                            <option value="1"
                                {{ request('featured') == '1' ? 'selected' : '' }}>
                                Yes
                            </option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="col-md-2">
                        <select name="sort" class="form-select">
                            <option value="">Latest</option>

                            <option value="popular"
                                {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                Most Viewed
                            </option>
                        </select>
                    </div>

                    <!-- Button -->
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Search
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- Keep your existing --}}
    {{-- @if($posts->isEmpty()) --}}

    <div class="row">

        <!-- Posts -->
        <div class="col-lg-8">

            <div class="row g-4">

                @foreach($posts as $post)

                <div class="col-lg-6 col-md-6">

                    <div class="card shadow-sm border-0 h-100">

                        {{-- Image --}}
                        @if($post->image)

                        <div class="position-relative">

                            <img
                                src="{{ Str::startsWith($post->image, 'http')
                        ? $post->image
                        : asset('storage/'.$post->image) }}"
                                class="card-img-top"
                                style="height:220px;width:100%;object-fit:contain;background:#f8f9fa;"
                                alt="{{ $post->title }}">

                            {{-- Featured Badge --}}
                            @if($post->is_featured)

                            <span
                                class="badge bg-danger position-absolute top-0 start-0 m-3">

                                ⭐ Featured

                            </span>

                            @endif

                        </div>

                        @else

                        <div
                            class="bg-light d-flex justify-content-center align-items-center"
                            style="height:220px;background:#f8f9fa;">
                            <div class="text-center">
                                <i class="bi bi-image display-3 text-secondary"></i>
                                <p class="text-muted mb-0 mt-2">No Image Available</p>
                            </div>

                            @if($post->is_featured)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-3">
                                ⭐ Featured
                            </span>
                            @endif
                        </div>

                        @endif

                        <div class="card-body">

                            {{-- Title --}}

                            <h5 class="fw-bold">

                                {{ Str::limit($post->title,60) }}

                            </h5>

                            {{-- Status Badge --}}

                            @if($post->status=='published')

                            <span class="badge bg-success">

                                Published

                            </span>

                            @else

                            <span class="badge bg-warning text-dark">

                                Draft

                            </span>

                            @endif

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <small class="text-muted">
                                    <i class="bi bi-book me-1"></i>
                                    {{ $post->reading_time }} min read
                                </small>

                                <small class="text-muted">
                                    <i class="bi bi-eye me-1"></i>
                                    {{ number_format($post->views) }} Views
                                </small>

                            </div>

                            {{-- Created Date --}}
                            <div class="mb-3">

                                <small class="text-muted">

                                    <i class="bi bi-calendar-event me-1"></i>

                                    {{ $post->created_at->format('d M Y') }}

                                </small>

                            </div>

                            {{-- Content --}}
                            <p class="text-muted">

                                {{ Str::limit(strip_tags($post->content), 120) }}

                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-4">

                                <a
                                    href="{{ route('posts.show',$post) }}"
                                    class="btn btn-sm btn-primary">

                                    <i class="bi bi-eye"></i>

                                    Read More

                                </a>

                                <div>

                                    <a
                                        href="{{ route('posts.edit',$post) }}"
                                        class="btn btn-warning btn-sm">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form
                                        action="{{ route('posts.destroy',$post) }}"
                                        method="POST"
                                        class="d-inline">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this post?')">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

        <!-- Popular Posts Sidebar -->
        <div class="col-lg-4">

           <div class="card shadow-sm border-0 sticky-sidebar">

                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-fire me-2"></i>
                        Popular Posts
                    </h5>
                </div>

                <div class="card-body">

                    @forelse($popularPosts as $popular)

                    <div class="mb-3 pb-3 border-bottom">

                        <a href="{{ route('posts.show', $popular) }}"
                            class="fw-bold text-decoration-none d-block">

                            {{ Str::limit($popular->title, 45) }}

                        </a>

                        <small class="text-muted">
                            <i class="bi bi-eye me-1"></i>
                            {{ number_format($popular->views) }} Views
                        </small>

                    </div>

                    @empty

                    <p class="text-muted mb-0">
                        No popular posts yet.
                    </p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

    @if($posts->isEmpty())

    <div class="text-center py-5">

        <i class="bi bi-file-earmark-text display-1 text-secondary"></i>

        <h3 class="mt-3">

            No Posts Found

        </h3>

        <p class="text-muted">

            There are no posts available.

        </p>

        <a
            href="{{ route('posts.create') }}"
            class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            Create First Post

        </a>

    </div>

    @else

    {{-- Pagination --}}

    <div class="mt-5">

        <div class="d-flex justify-content-center">

            {{ $posts->links('pagination::bootstrap-5') }}

        </div>

        <div class="text-center mt-3 text-muted">

            Showing

            {{ $posts->firstItem() }}

            -

            {{ $posts->lastItem() }}

            of

            {{ $posts->total() }}

            Posts

        </div>

    </div>

    @endif

    {{-- Back To Top Button --}}

    <button
        id="backToTop"
        class="btn btn-primary rounded-circle"
        style="position:fixed;
           bottom:25px;
           right:25px;
           display:none;
           width:50px;
           height:50px;">

        <i class="bi bi-arrow-up"></i>

    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let button = document.getElementById('backToTop');

            window.addEventListener('scroll', function() {

                if (window.scrollY > 300) {

                    button.style.display = 'block';

                } else {

                    button.style.display = 'none';

                }

            });

            button.addEventListener('click', function() {

                window.scrollTo({

                    top: 0,

                    behavior: 'smooth'

                });

            });

        });
    </script>

    @endsection