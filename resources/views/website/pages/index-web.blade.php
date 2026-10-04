@extends('website.templates.layout')

@section('content')
    <!-- Hero Slider Section -->
    <section class="hero-section" id="hero-section">
        <div class="hero-slider">
            @foreach($slides as $slide)
                <!-- Slide Item -->
                <div class="hero-slide active" style="background-image: url('{{ asset('storage/'.$slide->image_path) }}');">
                    <div class="hero-content">
                        <h1>{{$slide->title}}</h1>
                        <p>{{$slide->content}}</p>
                        @if(!empty($slide->button_text))
                            <div class="hero-btns">
                                <a href="{{url($slide->button_url ? $slide->button_url : '#')}}" class="btn btn-primary btn-lg">{{$slide->button_text}}</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            <!-- Slider Navigation -->
            <div class="slider-nav">
                <button class="slider-nav-btn" id="prevSlide">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slider-nav-btn" id="nextSlide">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Slider Dots -->
            <div class="slider-controls">
                <div class="slider-dots">
                    @foreach($slides as $index => $slide)
                        <div class="slider-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

{{--    <!-- Quick Links Section -->--}}
{{--    <section class="quick-links py-4 bg-light">--}}
{{--        <div class="container">--}}
{{--            <div class="row g-4">--}}
{{--                <div class="col-md-3 col-6">--}}
{{--                    <div class="d-flex align-items-center">--}}
{{--                        <div class="icon-box bg-primary text-white rounded-circle me-3">--}}
{{--                            <i class="fas fa-user-graduate"></i>--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            <h5 class="mb-1">Admissions</h5>--}}
{{--                            <p class="mb-0">2025/2026 Intake</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-3 col-6">--}}
{{--                    <div class="d-flex align-items-center">--}}
{{--                        <div class="icon-box bg-success text-white rounded-circle me-3">--}}
{{--                            <i class="fas fa-school"></i>--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            <h5 class="mb-1">Programs</h5>--}}
{{--                            <p class="mb-0">Babyclass to Form Six</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-3 col-6">--}}
{{--                    <div class="d-flex align-items-center">--}}
{{--                        <div class="icon-box bg-info text-white rounded-circle me-3">--}}
{{--                            <i class="fas fa-calendar-alt"></i>--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            <h5 class="mb-1">Academic Calendar</h5>--}}
{{--                            <p class="mb-0">Term Dates & Events</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-3 col-6">--}}
{{--                    <div class="d-flex align-items-center">--}}
{{--                        <div class="icon-box bg-warning text-white rounded-circle me-3">--}}
{{--                            <i class="fas fa-bus"></i>--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            <h5 class="mb-1">Transport</h5>--}}
{{--                            <p class="mb-0">School Bus Routes</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

    @foreach($posts as $post)
        @if($post->category == 'about')
            <!-- About School Section -->
            <section class="about-section py-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <img src="{{ asset('storage/'.$post->image_path) }}" alt="Our School" class="img-fluid rounded shadow">
                        </div>
                        <div class="col-lg-6">
                            <h2 class="section-title section-title-welcome">{{$post->title}}</h2>
                            <p class="lead">{{$post->excerpt}}</p>
                            <p>{{$post->content}}</p>
                            <div class="row mt-4">
                                <div class="col-sm-6 mb-3">
                                    <div class="d-flex">
                                        <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                        <div>
                                            <h5 class="mb-1">Holistic Development</h5>
                                            <p class="mb-0">Academic & character building</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="d-flex">
                                        <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                        <div>
                                            <h5 class="mb-1">Qualified Teachers</h5>
                                            <p class="mb-0">Dedicated & experienced staff</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="d-flex">
                                        <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                        <div>
                                            <h5 class="mb-1">Modern Facilities</h5>
                                            <p class="mb-0">Well-equipped classrooms & labs</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="d-flex">
                                        <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                        <div>
                                            <h5 class="mb-1">Sports & Arts</h5>
                                            <p class="mb-0">Comprehensive extracurricular</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{url('detail/about-us')}}" class="btn btn-outline-primary mt-3">Learn More About Us</a>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endforeach
    <!-- Academic Programs Section -->
    <section class="news-section py-5 bg-light @if(empty($programs)) visually-hidden @endif">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Academic Programs</h2>
                <p class="section-subtitle">Quality Education from Certificate to Diploma Level</p>
                <div class="divider"></div>
            </div>
            <div class="row g-4">
                @foreach($programs as $program)
                    <div class="col-md-6 col-lg-4">
                        <div class="card news-card h-100 border-0 shadow-sm overflow-hidden">
                            <div class="news-img">
                                <img src="{{ asset('storage/'.$program->photo_path) }}" class="card-img-top" alt="News">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{$program->name}}</h5>
                                <p class="card-text">{{$program->description}}</p>
                                <a href="{{url("program/".$program->code."/".$program->encrypted_id)}}" class="btn btn-sm btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{url('programs/all')}}" class="btn btn-primary px-4">View All News</a>
            </div>
        </div>
    </section>
    <!-- School Staff Section -->
{{--    <section class="staff-section py-5">--}}
{{--        <div class="container">--}}
{{--            <div class="section-header text-center mb-5">--}}
{{--                <h2 class="section-title">Meet Our Teaching Staff</h2>--}}
{{--                <p class="section-subtitle">Dedicated Educators Committed to Student Success</p>--}}
{{--                <div class="divider"></div>--}}
{{--            </div>--}}
{{--            <div class="row g-4 justify-content-center">--}}
{{--                @foreach($staff as $staffMember)--}}
{{--                    <div class="col-md-6 col-lg-3">--}}
{{--                        <div class="card staff-card text-center pt-5 pb-3 px-3 h-100">--}}
{{--                            @if(!empty($staffMember->photo_path))--}}
{{--                                <img src="{{ asset('storage/'.$staffMember->photo_path) }}" alt="{{ $staffMember->name }}" class="staff-photo mx-auto mb-3">--}}
{{--                            @else--}}
{{--                                <div class="staff-photo mx-auto mb-3 d-flex align-items-center justify-content-center bg-light" style="font-size:2.5rem; color:#0066cc;">--}}
{{--                                    <i class="fas fa-user-tie"></i>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                            <div class="card-body">--}}
{{--                                <h5 class="card-title mb-1">{{ $staffMember->initial." ".$staffMember->full_name }}</h5>--}}
{{--                                <div class="staff-title mb-2">{{ $staffMember->position }}</div>--}}
{{--                                <p class="card-text small mb-2 text-justify" style="text-align: justify;">{{ $staffMember->bio }}</p>--}}
{{--                                <div class="staff-social">--}}
{{--                                    <a href="#" title="Email"><i class="fas fa-envelope"></i></a>--}}
{{--                                    <a href="#" title="Subject"><i class="fas fa-book"></i></a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

    <!-- School Statistics -->
    <section class="stats-section py-5 text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="stat-item">
                        <h3 class="stat-number plus" data-count="{{$stats->students ?? 1200}}">0</h3>
                        <p class="stat-label">Students</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="stat-item">
                        <h3 class="stat-number plus" data-count="{{$stats->teachers ?? 45}}">0</h3>
                        <p class="stat-label">Qualified Teachers</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3 class="stat-number" data-count="{{$stats->classrooms ?? 32}}">0</h3>
                        <p class="stat-label">Classrooms</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3 class="stat-number plus" data-count="{{$stats->years ?? 25}}">0</h3>
                        <p class="stat-label">Years of Excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News & Events Section -->
    <section class="news-section py-5 bg-light @if(empty($posts)) visually-hidden @endif">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">School News & Events</h2>
                <p class="section-subtitle">Stay Updated with Our Latest Activities</p>
                <div class="divider"></div>
            </div>
            <div class="row g-4">
                @foreach($posts as $post)
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
            <div class="text-center mt-5">
                <a href="{{url('post/news-event/all')}}" class="btn btn-primary px-4">View All News</a>
            </div>
        </div>
    </section>

    <!-- School Gallery -->
    <section class="gallery-section py-5 @if(empty($gallery)) visually-hidden @endif" >
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">School Gallery</h2>
                <p class="section-subtitle">Explore Our Campus Life Through Photos</p>
                <div class="divider"></div>
            </div>
            <div class="position-relative">
                <button class="gallery-arrow left" id="galleryPrev" aria-label="Previous Image"><i class="fas fa-arrow-left"></i></button>
                <button class="gallery-arrow right" id="galleryNext" aria-label="Next Image"><i class="fas fa-arrow-right"></i></button>
                <div class="row g-3" id="galleryRow">
                    @foreach($gallery as $galleryItem)
                        <div class="col-6 col-md-4 col-lg-3 gallery-slide" data-title="{{$galleryItem->title}}">
                            <a href="{{ asset('storage/'.$galleryItem->image_path) }}" data-fancybox="gallery" class="gallery-item">
                                <img src="{{ asset('storage/'.$galleryItem->image_path) }}" class="img-fluid rounded" alt="School Activity">
                                <div class="gallery-overlay">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                                <div class="gallery-title-overlay">{{$galleryItem->title}}</div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Extracurricular Activities -->
    <section class="activities-section py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Extracurricular Activities</h2>
                <p class="section-subtitle">Developing Talents Beyond the Classroom</p>
                <div class="divider"></div>
            </div>
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <div class="activity-item">
                        <div class="activity-icon bg-primary text-white rounded-circle mx-auto mb-3">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <h5>Sports</h5>
                        <p class="small">Football, Athletics, Basketball</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="activity-item">
                        <div class="activity-icon bg-success text-white rounded-circle mx-auto mb-3">
                            <i class="fas fa-music"></i>
                        </div>
                        <h5>Music & Arts</h5>
                        <p class="small">Choir, Band, Drama Club</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="activity-item">
                        <div class="activity-icon bg-info text-white rounded-circle mx-auto mb-3">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h5>STEM Club</h5>
                        <p class="small">Science & Technology</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="activity-item">
                        <div class="activity-icon bg-warning text-white rounded-circle mx-auto mb-3">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h5>Community Service</h5>
                        <p class="small">Outreach Programs</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* School-specific theme colors */
        :root {
            --school-primary: #1a4f8c;
            --school-secondary: #e67e22;
            --school-accent: #27ae60;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--school-primary) 0%, #2980b9 100%);
        }

        .program-badge {
            background: var(--school-secondary);
        }

        .stats-section {
            background: linear-gradient(135deg, var(--school-primary) 0%, #34495e 100%);
        }

        .activity-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
    </style>
@endpush
