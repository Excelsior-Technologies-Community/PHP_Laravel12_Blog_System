<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BlogSphere - Modern Laravel 12 Publication')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4361ee;
            --primary-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            --accent-pink: #f72585;
            --accent-cyan: #4cc9f0;
            --font-main: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --font-code: 'JetBrains Mono', monospace;
        }

        body {
            font-family: var(--font-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Top Reading Progress Bar */
        #reading-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            width: 0%;
            background: linear-gradient(90deg, #4cc9f0, #4361ee, #f72585);
            z-index: 9999;
            transition: width 0.1s ease-out;
        }

        /* Navbar */
        .navbar {
            background: var(--primary-gradient);
            box-shadow: 0 4px 20px rgba(67, 97, 238, 0.18);
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.45rem;
            letter-spacing: -0.5px;
        }

        .nav-link {
            font-weight: 600;
            padding: 8px 14px !important;
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        /* Blog Card Styles */
        .blog-card {
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid rgba(0, 0, 0, 0.06);
            height: 100%;
            display: flex;
            flex-direction: column;
            background-color: var(--bs-card-bg);
        }

        [data-bs-theme="dark"] .blog-card {
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        .blog-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12);
        }

        .blog-image-wrapper {
            height: 220px;
            overflow: hidden;
            position: relative;
            background-color: #e9ecef;
        }

        .blog-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .blog-card:hover .blog-image-wrapper img {
            transform: scale(1.06);
        }

        /* Reaction Pill Buttons */
        .reaction-btn {
            border: 1px solid rgba(0, 0, 0, 0.1);
            background: var(--bs-tertiary-bg);
            border-radius: 30px;
            padding: 6px 14px;
            font-size: 0.88rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .reaction-btn:hover {
            transform: scale(1.08);
        }

        .reaction-btn.active {
            background: rgba(67, 97, 238, 0.12);
            border-color: #4361ee;
            color: #4361ee;
        }

        /* Bookmark Button */
        .bookmark-btn {
            border: none;
            background: transparent;
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .bookmark-btn:hover, .bookmark-btn.saved {
            color: #f72585;
            transform: scale(1.15);
        }

        /* Threaded Nested Comments */
        .comment-thread {
            border-left: 2px solid #dee2e6;
            margin-left: 20px;
            padding-left: 18px;
        }

        [data-bs-theme="dark"] .comment-thread {
            border-left-color: #343a40;
        }

        /* Table of Contents Floating Widget */
        .toc-widget {
            border-left: 3px solid #4361ee;
            padding-left: 14px;
        }

        .toc-link {
            display: block;
            padding: 4px 0;
            color: var(--bs-secondary-color);
            text-decoration: none;
            font-size: 0.88rem;
            transition: color 0.2s;
        }

        .toc-link:hover, .toc-link.active {
            color: #4361ee;
            font-weight: 700;
            padding-left: 4px;
        }

        /* Code block styling */
        pre, code {
            font-family: var(--font-code);
        }

        pre {
            background-color: #1e1e2f;
            color: #f8f9fa;
            padding: 16px;
            border-radius: 12px;
            overflow-x: auto;
        }

        /* Footer */
        .footer {
            background: #111827;
            color: #9ca3af;
            margin-top: auto;
            padding: 50px 0 25px;
        }

        .footer h5 {
            color: #f9fafb;
            font-weight: 700;
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Top Reading Progress Indicator -->
    <div id="reading-progress-bar"></div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex items-center gap-2" href="{{ route('posts.index') }}">
                <span class="w-9 h-9 rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center shadow-sm">
                    <i class="bi bi-pencil-square"></i>
                </span>
                <span>BlogSphere</span>
                <span class="badge bg-white text-primary rounded-pill fs-7 fw-bold ms-1">Laravel 12</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('posts.index') && !request('status') ? 'active bg-white bg-opacity-20' : '' }}" href="{{ route('posts.index') }}">
                            <i class="bi bi-compass me-1"></i>Explore
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('sort') === 'trending' ? 'active bg-white bg-opacity-20' : '' }}" href="{{ route('posts.index', ['sort' => 'trending']) }}">
                            <i class="bi bi-fire me-1 text-warning"></i>Trending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.readingList') }}" id="nav-bookmarks-link">
                            <i class="bi bi-bookmark-heart me-1"></i>Saved
                            <span class="badge bg-danger rounded-pill ms-1" id="saved-count-badge" style="display:none;">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.trash') }}">
                            <i class="bi bi-trash3 me-1"></i>Trash
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <!-- Create Post Button -->
                    <a href="{{ route('posts.create') }}" class="btn btn-light rounded-pill px-3 fw-bold text-primary shadow-sm">
                        <i class="bi bi-plus-circle-fill me-1 text-primary"></i>Write Post
                    </a>

                    <!-- Seed Data Button -->
                    <a href="{{ route('create.sample') }}"
                        onclick="return confirm('Reset and re-seed sample blog posts, categories, tags, reactions, and comments?')"
                        class="btn btn-outline-light rounded-pill px-3 btn-sm" title="Re-seed Sample Data">
                        <i class="bi bi-arrow-repeat me-1"></i>Seed Data
                    </a>

                    <!-- Dark Mode Toggle Button -->
                    <button type="button" id="theme-toggle-btn" class="btn btn-outline-light rounded-circle p-2" title="Toggle Dark/Light Mode">
                        <i class="bi bi-moon-stars-fill" id="theme-icon"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container my-4 flex-grow-1">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="fw-bold d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-primary"></i> BlogSphere
                    </h5>
                    <p class="small">
                        A modern Laravel 12 blogging engine built with Eloquent relationships, threaded comments, instant reactions, categories, and tags.
                    </p>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="text-white fw-bold">Explore</h6>
                    <ul class="list-unstyled small space-y-2">
                        <li><a href="{{ route('posts.index') }}" class="text-secondary text-decoration-none">Latest Stories</a></li>
                        <li><a href="{{ route('posts.index', ['sort' => 'trending']) }}" class="text-secondary text-decoration-none">Trending Feed</a></li>
                        <li><a href="{{ route('posts.readingList') }}" class="text-secondary text-decoration-none">Your Bookmarks</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="text-white fw-bold">Categories</h6>
                    <ul class="list-unstyled small space-y-2">
                        <li><a href="{{ route('posts.index', ['category' => 'web-development']) }}" class="text-secondary text-decoration-none">Web Dev</a></li>
                        <li><a href="{{ route('posts.index', ['category' => 'artificial-intelligence']) }}" class="text-secondary text-decoration-none">AI & LLMs</a></li>
                        <li><a href="{{ route('posts.index', ['category' => 'technology']) }}" class="text-secondary text-decoration-none">Technology</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-white fw-bold">Publication Engine</h6>
                    <p class="small text-secondary mb-2">Designed for fast reading, rich media, and developer-friendly pair programming.</p>
                    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2">
                        <i class="bi bi-shield-check me-1"></i> Laravel 12.x • PHP 8.4 • Bootstrap 5
                    </span>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center small text-secondary">
                &copy; {{ date('Y') }} BlogSphere Publication. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Toast Container for Live AJAX Actions -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
        <div id="liveToast" class="toast align-items-center text-white bg-primary border-0 rounded-4 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill fs-5" id="toast-icon"></i>
                    <span id="toast-message">Action successful!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global Scripts: Dark Mode, Reading Progress, Bookmarks Tracking -->
    <script>
        // 1. Dark Mode Theme Switcher
        (function() {
            const savedTheme = localStorage.getItem('blogsphere_theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            updateThemeIcon(savedTheme);

            const toggleBtn = document.getElementById('theme-toggle-btn');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('blogsphere_theme', newTheme);
                    updateThemeIcon(newTheme);
                });
            }

            function updateThemeIcon(theme) {
                const icon = document.getElementById('theme-icon');
                if (icon) {
                    if (theme === 'dark') {
                        icon.className = 'bi bi-sun-fill text-warning';
                    } else {
                        icon.className = 'bi bi-moon-stars-fill';
                    }
                }
            }
        })();

        // 2. Reading Progress Bar on Scroll
        window.addEventListener('scroll', function() {
            const bar = document.getElementById('reading-progress-bar');
            if (!bar) return;
            const scrollTop = window.scrollY || document.documentElement.scrollTop;
            const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (scrollTop / docHeight) * 100;
            bar.style.width = scrolled + '%';
        });

        // 3. Bookmarks Management via LocalStorage
        function getSavedBookmarks() {
            try {
                return JSON.parse(localStorage.getItem('blogsphere_bookmarks') || '[]');
            } catch(e) {
                return [];
            }
        }

        function toggleBookmark(postId, btn) {
            let saved = getSavedBookmarks();
            const index = saved.indexOf(postId);
            let message = '';

            if (index > -1) {
                saved.splice(index, 1);
                if (btn) btn.classList.remove('saved', 'text-danger');
                message = 'Removed from your reading list.';
            } else {
                saved.push(postId);
                if (btn) btn.classList.add('saved', 'text-danger');
                message = 'Saved to your reading list! 🔖';
            }

            localStorage.setItem('blogsphere_bookmarks', JSON.stringify(saved));
            updateBookmarkBadge();
            showAppToast(message);
        }

        function updateBookmarkBadge() {
            const saved = getSavedBookmarks();
            const badge = document.getElementById('saved-count-badge');
            if (badge) {
                if (saved.length > 0) {
                    badge.textContent = saved.length;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateBookmarkBadge();

            // Highlight existing bookmark buttons
            const saved = getSavedBookmarks();
            document.querySelectorAll('.bookmark-btn').forEach(btn => {
                const id = parseInt(btn.getAttribute('data-post-id'));
                if (saved.includes(id)) {
                    btn.classList.add('saved', 'text-danger');
                }
            });
        });

        // 4. Global Toast Notification Helper
        function showAppToast(message, iconClass = 'bi-check-circle-fill', bgClass = 'bg-primary') {
            const toastEl = document.getElementById('liveToast');
            if (!toastEl) return;
            document.getElementById('toast-message').textContent = message;
            document.getElementById('toast-icon').className = `bi ${iconClass} fs-5`;
            toastEl.className = `toast align-items-center text-white ${bgClass} border-0 rounded-4 shadow-lg`;
            const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
            toast.show();
        }
    </script>

    @yield('scripts')
</body>

</html>