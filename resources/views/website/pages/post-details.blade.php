@extends('website.templates.layout')
@section('content')
    <!-- News/Post Details Section -->
    <section class="page-details-section py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-10">
                    <div class="text-center mb-4">
                        <h1 class="fw-bold display-6 mb-2" style="color: #1a237e;">{{ $details->title ?? 'All News & Updates' }}</h1>
                        <div class="mx-auto" style="width: 80px; height: 4px; background: #1976d2; border-radius: 2px;"></div>
                        @if(!empty($details->created_at))
                            <div class="text-muted mt-2" style="font-size: 1rem;">
                                <i class="fa fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($details->created_at)->format('F d, Y') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                   <!-- Sidebar: All News/Posts List -->
                   <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fa fa-newspaper me-2"></i>Latest News &amp; Updates</h5>
                        </div>
                        <div class="card-body p-3" style="max-height: 500px; overflow-y: auto;">
                            @php
                                // Try to get all posts/news, fallback to empty if not provided
                                $allPosts = $posts ?? [];
                            @endphp
                            @if(count($allPosts))
                                <ul class="list-unstyled mb-0">
                                    @foreach($allPosts as $post)
                                        <li class="mb-3">
                                            <a href="{{ url('post/'.$post->slug."/".$post->encrypted_id) }}" class="text-decoration-none d-flex align-items-start">
                                                @if(!empty($post->image_path))
                                                    <img src="{{ asset('storage/'.$post->image_path) }}" alt="{{ $post->title }}" class="me-3 rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <span class="me-3 d-flex align-items-center justify-content-center bg-light rounded" style="width: 60px; height: 60px;">
                                                        <i class="fa fa-newspaper fa-2x text-primary"></i>
                                                    </span>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold" style="color: #1a237e;">{{ \Illuminate\Support\Str::limit($post->title, 60) }}</div>
                                                    <small class="text-muted">
                                                        <i class="fa fa-calendar-alt me-1"></i>
                                                        {{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}
                                                    </small>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-muted text-center">No news or posts available.</div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Main Post Content -->
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            @if($details["posts"] == "all")
                               <div class="row">
                                @foreach($allPosts as $post)
                                    @if(in_array($post->category, ['news', 'announcement', 'event']))
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card news-card h-100 border-0 shadow-sm overflow-hidden">
                                            <div class="news-img">
                                                <img src="{{ asset('storage/'.$post->image_path) }}" class="card-img-top" alt="News">
                                                <div class="news-date bg-primary text-white">
                                                    <span class="day">{{ \Carbon\Carbon::parse($post->published_at)->format('d') }}</span>
                                                    <span class="month">{{ \Carbon\Carbon::parse($post->published_at)->format('M') }}</span>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="news-category mb-2">
                                                    @php
                                                        $category = strtolower($post->category);
                                                        $badgeClass = 'bg-info';
                                                        if ($category === 'news') {
                                                            $badgeClass = 'bg-primary';
                                                        } elseif ($category === 'announcement') {
                                                            $badgeClass = 'bg-warning text-dark';
                                                        } elseif ($category === 'event') {
                                                            $badgeClass = 'bg-success';
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{$post->category}}</span>
                                                </div>
                                                <h5 class="card-title">{{$post->title}}</h5>
                                                <p class="card-text">{{$post->content}}</p>
                                                <a href="{{url("post/".$post->slug."/".$post->encrypted_id)}}" class="btn btn-sm btn-outline-primary">Read More</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            @else
                                @if(!empty($details->image_path))
                                    <div class="text-center mb-4">
                                        <img src="{{ asset('storage/'.$details->image_path) }}" alt="{{ $details->title }}" class="img-fluid rounded" style="max-height: 320px; object-fit: cover;">
                                    </div>
                                @endif
                                <div class="page-content" style="font-size: 1.15rem; color: #333;">
                                    {!! $details->content !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
             
            </div>
        </div>
    </section>
@endsection