@extends('website.templates.layout')
@section('content')
    <section class="admission-section py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-10">
                    <div class="text-center mb-4">
                        <h1 class="fw-bold display-6 mb-2" style="color: #1a237e;">Student Admission Portal</h1>
                        <div class="mx-auto" style="width: 80px; height: 4px; background: #1976d2; border-radius: 2px;"></div>
                        <p class="mt-3" style="font-size: 1.15rem; color: #333;">
                            Apply for your child's admission from KG1 to Form 1. Start their educational journey with us.
                        </p>
                    </div>
                </div>
            </div>

            @if($settings->metadata['application_status'] == 'open')
                <div class="row g-5 justify-content-center">
                    <!-- Admission Information -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <h4 class="mb-4" style="color: #1976d2;">
                                    <i class="fas fa-info-circle me-2"></i>Admission Info
                                    <button class="btn btn-sm btn-outline-primary float-end" type="button" data-bs-toggle="collapse" data-bs-target="#requirementsCollapse">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </h4>

                                <div class="collapse" id="requirementsCollapse">
                                    <div class="small">
                                        <p><strong>For KG1 to Form 1 Admissions</strong></p>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-check-circle me-2 text-success small"></i>
                                                Age-appropriate class placement
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check-circle me-2 text-success small"></i>
                                                Previous school records required
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check-circle me-2 text-success small"></i>
                                                Birth certificate needed
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check-circle me-2 text-success small"></i>
                                                Parent/guardian information
                                            </li>
                                            <li class="mb-3">
                                                <i class="fas fa-check-circle me-2 text-success small"></i>
                                                Emergency contact details
                                            </li>
                                        </ul>

                                        <div class="alert alert-info py-2">
                                            <small>
                                                <i class="fas fa-clock me-1"></i>
                                                <strong>Deadline:</strong> {{ $settings->metadata['application_deadline'] ?? '31st December 2024' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admission Form -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h4 class="mb-4" style="color: #1976d2;">
                                    <i class="fas fa-user-graduate me-2"></i>Student Admission Form
                                </h4>
                                <form id="admission-form" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <!-- Student Information -->
                                    <div class="section-header mb-3">
                                        <h6 class="text-primary mb-0">
                                            <i class="fas fa-child me-2"></i>Student Information
                                        </h6>
                                        <hr class="mt-2">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                            <div class="invalid-feedback" id="first_name_error"></div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="middle_name" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                                            <div class="invalid-feedback" id="middle_name_error"></div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                            <div class="invalid-feedback" id="last_name_error"></div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                            <div class="invalid-feedback" id="date_of_birth_error"></div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select class="form-control" id="gender" name="gender" required>
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            <div class="invalid-feedback" id="gender_error"></div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nationality" name="nationality" value="{{ old('nationality', 'Tanzanian') }}" required>
                                            <div class="invalid-feedback" id="nationality_error"></div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="id_number" class="form-label">Birth Certificate Number</label>
                                            <input type="text" class="form-control" id="id_number" name="id_number" value="{{ old('id_number') }}">
                                            <div class="invalid-feedback" id="id_number_error"></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="student_address" class="form-label">Student's Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="student_address" name="student_address" rows="2" required>{{ old('student_address') }}</textarea>
                                            <div class="invalid-feedback" id="student_address_error"></div>
                                        </div>
                                    </div>

                                    <!-- Parent/Guardian Information -->
                                    <div class="section-header mb-3 mt-4">
                                        <h6 class="text-primary mb-0">
                                            <i class="fas fa-users me-2"></i>Parent/Guardian Information
                                        </h6>
                                        <hr class="mt-2">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="parent_email" class="form-label">Parent Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="parent_email" name="parent_email" value="{{ old('parent_email') }}" required>
                                            <div class="invalid-feedback" id="parent_email_error"></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="parent_phone" class="form-label">Parent Phone <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}" required>
                                            <div class="invalid-feedback" id="parent_phone_error"></div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="parent_address" class="form-label">Parent/Guardian Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="parent_address" name="parent_address" rows="2" required>{{ old('parent_address') }}</textarea>
                                        <div class="invalid-feedback" id="parent_address_error"></div>
                                    </div>

                                    <!-- Academic Information -->
                                    <div class="section-header mb-3 mt-4">
                                        <h6 class="text-primary mb-0">
                                            <i class="fas fa-graduation-cap me-2"></i>Academic Information
                                        </h6>
                                        <hr class="mt-2">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="academic_level_id" class="form-label">Academic Level <span class="text-danger">*</span></label>
                                            <select class="form-control" id="academic_level_id" name="academic_level_id" required>
                                                <option value="">Select Academic Level</option>
                                                @foreach($academicLevels as $level)
                                                    <option value="{{ $level->encrypted_id }}" {{ old('academic_level_id') == $level->encrypted_id ? 'selected' : '' }}>
                                                        {{ $level->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="academic_level_id_error"></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="student_class_id" class="form-label">Class Applying For <span class="text-danger">*</span></label>
                                            <select class="form-control" id="student_class_id" name="student_class_id" required>
                                                <option value="">Select Class</option>
                                                @foreach($studentClasses as $class)
                                                    <option value="{{ $class->encrypted_id }}" {{ old('student_class_id') == $class->encrypted_id ? 'selected' : '' }}>
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="student_class_id_error"></div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="previous_school" class="form-label">Previous School <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="previous_school" name="previous_school" value="{{ old('previous_school') }}" required>
                                            <div class="invalid-feedback" id="previous_school_error"></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="year_completed" class="form-label">Year Completed <span class="text-danger">*</span></label>
                                            <select class="form-control" id="year_completed" name="year_completed" required>
                                                <option value="">Select Year of Completion</option>
                                                @for($i = date("Y"); $i > 2020; $i--)
                                                    <option value="{{ $i }}" {{ old('year_completed') == $i ? 'selected' : '' }}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                            <div class="invalid-feedback" id="year_completed_error"></div>
                                        </div>
                                    </div>

                                    <!-- Emergency Contact -->
                                    <div class="section-header mb-3 mt-4">
                                        <h6 class="text-primary mb-0">
                                            <i class="fas fa-phone-alt me-2"></i>Emergency Contact
                                        </h6>
                                        <hr class="mt-2">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="emergency_contact_name" class="form-label">Contact Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required>
                                            <div class="invalid-feedback" id="emergency_contact_name_error"></div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="emergency_contact_phone" class="form-label">Contact Phone <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" required>
                                            <div class="invalid-feedback" id="emergency_contact_phone_error"></div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="emergency_contact_relationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" required>
                                            <div class="invalid-feedback" id="emergency_contact_relationship_error"></div>
                                        </div>
                                    </div>

                                    <!-- Security Fields -->
                                    <div class="d-none">
                                        <label for="honeypot">Leave this field empty</label>
                                        <input type="text" name="honeypot" id="honeypot">
                                        <input type="hidden" name="timestamp" id="timestamp" value="{{ time() }}">
                                    </div>

                                    <div class="form-check mb-4 mt-3">
                                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I confirm that all information provided is accurate and agree to the
                                            <a href="{{ url('details/terms') }}" target="_blank">terms and conditions</a>
                                        </label>
                                        <div class="invalid-feedback" id="terms_error"></div>
                                    </div>

                                    <!-- Success/Error Messages -->
                                    <div id="alert-container"></div>

                                    <button type="submit" class="btn btn-primary px-4 py-2" id="submit-btn">
                                        <span id="submit-text">Submit Application</span>
                                        <div id="submit-spinner" class="spinner-border spinner-border-sm d-none" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Modal -->
                <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel">Complete Admission Payment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="payment-alert-container"></div>

                                <div class="text-center mb-4">
                                    <h6 class="text-muted">Application Fee</h6>
                                    <h3 class="text-primary" id="payment-amount">TZS 0</h3>
                                    <p class="text-muted small" id="payment-description">Admission Application Fee</p>
                                    <p class="small text-info" id="application-number"></p>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Select Payment Method</label>
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-outline-primary text-start py-3 payment-method" data-provider="azampesa">
                                            <i class="fas fa-mobile-alt me-3"></i>
                                            <strong>AzamPesa</strong>
                                            <small class="d-block text-muted">Pay with your AzamPesa account</small>
                                        </button>
                                        <button type="button" class="btn btn-outline-success text-start py-3 payment-method" data-provider="mpesa">
                                            <i class="fas fa-sim-card me-3"></i>
                                            <strong>M-Pesa</strong>
                                            <small class="d-block text-muted">Pay with your M-Pesa account</small>
                                        </button>
                                        <button type="button" class="btn btn-outline-warning text-start py-3 payment-method" data-provider="tigopesa">
                                            <i class="fas fa-signal me-3"></i>
                                            <strong>TigoPesa</strong>
                                            <small class="d-block text-muted">Pay with your TigoPesa account</small>
                                        </button>
                                        <button type="button" class="btn btn-outline-info text-start py-3 payment-method" data-provider="airtelmoney">
                                            <i class="fas fa-wifi me-3"></i>
                                            <strong>Airtel Money</strong>
                                            <small class="d-block text-muted">Pay with your Airtel Money account</small>
                                        </button>
                                    </div>
                                </div>

                                <!-- Phone Number Input -->
                                <div class="mb-3" id="phone-input-section">
                                    <label for="payment-phone" class="form-label">Enter your phone number</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+255</span>
                                        <input type="tel" class="form-control" id="payment-phone" placeholder="712345678" maxlength="9" value="{{ old('parent_phone') }}">
                                    </div>
                                    <div class="invalid-feedback" id="phone-error"></div>
                                </div>

                                <!-- Payment Status -->
                                <div id="payment-status" style="display: none;">
                                    <div class="alert alert-info text-center">
                                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                        <span id="status-message">Processing payment...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="confirm-payment">
                                    Proceed to Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="alert alert-warning text-center">
                            <h4><i class="fas fa-exclamation-triangle me-2"></i>Admissions Closed</h4>
                            <p class="mb-0">Admission applications are currently closed. Please check back later for updates on the next admission cycle.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .section-header {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 5px;
            border-left: 4px solid #1976d2;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('admission-form');
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const submitSpinner = document.getElementById('submit-spinner');
            const alertContainer = document.getElementById('alert-container');

            // Payment modal elements
            const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            const paymentMethods = document.querySelectorAll('.payment-method');
            const phoneInputSection = document.getElementById('phone-input-section');
            const paymentPhone = document.getElementById('payment-phone');
            const confirmPaymentBtn = document.getElementById('confirm-payment');
            const paymentStatus = document.getElementById('payment-status');
            const paymentAlertContainer = document.getElementById('payment-alert-container');
            const paymentAmount = document.getElementById('payment-amount');
            const paymentDescription = document.getElementById('payment-description');
            const applicationNumber = document.getElementById('application-number');

            let currentAdmissionId = null;
            let selectedProvider = null;
            let applicationData = null;

            // Form submission
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                clearErrors();
                setLoadingState(true);

                const formData = new FormData(form);

                try {
                    const response = await fetch('{{ route("opt1.v1.admission.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        currentAdmissionId = data.data.id;
                        applicationData = data.data;

                        // Show payment modal
                        paymentAmount.textContent = 'TZS 10,000'; // Will be fetched from server
                        paymentDescription.textContent = `Admission Fee for ${data.data.student_name}`;
                        applicationNumber.textContent = `Application: ${data.data.application_number}`;

                        // Pre-fill phone number from form
                        paymentPhone.value = data.data.parent_phone.replace('255', '');

                        paymentModal.show();

                        showAlert(alertContainer, 'Application submitted! Please complete payment to finalize your admission.', 'info');
                    } else {
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                showFieldError(field, data.errors[field][0]);
                            });
                            showAlert(alertContainer, data.message || 'Please correct the errors below.', 'warning');
                        } else {
                            showAlert(alertContainer, data.message || 'An error occurred. Please try again.', 'danger');
                        }
                    }
                } catch (error) {
                    console.error('Submission error:', error);
                    showAlert(alertContainer, 'Network error. Please check your connection and try again.', 'danger');
                } finally {
                    setLoadingState(false);
                }
            });

            // Payment method selection
            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    paymentMethods.forEach(m => m.classList.remove('active'));
                    this.classList.add('active');
                    selectedProvider = this.dataset.provider;
                });
            });

            // Confirm payment
            confirmPaymentBtn.addEventListener('click', async function() {
                const phone = paymentPhone.value.trim();

                if (!validatePhoneNumber(phone)) {
                    paymentPhone.classList.add('is-invalid');
                    document.getElementById('phone-error').textContent = 'Please enter a valid Tanzanian phone number';
                    return;
                }

                if (!selectedProvider) {
                    showAlert(paymentAlertContainer, 'Please select a payment method', 'warning');
                    return;
                }

                setPaymentLoading(true);
                paymentStatus.style.display = 'block';
                document.getElementById('status-message').textContent = 'Creating payment request...';

                try {
                    // First create bill
                    const billResponse = await fetch('{{ route("opt1.v1.admission.create-bill") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            admission_id: currentAdmissionId,
                            payment_method: selectedProvider
                        })
                    });

                    const billData = await billResponse.json();

                    if (!billResponse.ok || !billData.success) {
                        throw new Error(billData.message || 'Failed to create payment bill');
                    }
                    console.log(billData);
                    await pollPaymentStatus(currentAdmissionId);
                } catch (error) {
                    console.error('Payment error:', error);
                    showAlert(paymentAlertContainer, error.message || 'Payment failed. Please try again.', 'danger');
                    setPaymentLoading(false);
                    paymentStatus.style.display = 'none';
                }
            });

            // Poll payment status
            async function pollPaymentStatus(admissionId) {
                const maxAttempts = 30;
                let attempts = 0;

                const poll = async () => {
                    attempts++;
                    try {
                        const response = await fetch(`{{ route("opt1.v1.admission.payment-status") }}?admission_id=${admissionId}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();
console.log(data);
                        if (data.data.bill_status === 'paid') {
                            document.getElementById('status-message').textContent = 'Payment completed successfully!';
                            showAlert(paymentAlertContainer, 'Payment completed successfully! Your application is now complete.', 'success');

                            setTimeout(() => {
                                paymentModal.hide();
                                showAlert(alertContainer, 'Your admission application has been submitted successfully! You will receive a confirmation email shortly.', 'success');
                                form.reset();
                            }, 3000);
                        } else if (data.data.bill_status === 'failed') {
                            throw new Error(data.data.message || 'Payment failed');
                        } else if (attempts >= maxAttempts) {
                            throw new Error('Payment timeout. Please check your transaction status.');
                        } else {
                            setTimeout(poll, 6000);
                        }
                    } catch (error) {
                        console.error('Polling error:', error);
                        showAlert(paymentAlertContainer, error.message || 'Payment verification failed', 'danger');
                        setPaymentLoading(false);
                        paymentStatus.style.display = 'none';
                    }
                };

                poll();
            }

            // Helper functions
            function validatePhoneNumber(phone) {
                const phoneRegex = /^(67|68|69|62|65|74|75|76|78|79|71|73|77)[0-9]{7}$/;
                return phoneRegex.test(phone);
            }

            function showAlert(container, message, type = 'success') {
                container.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            }

            function clearErrors() {
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            }

            function showFieldError(fieldName, message) {
                const field = document.getElementById(fieldName);
                const errorElement = document.getElementById(fieldName + '_error');
                if (field && errorElement) {
                    field.classList.add('is-invalid');
                    errorElement.textContent = message;
                }
            }

            function setLoadingState(loading) {
                submitBtn.disabled = loading;
                submitText.textContent = loading ? 'Submitting...' : 'Submit Application';
                submitSpinner.classList.toggle('d-none', !loading);
            }

            function setPaymentLoading(loading) {
                confirmPaymentBtn.disabled = loading;
                confirmPaymentBtn.innerHTML = loading
                    ? '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...'
                    : 'Proceed to Payment';
            }

            // Reset payment modal when closed
            document.getElementById('paymentModal').addEventListener('hidden.bs.modal', function() {
                paymentMethods.forEach(m => m.classList.remove('active'));
                paymentStatus.style.display = 'none';
                selectedProvider = null;
                setPaymentLoading(false);
                paymentAlertContainer.innerHTML = '';
            });

            const requirementsCollapse = document.getElementById('requirementsCollapse');
            let bsCollapse = new bootstrap.Collapse(requirementsCollapse, {
                toggle: false
            });

            function handleCollapseByScreen() {
                if (window.innerWidth >= 992) {
                    bsCollapse.show(); // open on desktop
                } else {
                    bsCollapse.hide(); // close on mobile
                }
            }

            // Run on load
            handleCollapseByScreen();

            // Run when window is resized
            window.addEventListener('resize', handleCollapseByScreen);
        });
    </script>
@endpush
