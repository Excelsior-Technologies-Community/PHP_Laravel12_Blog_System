@extends('layouts.app')

@section('title', 'Create New Post - BlogSphere')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold mb-3">Create New Blog Post</h1>
                <p class="text-muted lead">Share your thoughts and ideas with the world</p>
            </div>

            <!-- Form Card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Form Header -->
                <div class="card-header bg-gradient-primary py-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white rounded-circle p-2 me-3">
                            <i class="bi bi-pencil-square text-primary fs-4"></i>
                        </div>
                        <div>
                            <h4 class="card-title text-white mb-0">Post Details</h4>
                            <p class="text-white-50 mb-0">Fill in all required fields</p>
                        </div>
                    </div>
                </div>

                <!-- Form Body -->
                <div class="card-body p-5">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
                        @csrf

                        <!-- Title Field -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                Post Title <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-type-h1 text-primary"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       placeholder="Enter a catchy title for your post..."
                                       required
                                       autofocus>
                                @error('title')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted d-flex align-items-center mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Keep it concise and descriptive (max 255 characters)
                            </small>
                        </div>

                        <!-- Content Field -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">
                                Content <span class="text-danger">*</span>
                            </label>
                            <div class="position-relative">
                                <div class="border rounded-3 p-3 @error('content') border-danger @enderror">
                                    <textarea class="form-control border-0 @error('content') is-invalid @enderror" 
                                              id="content" 
                                              name="content" 
                                              rows="8" 
                                              placeholder="Write your amazing content here..."
                                              required>{{ old('content') }}</textarea>
                                </div>
                                @error('content')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted d-flex align-items-center">
                                    <i class="bi bi-markdown me-1"></i>
                                    Markdown is supported
                                </small>
                                <small class="text-muted" id="charCount">0 characters</small>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-5">
                            <label for="image" class="form-label fw-semibold">
                                Featured Image <span class="text-muted">(Optional)</span>
                            </label>
                            
                            <!-- Image Preview -->
                            <div class="image-upload-container mb-3">
                                <div class="image-preview border-2 border-dashed rounded-4 p-4 text-center"
                                     id="imagePreview"
                                     style="border-style: dashed; border-color: #dee2e6;">
                                    <div class="preview-placeholder">
                                        <i class="bi bi-image display-4 text-muted mb-3"></i>
                                        <p class="text-muted mb-2">Click to upload or drag and drop</p>
                                        <p class="small text-muted">PNG, JPG, GIF up to 5MB</p>
                                    </div>
                                    <img id="previewImage" class="img-fluid rounded-3 d-none" alt="Preview">
                                </div>
                            </div>
                            
                            <!-- File Input -->
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-upload text-primary"></i>
                                </span>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Image Requirements -->
                            <div class="mt-3 p-3 bg-light rounded-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="small fw-semibold mb-2">Recommendations:</h6>
                                        <ul class="small text-muted mb-0">
                                            <li>Use high-quality images</li>
                                            <li>Optimal size: 1200 x 630px</li>
                                            <li>Max file size: 5MB</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="small fw-semibold mb-2">Supported Formats:</h6>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-secondary">JPG</span>
                                            <span class="badge bg-secondary">PNG</span>
                                            <span class="badge bg-secondary">GIF</span>
                                            <span class="badge bg-secondary">WebP</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                            <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary px-4 rounded-3">
                                <i class="bi bi-arrow-left me-2"></i>Back to Posts
                            </a>
                            
                            <div class="d-flex gap-3">
                                <button type="button" class="btn btn-light px-4 rounded-3" id="previewBtn">
                                    <i class="bi bi-eye me-2"></i>Preview
                                </button>
                                <button type="submit" class="btn btn-primary px-5 rounded-3" id="submitBtn">
                                    <i class="bi bi-plus-circle me-2"></i>Publish Post
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Form Footer -->
                <div class="card-footer bg-light py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            Your post will be visible to all users
                        </small>
                        <small class="text-muted">
                            <i class="bi bi-clock-history me-1"></i>
                            Last saved: <span id="lastSaved">Not saved yet</span>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            {{-- <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Writing Tips
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Write a compelling introduction
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Use headings to organize content
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Add relevant images
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Proofread before publishing
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Use tags for better discovery
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Optimize for mobile reading
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Post Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="blog-card">
                    <div class="blog-image bg-light d-flex align-items-center justify-content-center" id="modalImagePreview">
                        <i class="bi bi-card-image display-4 text-muted"></i>
                    </div>
                    <div class="blog-content">
                        <div class="category-badge mb-3">Preview</div>
                        <h3 id="modalTitle">Your Title Here</h3>
                        <div class="blog-meta mb-3">
                            <span><i class="bi bi-calendar3"></i> {{ date('M d, Y') }}</span>
                        </div>
                        <div id="modalContent" class="blog-text">
                            Your content will appear here...
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('submitBtn').click()">
                    <i class="bi bi-check-circle me-2"></i>Looks Good, Publish!
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Styles for Create Post Page */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
    }

    .border-dashed {
        border-style: dashed !important;
    }

    .image-upload-container {
        position: relative;
        cursor: pointer;
    }

    .image-preview {
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }

    .image-preview:hover {
        background-color: #e9ecef;
        border-color: #4361ee !important;
    }

    .preview-placeholder {
        transition: all 0.3s ease;
    }

    .image-preview:hover .preview-placeholder {
        transform: translateY(-5px);
    }

    .image-preview.has-image {
        border-color: #4361ee !important;
        background-color: #f0f4ff;
    }

    #previewImage {
        max-height: 300px;
        object-fit: contain;
    }

    /* Form Validation Styles */
    .was-validated .form-control:invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    }

    .was-validated .form-control:valid {
        border-color: #198754;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    }

    /* Character Counter */
    #charCount {
        font-weight: 500;
    }

    #charCount.warning {
        color: #ff9800;
    }

    #charCount.danger {
        color: #f44336;
    }

    /* Loading State */
    .btn-loading {
        position: relative;
        color: transparent !important;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin-left: -10px;
        margin-top: -10px;
        border: 2px solid #fff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spinner 0.8s linear infinite;
    }

    @keyframes spinner {
        to { transform: rotate(360deg); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const titleInput = document.getElementById('title');
        const contentTextarea = document.getElementById('content');
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        const previewPlaceholder = imagePreview.querySelector('.preview-placeholder');
        const charCount = document.getElementById('charCount');
        const lastSaved = document.getElementById('lastSaved');
        const previewBtn = document.getElementById('previewBtn');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('postForm');

        // Character Counter
        function updateCharCount() {
            const count = contentTextarea.value.length;
            charCount.textContent = `${count.toLocaleString()} characters`;
            
            if (count > 5000) {
                charCount.classList.add('danger');
                charCount.classList.remove('warning');
            } else if (count > 3000) {
                charCount.classList.add('warning');
                charCount.classList.remove('danger');
            } else {
                charCount.classList.remove('warning', 'danger');
            }
        }

        // Image Preview
        function handleImagePreview(file) {
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('d-none');
                    previewPlaceholder.classList.add('d-none');
                    imagePreview.classList.add('has-image');
                    
                    // Update modal preview
                    const modalImage = document.querySelector('#modalImagePreview');
                    modalImage.innerHTML = `<img src="${e.target.result}" class="img-fluid" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            }
        }

        // Auto-save simulation
        function updateLastSaved() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            lastSaved.textContent = timeString;
        }

        // Preview Modal
        function updatePreviewModal() {
            document.getElementById('modalTitle').textContent = titleInput.value || 'Your Title Here';
            document.getElementById('modalContent').textContent = 
                contentTextarea.value.substring(0, 300) + 
                (contentTextarea.value.length > 300 ? '...' : '');
        }

        // Event Listeners
        contentTextarea.addEventListener('input', updateCharCount);
        contentTextarea.addEventListener('input', updateLastSaved);
        titleInput.addEventListener('input', updateLastSaved);

        imageInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                handleImagePreview(this.files[0]);
                updateLastSaved();
            }
        });

        // Drag and Drop for Image
        imagePreview.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = '#4361ee';
            this.style.backgroundColor = '#f0f4ff';
        });

        imagePreview.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.borderColor = '#dee2e6';
            this.style.backgroundColor = '#f8f9fa';
        });

        imagePreview.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = '#dee2e6';
            this.style.backgroundColor = '#f8f9fa';
            
            if (e.dataTransfer.files.length) {
                imageInput.files = e.dataTransfer.files;
                handleImagePreview(e.dataTransfer.files[0]);
                updateLastSaved();
            }
        });

        // Click to upload
        imagePreview.addEventListener('click', function() {
            imageInput.click();
        });

        // Preview Button
        previewBtn.addEventListener('click', function() {
            updatePreviewModal();
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        });

        // Form Submission
        form.addEventListener('submit', function(e) {
            // Show loading state
            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;
            
            // Optional: Validate minimum content length
            if (contentTextarea.value.trim().length < 50) {
                e.preventDefault();
                alert('Please write at least 50 characters for the content.');
                submitBtn.classList.remove('btn-loading');
                submitBtn.disabled = false;
                return;
            }
            
            // Auto-save on submit
            updateLastSaved();
        });

        // Initialize
        updateCharCount();
        updateLastSaved();
    });
</script>
@endsection