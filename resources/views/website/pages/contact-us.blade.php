@extends('website.templates.layout')
@section('content')
    <section class="contact-us-section py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
                    <div class="text-center mb-4">
                        <h1 class="fw-bold display-6 mb-2" style="color: #1a237e;">Contact Us</h1>
                        <div class="mx-auto" style="width: 80px; height: 4px; background: #1976d2; border-radius: 2px;"></div>
                        <p class="mt-3" style="font-size: 1.15rem; color: #333;">
                            Have questions or need more information? Reach out to us using the form below or through our contact details.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row g-5 justify-content-center">
                <!-- Contact Details -->
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <h4 class="mb-4" style="color: #1976d2;"><i class="fas fa-info-circle me-2"></i>Contact Information</h4>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-3">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                    <strong>Address:</strong> {{ $settings->metadata['address'] ?? 'P. O. BOX 4823, Kariakoo, Zanzibar, Tanzania' }}
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-phone-alt me-2 text-primary"></i>
                                    <strong>Phone 1:</strong> <a href="tel:{{ $settings->metadata['phone'] ?? '+255774579698' }}" class="text-decoration-none text-dark">{{ $settings->metadata['phone'] ?? '+255774579698' }}</a>
                                </li>
                                @if(isset($settings->metadata['phone2']) && $settings->metadata['phone2'] != '')
                                    <li class="mb-3">
                                        <i class="fas fa-phone-alt me-2 text-primary"></i>
                                        <strong>Phone 2:</strong> <a href="tel:{{ $settings->metadata['phone2'] ?? '+25574579698' }}" class="text-decoration-none text-dark">{{ $settings->metadata['phone2'] ?? '+25574579698' }}</a>
                                    </li>
                                @endif
                                @if(isset($settings->metadata['phone3']) && $settings->metadata['phone3'] != '')
                                    <li class="mb-3">
                                        <i class="fas fa-phone-alt me-2 text-primary"></i>
                                        <strong>Phone 3:</strong> <a href="tel:{{ $settings->metadata['phone3'] ?? '+25574579698' }}" class="text-decoration-none text-dark">{{ $settings->metadata['phone3'] ?? '+25574579698' }}</a>
                                    </li>
                                @endif
                                <li class="mb-3">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    <strong>Email:</strong> <a href="mailto:{{ $settings->metadata['email'] ?? 'info@semis.li' }}" class="text-decoration-none text-dark">{{ $settings->metadata['email'] ?? 'info@semis.li' }}</a>
                                </li>
                                <li>
                                    <i class="fas fa-clock me-2 text-primary"></i>
                                    <strong>Hours:</strong> {{ $settings->metadata['working_hours'] ?? 'Mon-Fri: 8:00 AM - 5:00 PM' }}
                                </li>
                            </ul>
                            <div class="social-icons mt-3">
                                <a href="{{$settings->metadata['facebook']??'#'}}" class="text-primary me-2" target="_blank"><i class="fab fa-facebook-f fa-lg"></i></a>
                                <a href="{{$settings->metadata['twitter']??'#'}}" class="text-primary me-2" target="_blank"><i class="fab fa-twitter fa-lg"></i></a>
                                <a href="{{$settings->metadata['instagram']??'#'}}" class="text-primary me-2" target="_blank"><i class="fab fa-instagram fa-lg"></i></a>
                                <a href="{{$settings->metadata['linkedin']??'#'}}" class="text-primary" target="_blank"><i class="fab fa-linkedin-in fa-lg"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Contact Form -->
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <h4 class="mb-4" style="color: #1976d2;"><i class="fas fa-paper-plane me-2"></i>Send Us a Message</h4>
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('contact.submit') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Your Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="service" class="form-label">Service Interested In</label>
                                    <select class="form-select" id="service" required>
                                        <option value="">Select a service</option>
                                        <option value="software">Custom Software Development</option>
                                        <option value="samis">SAMIS - School Management</option>
                                        <option value="inventory">Inventory Management System</option>
                                        <option value="marketplace">Online Marketplace</option>
                                        <option value="restaurant">Restaurant/Cafe Management</option>
                                        <option value="printing">Digital Printing</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-none">
                                    <label for="honeypot">Leave this field empty</label>
                                    <input type="text" name="honeypot" id="honeypot">
                                </div>
                                <button type="submit" class="btn btn-primary px-4 py-2">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Optional: Google Map Embed -->
            <div class="row justify-content-center mt-5">
                <div class="col-lg-10">
                    <div class="ratio ratio-16x9 rounded shadow-sm overflow-hidden">
                        <iframe src="{{$settings->metadata['map_link']??'#'}}" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
