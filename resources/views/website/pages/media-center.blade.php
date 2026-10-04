@extends('website.templates.layout')
@section('content')
    <!-- Details Section -->        
    <section class="py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);">
        <div class="container-fluid" style="max-width: 1600px;">
            <div class="row justify-content-center mb-4">
                <div class="col-12 col-xxl-10">
                    <div class="text-center mb-4">
                        <h1 class="fw-bold display-6 mb-2" style="color: #1a237e;">{{ $details->title }}</h1>
                        <div class="mx-auto" style="width: 80px; height: 4px; background: #1976d2; border-radius: 2px;"></div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-xxl-10">
                    <div class="row">
                        <!-- Related Pages Sidebar (Left) -->
                        <div class="col-lg-3 mb-4 mb-lg-0">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3" style="color: #1976d2;">
                                        <i class="fa fa-link me-2"></i>Related Pages
                                    </h5>
                                    <ul class="list-group list-group-flush">
                                        @if(isset($relatedLinks) && count($relatedLinks) > 0)
                                            @foreach($relatedLinks as $page)
                                                <li class="list-group-item px-0 py-2">
                                                    <a href="{{ $page->type == 'content' ? url('detail/'.$page->url) : url($page->url) }}" class="{{ $page->id  == $details->id ? 'text-primary' : 'text-dark' }}" style="text-decoration: none;">
                                                        {{ $page->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="list-group-item px-0 py-2 text-muted">No related pages found.</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Main Content (Right) -->
                        <div class="col-lg-9">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body p-4">
                                    @if(!empty($details->image))
                                        <div class="text-center mb-4">
                                            <a href="{{ asset($details->image) }}" data-fancybox="gallery" data-caption="{{ $details->title }}">
                                                <img src="{{ asset($details->image) }}" alt="{{ $details->title }}" class="img-fluid rounded" style="max-height: 320px; object-fit: cover;">
                                            </a>
                                        </div>
                                    @endif
                                    <div class="page-content" style="font-size: 1.15rem; color: #333;">
                                        @if(isset($linkedPost) && count($linkedPost) > 0)
                                            @php
                                                $hasGallery = false;
                                                foreach($linkedPost as $post) {
                                                    if(($post->category ?? '') === 'gallery') {
                                                        $hasGallery = true;
                                                        break;
                                                    }
                                                }
                                            @endphp

                                            @if($hasGallery)
                                                <div class="row g-3">
                                                    @foreach($linkedPost as $post)
                                                        @php
                                                            $category = $post->category ?? '';
                                                            if($category !== 'gallery') continue;
                                                            $fileUrl = !empty($post->image_path) ? asset('storage/'.$post->image_path) : '';
                                                            $thumbnail = !empty($post->image_path) ? asset('storage/'.$post->image_path) : asset('samis-asset/image/placeholder.png');
                                                            $caption = $post->title;
                                                        @endphp
                                                        <div class="col-6 col-md-4 col-lg-3 gallery-slide" data-title="{{ $caption }}">
                                                            <a href="{{ $fileUrl }}" data-fancybox="gallery" data-caption="{{ $caption }}" class="gallery-item">
                                                                <img src="{{ $thumbnail }}" class="img-fluid rounded" alt="{{ $caption }}">
                                                                <div class="gallery-overlay">
                                                                    <i class="fas fa-search-plus"></i>
                                                                </div>
                                                                <div class="gallery-title-overlay">{{ $caption }}</div>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="row g-4">
                                                    @foreach($linkedPost as $post)
                                                        @php
                                                            $category = $post->category ?? '';
                                                            $isGallery = $category === 'gallery';
                                                            $isVideo = $category === 'video';
                                                            $isAudio = $category === 'audio';
                                                            $isDocument = $category === 'documents';
                                                            $fileUrl = !empty($post->image_path) ? asset('storage/'.$post->image_path) : '';
                                                            $thumbnail = !empty($post->image_path) ? asset('storage/'.$post->image_path) : asset('samis-asset/image/placeholder.png');
                                                            $isPdf = $isDocument && (Str::endsWith(strtolower($fileUrl), '.pdf'));
                                                        @endphp
                                                        <div class="col-md-4">
                                                            <div class="card h-100">
                                                                @if($isGallery && $fileUrl)
                                                                    <a href="{{ $fileUrl }}" data-fancybox="gallery" data-caption="{{ $post->title }}" class="gallery-item">
                                                                        <img src="{{ $thumbnail }}" class="card-img-top img-fluid" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                                                                    </a>
                                                                @elseif($isVideo && $fileUrl)
                                                                    <a href="#" class="video-item" data-bs-toggle="modal" data-bs-target="#videoModal" data-video="{{ $fileUrl }}">
                                                                        <img src="{{ $thumbnail }}" class="card-img-top img-fluid" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                                                                        <span class="position-absolute top-50 start-50 translate-middle" style="font-size: 2.5rem; color: #fff; text-shadow: 0 0 8px #000;"><i class="fa fa-play-circle"></i></span>
                                                                    </a>
                                                                @elseif($isDocument && $isPdf && $fileUrl)
                                                                    <a href="{{ $fileUrl }}" data-fancybox data-type="iframe" data-caption="{{ $post->title }}" class="pdf-item">
                                                                        <div class="d-flex align-items-center justify-content-center" style="height: 200px; background: #f5f5f5;">
                                                                            <i class="fa fa-file-pdf-o" style="font-size: 4rem; color: #d32f2f;"></i>
                                                                        </div>
                                                                        <div class="text-center mt-2 text-danger" style="font-size: 0.95rem;">
                                                                            <i class="fa fa-exclamation-circle me-1"></i>
                                                                            PDF preview. <span class="d-block">Click to view in a popup.</span>
                                                                        </div>
                                                                    </a>
                                                                @elseif($fileUrl && Str::endsWith(strtolower($fileUrl), ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                                                    <a href="{{ $fileUrl }}" data-fancybox="gallery" data-caption="{{ $post->title }}" class="gallery-item">
                                                                        <img src="{{ $thumbnail }}" class="card-img-top img-fluid" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                                                                    </a>
                                                                @else
                                                                    @if($fileUrl)
                                                                        <img src="{{ $thumbnail }}" class="card-img-top img-fluid" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                                                                    @endif
                                                                @endif
                                                                <div class="card-body">
                                                                    <h5 class="card-title">{{ $post->title }}</h5>
                                                                    <p class="card-text">{{ $post->excerpt }}</p>
                                                                    <a href="{{ url('detail/'.$post->url) }}" class="btn btn-primary">Read More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach 
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Main Content -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    @if(isset($linkedPost))
        @php
            $hasGallery = false;
            foreach($linkedPost as $post) {
                if(($post->category ?? '') === 'gallery') {
                    $hasGallery = true;
                    break;
                }
            }
        @endphp
        @if($hasGallery)
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
            <style>
                .gallery-overlay {
                    position: absolute;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background: rgba(30, 34, 126, 0.25);
                    opacity: 0;
                    transition: opacity 0.2s;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: .5rem;
                }
                .gallery-slide {
                    position: relative;
                    margin-bottom: 1rem;
                }
                .gallery-slide:hover .gallery-overlay {
                    opacity: 1;
                }
                .gallery-title-overlay {
                    position: absolute;
                    left: 0; right: 0; bottom: 0;
                    background: rgba(25, 118, 210, 0.85);
                    color: #fff;
                    padding: 0.25rem 0.5rem;
                    font-size: 1rem;
                    border-radius: 0 0 .5rem .5rem;
                    text-align: center;
                }
            </style>
        @else
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
            <style>
                .gallery-overlay {
                    position: absolute;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background: rgba(30, 34, 126, 0.25);
                    opacity: 0;
                    transition: opacity 0.2s;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: .5rem;
                }
                .gallery-slide {
                    position: relative;
                    margin-bottom: 1rem;
                }
                .gallery-slide:hover .gallery-overlay {
                    opacity: 1;
                }
                .gallery-title-overlay {
                    position: absolute;
                    left: 0; right: 0; bottom: 0;
                    background: rgba(25, 118, 210, 0.85);
                    color: #fff;
                    padding: 0.25rem 0.5rem;
                    font-size: 1rem;
                    border-radius: 0 0 .5rem .5rem;
                    text-align: center;
                }
            </style>
        @endif
    @endif
    @endpush

    @push('scripts')
    @if(isset($linkedPost))
        @php
            $hasGallery = false;
            foreach($linkedPost as $post) {
                if(($post->category ?? '') === 'gallery') {
                    $hasGallery = true;
                    break;
                }
            }
        @endphp
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Fancybox will auto-initialize for [data-fancybox]
                // No extra JS needed for images and PDFs
                // Video modal logic
                document.querySelectorAll('.video-item').forEach(function(el) {
                    el.addEventListener('click', function(e) {
                        e.preventDefault();
                        var video = this.getAttribute('data-video');
                        var player = document.getElementById('videoModalPlayer');
                        player.querySelector('source').src = video;
                        player.load();
                    });
                });
                document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
                    var player = document.getElementById('videoModalPlayer');
                    player.pause();
                    player.currentTime = 0;
                    player.querySelector('source').src = '';
                    player.load();
                });
            });
        </script>
    @endif
    @endpush

    @if(isset($linkedPost))
        <!-- Video Modal -->
        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-body p-0">
                <video id="videoModalPlayer" class="w-100" controls>
                  <source src="" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              </div>
            </div>
          </div>
        </div>
    @endif
@endsection