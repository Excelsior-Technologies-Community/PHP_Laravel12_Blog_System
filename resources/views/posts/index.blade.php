@extends('layouts.app')

@section('title', 'BlogSphere - Explore Stories & Insights')

@section('content')

<!-- Header Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-body">
            <div class="text-primary fs-3 mb-1"><i class="bi bi-journal-text"></i></div>
            <h4 class="fw-bold mb-0">{{ $statistics['total'] }}</h4>
            <div class="text-secondary small">Total Posts</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-body">
            <div class="text-success fs-3 mb-1"><i class="bi bi-check2-circle"></i></div>
            <h4 class="fw-bold mb-0 text-success">{{ $statistics['published'] }}</h4>
            <div class="text-secondary small">Published</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-body">
            <div class="text-warning fs-3 mb-1"><i class="bi bi-star-fill"></i></div>
            <h4 class="fw-bold mb-0 text-warning">{{ $statistics['featured'] }}</h4>
            <div class="text-secondary small">Featured</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-body">
            <div class="text-info fs-3 mb-1"><i class="bi bi-tags-fill"></i></div>
            <h4 class="fw-bold mb-0 text-info">{{ $statistics['tags'] }}</h4>
            <div class="text-secondary small">Active Tags</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-body">
            <div class="text-purple fs-3 mb-1" style="color: #7209b7;"><i class="bi bi-folder2-open"></i></div>
            <h4 class="fw-bold mb-0" style="color: #7209b7;">{{ $statistics['categories'] }}</h4>
            <div class="text-secondary small">Categories</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-body">
            <div class="text-danger fs-3 mb-1"><i class="bi bi-trash3"></i></div>
            <h4 class="fw-bold mb-0 text-danger">{{ $statistics['trash'] }}</h4>
            <div class="text-secondary small">In Trash</div>
        </div>
    </div>
</div>

<!-- Search & Category Filter Toolbar -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-body">
    <form method="GET" action="{{ route('posts.index') }}" class="row g-2 align-items-center">
        <!-- Search Input -->
        <div class="col-lg-4 col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-secondary">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search titles, concepts, keywords..."
                    class="form-control border-start-0 rounded-end-3">
            </div>
        </div>

        <!-- Category Dropdown -->
        <div class="col-lg-2 col-md-3 col-6">
            <select name="category" class="form-select rounded-3">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                    {{ $cat->name }} ({{ $cat->published_posts_count }})
                </option>
                @endforeach
            </select>
        </div>

        <!-- Tag Dropdown -->
        <div class="col-lg-2 col-md-3 col-6">
            <select name="tag" class="form-select rounded-3">
                <option value="">All Tags</option>
                @foreach($popularTags as $t)
                <option value="{{ $t->slug }}" {{ request('tag') == $t->slug ? 'selected' : '' }}>
                    #{{ $t->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Sort Filter -->
        <div class="col-lg-2 col-md-4 col-6">
            <select name="sort" class="form-select rounded-3">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>⏱️ Latest Stories</option>
                <option value="trending" {{ request('sort') == 'trending' ? 'selected' : '' }}>🔥 Weekly Trending</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>👁️ Most Viewed</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>⌛ Oldest First</option>
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="col-lg-2 col-md-4 col-6 d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-3 flex-grow-1 fw-semibold">
                <i class="bi bi-funnel-fill me-1"></i>Filter
            </button>
            <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary rounded-3" title="Reset Filters">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>
</div>

