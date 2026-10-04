
<!DOCTYPE html>
<html lang="en" class="ega-inspired">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->metadata['school_name'] ?? 'Primary & Secondary School' }} - {{ $page->title ?? 'Home' }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/web/css/style.css') }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('samis-assets/image/'.config('app.samis.brandLogo')) }}">
    <!-- Fancybox CSS for image preview -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    <!-- School Theme Styles -->
    <style>
        :root {
            --school-primary: #1a4f8c;
            --school-secondary: #e67e22;
            --school-accent: #27ae60;
            --school-gold: #f39c12;
        }

        .banner-section {
            /*position: relative;*/
            /*background: linear-gradient(135deg, #1a4f8c 0%, #2980b9 100%);*/
            /*overflow: hidden;*/
            border-bottom: solid 1px var(--school-primary);
        }

        .banner-company-name {
            font-size: 2.3rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--school-primary);
            text-shadow: 0 0 18px rgba(255,255,255,0.5), 0 0 32px rgba(255,255,255,0.3);
        }

        .banner-company-slogan {
            font-size: 1.1rem;
            color: #f39c12;
            text-shadow: 0 0 10px rgba(243,156,18,0.3);
        }

        .banner-logo, .banner-zanzibar-logo {
            max-height: 80px;
            max-width: 100%;
            filter: drop-shadow(0 0 16px rgba(255,255,255,0.5));
        }

        .top-bar {
            background: var(--school-primary);
        }

        .nav-link {
            color: #111212 !important;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--school-gold) !important;
        }

        .btn-primary {
            background: var(--school-primary);
            border-color: var(--school-primary);
        }

        .btn-primary:hover {
            background: #153964;
            border-color: #153964;
        }

        /* School-specific banner background */
        .banner-section .banner-bg-svg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .banner-section .banner-glow {
            position: absolute;
            z-index: 1;
            pointer-events: none;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 90vw;
            height: 200px;
            filter: blur(40px) brightness(1.2);
            opacity: 0.7;
        }

        /* Mobile styles */
        @media (max-width: 991.98px) {
            .banner-section {
                display: none !important;
            }
            .navbar-college-mobile {
                display: flex !important;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                padding: 0.2rem 0 0.2rem 0;
            }
            .navbar-college-mobile .navbar-college-left {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                justify-content: center;
                flex: 1 1 auto;
                min-width: 0;
            }
            .navbar-college-mobile .banner-company-name {
                font-size: 1.1rem;
                font-weight: bold;
                color: #1e1d1d;
                text-shadow: 0 0 8px rgba(255,255,255,0.5), 0 0 12px rgba(255,255,255,0.3);
                letter-spacing: 0.5px;
                text-transform: uppercase;
                margin-bottom: 0.1rem;
                line-height: 1.1;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 90vw;
            }
            .navbar-college-mobile .banner-company-slogan {
                font-size: 0.85rem;
                color: #f39c12;
                text-shadow: 0 0 4px rgba(243,156,18,0.3);
                margin-bottom: 0;
                line-height: 1;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 90vw;
            }
        }

        @media (min-width: 992px) {
            .navbar-college-mobile {
                display: none !important;
            }
            .banner-section {
                display: block !important;
            }
        }

        .navbar-toggler {
            display: none;
        }
        @media (max-width: 991.98px) {
            .navbar-toggler {
                display: block !important;
            }
        }

        /* School badge styles */
        .school-badge {
            background: var(--school-gold);
            color: #2c3e50;
            font-weight: bold;
            padding: 1px 5px 1px 5px;
            border-radius: 1rem;
            font-size: 0.8rem;
        }
    </style>
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aviation-public.css') }}">
</head>
<body class="operations-public">
<!-- Top Bar -->
<div class="top-bar">
    <div class="container-fluid">
        <div class="row align-items-center text-center text-md-start">
            <div class="col-md-6 contact-info d-flex flex-wrap align-items-center justify-content-center justify-content-md-start mb-2 mb-md-0">
                <span class="me-3"><i class="fas fa-phone-alt me-1"></i> {{ $settings->metadata['phone'] ?? '+255 XXX XXX XXX' }}</span>
                <span><i class="fas fa-envelope me-1"></i> {{ $settings->metadata['email'] ?? 'info@school.ac.tz' }}</span>
                <span class="ms-3 d-none d-md-inline"><i class="fas fa-map-marker-alt me-1"></i>{{ $settings->metadata['address'] ?? 'P. O. BOX XXX, City, Tanzania' }}</span>
            </div>
            <div class="col-md-6  d-flex flex-wrap align-items-center justify-content-center justify-content-md-end mb-2 mb-md-0 apply-portal" style="font-size: 12px;">
                @if(isset($settings->metadata['school_badge']))
                    <span class="school-badge me-2">{{$settings->metadata['school_badge']}}</span>
                @endif
                @if(isset($settings->metadata['portal_url']))
                    <a href="{{url($settings->metadata['portal_url']??'#')}}" target="_blank" class="ms-1 text-white" aria-label="Login" style="text-decoration: none;background: none;">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        Student Portal
                    </a>
                @endif
                @if($settings->metadata['application_status'] == 'open')
                    <a href="{{url($settings->metadata['admission_url']??'#')}}" class="ms-2 text-white" aria-label="Apply" style="text-decoration: none;background: none;">
                        <i class="fas fa-paper-plane me-1 fa-shake" style="animation-iteration-count: 4;"></i>
                        Apply Now
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Banner Section -->
<div class="banner-section py-2" style="position: relative; overflow: hidden;">
    <!-- School-themed SVG Background -->
    <svg class="banner-bg-svg" width="100%" height="180" viewBox="0 0 1920 180" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <defs>
            <linearGradient id="bandSchool1" x1="0" y1="0" x2="1920" y2="180" gradientUnits="userSpaceOnUse">
                <stop stop-color="#3498db" />
                <stop offset="1" stop-color="#1a4f8c" />
            </linearGradient>
            <linearGradient id="bandSchool2" x1="0" y1="0" x2="1920" y2="180" gradientUnits="userSpaceOnUse">
                <stop stop-color="#e67e22" />
                <stop offset="1" stop-color="#d35400" />
            </linearGradient>
        </defs>
        <!-- School-themed shapes -->
        <path d="M0 40 Q 480 80 960 40 T 1920 40 L1920 60 Q 1440 100 960 60 T 0 60Z" fill="url(#bandSchool1)" fill-opacity="0.15"/>
        <path d="M0 100 Q 600 140 1200 100 T 1920 100 L1920 120 Q 1320 160 720 120 T 0 120Z" fill="url(#bandSchool2)" fill-opacity="0.1"/>

        <!-- School icons as decorative elements -->
        <circle cx="300" cy="60" r="25" fill="#f39c12" fill-opacity="0.3"/>
        <rect x="500" y="40" width="40" height="30" rx="5" fill="#27ae60" fill-opacity="0.3"/>
        <polygon points="800,30 820,60 780,60" fill="#e74c3c" fill-opacity="0.3"/>
        <circle cx="1100" cy="50" r="20" fill="#9b59b6" fill-opacity="0.3"/>
    </svg>

    <div class="banner-glow"></div>

    <div class="container-fluid">
        <div class="row align-items-center text-center">
            <!-- Left: School Logo -->
            <div class="col-4 col-md-3 text-start text-md-start">
                <img src="{{ asset('samis-asset/image/'.config('app.samis.brandLogo')) }}" alt="School Logo" class="banner-logo" style="max-height: 80px; max-width: 100%;">
            </div>
            <!-- Center: School Name and Motto -->
            <div class="col-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                    <span class="fw-bold text-uppercase banner-company-name" style="font-size:1.7rem;line-height:1.1; letter-spacing: 1px;">
                        {{ strtoupper($settings->metadata['school_name'] ?? 'Primary & Secondary School') }}
                    </span>
                <span class="banner-company-slogan" style="font-size:1.1rem;line-height:1;">
                        "{{ $settings->metadata['school_motto'] ?? 'Education for Excellence' }}"
                    </span>
                <small class="text-primary mt-1">{{$settings->metadata['study_level']??''}}</small>
            </div>
            <!-- Right: Accreditation Logo -->
            <div class="col-4 col-md-3 text-end text-md-end">
                <img src="{{ asset('samis-asset/image/smz.png') }}" alt="Ministry of Education" class="banner-zanzibar-logo" style="max-height: 80px; max-width: 100%;">
            </div>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg custom-navbar shadow-sm sticky-top">
    <div class="container-fluid">
        <!-- Mobile: School name and motto -->
        <div class="navbar-college-mobile d-flex d-lg-none">
            <div class="navbar-college-left">
                    <span class="banner-company-name">
                        {{ strtoupper($settings->metadata['school_name'] ?? 'Primary & Secondary School') }}
                    </span>
                    <span class="banner-company-slogan">
                        "{{ $settings->metadata['school_motto'] ?? 'Education for Excellence' }}"
                    </span>
            </div>
            <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
            <!-- Desktop: menu only, no toggle -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                @if($menu)
                    <ul class="navbar-nav mx-auto align-items-lg-center" style="font-size: 1.08rem; font-weight: 500;">
                        @foreach($menu->items as $item)
                            @if($item->children->count() > 0)
                                <li class="nav-item dropdown has-submenu">
                                    <a class="nav-link dropdown-toggle" href="#" id="{{ Str::slug($item->title) }}Dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        @if($item->icon)
                                            <i class="{{ $item->icon }} me-1 d-lg-none"></i>
                                        @endif
                                        {{ $item->title }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="{{ Str::slug($item->title) }}Dropdown">
                                        @foreach($item->children as $child)
                                            @if($child->children->count() > 0)
                                                <li class="dropdown-submenu">
                                                    @php
                                                        $childHref = '#';
                                                        if ($child->type == 'content') {
                                                            $childHref = url('/detail/'.$child->slug);
                                                        } elseif ($child->type == 'media' && $child->media_file) {
                                                            $childHref = asset('storage/'.$child->media_file);
                                                        } else {
                                                            $childHref = url($child->url);
                                                        }
                                                    @endphp
                                                    <a class="dropdown-item"
                                                       @if($child->type == 'media' && $child->media_file)
                                                           href="{{ $childHref }}" target="_blank"
                                                       @else
                                                           href="{{ $childHref }}"
                                                       @endif
                                                    >
                                                        {{ $child->title }}
                                                        @if($child->type == 'media' && $child->media_file)
                                                            <span class="ms-1"><i class="fa fa-file"></i></span>
                                                        @endif
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        @foreach($child->children as $subchild)
                                                            @php
                                                                $subchildHref = '#';
                                                                if ($subchild->type == 'content') {
                                                                    $subchildHref = url('/detail/'.$subchild->slug);
                                                                } elseif ($subchild->type == 'media' && $subchild->media_file) {
                                                                    $subchildHref = asset('storage/'.$subchild->media_file);
                                                                } else {
                                                                    $subchildHref = url($subchild->url);
                                                                }
                                                            @endphp
                                                            <li>
                                                                <a class="dropdown-item"
                                                                   @if($subchild->type == 'media' && $subchild->media_file)
                                                                       href="{{ $subchildHref }}" target="_blank"
                                                                   @else
                                                                       href="{{ $subchildHref }}"
                                                                   @endif
                                                                >
                                                                    {{ $subchild->title }}
                                                                    @if($subchild->type == 'media' && $subchild->media_file)
                                                                        <span class="ms-1"><i class="fa fa-file"></i></span>
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @else
                                                @if($child->is_divider)
                                                    <li><hr class="dropdown-divider"></li>
                                                @else
                                                    @php
                                                        $childHref = '#';
                                                        if ($child->type == 'content') {
                                                            $childHref = url('/detail/'.$child->slug);
                                                        } elseif ($child->type == 'media' && $child->media_file) {
                                                            $childHref = asset('storage/'.$child->media_file);
                                                        } else {
                                                            $childHref = url($child->url);
                                                        }
                                                    @endphp
                                                    <li>
                                                        <a class="dropdown-item"
                                                           @if($child->type == 'media' && $child->media_file)
                                                               href="{{ $childHref }}" target="_blank"
                                                           @else
                                                               href="{{ $childHref }}"
                                                           @endif
                                                        >
                                                            {{ $child->title }}
                                                            @if($child->type == 'media' && $child->media_file)
                                                                <span class="ms-1"><i class="fa fa-file"></i></span>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                @php
                                    $itemHref = '#';
                                    if ($item->url == null) {
                                        $itemHref = url('/');
                                    } elseif ($item->type == 'content') {
                                        $itemHref = url('/detail/'.$item->slug);
                                    } elseif ($item->type == 'media' && $item->media_file) {
                                        $itemHref = asset('storage/'.$item->media_file);
                                    } else {
                                        $itemHref = url($item->url);
                                    }
                                    $isActive = request()->url() == $itemHref ? 'active' : '';
                                @endphp
                                <li class="nav-item">
                                    <a class="nav-link {{ $isActive }}"
                                       @if($item->type == 'media' && $item->media_file)
                                           href="{{ $itemHref }}" target="_blank"
                                       @else
                                           href="{{ $itemHref }}"
                                       @endif
                                    >
                                        @if($item->icon)
                                            <i class="{{ $item->icon }} me-1 d-lg-none"></i>
                                        @endif
                                        {{ $item->title }}
                                        @if($item->type == 'media' && $item->media_file)
                                            <span class="ms-1"><i class="fa fa-file"></i></span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </nav>

<!-- Content -->
@yield('content')

<!-- School CTA Section -->
<section class="cta-section py-5 text-white" style="background: linear-gradient(135deg, #1a4f8c 0%, #2980b9 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h3 class="mb-3">Ready to Join Our School Community?</h3>
                <p class="mb-0">{{$settings->metadata['admission_statement']??'Enroll your child for quality education from Babyclass to Form Six. Limited spaces available'}}</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                @if($settings->metadata['application_status'] == 'open')
                    <a href="{{url($settings->metadata['admission_url']??'#')}}" class="btn btn-warning btn-lg me-2">Apply Now</a>
                @endif
                <a href="{{ url('contact-us') }}" class="btn btn-outline-light btn-lg">Visit Us</a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <img src="{{ asset('samis-asset/image/'.config('app.samis.brandLogo')) }}" alt="{{ $settings->metadata['school_name'] ?? 'School' }}" class="mb-3" height="60">
                <span class="mb-3">{{ $settings->metadata['short_about']??'' }}</span>
                <div class="social-icons">
                    <a href="#" class="text-white me-2" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-2" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-2" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white" target="_blank"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="footer-title mb-4">Quick link</h5>
                <ul class="list-unstyled">
                    @foreach($footerLinks->items as $r)
                        <li class="mb-2">
                            <i class="fas fa-link me-2"></i>
                            <a href="{{$r->url}}">{{$r->title}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-4 col-md-6">
                <h5 class="footer-title mb-4">Contact Information</h5>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $settings->metadata['address'] ?? 'P. O. BOX XXX, City, Tanzania' }}
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-phone-alt me-2"></i>
                        {{ $settings->metadata['phone'] ?? '+255 XXX XXX XXX' }}
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-envelope me-2"></i>
                        {{ $settings->metadata['email'] ?? 'info@school.ac.tz' }}
                    </li>
                    <li>
                        <i class="fas fa-clock me-2"></i>
                        {{ $settings->metadata['working_hours'] ?? 'Mon-Fri: 7:30 AM - 4:00 PM' }}
                    </li>
                </ul>
            </div>
        </div>
        <hr class="my-4 bg-secondary">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">&copy; {{ date('Y') }} {{ $settings->metadata['school_name'] ?? 'School' }}. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0">Accredited by Ministry of Education</p>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<a href="#" class="back-to-top btn btn-primary rounded-circle shadow">
    <i class="fas fa-arrow-up"></i>
</a>

<!-- Scripts remain the same -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="{{ asset('assets/web/js/script.js') }}"></script>
@stack('scripts')
</body>
</html>
