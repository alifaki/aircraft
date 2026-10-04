@extends('layouts.app')

@section('title', $page = 'Contact Messages')

@section('content')
    <div class="mb-4 overflow-hidden position-relative">
        <div class="px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="#" class="fa fa-home"> Home</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">Manage {{$page}}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row" id="detailed-data-info">
        <div class="col-xxl-12 col-md-12 detailed-info">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white">List of {{$page}}</h5>
                    <div>
                        <button class="btn btn-sm btn-light print-details me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                            <i class="ti ti-printer"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="search-input" class="form-control" placeholder="Search...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="unread">Unread</option>
                                <option value="read">Read</option>
                                <option value="replied">Replied</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" id="filter-date" class="form-control" placeholder="Filter by date">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr><td id="table-loader" colspan="8"></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Details Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> <span id="detail-name"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong> <span id="detail-email"></span></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p><strong>Subject:</strong> <span id="detail-subject"></span></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p><strong>Message:</strong></p>
                            <div class="bg-light p-3" id="detail-message"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Date Sent:</strong> <span id="detail-date"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>IP Address:</strong> <span id="detail-ip"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success btn-sm" id="mark-replied">
                        <i class="ti ti-check"></i> Mark as Replied
                    </button>
                    <a href="#" class="btn btn-primary btn-sm" id="reply-link">
                        <i class="ti ti-mail"></i> Reply
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const apiUrl = '/web/v1/contact-messages';
            const tableElement = $('.dataTable');
            let currentMessageId = null;

            // Fetch and initialize data
            async function fetchMessages(filters = {}) {
                try {
                    showLoader();

                    const queryParams = new URLSearchParams(filters).toString();
                    const url = queryParams ? `${apiUrl}?${queryParams}` : apiUrl;

                    const response = await fetch(url);
                    const data = await response.json();

                    initializeDataTable(data);
                } catch (error) {
                    showToast('error', error.message);
                } finally {
                    hideLoader();
                }
            }

            function showLoader() {
                $('#table-loader').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
            }

            function hideLoader() {
                $('#table-loader').html('');
            }

            function showToast(type, message) {
                // Implement your toast notification here
                console.log(`${type}: ${message}`);
            }

            function initializeDataTable(res) {
                const data = res.data;
                tableElement.DataTable({
                    destroy: true,
                    data: data,
                    columns: [
                        { 
                            data: null, 
                            render: (data, type, row, meta) => meta.row + 1 
                        },
                        { data: 'name' },
                        { 
                            data: 'email',
                            render: (email) => `<a href="mailto:${email}">${email}</a>`
                        },
                        { data: 'subject' },
                        { 
                            data: 'message',
                            render: (message) => message.length > 50 ? 
                                `${message.substring(0, 50)}...` : message
                        },
                        { 
                            data: 'created_at',
                            render: (date) => new Date(date).toLocaleString()
                        },
                        { 
                            data: 'status',
                            render: (status) => {
                                const badgeClass = status === 'unread' ? 'bg-warning' : 
                                                 status === 'replied' ? 'bg-success' : 'bg-info';
                                return `<span class="badge ${badgeClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
                            }
                        },
                        {
                            data: null,
                            render: (data) => `
                                <button class="btn btn-sm btn-info view-message" 
                                        data-id="${data.id}"
                                        data-name="${data.name}"
                                        data-email="${data.email}"
                                        data-subject="${data.subject}"
                                        data-message="${data.message.replace(/"/g, '&quot;')}"
                                        data-date="${data.created_at}"
                                        data-ip="${data.ip_address || 'N/A'}"
                                        data-status="${data.status}">
                                    <i class="ti ti-eye"></i> View
                                </button>
                            `
                        }
                    ],
                    order: [[5, 'desc']] // Sort by date descending
                });
            }

            // View message handler
            $(document).on('click', '.view-message', function() {
                currentMessageId = $(this).data('id');
                
                $('#detail-name').text($(this).data('name'));
                $('#detail-email').text($(this).data('email'));
                $('#detail-subject').text($(this).data('subject'));
                $('#detail-message').text($(this).data('message'));
                $('#detail-date').text(new Date($(this).data('date')).toLocaleString());
                $('#detail-ip').text($(this).data('ip'));
                
                // Update reply link
                $('#reply-link').attr('href', `mailto:${$(this).data('email')}?subject=Re: ${$(this).data('subject')}`);
                
                // Update status button
                const status = $(this).data('status');
                $('#mark-replied').toggle(status !== 'replied');
                
                $('#messageModal').modal('show');
                
                // Mark as read if unread
                if (status === 'unread') {
                    updateMessageStatus(currentMessageId, 'read');
                }
            });

            // Mark as replied handler
            $('#mark-replied').click(async function() {
                await updateMessageStatus(currentMessageId, 'replied');
                $('#messageModal').modal('hide');
                fetchMessages();
            });

            // Delete message handler
            $(document).on('click', '.delete-message', async function() {
                if (confirm('Are you sure you want to delete this message?')) {
                    const messageId = $(this).data('id');
                    try {
                        const response = await fetch(`${apiUrl}/${messageId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            showToast('success', 'Message deleted successfully');
                            fetchMessages();
                        } else {
                            throw new Error('Failed to delete message');
                        }
                    } catch (error) {
                        showToast('error', error.message);
                    }
                }
            });

            // Update message status
            async function updateMessageStatus(id, status) {
                try {
                    const response = await fetch(`${apiUrl}/${id}/status`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ status })
                    });
                    
                    if (!response.ok) {
                        throw new Error('Failed to update status');
                    }
                    
                    showToast('success', 'Status updated successfully');
                    fetchMessages();
                } catch (error) {
                    showToast('error', error.message);
                }
            }

            // Search functionality
            $('#search-btn').click(async function () {
                const searchIcon = $('.filter-btn');
                searchIcon.addClass('ti-loader').removeClass("ti-search");
                await fetchMessages({ search: $('#search-input').val() });
                searchIcon.removeClass('ti-loader').addClass("ti-search");
            });

            // Filter by status
            $('#filter-status').change(() => {
                fetchMessages({ status: $('#filter-status').val() });
            });

            // Filter by date
            $('#filter-date').change(() => {
                fetchMessages({ date: $('#filter-date').val() });
            });

            // Initial load
            fetchMessages();
        });
    </script>
@endpush