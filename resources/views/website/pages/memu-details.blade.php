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
                                                    <a href="{{ $page->type == 'content' ? url('detail/'.$page->url) : url((string) $page->url) }}"
                                                       class="{{ $page->id == $details->id ? 'text-primary' : 'text-dark' }}">
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
                                            <img src="{{ asset($details->image) }}" alt="{{ $details->title }}" class="img-fluid rounded" style="max-height: 320px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <div class="page-content" style="font-size: 1.15rem; color: #333;">
                                        {!! $details->content !!}
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
@endsection
