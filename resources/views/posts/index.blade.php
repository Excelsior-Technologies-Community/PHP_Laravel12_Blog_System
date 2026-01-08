@extends('layouts.app')

@section('title', 'Blog Posts - BlogSphere')

@section('content')
<div class="container my-5">

    <!-- Header with Stats -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold display-6 mb-2">Latest Blog Posts</h1>
            <p class="text-muted">
                <i class="bi bi-newspaper me-1"></i>
                {{ $posts->total() }} posts published • {{ $posts->count() }} showing
            </p>
        </div>
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Create New Post
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <form action="{{ route('posts.index') }}" method="GET" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search posts by title or content..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search me-1"></i>Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if($posts->isEmpty())
        <!-- Empty State -->
        <div class="text-center py-5 my-5">
            <div class="mb-4">
                <i class="bi bi-file-text display-1 text-muted"></i>
            </div>
            <h3 class="mb-3">No posts found</h3>
            <p class="text-muted mb-4">
                {{ request('search') ? 'No posts match your search criteria.' : 'Start by creating your first blog post!' }}
            </p>
            @if(!request('search'))
                <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Create Your First Post
                </a>
            @endif
        </div>
    @else
        <!-- Blog Cards Grid -->
        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="blog-card">
                        @if($post->image)
                            <div class="blog-image">
                                <img src="{{ asset('storage/'.$post->image) }}" 
                                     alt="{{ $post->title }}"
                                     class="img-fluid">
                                <div class="category-badge" style="position: absolute; top: 15px; left: 15px;">
                                    Featured
                                </div>
                            </div>
                        @else
                            <div class="blog-image bg-light d-flex align-items-center justify-content-center">
                                <i class="bi bi-card-image display-4 text-muted"></i>
                            </div>
                        @endif

                        <div class="blog-content">
                            <div class="category-badge">Uncategorized</div>
                            
                            <h5 class="blog-title">
                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($post->title, 60) }}
                                </a>
                            </h5>

                            <div class="blog-meta">
                                <span>
                                    <i class="bi bi-calendar3"></i>
                                    {{ $post->created_at->format('M d, Y') }}
                                </span>
                                <span>
                                    <i class="bi bi-clock"></i>
                                    {{ $post->created_at->format('h:i A') }}
                                </span>
                            </div>

                            <p class="blog-text">
                                {{ Str::limit(strip_tags($post->content), 150) }}
                            </p>

                            <a href="{{ route('posts.show', $post) }}" class="read-more">
                                Read More <i class="bi bi-arrow-right"></i>
                            </a>

                            <!-- Admin Actions -->
                            <div class="blog-actions">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-person-circle me-1"></i>
                                        {{ $post->author ?? 'Admin' }}
                                    </small>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('posts.edit', $post) }}" 
                                           class="btn btn-sm btn-edit">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        
                                        <form action="{{ route('posts.destroy', $post) }}" 
                                              method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-delete"
                                                    onclick="return confirm('Delete this post?')">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-container mt-5">
            <div class="d-flex flex-column align-items-center">
                <!-- Pagination Links -->
                <div class="mb-3">
                    {{ $posts->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
                
                <!-- Results Info -->
                <div class="text-center text-muted">
                    <p class="mb-0">
                        Showing 
                        <strong>{{ $posts->firstItem() }}–{{ $posts->lastItem() }}</strong> 
                        of <strong>{{ $posts->total() }}</strong> posts
                    </p>
                    @if(request('search'))
                        <small class="text-primary">
                            <i class="bi bi-search me-1"></i>
                            Searching for: "{{ request('search') }}"
                        </small>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary rounded-circle position-fixed" 
        style="bottom: 30px; right: 30px; width: 50px; height: 50px; display: none;">
    <i class="bi bi-arrow-up"></i>
</button>

<script>
    // Back to Top Button
    document.addEventListener('DOMContentLoaded', function() {
        const backToTop = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
@endsection