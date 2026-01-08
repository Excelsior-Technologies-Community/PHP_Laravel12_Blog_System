@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-3">
                <!-- Header -->
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Edit Post
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" id="editForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">Title *</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $post->title) }}" 
                                   placeholder="Enter post title"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">Content *</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="8" 
                                      placeholder="Write your content here..."
                                      required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <span id="charCount">{{ strlen($post->content) }}</span> characters
                            </small>
                        </div>

                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-semibold">Image</label>
                            
                            @if($post->image)
                                <div class="mb-3 border rounded p-3 bg-light">
                                    <p class="text-muted mb-2">Current Image:</p>
                                    <img src="{{ asset('storage/' . $post->image) }}" 
                                         alt="Current post image" 
                                         class="img-fluid rounded mb-2"
                                         style="max-height: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="removeImage" name="remove_image">
                                        <label class="form-check-label text-danger" for="removeImage">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="input-group">
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted mt-1">Optional. Leave empty to keep current image.</small>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancel
                            </a>
                            
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" onclick="previewContent()">
                                    <i class="bi bi-eye me-1"></i>Preview
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Update Post
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h4 id="previewTitle">{{ $post->title }}</h4>
                <div class="text-muted small mb-3">
                    <i class="bi bi-calendar3"></i> {{ date('M d, Y') }}
                </div>
                <div id="previewContent">
                    {{ Str::limit($post->content, 300) }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentTextarea = document.getElementById('content');
        const charCount = document.getElementById('charCount');
        
        // Update character count
        contentTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        
        // Preview image if selected
        const imageInput = document.getElementById('image');
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const removeCheckbox = document.getElementById('removeImage');
                if (removeCheckbox) {
                    removeCheckbox.checked = false;
                }
            }
        });
        
        // If remove image is checked, disable file input
        const removeCheckbox = document.getElementById('removeImage');
        if (removeCheckbox) {
            removeCheckbox.addEventListener('change', function() {
                imageInput.disabled = this.checked;
            });
        }
    });
    
    function previewContent() {
        const title = document.getElementById('title').value || '{{ $post->title }}';
        const content = document.getElementById('content').value || '{{ $post->content }}';
        
        document.getElementById('previewTitle').textContent = title;
        document.getElementById('previewContent').textContent = content.substring(0, 300) + 
            (content.length > 300 ? '...' : '');
        
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    }
</script>

<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border: none;
    }
    
    .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }
    
    #charCount {
        font-weight: 500;
        color: #4361ee;
    }
</style>
@endsection