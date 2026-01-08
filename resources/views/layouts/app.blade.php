<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel Blog System')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #2b2d42;
            --light-text: #6c757d;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--dark-text);
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            flex: 1;
        }

        /* Navigation */
        .navbar {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -0.5px;
        }

        .nav-link {
            font-weight: 500;
            margin: 0 8px;
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        /* Blog Cards */
        .blog-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .blog-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .blog-image {
            height: 220px;
            overflow: hidden;
            position: relative;
        }

        .blog-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .blog-card:hover .blog-image img {
            transform: scale(1.05);
        }

        .blog-content {
            padding: 24px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .category-badge {
            display: inline-block;
            padding: 6px 14px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            align-self: flex-start;
        }

        .blog-title {
            font-weight: 700;
            font-size: 1.4rem;
            line-height: 1.4;
            margin-bottom: 12px;
            color: var(--dark-text);
        }

        .blog-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 16px;
            font-size: 0.9rem;
            color: var(--light-text);
        }

        .blog-meta i {
            margin-right: 5px;
        }

        .blog-text {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .read-more {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .read-more:hover {
            color: var(--secondary-color);
            gap: 12px;
        }

        /* Action Buttons */
        .blog-actions {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
        }

        .btn-edit {
            background: linear-gradient(135deg, #ffd166, #ffb347);
            border: none;
            color: #333;
            font-weight: 600;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef476f, #ff0054);
            border: none;
            color: white;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }

        /* Search Form */
        .search-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .search-form .form-control {
            border-radius: 10px;
            padding: 12px 20px;
            border: 2px solid #e9ecef;
            font-size: 1rem;
        }

        .search-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        /* Pagination */
        .pagination-container {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .pagination .page-item .page-link {
            border: none;
            color: var(--dark-text);
            padding: 8px 16px;
            margin: 0 4px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .pagination .page-item:not(.active) .page-link:hover {
            background-color: rgba(67, 97, 238, 0.1);
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #2b2d42 0%, #1a1c2e 100%);
            color: white;
            margin-top: auto;
            padding: 60px 0 30px;
        }

        .footer h5 {
            font-weight: 700;
            margin-bottom: 20px;
            color: #fff;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--accent-color);
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #adb5bd;
            font-size: 0.9rem;
        }

        /* Alert Messages */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .alert-success {
            background: linear-gradient(135deg, #4cc9f0, #4361ee);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.4rem;
            }
            
            .blog-title {
                font-size: 1.2rem;
            }
            
            .blog-content {
                padding: 20px;
            }
            
            .footer {
                text-align: center;
            }
            
            .social-links {
                justify-content: center;
            }
        }
    </style>
    
    @yield('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('posts.index') }}">
                <i class="bi bi-pencil-square me-2"></i>BlogSphere
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.index') }}">
                            <i class="bi bi-house-door me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.create') }}">
                            <i class="bi bi-plus-circle me-1"></i>Create Post
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-info-circle me-1"></i>About
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>BlogSphere</h5>
                    <p class="text-light">
                        A modern blogging platform built with Laravel. Share your thoughts, ideas, and stories with the world.
                    </p>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-github"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('posts.index') }}">Home</a></li>
                        <li><a href="{{ route('posts.create') }}">Create Post</a></li>
                        <li><a href="#">All Posts</a></li>
                        <li><a href="#">Categories</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Resources</h5>
                    <ul class="footer-links">
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Blogging Guide</a></li>
                        <li><a href="#">Community</a></li>
                        <li><a href="#">Support</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Contact</h5>
                    <ul class="footer-links">
                        <li><i class="bi bi-envelope me-2"></i>contact@blogsphere.com</li>
                        <li><i class="bi bi-telephone me-2"></i>090330 55100</li>
                        <li><i class="bi bi-geo-alt me-2"></i>Bodakdev, Ahmedabad</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; {{ date('Y') }} BlogSphere. All rights reserved. | Built with Laravel & Bootstrap</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Confirm before deleting
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
            
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
                        e.preventDefault();
                    }
                });
            });
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    
    @yield('scripts')
</body>

</html>