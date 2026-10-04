@extends('layouts.app')

@section('title', $page = 'Posts')

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
    <div class="row visually-hidden" id="more-details">
        <div class="col-xxl-12 col-md-12">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white">More {{$page}} Details</h5>
                    <button class="btn btn-sm btn-danger close-detailed-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Close">
                        <span class="ti ti-x"></span>
                    </button>
                </div>
                <div class="card-body" id="detail-info-body">

                </div>
            </div>
        </div>
    </div>
    <div class="row" id="detailed-data-info">
        <div class="col-xxl-5 col-md-5 visually-hidden form-input">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white"><span class="btn-label">Create</span> {{$page}}</h5>
                    <div>
                        <button class="btn btn-sm btn-light expand-form resize-form" data-bs-toggle="tooltip" data-bs-placement="top" title="Resize">
                            <i class="ti ti-resize"></i>
                        </button>
                        <button class="btn btn-sm btn-danger close-form" data-bs-toggle="tooltip" data-bs-placement="top" title="Close">
                            <span class="ti ti-x"></span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="postForm" enctype="multipart/form-data">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="text" id="title" name="title" class="input-field form-control controlled" placeholder=" ">
                                    <label for="title" class="input-label">
                                        <i class="ti ti-news me-1 fs-3 text-primary"></i>
                                        Title <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="category" name="category" class="input-field form-select controlled">
                                        <option value="">Select Category</option>
                                        <option value="news">News</option>
                                        <option value="event">Event</option>
                                        <option value="announcement">Announcement</option>
                                        <option value="gallery">Gallery</option>
                                        <option value="about">Welcome notes</option>
                                        <option value="video">Video</option>
                                        <option value="audio">Audio</option>
                                        <option value="documents">Documents</option>
                                        <option value="program">Program</option>
                                        <option value="staff">Staff</option>
                                        <option value="student">Student</option>
                                        <option value="other">Other</option>
                                        <option value="campus">Campus</option>
                                        <option value="classroom">Classroom</option>
                                        <option value="lab">Lab</option>
                                        <option value="students">Students</option>
                                        <option value="staffs">Staffs</option>
                                        <option value="programs">Programs</option>
                                        <option value="slider">Slider</option>
                                    </select>
                                    <label for="category" class="input-label">
                                        <i class="ti ti-category me-1 fs-3 text-primary"></i>
                                        Category <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="status" name="status" class="input-field form-select controlled">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                    <label for="status" class="input-label">
                                        <i class="ti ti-status-change me-1 fs-3 text-primary"></i>
                                        Status <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="file" id="image_path" name="image_path" class="input-field form-control">
                                    <label for="image_path" class="input-label" id="image_path_label">
                                        <i class="ti ti-photo me-1 fs-3 text-primary"></i>
                                        <span id="file-label-text">Featured Image</span> <span class="text-red"> *</span>
                                    </label>
                                    <small id="file-help-text" class="form-text text-muted"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <textarea id="excerpt" name="excerpt" class="input-field form-control controlled" placeholder=" "></textarea>
                                    <label for="excerpt" class="input-label">
                                        <i class="ti ti-file-description me-1 fs-3 text-primary"></i>
                                        Excerpt <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <textarea id="content" name="content" class="input-field form-control controlled" placeholder=" "></textarea>
                                    <label for="content" class="input-label">
                                        <i class="ti ti-article me-1 fs-3 text-primary"></i>
                                        Content <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="button_text" name="button_text" class="input-field form-control" placeholder=" ">
                                    <label for="button_text" class="input-label">
                                        <i class="ti ti-click me-1 fs-3 text-primary"></i>
                                        Button Text
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="url" id="button_url" name="button_url" class="input-field form-control" placeholder=" ">
                                    <label for="button_url" class="input-label">
                                        <i class="ti ti-link me-1 fs-3 text-primary"></i>
                                        Button URL
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input controlled" type="checkbox" id="is_featured" name="is_featured">
                                    <label for="is_featured">Featured Post</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="number" id="order" name="order" class="input-field form-control" placeholder=" " value="0">
                                    <label for="order" class="input-label">
                                        <i class="ti ti-sort-descending me-1 fs-3 text-primary"></i>
                                        Order
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="text" id="meta_title" name="meta_title" class="input-field form-control" placeholder=" ">
                                    <label for="meta_title" class="input-label">
                                        <i class="ti ti-brand-meta me-1 fs-3 text-primary"></i>
                                        Meta Title
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <textarea id="meta_description" name="meta_description" class="input-field form-control" placeholder=" "></textarea>
                                    <label for="meta_description" class="input-label">
                                        <i class="ti ti-brand-meta me-1 fs-3 text-primary"></i>
                                        Meta Description
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <textarea id="meta_keywords" name="meta_keywords" class="input-field form-control" placeholder=" "></textarea>
                                    <label for="meta_keywords" class="input-label">
                                        <i class="ti ti-brand-meta me-1 fs-3 text-primary"></i>
                                        Meta Keywords
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="postForm" id="reset-btn">
                                        <i class="ti ti-x"></i>
                                        <span>Reset</span>
                                    </button>
                                    <button type="submit" class="btn btn-sm btn-primary ms-1" data-url="" id="create-btn">
                                        <i class="ti ti-device-floppy"></i>
                                        <span class="btn-label">Create</span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 col-md-12 detailed-info">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white">List of {{$page}}</h5>
                    <div>
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="postForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
                            <i class="ti ti-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-light print-details me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                            <i class="ti ti-printer"></i>
                        </button>
                        <button class="btn btn-sm btn-light expand-details resize-details visually-hidden" data-bs-toggle="tooltip" data-bs-placement="top" title="Resize">
                            <i class="ti ti-resize"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="search-input" class="form-control" placeholder="Search posts...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-category" class="form-select">
                                <option value="">All Categories</option>
                                <option value="news">News</option>
                                <option value="event">Event</option>
                                <option value="announcement">Announcement</option>
                                <option value="gallery">Gallery</option>
                                <option value="program">Program</option>
                                <option value="staff">Staff</option>
                                <option value="student">Student</option>
                                <option value="other">Other</option>
                                <option value="campus">Campus</option>
                                <option value="classroom">Classroom</option>
                                <option value="lab">Lab</option>
                                <option value="students">Students</option>
                                <option value="staffs">Staffs</option>
                                <option value="programs">Programs</option>
                                <option value="slider">Slider</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button id="reset-filters" class="btn btn-secondary btn-sm btn-h">Reset</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable">
                            <tr><td id="table-loader"></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Post Preview Modal -->
    <div class="modal fade" id="postPreviewModal" tabindex="-1" aria-labelledby="postPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="postPreviewModalLabel">Post Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="post-preview-body">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tableElement = $('.dataTable');
            const apiUrl = '/web/v1/posts';

            // --- File input logic for category type ---
            const categorySelect = document.getElementById('category');
            const fileInput = document.getElementById('image_path');
            const fileLabelText = document.getElementById('file-label-text');
            const fileHelpText = document.getElementById('file-help-text');

            function setFileInputForCategory(category) {
                // Reset
                fileInput.value = '';
                fileInput.removeAttribute('accept');
                fileInput.setAttribute('multiple', false);

                // Default
                let label = 'Featured Image';
                let help = 'Upload an image file (JPG, PNG, JPEG, GIF, SVG, WebP).';
                let accept = 'image/*';

                if (category === 'documents') {
                    label = 'Document (PDF)';
                    help = 'Upload a PDF document only.';
                    accept = 'application/pdf';
                } else if (category === 'video') {
                    label = 'Video File';
                    help = 'Upload a short video file (MP4, WebM, Ogg, max 60MB, max 5 minutes).';
                    accept = 'video/mp4,video/webm,video/ogg';
                } else if (category === 'audio') {
                    label = 'Audio File';
                    help = 'Upload an audio file (MP3, WAV, OGG, max 20MB).';
                    accept = 'audio/*';
                } else {
                    // Default is image
                    label = 'Featured Image';
                    help = 'Upload an image file (JPG, PNG, JPEG, GIF, SVG, WebP).';
                    accept = 'image/*';
                }

                fileLabelText.textContent = label;
                fileHelpText.textContent = help;
                fileInput.setAttribute('accept', accept);
            }

            // Initial set
            setFileInputForCategory(categorySelect.value);

            // On category change
            categorySelect.addEventListener('change', function() {
                setFileInputForCategory(this.value);
            });

            // Optional: Client-side file validation for size/duration
            fileInput.addEventListener('change', function(e) {
                const category = categorySelect.value;
                const file = this.files[0];
                if (!file) return;

                // PDF
                if (category === 'documents') {
                    if (file.type !== 'application/pdf') {
                        alert('Please upload a PDF document.');
                        this.value = '';
                        return;
                    }
                }
                // Video
                else if (category === 'video') {
                    if (!file.type.startsWith('video/')) {
                        alert('Please upload a valid video file.');
                        this.value = '';
                        return;
                    }
                    // Check file size (max 60MB)
                    if (file.size > 60 * 1024 * 1024) {
                        alert('Video file size must be less than 60MB.');
                        this.value = '';
                        return;
                    }
                    // Check duration (max 5 minutes)
                    const url = URL.createObjectURL(file);
                    const video = document.createElement('video');
                    video.preload = 'metadata';
                    video.onloadedmetadata = function() {
                        URL.revokeObjectURL(url);
                        if (video.duration > 300) {
                            alert('Video duration must be less than 5 minutes.');
                            fileInput.value = '';
                        }
                    };
                    video.src = url;
                }
                // Audio
                else if (category === 'audio') {
                    if (!file.type.startsWith('audio/')) {
                        alert('Please upload a valid audio file.');
                        this.value = '';
                        return;
                    }
                    // Check file size (max 20MB)
                    if (file.size > 20 * 1024 * 1024) {
                        alert('Audio file size must be less than 20MB.');
                        this.value = '';
                        return;
                    }
                }
                // Image (default)
                else {
                    if (!file.type.startsWith('image/')) {
                        alert('Please upload a valid image file.');
                        this.value = '';
                        return;
                    }
                }
            });

            // --- End file input logic ---

            // Fetch posts and populate the DataTable
            async function fetchPosts(filters = {}) {
                try {
                    showLoader();

                    // Build query string from filters
                    const queryParams = new URLSearchParams();
                    for (const key in filters) {
                        if (filters[key]) {
                            queryParams.append(key, filters[key]);
                        }
                    }

                    const url = queryParams.toString() ? `${apiUrl}?${queryParams}` : apiUrl;

                    const response = await fetch(url, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const res = await response.json();
                    initializeDataTable(res);
                } catch (error) {
                    showToast('error', 'Error', error.message);
                } finally {
                    hideLoader();
                }
            }

            function initializeDataTable(res) {
                const data = res.data;
                tableElement.DataTable({
                    destroy: true,
                    data: data,
                    columns: [
                        {
                            title: "SN",
                            render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        { title: "Title", data: "title" },
                        {
                            title: "Category",
                            data: "category",
                            render: function(data) {
                                return data.charAt(0).toUpperCase() + data.slice(1);
                            }
                        },
                        {
                            title: "Author",
                            data: "author",
                            render: function(data) {
                                return data ? `${data.first_name} ${data.last_name}` : 'Automatic';
                            }
                        },
                        {
                            title: "Published At",
                            data: "published_at",
                            render: function(data) {
                                return data ? new Date(data).toLocaleString() : 'Automatic';
                            }
                        },
                        {
                            title: "Status",
                            data: "status",
                            render: function(data, type, row) {
                                const badgeClass = {
                                    'draft': 'bg-secondary',
                                    'pending': 'bg-warning',
                                    'published': 'bg-success',
                                    'archived': 'bg-danger'
                                }[data] || 'bg-secondary';
                                // Add toggle button for pending/published
                                let toggleBtn = '';
                                if (data === 'draft' || data === 'published') {
                                    toggleBtn = `
                                        <button class="btn btn-sm btn-outline-primary ms-2 toggle-status-btn" data-id="${row.id}" data-status="${data}">
                                            ${data === 'draft' ? 'Publish' : 'Set draft'}
                                        </button>
                                    `;
                                }
                                return `<span class="badge ${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>${toggleBtn}`;
                            }
                        },
                        {
                            title: "Featured",
                            data: "is_featured",
                            render: function(data) {
                                return data ? '<span class="badge bg-info">Yes</span>' : '<span class="badge bg-secondary">No</span>';
                            }
                        },
                        {
                            title: "Actions",
                            data: null,
                            className: "text-center",
                            orderable: false,
                            render: function (data, type, row) {
                                return `
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-info show-detailed-info" data-label="${row.title}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="postForm" data-url="${apiUrl}/${row.id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-danger confirm-delete" data-label="${row.title}" data-url="${apiUrl}/${row.id}"  data-bs-toggle="modal" data-bs-target="#confirm-delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                `;
                            }
                        }
                    ],
                    responsive: true,
                    language: {
                        loadingRecords: "Loading...",
                        emptyTable: "No post records available"
                    }
                });
            }

            function addNewRecordToTable(newRecord) {
                if (!$.fn.DataTable.isDataTable(tableElement)) {
                    initializeDataTable({ data: [newRecord] });
                    return;
                }

                const dataTable = tableElement.DataTable();
                dataTable.row.add(newRecord).draw(false);

                // Update serial numbers
                $(".dataTable tbody tr").each(function (index) {
                    $(this).find("td:first").text(index + 1);
                });
            }

            const postForm = document.getElementById("postForm");
            const submitButton = postForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            postForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                // Run validation before submitting
                let isValid = true;
                document.querySelectorAll(".controlled").forEach(input => {
                    input.style.borderColor = "";
                    if (!validateInput(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    progressMessage.classList.remove("text-primary");
                    progressMessage.classList.add("error-message");
                    progressMessage.textContent = "Please fix the errors before submitting.";
                    return;
                }

                // Disable button and show loading spinner
                submitButton.disabled = true;
                btnIcon.classList.remove("ti-plus");
                btnIcon.classList.add("fa", "ti-loader", "fa-spin");
                progressMessage.textContent = "Processing post data, please wait...";
                progressMessage.classList.remove("error-message");
                progressMessage.classList.add("text-primary");

                // Clear previous error messages
                document.querySelectorAll(".error-message").forEach(el => el.remove());

                const formData = new FormData(postForm);

                // Remove author_id and published_at from formData if present
                formData.delete('author_id');
                formData.delete('published_at');

                const dataUrl = submitButton.getAttribute("data-url")?.trim();
                const isUpdate = !!dataUrl;

                try {
                    let response = await fetch(dataUrl || apiUrl, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    let res = await response.json();

                    if (response.ok) {
                        if (!isUpdate && res.data) {
                            addNewRecordToTable(res.data);
                        } else if (isUpdate) {
                            await fetchPosts();
                        }

                        progressMessage.classList.remove("error-message");
                        progressMessage.classList.add("text-primary");
                        progressMessage.textContent = res.message || (isUpdate ? "Post updated successfully!" : "Post created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            postForm.reset();
                            postForm.classList.remove('was-validated');
                            // Reset file input label/help
                            setFileInputForCategory(categorySelect.value);
                        }
                    } else {
                        if (res.errors) {
                            handleServerErrors(res.errors);
                        }
                        progressMessage.className = "text-danger small";
                        progressMessage.textContent = res.message || "Unexpected error occurred";
                        showToast('error', 'Error', res.message || 'Operation failed');
                    }
                } catch (error) {
                    progressMessage.className = "text-danger small";
                    progressMessage.textContent = error.message || "Network error occurred";
                    showToast('error', 'Error', error.message || 'Network error occurred');
                } finally {
                    submitButton.disabled = false;
                    btnIcon.classList.remove("ti-loader", "fa-spin");
                    btnIcon.classList.add("ti-device-floppy");
                }
            });

            // Toggle status between pending and published
            $(document).on('click', '.toggle-status-btn', async function() {
                const postId = $(this).data('id');
                const currentStatus = $(this).data('status');
                let newStatus = currentStatus === 'draft' ? 'published' : 'draft';

                try {
                    const response = await fetch(`${apiUrl}/${postId}/change-status`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ status: newStatus })
                    });
                    const res = await response.json();
                    if (response.ok) {
                        showToast('success', 'Success', res.message || 'Status updated');
                        fetchPosts();
                    } else {
                        showToast('error', 'Error', res.message || 'Failed to update status');
                    }
                } catch (error) {
                    showToast('error', 'Error', error.message || 'Network error');
                }
            });

            // Post preview button handler
            $(document).on('click', '.show-detailed-info', function() {
                const postData = JSON.parse($(this).data('browse'));
                $('#postPreviewModalLabel').text(postData.title);

                let html = `
                    <div class="row">
                        <div class="col-md-4">
                            ${postData.image_path ?
                                `<img src="/storage/${postData.image_path}" class="img-fluid rounded mb-3" alt="${postData.title}">` :
                                '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;"><i class="ti ti-news text-muted" style="font-size: 3rem;"></i></div>'}
                        </div>
                        <div class="col-md-8">
                            <h4>${postData.title}</h4>
                            <p class="text-muted">${postData.category.charAt(0).toUpperCase() + postData.category.slice(1)}</p>
                            <p><strong>Author:</strong> ${postData.author ? `${postData.author.first_name} ${postData.author.last_name}` : 'Automatic'}</p>
                            <p><strong>Published:</strong> ${postData.published_at ? new Date(postData.published_at).toLocaleString() : 'Automatic'}</p>
                            <p><strong>Status:</strong> <span class="badge ${{
                                'draft': 'bg-secondary',
                                'pending': 'bg-warning',
                                'published': 'bg-success',
                                'archived': 'bg-danger'
                            }[postData.status] || 'bg-secondary'}">${postData.status.charAt(0).toUpperCase() + postData.status.slice(1)}</span></p>
                            ${postData.is_featured ? '<p><span class="badge bg-info">Featured Post</span></p>' : ''}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Excerpt</h5>
                            <div class="border p-3 rounded">${postData.excerpt || 'No excerpt provided'}</div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Content</h5>
                            <div class="border p-3 rounded">${postData.content || 'No content provided'}</div>
                        </div>
                    </div>
                    ${postData.button_text && postData.button_url ? `
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a href="${postData.button_url}" class="btn btn-primary" target="_blank">${postData.button_text}</a>
                        </div>
                    </div>
                    ` : ''}
                `;

                $('#post-preview-body').html(html);
                $('#postPreviewModal').modal('show');
            });

            // Search functionality
            $('#search-btn').click(async function () {
                const searchTerm = $('#search-input').val();
                const searchIcom = $('.filter-btn');
                searchIcom.addClass('ti-loader', 'fa-spinner').removeClass("ti-search");
                await fetchPosts({search: searchTerm});
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            // Filter by category
            $('#filter-category').change(function() {
                const category = $(this).val();
                fetchPosts({category: category});
            });

            // Filter by status
            $('#filter-status').change(function() {
                const status = $(this).val();
                fetchPosts({status: status});
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#search-input').val('');
                $('#filter-category').val('');
                $('#filter-status').val('');
                fetchPosts();
            });

            // Initial load
            fetchPosts();
        });
    </script>
@endpush
