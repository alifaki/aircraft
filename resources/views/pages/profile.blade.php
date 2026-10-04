@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="mb-4 overflow-hidden position-relative">
        <div class="px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="#" class="fa fa-home"> Home</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">My Profile</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-xxl-4 col-md-4">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white">Profile Information</h5>
                    <button class="btn btn-sm btn-light" id="editProfileBtn">
                        <i class="ti ti-edit"></i> Edit
                    </button>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="{{ auth()->user()->staff->photo_path ? asset('storage/' . auth()->user()->staff->photo_path) : asset('samis-asset/image/default.png') }}" 
                                 class="rounded-circle border" 
                                 alt="Profile Photo" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                            <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
                                    style="width: 32px; height: 32px;"
                                    onclick="document.getElementById('photoInput').click()">
                                <i class="ti ti-camera fs-4"></i>
                            </button>
                            <input type="file" id="photoInput" class="d-none" accept="image/*">
                        </div>
                        <h4 class="mt-3 mb-1">{{ auth()->user()->staff->first_name }} {{ auth()->user()->staff->last_name }}</h4>
                        <p class="text-muted mb-2">{{ auth()->user()->staff->position }}</p>
                        <span class="badge bg-{{ auth()->user()->status === 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst(auth()->user()->status) }}
                        </span>
                    </div>

                    <div class="profile-details">
                        <div class="row mb-2">
                            <div class="col-4 fw-bold text-muted">Username:</div>
                            <div class="col-8">{{ auth()->user()->username }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-bold text-muted">Employee No:</div>
                            <div class="col-8">{{ auth()->user()->staff->employee_number }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-bold text-muted">Email:</div>
                            <div class="col-8">{{ auth()->user()->staff->email ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-bold text-muted">Phone:</div>
                            <div class="col-8">{{ auth()->user()->staff->phone }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-bold text-muted">Branch:</div>
                            <div class="col-8">{{ auth()->user()->staff->branch->branch_name ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-bold text-muted">Section:</div>
                            <div class="col-8">{{ auth()->user()->staff->section->name ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-bold text-muted">Role:</div>
                            <div class="col-8">{{ auth()->user()->role->name ?? 'N/A' }}</div>
                        </div>
                        @if(auth()->user()->staff->bio)
                        <div class="row mb-2">
                            <div class="col-4 fw-bold text-muted">Bio:</div>
                            <div class="col-8">{{ auth()->user()->staff->bio }}</div>
                        </div>
                        @endif
                    </div>

                    <!-- Edit Profile Form (Hidden by default) -->
                    <form id="profileForm" class="visually-hidden" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="edit_first_name" name="first_name" class="input-field form-control" 
                                           value="{{ auth()->user()->staff->first_name }}" placeholder=" " required>
                                    <label for="edit_first_name" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        First Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="edit_last_name" name="last_name" class="input-field form-control" 
                                           value="{{ auth()->user()->staff->last_name }}" placeholder=" " required>
                                    <label for="edit_last_name" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        Last Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="email" id="edit_email" name="email" class="input-field form-control" 
                                           value="{{ auth()->user()->staff->email }}" placeholder=" ">
                                    <label for="edit_email" class="input-label">
                                        <i class="ti ti-mail me-1 fs-3 text-primary"></i>
                                        Email Address
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="text" id="edit_phone" name="phone" class="input-field form-control" 
                                           value="{{ auth()->user()->staff->phone }}" placeholder=" " required>
                                    <label for="edit_phone" class="input-label">
                                        <i class="ti ti-phone me-1 fs-3 text-primary"></i>
                                        Phone Number <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <textarea id="edit_bio" name="bio" class="input-field form-control" placeholder=" " rows="3">{{ auth()->user()->staff->bio }}</textarea>
                                    <label for="edit_bio" class="input-label">
                                        <i class="ti ti-info-circle me-1 fs-3 text-primary"></i>
                                        Bio
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="file" id="edit_photo_path" name="photo_path" class="input-field form-control" accept="image/*">
                                    <label for="edit_photo_path" class="input-label">
                                        <i class="ti ti-camera me-1 fs-3 text-primary"></i>
                                        Profile Photo
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-sm btn-secondary" id="cancelEditBtn">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ti ti-device-floppy"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password & Activity -->
        <div class="col-xxl-8 col-md-8">
            <!-- Change Password Card -->
            <div class="card border-bottom border-info mb-4">
                <div class="card-header bg-primary text-white py-2 px-4">
                    <h5 class="mb-0 text-white">Change Password</h5>
                </div>
                <div class="card-body">
                    <form id="changePasswordForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="password" id="current_password" name="current_password" class="input-field form-control" placeholder=" " required>
                                    <label for="current_password" class="input-label">
                                        <i class="ti ti-lock me-1 fs-3 text-primary"></i>
                                        Current Password <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="password" id="new_password" name="new_password" class="input-field form-control" placeholder=" " required>
                                    <label for="new_password" class="input-label">
                                        <i class="ti ti-lock me-1 fs-3 text-primary"></i>
                                        New Password <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="password" id="confirm_password" name="confirm_password" class="input-field form-control" placeholder=" " required>
                                    <label for="confirm_password" class="input-label">
                                        <i class="ti ti-lock me-1 fs-3 text-primary"></i>
                                        Confirm Password <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="password-requirements mb-3">
                            <small class="text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                Password must be at least 8 characters long
                            </small>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ti ti-key me-1"></i> Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Activity Card -->
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white py-2 px-4">
                    <h5 class="mb-0 text-white">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        <div class="activity-item d-flex mb-3">
                            <div class="activity-icon me-3">
                                <i class="ti ti-login text-success"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="mb-1">Login</h6>
                                <p class="text-muted mb-0">You logged in to the system</p>
                                <small class="text-muted">{{ now()->format('M j, Y g:i A') }}</small>
                            </div>
                        </div>
                        <div class="activity-item d-flex mb-3">
                            <div class="activity-icon me-3">
                                <i class="ti ti-user-check text-info"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="mb-1">Profile Updated</h6>
                                <p class="text-muted mb-0">Your profile information was updated</p>
                                <small class="text-muted">{{ auth()->user()->updated_at->format('M j, Y g:i A') }}</small>
                            </div>
                        </div>
                        <!-- Add more activity items as needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editProfileBtn = document.getElementById('editProfileBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const profileDetails = document.querySelector('.profile-details');
        const profileForm = document.getElementById('profileForm');
        const changePasswordForm = document.getElementById('changePasswordForm');
        const photoInput = document.getElementById('photoInput');

        // Toggle edit profile form
        editProfileBtn.addEventListener('click', function() {
            profileDetails.classList.add('visually-hidden');
            profileForm.classList.remove('visually-hidden');
            editProfileBtn.classList.add('visually-hidden');
        });

        cancelEditBtn.addEventListener('click', function() {
            profileForm.classList.add('visually-hidden');
            profileDetails.classList.remove('visually-hidden');
            editProfileBtn.classList.remove('visually-hidden');
        });

        // Profile photo upload
        photoInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const formData = new FormData();
                formData.append('photo_path', e.target.files[0]);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                // Show loading state
                const originalText = editProfileBtn.innerHTML;
                editProfileBtn.innerHTML = '<i class="ti ti-loader fa-spin"></i> Uploading...';
                editProfileBtn.disabled = true;

                fetch('/web/v1/profile/update-photo', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        // Update profile image
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.querySelector('.rounded-circle').src = e.target.result;
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    } else {
                        toastr.error(data.message);
                    }
                })
                .catch(error => {
                    toastr.error('Failed to upload photo');
                })
                .finally(() => {
                    editProfileBtn.innerHTML = originalText;
                    editProfileBtn.disabled = false;
                });
            }
        });

        // Profile form submission
        profileForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            try {
                submitBtn.innerHTML = '<i class="ti ti-loader fa-spin"></i> Saving...';
                submitBtn.disabled = true;

                const response = await fetch('/web/v1/profile/update', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    toastr.success(data.message);
                    // Reload page to reflect changes
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(data.message);
                    if (data.errors) {
                        handleServerErrors(data.errors);
                    }
                }
            } catch (error) {
                toastr.error('Failed to update profile');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Change password form submission
        changePasswordForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Validate passwords match
            if (data.new_password !== data.confirm_password) {
                toastr.error('New password and confirm password do not match');
                return;
            }

            // Validate password length
            if (data.new_password.length < 8) {
                toastr.error('Password must be at least 8 characters long');
                return;
            }

            try {
                submitBtn.innerHTML = '<i class="ti ti-loader fa-spin"></i> Changing...';
                submitBtn.disabled = true;

                const response = await fetch('/web/v1/profile/change-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    toastr.success(result.message);
                    this.reset();
                } else {
                    toastr.error(result.message);
                    if (result.errors) {
                        handleServerErrors(result.errors);
                    }
                }
            } catch (error) {
                toastr.error('Failed to change password');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Phone number masking
        $.mask.definitions['~']='[+-]';
        $('#edit_phone').mask('255999999999');
    });
</script>
@endpush