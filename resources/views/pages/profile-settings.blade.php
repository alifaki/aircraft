@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="mb-4 overflow-hidden position-relative">
        <div class="px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="#" class="fa fa-home"> Home</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- System Settings -->
        <div class="col-xxl-6 col-md-6">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white py-2 px-4">
                    <h5 class="mb-0 text-white">System Settings</h5>
                </div>
                <div class="card-body">
                    <form id="systemSettingsForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" checked>
                                    <label class="form-check-label" for="email_notifications">Email Notifications</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sms_notifications" name="sms_notifications" checked>
                                    <label class="form-check-label" for="sms_notifications">SMS Notifications</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="two_factor_auth" name="two_factor_auth">
                                    <label class="form-check-label" for="two_factor_auth">Two-Factor Authentication</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <select id="language" name="language" class="input-field form-select">
                                        <option value="en">English</option>
                                        <option value="sw">Swahili</option>
                                        <option value="fr">French</option>
                                    </select>
                                    <label for="language" class="input-label">
                                        <i class="ti ti-language me-1 fs-3 text-primary"></i>
                                        Language Preference
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <select id="timezone" name="timezone" class="input-field form-select">
                                        <option value="Africa/Dar_es_Salaam">East Africa Time (EAT)</option>
                                        <option value="UTC">UTC</option>
                                        <option value="America/New_York">Eastern Time (ET)</option>
                                        <option value="Europe/London">Greenwich Mean Time (GMT)</option>
                                    </select>
                                    <label for="timezone" class="input-label">
                                        <i class="ti ti-clock me-1 fs-3 text-primary"></i>
                                        Timezone
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <select id="records_per_page" name="records_per_page" class="input-field form-select">
                                        <option value="10">10 records per page</option>
                                        <option value="25">25 records per page</option>
                                        <option value="50">50 records per page</option>
                                        <option value="100">100 records per page</option>
                                    </select>
                                    <label for="records_per_page" class="input-label">
                                        <i class="ti ti-layout-grid me-1 fs-3 text-primary"></i>
                                        Records Per Page
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ti ti-device-floppy me-1"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="col-xxl-6 col-md-6">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white py-2 px-4">
                    <h5 class="mb-0 text-white">Security Settings</h5>
                </div>
                <div class="card-body">
                    <div class="security-settings">
                        <!-- Session Management -->
                        <div class="mb-4">
                            <h6 class="mb-3">Active Sessions</h6>
                            <div class="session-list">
                                <div class="session-item d-flex justify-content-between align-items-center p-3 border rounded mb-2">
                                    <div>
                                        <h6 class="mb-1">Current Session</h6>
                                        <small class="text-muted">
                                            <i class="ti ti-device-desktop me-1"></i>
                                            {{ request()->ip() }} • {{ now()->format('M j, Y g:i A') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-success">Current</span>
                                </div>
                                <!-- Add more session items as needed -->
                            </div>
                            <button class="btn btn-sm btn-outline-danger mt-2" id="logoutOtherSessions">
                                <i class="ti ti-logout me-1"></i> Logout Other Sessions
                            </button>
                        </div>

                        <!-- Password Strength -->
                        <div class="mb-4">
                            <h6 class="mb-3">Password Strength</h6>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted">Your password strength is good</small>
                        </div>

                        <!-- Security Log -->
                        <div class="mb-3">
                            <h6 class="mb-3">Recent Security Events</h6>
                            <div class="security-events">
                                <div class="event-item d-flex align-items-center mb-2">
                                    <i class="ti ti-login text-success me-2"></i>
                                    <div class="flex-grow-1">
                                        <small class="d-block">Successful login</small>
                                        <small class="text-muted">{{ now()->subHours(2)->format('M j, Y g:i A') }}</small>
                                    </div>
                                </div>
                                <div class="event-item d-flex align-items-center mb-2">
                                    <i class="ti ti-password text-info me-2"></i>
                                    <div class="flex-grow-1">
                                        <small class="d-block">Password changed</small>
                                        <small class="text-muted">{{ now()->subDays(15)->format('M j, Y g:i A') }}</small>
                                    </div>
                                </div>
                                <div class="event-item d-flex align-items-center mb-2">
                                    <i class="ti ti-device-desktop text-warning me-2"></i>
                                    <div class="flex-grow-1">
                                        <small class="d-block">New device login</small>
                                        <small class="text-muted">{{ now()->subDays(30)->format('M j, Y g:i A') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="col-xxl-12 col-md-12 mt-4">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white py-2 px-4">
                    <h5 class="mb-0 text-white">Notification Preferences</h5>
                </div>
                <div class="card-body">
                    <form id="notificationSettingsForm">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <h6 class="mb-3">System Notifications</h6>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="system_updates" name="system_updates" checked>
                                    <label class="form-check-label" for="system_updates">System Updates</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="security_alerts" name="security_alerts" checked>
                                    <label class="form-check-label" for="security_alerts">Security Alerts</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="maintenance_notices" name="maintenance_notices">
                                    <label class="form-check-label" for="maintenance_notices">Maintenance Notices</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <h6 class="mb-3">Email Notifications</h6>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_newsletter" name="email_newsletter">
                                    <label class="form-check-label" for="email_newsletter">Newsletter</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_promotions" name="email_promotions">
                                    <label class="form-check-label" for="email_promotions">Promotions</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_announcements" name="email_announcements" checked>
                                    <label class="form-check-label" for="email_announcements">Announcements</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <h6 class="mb-3">SMS Notifications</h6>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="sms_urgent" name="sms_urgent" checked>
                                    <label class="form-check-label" for="sms_urgent">Urgent Alerts</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="sms_reminders" name="sms_reminders">
                                    <label class="form-check-label" for="sms_reminders">Reminders</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="sms_updates" name="sms_updates">
                                    <label class="form-check-label" for="sms_updates">Daily Updates</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ti ti-bell me-1"></i> Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Management -->
        <div class="col-xxl-12 col-md-12 mt-4">
            <div class="card border-bottom border-danger">
                <div class="card-header bg-danger text-white py-2 px-4">
                    <h5 class="mb-0 text-white">Data Management</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h6 class="mb-3">Export Data</h6>
                            <p class="text-muted mb-3">Download your personal data from the system.</p>
                            <button class="btn btn-outline-primary btn-sm" id="exportDataBtn">
                                <i class="ti ti-download me-1"></i> Export My Data
                            </button>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="mb-3 text-danger">Danger Zone</h6>
                            <p class="text-muted mb-3">Permanently delete your account and all associated data.</p>
                            <button class="btn btn-outline-danger btn-sm" id="deleteAccountBtn" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="ti ti-trash me-1"></i> Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-triangle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone. This will permanently delete your account and remove all your data from our system.
                    </div>
                    <p>Please type your password to confirm you want to permanently delete your account.</p>
                    <form id="deleteAccountForm">
                        @csrf
                        <div class="input-container">
                            <input type="password" class="form-control input-field" id="confirm_password_delete" name="confirm_password" placeholder=" " required>
                            <label for="confirm_password_delete" class="input-label">Confirm Password <span class="text-red">*</span></label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteAccount">
                        <i class="ti ti-trash me-1"></i> Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const systemSettingsForm = document.getElementById('systemSettingsForm');
        const notificationSettingsForm = document.getElementById('notificationSettingsForm');
        const logoutOtherSessionsBtn = document.getElementById('logoutOtherSessions');
        const exportDataBtn = document.getElementById('exportDataBtn');
        const deleteAccountBtn = document.getElementById('confirmDeleteAccount');

        // Load saved settings
        function loadSettings() {
            // This would typically load from an API
            const savedSettings = {
                email_notifications: true,
                sms_notifications: true,
                two_factor_auth: false,
                language: 'en',
                timezone: 'Africa/Dar_es_Salaam',
                records_per_page: '25'
            };

            // Apply saved settings to form
            document.getElementById('email_notifications').checked = savedSettings.email_notifications;
            document.getElementById('sms_notifications').checked = savedSettings.sms_notifications;
            document.getElementById('two_factor_auth').checked = savedSettings.two_factor_auth;
            document.getElementById('language').value = savedSettings.language;
            document.getElementById('timezone').value = savedSettings.timezone;
            document.getElementById('records_per_page').value = savedSettings.records_per_page;
        }

        // System settings form submission
        systemSettingsForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            try {
                submitBtn.innerHTML = '<i class="ti ti-loader fa-spin"></i> Saving...';
                submitBtn.disabled = true;

                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                toastr.success('System settings saved successfully');
            } catch (error) {
                toastr.error('Failed to save settings');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Notification settings form submission
        notificationSettingsForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            try {
                submitBtn.innerHTML = '<i class="ti ti-loader fa-spin"></i> Saving...';
                submitBtn.disabled = true;

                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                toastr.success('Notification preferences saved successfully');
            } catch (error) {
                toastr.error('Failed to save preferences');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Logout other sessions
        logoutOtherSessionsBtn.addEventListener('click', async function() {
            if (!confirm('Are you sure you want to logout all other sessions?')) {
                return;
            }

            const originalText = this.innerHTML;
            
            try {
                this.innerHTML = '<i class="ti ti-loader fa-spin"></i> Logging out...';
                this.disabled = true;

                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                toastr.success('All other sessions have been logged out');
            } catch (error) {
                toastr.error('Failed to logout other sessions');
            } finally {
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });

        // Export data
        exportDataBtn.addEventListener('click', async function() {
            const originalText = this.innerHTML;
            
            try {
                this.innerHTML = '<i class="ti ti-loader fa-spin"></i> Exporting...';
                this.disabled = true;

                // Simulate export process
                await new Promise(resolve => setTimeout(resolve, 2000));
                
                toastr.success('Your data export has been started. You will receive an email when it\'s ready.');
            } catch (error) {
                toastr.error('Failed to export data');
            } finally {
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });

        // Delete account
        deleteAccountBtn.addEventListener('click', async function() {
            const password = document.getElementById('confirm_password_delete').value;
            
            if (!password) {
                toastr.error('Please enter your password to confirm');
                return;
            }

            if (!confirm('Are you absolutely sure? This action cannot be undone!')) {
                return;
            }

            const originalText = this.innerHTML;
            
            try {
                this.innerHTML = '<i class="ti ti-loader fa-spin"></i> Deleting...';
                this.disabled = true;

                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 2000));
                
                toastr.success('Your account has been scheduled for deletion');
                $('#deleteAccountModal').modal('hide');
                
                // Redirect to login page after deletion
                setTimeout(() => {
                    window.location.href = '/login';
                }, 3000);
            } catch (error) {
                toastr.error('Failed to delete account');
            } finally {
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });

        // Load settings on page load
        loadSettings();
    });
</script>
@endpush