<!-- Main Feed Layout (Posts Grid + Right Sidebar) -->
<div class="row g-4">
    <!-- Posts Grid Column (8 Cols) -->
    <div class="col-lg-8">
        @if(request()->anyFilled(['search', 'category', 'tag', 'sort']))
        <div class="d-flex align-items-center justify-content-between mb-3 px-1">
            <div class="text-secondary small">
                Showing filtered results
                @if(request('category')) for category <strong class="text-primary">{{ request('category') }}</strong>@endif
                @if(request('tag')) with tag <strong class="text-primary">#{{ request('tag') }}</strong>@endif
                @if(request('search')) matching "<strong class="text-dark">{{ request('search') }}</strong>"@endif
            </div>
            <a href="{{ route('posts.index') }}" class="btn btn-sm btn-link text-decoration-none">Clear all</a>
        </div>
        @endif

        <div class="row g-4">
            @forelse($posts as $post)
            <div class="col-md-6">
                <article class="blog-card">
                    <!-- Image Wrapper -->
                    <div class="blog-image-wrapper">
                        <a href="{{ route('posts.show', $post->slug ?: $post->id) }}">
                            @if($post->image)
                            <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}"
                                alt="{{ $post->title }}" loading="lazy">
                            @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-file-earmark-richtext fs-1 opacity-50"></i>
                            </div>
                            @endif
                        </a>

                        <!-- Category Badge Overlay -->
                        @if($post->category)
                        <span class="position-absolute top-0 start-0 m-3 badge rounded-pill shadow-sm"
                            style="background-color: {{ $post->category->color }}; color: #fff;">
                            <i class="bi {{ $post->category->icon }} me-1"></i>{{ $post->category->name }}
                        </span>
                        @endif

                        <!-- Bookmark Button Overlay -->
                        <button type="button"
                            class="position-absolute top-0 end-0 m-3 btn btn-sm btn-light rounded-circle shadow-sm bookmark-btn p-1 px-2"
                            data-post-id="{{ $post->id }}"
                            onclick="toggleBookmark({{ $post->id }}, this)"
                            title="Save to Reading List">
                            <i class="bi bi-bookmark-fill"></i>
                        </button>
                    </div>

                    <!-- Post Content -->
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex align-items-center gap-2 text-secondary small mb-2">
                            <span><i class="bi bi-calendar3 me-1"></i>{{ $post->created_at->format('M d, Y') }}</span>
                            <span>•</span>
                            <span><i class="bi bi-clock me-1"></i>{{ $post->reading_time }} min read</span>
                            @if($post->is_featured)
                            <span class="badge bg-warning text-dark rounded-pill ms-auto">⭐ Featured</span>
                            @endif
                        </div>

                        <!-- Post Title -->
                        <h5 class="fw-bold mb-2">
                            <a href="{{ route('posts.show', $post->slug ?: $post->id) }}" class="text-decoration-none text-body hover-primary">
                                {{ $post->title }}
                            </a>
                        </h5>

                        <!-- Post Excerpt -->
                        <p class="text-secondary small mb-3 flex-grow-1 line-clamp-3">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>

                        <!-- Tags List -->
                        @if($post->tags->isNotEmpty())
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            @foreach($post->tags as $tag)
                            <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                                class="badge bg-secondary bg-opacity-10 text-secondary text-decoration-none rounded-pill px-2 py-1 small">
                                #{{ $tag->name }}
                            </a>
                            @endforeach
                        </div>
                        @endif

                        <!-- Card Footer -->
                        <div class="pt-3 border-top d-flex align-items-center justify-content-between text-secondary small">
                            <div class="d-flex align-items-center gap-3">
                                <span title="Total Views"><i class="bi bi-eye me-1"></i>{{ $post->views }}</span>
                                <span title="Reactions"><i class="bi bi-heart-fill text-danger me-1"></i>{{ $post->reactions->count() }}</span>
                                <span title="Comments"><i class="bi bi-chat-dots me-1"></i>{{ $post->comments_count }}</span>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-warning rounded-circle p-1 px-2" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Move this post to trash?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-circle p-1 px-2" title="Delete">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-body">
                    <div class="text-secondary fs-1 mb-3"><i class="bi bi-journal-x"></i></div>
                    <h4 class="fw-bold">No Stories Found</h4>
                    <p class="text-secondary small mb-4">No published articles matched your search and filter criteria.</p>
                    <div>
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-primary rounded-pill px-4 me-2">Clear Filters</a>
                        <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">Create New Post</a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- Right Sidebar Column (4 Cols) -->
    <div class="col-lg-4 space-y-4">

        <!-- 1. Weekly Trending Stories -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-body mb-4">
            <h5 class="fw-bold d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-fire text-danger"></i>
                <span>Weekly Trending Stories</span>
            </h5>

            <div class="list-group list-group-flush">
                @foreach($trendingPosts as $index => $tPost)
                <a href="{{ route('posts.show', $tPost->slug ?: $tPost->id) }}"
                    class="list-group-item list-group-item-action bg-transparent border-0 px-0 py-2.5 d-flex align-items-start gap-3">
                    <span class="fs-4 fw-extrabold text-secondary opacity-50 font-monospace">0{{ $index + 1 }}</span>
                    <div class="flex-grow-1">
                        @if($tPost->category)
                        <span class="badge rounded-pill text-white px-2 py-0.5 text-uppercase"
                            style="background-color: {{ $tPost->category->color }}; font-size: 0.65rem;">
                            {{ $tPost->category->name }}
                        </span>
                        @endif
                        <h6 class="fw-bold mb-1 text-body text-truncate-2 mt-1" style="font-size: 0.9rem;">
                            {{ $tPost->title }}
                        </h6>
                        <div class="text-secondary" style="font-size: 0.75rem;">
                            <span><i class="bi bi-eye me-1"></i>{{ $tPost->views }} views</span>
                            <span class="ms-2"><i class="bi bi-heart-fill text-danger me-1"></i>{{ $tPost->reactions_count }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- 2. Visual Category Analytics & Distribution -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-body mb-4">
            <h5 class="fw-bold d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-pie-chart-fill text-primary"></i>
                <span>Category Distribution</span>
            </h5>

            <div class="space-y-3">
                @foreach($categoryAnalytics as $catStat)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center small fw-semibold mb-1">
                        <a href="{{ route('posts.index', ['category' => $catStat['slug']]) }}" class="text-decoration-none text-body">
                            <i class="bi {{ $catStat['icon'] }} me-1" style="color: {{ $catStat['color'] }};"></i>
                            {{ $catStat['name'] }}
                        </a>
                        <span class="text-secondary">{{ $catStat['count'] }} posts ({{ $catStat['percentage'] }}%)</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar rounded-pill" role="progressbar"
                            style="width: {{ $catStat['percentage'] }}%; background-color: {{ $catStat['color'] }};"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 3. Popular Tags Cloud -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-body mb-4">
            <h5 class="fw-bold d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-tags-fill text-info"></i>
                <span>Explore Topics</span>
            </h5>

            <div class="d-flex flex-wrap gap-2">
                @foreach($popularTags as $tag)
                <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                    class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 {{ request('tag') == $tag->slug ? 'active' : '' }}">
                    #{{ $tag->name }}
                    <span class="badge bg-secondary rounded-pill ms-1 opacity-75">{{ $tag->posts_count }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- 4. Reading List / Saved Bookmarks Promo -->
        <div class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #7209b7 0%, #3a0ca3 100%);">
            <h5 class="fw-bold d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-bookmark-star-fill text-warning"></i>
                <span>Your Reading List</span>
            </h5>
            <p class="small text-white-50 mb-3">
                Save insightful stories to read later. Access your personalized reading list anytime with 1 click.
            </p>
            <a href="{{ route('posts.readingList') }}" class="btn btn-light rounded-pill fw-bold text-primary btn-sm px-3 shadow-sm">
                Open Reading List <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

    </div>
</div>

@endsection