@extends('layouts.app')

@section('title','Trash Posts - BlogSphere')

@section('content')

<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">

                <i class="bi bi-trash3 text-danger"></i>

                Trash Posts

            </h1>

            <p class="text-muted">

                Deleted posts can be restored or permanently removed.

            </p>
        </div>


        <a href="{{ route('posts.index') }}"
            class="btn btn-primary">

            <i class="bi bi-arrow-left"></i>

            Back To Posts

        </a>

    </div>



    {{-- Success Message --}}
    @if(session('success'))

    <div class="alert alert-success">

        {{ session('success') }}

    </div>

    @endif



    @if($posts->count() == 0)

    <div class="text-center py-5">

        <i class="bi bi-trash display-1 text-secondary"></i>

        <h3 class="mt-3">

            Trash is Empty

        </h3>

        <p class="text-muted">

            No deleted posts found.

        </p>

    </div>


    @else


    <div class="row g-4">


        @foreach($posts as $post)


        <div class="col-md-6 col-lg-4">


            <div class="card shadow-sm border-0 h-100">


                {{-- Image --}}

                @if($post->image)

                <img
                    src="{{ asset('storage/'.$post->image) }}"
                    class="card-img-top"
                    style="height:220px;object-fit:cover;opacity:.7;">


                @endif



                <div class="card-body">


                    <h5 class="fw-bold">

                        {{ $post->title }}

                    </h5>



                    <span class="badge bg-secondary mb-3">

                        Deleted

                    </span>



                    <p class="text-muted">

                        {{ Str::limit($post->content,120) }}

                    </p>



                    <small class="text-muted">

                        <i class="bi bi-clock"></i>

                        Deleted :

                        {{ $post->deleted_at->format('d M Y h:i A') }}

                    </small>



                </div>



                <div class="card-footer bg-white">


                    <div class="d-flex justify-content-between">


                        {{-- Restore --}}

                        <form
                            action="{{ route('posts.restore',$post->id) }}"
                            method="POST">

                            @csrf

                            <button
                                class="btn btn-success btn-sm">

                                <i class="bi bi-arrow-clockwise"></i>

                                Restore

                            </button>

                        </form>




                        {{-- Force Delete --}}

                        <form
                            action="{{ route('posts.forceDelete',$post->id) }}"
                            method="POST">


                            @csrf

                            @method('DELETE')


                            <button
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Permanent delete this post?')">


                                <i class="bi bi-trash3"></i>

                                Delete Forever


                            </button>


                        </form>


                    </div>


                </div>


            </div>


        </div>


        @endforeach


    </div>



    {{-- Pagination --}}

    <div class="mt-5 d-flex justify-content-center">

        {{ $posts->links('pagination::bootstrap-5') }}

    </div>



    @endif


</div>


@endsection