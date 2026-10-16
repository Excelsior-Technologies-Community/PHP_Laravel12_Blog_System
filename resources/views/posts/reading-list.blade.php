@extends('layouts.app')

@section('title', 'Your Reading List - BlogSphere')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-bookmark-heart-fill text-danger"></i>
            <span>Your Reading List</span>
        </h2>
        <p class="text-secondary small mb-0">Stories you have bookmarked to read at your own pace.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
            <i class="bi bi-compass me-1"></i>Discover More
        </a>
        <button type="button" onclick="clearAllBookmarks()" class="btn btn-outline-danger rounded-pill px-3 btn-sm" id="clear-all-btn">
            <i class="bi bi-trash3 me-1"></i>Clear All
        </button>
    </div>
</div>

<div class="row g-4" id="reading-list-grid">
    @forelse($posts as $post)
    <div class="col-md-6 col-lg-4" id="bookmark-card-{{ $post->id }}">
        <div class="blog-card">
            <!-- Image Wrapper -->
            <div class="blog-image-wrapper">
                <a href="{{ route('posts.show', $post->slug ?: $post->id) }}">
                    @if($post->image)
                    <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                    @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-file-earmark-richtext fs-1 opacity-50"></i>
                    </div>
                    @endif
                </a>

                @if($post->category)
                <span class="position-absolute top-0 start-0 m-3 badge rounded-pill shadow-sm"
                    style="background-color: {{ $post->category->color }}; color: #fff;">
                    {{ $post->category->name }}
                </span>
                @endif

                <button type="button" class="position-absolute top-0 end-0 m-3 btn btn-sm btn-light rounded-circle shadow-sm text-danger p-1 px-2"
                    onclick="removeBookmarkItem({{ $post->id }})" title="Remove from reading list">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Content -->
            <div class="p-4 d-flex flex-column flex-grow-1">
                <div class="text-secondary small mb-2">
                    <span>{{ $post->created_at->format('M d, Y') }}</span> •
                    <span>{{ $post->reading_time }} min read</span>
                </div>

                <h5 class="fw-bold mb-2">
                    <a href="{{ route('posts.show', $post->slug ?: $post->id) }}" class="text-decoration-none text-body">
                        {{ $post->title }}
                    </a>
                </h5>

                <p class="text-secondary small mb-3 flex-grow-1">
                    {{ Str::limit(strip_tags($post->content), 100) }}
                </p>

                <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                    <a href="{{ route('posts.show', $post->slug ?: $post->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                        Read Story <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <button type="button" onclick="removeBookmarkItem({{ $post->id }})" class="btn btn-sm btn-link text-danger text-decoration-none small">
                        Remove
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12" id="empty-state">
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-body">
            <div class="text-secondary fs-1 mb-3"><i class="bi bi-bookmark-x text-danger opacity-50"></i></div>
            <h4 class="fw-bold">Your Reading List is Empty</h4>
            <p class="text-secondary small mb-4">Click the bookmark icon on any story to save it here for later reading.</p>
            <div>
                <a href="{{ route('posts.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-compass me-1"></i>Explore Stories
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const hasIds = urlParams.has('ids');

        if (!hasIds) {
            const saved = getSavedBookmarks();
            if (saved.length > 0) {
                window.location.replace('{{ route("posts.readingList") }}?ids=' + saved.join(','));
            }
        }
    });

    function removeBookmarkItem(postId) {
        toggleBookmark(postId, null);
        const card = document.getElementById(`bookmark-card-${postId}`);
        if (card) {
            card.remove();
        }
        const remaining = document.querySelectorAll('#reading-list-grid .col-md-6');
        if (remaining.length === 0) {
            location.href = '{{ route("posts.readingList") }}';
        }
    }

    function clearAllBookmarks() {
        if (!confirm('Are you sure you want to clear your entire reading list?')) return;
        localStorage.removeItem('blogsphere_bookmarks');
        updateBookmarkBadge();
        location.href = '{{ route("posts.readingList") }}';
    }
</script>

@endsection
