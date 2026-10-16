@extends('layouts.app')

@section('title', $post->title . ' - BlogSphere')

@section('content')

<div class="row g-4">
    <!-- Main Article Column (8 Cols) -->
    <div class="col-lg-8">
        <article class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">

            <!-- Featured Image -->
            @if($post->image)
            <div class="w-100 position-relative" style="max-height: 440px; overflow: hidden; background: #000;">
                <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}"
                    alt="{{ $post->title }}" class="w-100 h-100 object-fit-cover" style="opacity: 0.95;">
            </div>
            @endif

            <div class="p-4 p-md-5">

                <!-- Meta Header -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    @if($post->category)
                    <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}"
                        class="badge rounded-pill text-decoration-none px-3 py-1.5 fw-bold"
                        style="background-color: {{ $post->category->color }}; color: #fff;">
                        <i class="bi {{ $post->category->icon }} me-1"></i>{{ $post->category->name }}
                    </a>
                    @endif

                    @if($post->is_featured)
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold">
                        ⭐ Featured Story
                    </span>
                    @endif

                    <span class="text-secondary small ms-auto d-flex align-items-center gap-1">
                        <i class="bi bi-calendar3"></i> {{ $post->created_at->format('F d, Y') }}
                    </span>
                    <span class="text-secondary small d-flex align-items-center gap-1">
                        <i class="bi bi-clock"></i> {{ $post->reading_time }} min read
                    </span>
                    <span class="text-secondary small d-flex align-items-center gap-1">
                        <i class="bi bi-eye"></i> {{ $post->views }} views
                    </span>
                </div>

                <!-- Article Title -->
                <h1 class="fw-extrabold display-6 mb-4 tracking-tight">
                    {{ $post->title }}
                </h1>

                <!-- 1-Click Interactive Reactions & Social Share Bar -->
                <div class="py-3 px-4 rounded-4 bg-body-tertiary mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3 border">
                    <!-- Reactions (Love, Clap, Insightful) -->
                    <div class="d-flex align-items-center gap-2">
                        <!-- Love Button -->
                        <button type="button" class="reaction-btn" id="react-love-btn" onclick="sendReaction('love')">
                            <span class="fs-6">❤️</span>
                            <span id="react-love-count">{{ $post->reactionCount('love') }}</span>
                        </button>

                        <!-- Clap Button (Medium style) -->
                        <button type="button" class="reaction-btn" id="react-clap-btn" onclick="sendReaction('clap')">
                            <span class="fs-6">👏</span>
                            <span id="react-clap-count">{{ $post->reactionCount('clap') }}</span>
                        </button>

                        <!-- Insightful Button -->
                        <button type="button" class="reaction-btn" id="react-insightful-btn" onclick="sendReaction('insightful')">
                            <span class="fs-6">💡</span>
                            <span id="react-insightful-count">{{ $post->reactionCount('insightful') }}</span>
                        </button>

                        <!-- Bookmark Button -->
                        <button type="button" class="reaction-btn bookmark-btn ms-2"
                            data-post-id="{{ $post->id }}"
                            onclick="toggleBookmark({{ $post->id }}, this)"
                            title="Save for Later">
                            <i class="bi bi-bookmark-fill"></i>
                            <span class="d-none d-sm-inline small">Save</span>
                        </button>
                    </div>

                    <!-- Social Sharing Utility -->
                    <div class="d-flex align-items-center gap-1">
                        <span class="text-secondary small fw-semibold me-1 d-none d-md-inline">Share:</span>
                        
                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                            target="_blank" class="btn btn-sm btn-outline-success rounded-circle p-1 px-2" title="Share on WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>

                        <!-- X / Twitter -->
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}"
                            target="_blank" class="btn btn-sm btn-outline-dark rounded-circle p-1 px-2" title="Share on X">
                            <i class="bi bi-twitter-x"></i>
                        </a>

                        <!-- LinkedIn -->
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                            target="_blank" class="btn btn-sm btn-outline-primary rounded-circle p-1 px-2" title="Share on LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>

                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            target="_blank" class="btn btn-sm btn-outline-primary rounded-circle p-1 px-2" title="Share on Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>

                        <!-- Copy Link -->
                        <button type="button" onclick="copyArticleLink()" class="btn btn-sm btn-outline-secondary rounded-circle p-1 px-2" title="Copy Link">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </div>
                </div>

                <!-- Article Body with Markdown Header Parsing -->
                <div id="article-content" class="fs-5 lh-lg mb-5 text-body">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <!-- Tags Footer -->
                @if($post->tags->isNotEmpty())
                <div class="pt-4 border-top mb-4">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-2">Related Topics</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                            class="badge bg-body-secondary text-body text-decoration-none rounded-pill px-3 py-2 fs-7 fw-semibold">
                            #{{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Post Navigation Bar -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-1"></i>Back to Stories
                    </a>

                    <div class="d-flex gap-2">
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning rounded-pill px-4 text-dark fw-bold">
                            <i class="bi bi-pencil-square me-1"></i>Edit
                        </a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST"
                            onsubmit="return confirm('Move this post to trash?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger rounded-pill px-3">
                                <i class="bi bi-trash3 me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </article>

        <!-- Nested Threaded Comments Section -->
        <section class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mt-4 bg-body" id="comments-section">
            <h4 class="fw-bold d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-chat-left-text-fill text-primary"></i>
                <span>Discussion (<span id="comments-count">{{ $post->allApprovedCommentsCount() }}</span>)</span>
            </h4>

            <!-- Add Top-Level Comment Form -->
            <div class="card border-0 bg-body-tertiary rounded-4 p-4 mb-5 border">
                <h6 class="fw-bold mb-3">Leave a Thought</h6>
                <form id="main-comment-form" method="POST" action="{{ route('comments.store', $post->id) }}">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <input type="text" name="author_name" required placeholder="Your Name"
                                class="form-control rounded-3 bg-body border-0 shadow-sm">
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="author_email" required placeholder="Your Email Address"
                                class="form-control rounded-3 bg-body border-0 shadow-sm">
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea name="content" rows="3" required placeholder="What are your thoughts on this story?..."
                            class="form-control rounded-3 bg-body border-0 shadow-sm"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-send-fill me-1"></i>Post Comment
                    </button>
                </form>
            </div>

            <!-- Comments List -->
            <div id="comments-container" class="space-y-4">
                @forelse($post->comments as $comment)
                <div class="comment-item mb-4" id="comment-{{ $comment->id }}">
                    <div class="d-flex align-items-start gap-3">
                        <img src="{{ $comment->avatarUrl(54) }}" alt="{{ $comment->author_name }}"
                            class="rounded-circle shadow-sm" width="48" height="48">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="fw-bold mb-0 text-body">{{ $comment->author_name }}</h6>
                                    @if($comment->is_admin)
                                    <span class="badge bg-primary rounded-pill text-white" style="font-size: 0.65rem;">
                                        Author / Staff
                                    </span>
                                    @endif
                                </div>
                                <span class="text-secondary small">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-body mb-2 small lh-base">
                                {{ $comment->content }}
                            </p>

                            <!-- Comment Action Buttons -->
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" class="btn btn-link btn-sm text-primary text-decoration-none p-0 fw-semibold"
                                    onclick="toggleReplyForm({{ $comment->id }})">
                                    <i class="bi bi-reply-fill me-1"></i>Reply
                                </button>
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this comment and its replies?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-link btn-sm text-danger text-decoration-none p-0">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>

                            <!-- Inline Reply Form (Hidden by default) -->
                            <div id="reply-form-{{ $comment->id }}" class="mt-3 p-3 bg-body-tertiary rounded-4 border d-none">
                                <form method="POST" action="{{ route('comments.store', $post->id) }}">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <input type="text" name="author_name" required placeholder="Your Name" class="form-control form-control-sm rounded-3">
                                        </div>
                                        <div class="col-6">
                                            <input type="email" name="author_email" required placeholder="Your Email" class="form-control form-control-sm rounded-3">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <textarea name="content" rows="2" required placeholder="Write a reply to {{ $comment->author_name }}..." class="form-control form-control-sm rounded-3"></textarea>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">Send Reply</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="toggleReplyForm({{ $comment->id }})">Cancel</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Nested Replies Thread -->
                            @if($comment->replies->isNotEmpty())
                            <div class="comment-thread mt-3 space-y-3">
                                @foreach($comment->replies as $reply)
                                <div class="d-flex align-items-start gap-2 pt-2" id="comment-{{ $reply->id }}">
                                    <img src="{{ $reply->avatarUrl(40) }}" alt="{{ $reply->author_name }}"
                                        class="rounded-circle shadow-sm" width="36" height="36">
                                    <div class="flex-grow-1 bg-body-tertiary p-3 rounded-4 border">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <strong class="text-body small">{{ $reply->author_name }}</strong>
                                                @if($reply->is_admin)
                                                <span class="badge bg-primary rounded-pill text-white" style="font-size: 0.6rem;">Staff</span>
                                                @endif
                                            </div>
                                            <span class="text-secondary" style="font-size: 0.72rem;">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-body mb-1 small lh-base">{{ $reply->content }}</p>
                                        <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete this reply?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-link btn-sm text-danger text-decoration-none p-0" style="font-size: 0.72rem;">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-secondary">
                    <i class="bi bi-chat-dots fs-2 opacity-50 mb-2 d-block"></i>
                    <p class="small mb-0">No comments yet. Be the first to start the conversation!</p>
                </div>
                @endforelse
            </div>
        </section>

    </div>

    <!-- Right Sidebar Column (4 Cols) -->
    <div class="col-lg-4 space-y-4">

        <!-- 1. Floating Table of Contents (TOC) -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-body sticky-top mb-4" style="top: 80px; z-index: 1020;">
            <h6 class="fw-bold d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-list-nested text-primary"></i>
                <span>Table of Contents</span>
            </h6>

            <div id="table-of-contents" class="toc-widget">
                <!-- Dynamically populated via JS from content headings -->
                <div class="text-secondary small italic">Scanning article structure...</div>
            </div>
        </div>

        <!-- 2. Related Stories in Category -->
        @if($relatedPosts->isNotEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-body mb-4">
            <h6 class="fw-bold d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-grid-fill text-purple" style="color: #7209b7;"></i>
                <span>More Stories Like This</span>
            </h6>

            <div class="list-group list-group-flush">
                @foreach($relatedPosts as $rPost)
                <a href="{{ route('posts.show', $rPost->slug ?: $rPost->id) }}"
                    class="list-group-item list-group-item-action bg-transparent border-0 px-0 py-2.5">
                    <div class="fw-bold text-body small mb-1">{{ $rPost->title }}</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">
                        <span>{{ $rPost->created_at->format('M d') }}</span> •
                        <span>{{ $rPost->reading_time }} min read</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 3. Trending Stories -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-body">
            <h6 class="fw-bold d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-fire text-danger"></i>
                <span>Trending Right Now</span>
            </h6>

            <div class="list-group list-group-flush">
                @foreach($trendingPosts as $tPost)
                <a href="{{ route('posts.show', $tPost->slug ?: $tPost->id) }}"
                    class="list-group-item list-group-item-action bg-transparent border-0 px-0 py-2">
                    <div class="fw-bold text-body small text-truncate">{{ $tPost->title }}</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">
                        <i class="bi bi-eye me-1"></i>{{ $tPost->views }} views •
                        <i class="bi bi-heart-fill text-danger me-1"></i>{{ $tPost->reactions_count }}
                    </div>
                </a>
                @endforeach
            </div>
        </div>

    </div>
</div>

<script>
    // 1. AJAX Reaction Submission (Love, Clap, Insightful)
    async function sendReaction(type) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url = '{{ route("posts.react", $post->id) }}';

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ type: type })
            });

            const data = await res.json();
            if (data.success) {
                document.getElementById('react-love-count').textContent = data.love_count;
                document.getElementById('react-clap-count').textContent = data.clap_count;
                document.getElementById('react-insightful-count').textContent = data.insightful_count;

                const btn = document.getElementById(`react-${type}-btn`);
                if (btn) {
                    btn.classList.add('active');
                    btn.style.transform = 'scale(1.2)';
                    setTimeout(() => btn.style.transform = 'scale(1)', 200);
                }

                showAppToast(data.message, 'bi-heart-fill', 'bg-danger');
            }
        } catch (e) {
            showAppToast('Failed to record reaction', 'bi-exclamation-circle-fill', 'bg-danger');
        }
    }

    // 2. Toggle Inline Reply Form
    function toggleReplyForm(commentId) {
        const form = document.getElementById(`reply-form-${commentId}`);
        if (form) {
            form.classList.toggle('d-none');
        }
    }

    // 3. Copy Link to Clipboard
    function copyArticleLink() {
        navigator.clipboard.writeText(window.location.href);
        showAppToast('Article link copied to clipboard! 📋', 'bi-clipboard-check-fill', 'bg-success');
    }

    // 4. Auto Table of Contents (TOC) Generator
    document.addEventListener('DOMContentLoaded', function() {
        const content = document.getElementById('article-content');
        const tocContainer = document.getElementById('table-of-contents');

        if (!content || !tocContainer) return;

        // Parse markdown style headers if rendered as text (e.g. ## Title)
        const lines = content.innerHTML.split('\n');
        let newContent = '';
        const headings = [];

        lines.forEach((line, index) => {
            const h2Match = line.match(/^##\s+(.+)$/);
            const h3Match = line.match(/^###\s+(.+)$/);

            if (h2Match) {
                const title = h2Match[1].trim();
                const id = 'heading-' + index;
                headings.push({ id, title, level: 2 });
                newContent += `<h3 id="${id}" class="fw-bold mt-4 mb-2 text-primary">${title}</h3>`;
            } else if (h3Match) {
                const title = h3Match[1].trim();
                const id = 'heading-' + index;
                headings.push({ id, title, level: 3 });
                newContent += `<h4 id="${id}" class="fw-bold mt-3 mb-2">${title}</h4>`;
            } else {
                newContent += line + '\n';
            }
        });

        content.innerHTML = newContent;

        if (headings.length > 0) {
            tocContainer.innerHTML = '';
            headings.forEach(h => {
                const a = document.createElement('a');
                a.href = '#' + h.id;
                a.className = 'toc-link ' + (h.level === 3 ? 'ps-3 small text-secondary' : 'fw-semibold');
                a.textContent = h.title;
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById(h.id).scrollIntoView({ behavior: 'smooth' });
                });
                tocContainer.appendChild(a);
            });
        } else {
            tocContainer.innerHTML = '<span class="text-secondary small italic">No sub-sections found.</span>';
        }
    });
</script>

@endsection