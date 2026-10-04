/** Div control v1
 * Div Resize and Display Control (jQuery Optimized)
 * - Uses efficient selectors and method chaining
 */
const $displayInfo = $('.detailed-info');
const $inputForm = $('.form-input');
const $createRecordBtn = $('.create-record');
const $moreDetails = $('#more-details');
const $detailedData = $('#detailed-data-info');
const $paymentDetails = $('#payment-details');
const $createBtn = $('#create-btn');
const $createBtnLbl = $('.btn-label');

window.creatRecord = function () {
    $createRecordBtn.addClass("visually-hidden");
    $('.resize-details').addClass("expand-details").removeClass("reset-details visually-hidden");
    $inputForm.removeClass('col-xxl-12 col-md-12 visually-hidden').addClass("col-xxl-5 col-md-5");
    $displayInfo.removeClass('col-xxl-12 col-md-12').addClass("col-xxl-7 col-md-7");
    $createBtn.attr('data-url', "");
    $createBtnLbl.html("Create");
}

// Event delegation for better performance
$(document)
    .on('click', '.create-record', function() {
        $('#'+$createRecordBtn.data("for"))[0].reset();
        $createBtnLbl.html("Create");
        $createBtn.attr("data-url", "");
        creatRecord();
    })
    .on('click', '.close-form', function() {
        $createRecordBtn.removeClass("visually-hidden");
        $('.resize-details').addClass("expand-details visually-hidden").removeClass("reset-details");
        $inputForm.addClass('visually-hidden');
        $displayInfo.removeClass('col-xxl-7 col-md-7 visually-hidden').addClass("col-xxl-12 col-md-12");
    })
    .on('click', '.expand-form', function() {
        $(this).removeClass("expand-form").addClass("reset-form");
        $inputForm.removeClass('col-xxl-5 col-md-5 visually-hidden').addClass("col-xxl-12 col-md-12");
        $displayInfo.addClass("visually-hidden");
    })
    .on('click', '.reset-form', function() {
        $(this).removeClass("reset-form").addClass("expand-form");
        $('.resize-details').addClass("expand-details").removeClass("reset-details");
        $inputForm.removeClass('col-xxl-12 col-md-12').addClass("col-xxl-5 col-md-5");
        $displayInfo.removeClass("col-xxl-12 col-md-12 visually-hidden").addClass("col-xxl-7 col-md-7");
    })
    .on('click', '.expand-details', function() {
        $(this).addClass("visually-hidden")
        $createRecordBtn.removeClass("visually-hidden");
        $displayInfo.removeClass('col-xxl-7 col-md-7 visually-hidden').addClass("col-xxl-12 col-md-12");
        $inputForm.addClass("visually-hidden");
    })
    .on('click', '.reset-details', function() {
        $(this).addClass("expand-details").removeClass("reset-details");
        $inputForm.removeClass('col-xxl-12 col-md-12 visually-hidden').addClass("col-xxl-5 col-md-5");
        $displayInfo.removeClass("col-xxl-12 col-md-12 visually-hidden").addClass("col-xxl-7 col-md-7");
    })
    .on('click', '.close-detailed-info', function() {
        $moreDetails.addClass("visually-hidden");
        $detailedData.removeClass("visually-hidden");
        $paymentDetails.addClass('visually-hidden');
    })
    .on("click", ".update-record", function () {
        creatRecord();
        const $this = $(this);
        const rowData = $this.data("browse");
        const $url = $this.attr("data-url");

        $createBtn.attr("data-url", $url);
        $createBtnLbl.html("Update");

        if (!rowData || typeof rowData !== "object") {
            console.warn("Invalid rowData received.");
            return;
        }

        // Cache form elements as an array to avoid live NodeList issues
        const formElements = Array.from(document.querySelectorAll("input, select, textarea"));

        for (const key in rowData) {
            if (!Object.prototype.hasOwnProperty.call(rowData, key)) continue;

            const value = rowData[key];
            // Find element by name, but skip elements that are disconnected from the DOM
            const input = formElements.find(el => el.name === key && el.isConnected);

            if (!input) {
                continue;
            }

            // Check if input is for metadata
            if (input.name && input.name.startsWith("metadata")) {
                console.log(input.value);
                continue;
            } else if (input.type === "checkbox") {
                input.checked = value === true || value === 1 || value === "true" || value === "active" || value === "approved";
            } else if (input.multiple) {
                let valuesArray = Array.isArray(value) ? value : (typeof value === "string" ? value.split(",").map(v => v.trim()) : []);
                // Only set value if input is still connected to the DOM
                if (input.isConnected) {
                    $(input).val(valuesArray).trigger("change");
                }
            } else if (input.tagName === "SELECT") {
                if (input.isConnected) {
                    $(input).val(value).trigger("change");
                }
            } else {
                input.value = value;
            }
        }

        // Update chosen select elements efficiently, only if they are still in the DOM
        $(".chosen-select").each(function() {
            if (this.isConnected) $(this).trigger("chosen:updated");
        });
    })
    .on("click", "#reset-btn", function () {
        const formId = $(this).data("for"); // Get the form ID
        const $form = $(`#${formId}`);

        if ($form.length) {
            $form[0].reset(); // Reset only if the form exists
        } else {
            console.warn(`Form with ID '${formId}' not found.`);
        }

        $createBtnLbl.html("Create");
        $createBtn.attr("data-url", "");
    })
    .on('click', '.show-invoice-details', function() {
        $moreDetails.removeClass("visually-hidden");
        $detailedData.addClass("visually-hidden");
    })
    .on('click', '.show-detailed-info', function() {
        const $this = $(this);
        const rowData = $this.data('browse');
        const itemLabel = $this.data("label") || "Item";

        // Toggle views
        $moreDetails.removeClass("visually-hidden");
        $detailedData.addClass("visually-hidden");

        // Prepare the detail body
        const $moreDetailBody = $('#detail-info-body').html("");

        // --- Add a primary header to differentiate between objects ---
        const primaryHeader = `
            <div class="primary-detail-header mb-3 card-header bg-primary text-white py-2 px-4">
                <h3 class="mb-0 text-white">${itemLabel}
                ${rowData.status ? `<span class="detail-meta float-end" style="font-size: 12px;"><span class="badge bg-${rowData.status === 'active' ? 'success' : 'danger'}">${formatStatus(rowData.status)}</span></span>` : ''}
            </h3>
                </div>
        `;
        $moreDetailBody.append(primaryHeader);

        // Create header without ID
        const header = ``;

        // Create main content container
        const $contentContainer = $('<div class="detail-content-container"></div>');

        // --- Preview image or file if present ---
        // Look for common image/file fields
        let previewHtml = '';
        const imageFields = ['photo_path', 'image','image_path', 'avatar', 'logo', 'picture', 'file', 'file_path', 'document', 'attachment'];
        for (const field of imageFields) {
            if (rowData[field]) {
                const value = rowData[field];
                // Check if it's an image by extension
                const isImage = /\.(jpe?g|png|gif|bmp|webp|svg)$/i.test(value);
                if (isImage) {
                    previewHtml = `
                        <div class="detail-preview mb-3">
                            <label class="fw-bold mb-1">${formatKey(field)} Preview:</label><br>
                            <img src="/storage/${value}" alt="${formatKey(field)}" class="img-thumbnail" style="max-width:220px;max-height:180px;">
                        </div>
                    `;
                } else {
                    // File preview (link)
                    previewHtml = `
                        <div class="detail-preview mb-3">
                            <label class="fw-bold mb-1">${formatKey(field)}:</label><br>
                            <a href="${value}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-file"></i> View File
                            </a>
                        </div>
                    `;
                }
                break; // Only show one preview
            }
        }
        if (previewHtml) $contentContainer.append(previewHtml);

        // Fields to exclude (case insensitive)
        const excludedFields = [
            'id', '_id', /_id$/, /^id_/, 'pivot',
            'created_at', 'updated_at'
        ];

        // Also exclude previewed image/file fields from mainData
        function isExcludedField(key) {
            return excludedFields.some(pattern => {
                if (typeof pattern === 'string') {
                    return key.toLowerCase() === pattern.toLowerCase();
                } else if (pattern instanceof RegExp) {
                    return pattern.test(key.toLowerCase());
                }
                return false;
            }) || imageFields.includes(key);
        }

        // Helper: Recursively collect objects for card rendering
        function collectObjectCards(obj, parentKey = null, cards = []) {
            // Only process if obj is an object and not null
            if (typeof obj !== 'object' || obj === null || Array.isArray(obj) || obj instanceof Date) return cards;

            // Prepare fields for this card
            let simpleFields = {};
            let nestedObjects = {};

            for (const key in obj) {
                if (!obj.hasOwnProperty(key)) continue;
                if (isExcludedField(key)) continue;
                const value = obj[key];
                if (
                    value &&
                    typeof value === 'object' &&
                    !Array.isArray(value) &&
                    !(value instanceof Date) &&
                    !isStatusField(key, value)
                ) {
                    nestedObjects[key] = value;
                } else if (Array.isArray(value)) {
                    // Arrays will be handled separately in the main handler
                    // For now, skip
                } else {
                    simpleFields[key] = value;
                }
            }

            // Only add a card if there are simple fields
            if (Object.keys(simpleFields).length > 0) {
                cards.push({
                    title: parentKey ? formatKey(parentKey) : "Basic Information",
                    fields: simpleFields
                });
            }

            // Recursively add cards for nested objects
            for (const nestedKey in nestedObjects) {
                collectObjectCards(nestedObjects[nestedKey], nestedKey, cards);
            }

            return cards;
        }

        // Separate arrays from the root object, and collect all object cards
        const arrayFields = {};
        for (const key in rowData) {
            if (!rowData.hasOwnProperty(key)) continue;
            if (isExcludedField(key)) continue;
            const value = rowData[key];
            if (Array.isArray(value)) {
                arrayFields[key] = value;
            }
        }

        // Collect all object cards (including root and all nested objects)
        const objectCards = collectObjectCards(rowData);

        // Render each object card as a separate card-header/card
        objectCards.forEach((card, idx) => {
            let cardHtml = `
                <div class="detail-section card${idx > 0 ? ' mt-3' : ''}">
                    <div class="card-header bg-primary text-white py-2 px-4">
                        <h5 class="section-title mb-0 text-white">${card.title}</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover detail-table mb-0">
                            <colgroup>
                                <col style="width: 40%">
                                <col style="width: 60%">
                            </colgroup>
                            <tbody>`;
                                for (const key in card.fields) {
                                    if (!card.fields.hasOwnProperty(key)) continue;
                                    const value = card.fields[key];
                                    const formattedKey = formatKey(key);
                                    const formattedValue = isStatusField(key, value) ? formatStatus(value) : formatValue(value);
                                    
                                    cardHtml += `
                                        <tr>
                                            <td class="detail-key">${formattedKey}</td>
                                            <td class="detail-value">${formattedValue}</td>
                                        </tr>`;
                                }
                                cardHtml += `
                            </tbody>
                        </table>
                    </div>
                </div>`;
            $contentContainer.append(cardHtml);
        });

        // Process array fields (arrays at root only)
        for (const key in arrayFields) {
            if (!arrayFields.hasOwnProperty(key)) continue;
            const value = arrayFields[key];
            const formattedKey = formatKey(key);

            let sectionHtml = `
                <div class="detail-section card mt-3">
                    <div class="card-header bg-primary text-white py-2 px-4">
                        <h5 class="section-title mb-0 text-white">${formattedKey}</h5>
                    </div>
                    <div class="card-body p-0">
            `;

            if (Array.isArray(value)) {
                if (value.length === 0) {
                    sectionHtml += `<p class="text-muted m-3">No ${formattedKey} available</p>`;
                } else {
                    // Flatten each array item (object) for table display
                    const filteredItems = value.map(item => {
                        let flat = {};
                        if (typeof item === 'object' && item !== null) {
                            // Flatten only one level for array items
                            for (const k in item) {
                                if (!isExcludedField(k)) {
                                    const v = item[k];
                                    if (
                                        v &&
                                        typeof v === 'object' &&
                                        !Array.isArray(v) &&
                                        !(v instanceof Date) &&
                                        !isStatusField(k, v)
                                    ) {
                                        // Merge inner object fields into parent (one level deep)
                                        for (const subk in v) {
                                            if (!isExcludedField(subk)) {
                                                flat[`${k}.${subk}`] = v[subk];
                                            }
                                        }
                                    } else {
                                        flat[k] = v;
                                    }
                                }
                            }
                        } else {
                            flat = { value: item };
                        }
                        return flat;
                    });

                    if (Object.keys(filteredItems[0]).length > 0) {
                        sectionHtml += `
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered detail-subtable mb-0">
                                    <colgroup>
                                        ${Object.keys(filteredItems[0]).map(() => `<col style="width: ${100/Object.keys(filteredItems[0]).length}%">`).join('')}
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            ${Object.keys(filteredItems[0]).map(k =>
                            `<th>${formatKey(k)}</th>`
                        ).join('')}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${filteredItems.map(item => `
                                            <tr>
                                                ${Object.values(item).map(v => `<td>${formatValue(v)}</td>`).join('')}
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        `;
                    } else {
                        sectionHtml += `<p class="text-muted m-3">No relevant ${formattedKey} data to display</p>`;
                    }
                }
            }

            sectionHtml += `</div></div>`;
            $contentContainer.append(sectionHtml);
        }

        // Combine all elements
        $moreDetailBody.append(header).append($contentContainer);
    });

// Helper function to identify status fields
// Enhanced function to identify status fields
function isStatusField(key, value) {
    const statusKeys = ['status', 'active', 'enabled', 'state'];
    const statusKeyPattern = /(^|_)(status|active|enabled|state)($|_)/i;

    return (statusKeys.some(k => key.toLowerCase().includes(k)) ||
            statusKeyPattern.test(key)) &&
        (typeof value === 'boolean' ||
            (typeof value === 'string' &&
                ['active', 'inactive', 'enabled', 'disabled', 'pending', 'approved', 'rejected']
                    .includes(value.toLowerCase())));
}

// Enhanced status formatting
function formatStatus(value) {
    if (value === null || value === undefined) return '<span class="text-muted">N/A</span>';

    if (typeof value === 'boolean') {
        return `<span class="badge bg-${value ? 'success' : 'secondary'}">${
            value ? 'Active' : 'Inactive'}</span>`;
    }

    const statusMap = {
        'active': 'success',
        'enabled': 'success',
        'approved': 'success',
        'inactive': 'secondary',
        'disabled': 'danger',
        'pending': 'warning',
        'rejected': 'danger'
    };

    const lowerValue = value.toLowerCase();
    const statusClass = statusMap[lowerValue] || 'info';
    const displayValue = value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();

    return `<span class="badge bg-${statusClass}">${displayValue}</span>`;
}

// Helper function to format keys
function formatKey(key) {
    return key.replace(/_/g, ' ')
        .replace(/(^|\s)\w/g, l => l.toUpperCase())
        .replace(/\b(Id|Uuid)\b/gi, '')
        .replace(/\bIs\b/gi, '');
}

// Helper function to format different value types
function formatValue(value) {
    if (value === null || value === undefined) {
        return '<span class="text-muted">N/A</span>';
    }

    if (typeof value === 'boolean') {
        return `<span class="badge bg-${value ? 'success' : 'secondary'}">${value ? 'Yes' : 'No'}</span>`;
    }

    if (typeof value === 'object' && !(value instanceof Date)) {
        return '<span class="text-muted">[Object]</span>';
    }

    if (value instanceof Date || !isNaN(Date.parse(value))) {
        const date = new Date(value);
        return date.toLocaleString();
    }

    return value;
}
