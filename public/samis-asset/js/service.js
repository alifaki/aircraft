let closestDeleteRow = null;
const tableData = $('.dataTable');
$(document).on("click", '.confirm-delete', function (event) {
    event.preventDefault();
    const $this = $(this);
    $("#confirm-delete-btn")
        .attr("data-url", $this.data("url"))
        .attr("data-label", $this.data("label"));
    closestDeleteRow = $(this).closest("tr");
    $("#confirm-delete-progress").html(`Are you sure you want to delete <strong>${$this.data("label")}</strong>?`);
}).
on("click", "#confirm-delete-btn", async function () {
    const $this = $(this);
    const $url = $this.attr("data-url");
    const $label = $this.attr("data-label");
    const $deleteProgress = $("#confirm-delete-progress");
    const btnIcon = $(".d-btn");
    $this.attr("disabled", true);
    $deleteProgress.html(`<div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div> Deleting ${$label}, please wait...`);

    btnIcon.removeClass("ti-trash");
    btnIcon.addClass("ti-loader", "fa-spin");
    try {
        const response = await fetch($url, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        const data = await response.json();
        if (response.ok) {
            const dataTable = tableData.DataTable();
            dataTable.row(closestDeleteRow).remove().draw(false);

            if (dataTable.rows().count() === 0) {
                dataTable.clear().draw();
            } else {
                $(".dataTable tbody tr").each(function (index) {
                    $(this).find("td:first").text(index + 1);
                });
            }
            $deleteProgress.html(`${$label} deleted successfully`);
            showToast('success', 'Success', `${$label} deleted successfully`);
        }else{
            throw new Error(`${data.message || "Unknown error occur"}`);
        }
    } catch (error) {
        $deleteProgress.html(`Failed to delete ${$label}: ${error.message}`);
        showToast('error', 'Error', error.message || `Failed to delete ${$label}`);
    } finally {
        btnIcon.removeClass("ti-loader", "fa-spin");
        btnIcon.addClass("ti-trash");
        $this.attr("disabled", false);
        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('confirm-delete'));
        deleteModal.hide();
    }
});

// Print invoice
$(document).on('click', '.-print-invoice', function() {
    const invoiceContent = $('#detail-info-body').html();
    const printWindow = window.open('', '#');

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Bill Invoice</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    color: #333;
                }
                .invoice-container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #eee;
                }
                .invoice-header {
                    text-align: center;
                    margin-bottom: 20px;
                    padding-bottom: 20px;
                    border-bottom: 1px solid #eee;
                }
                .invoice-header h3 {
                    margin: 0;
                    color: #333;
                }
                .invoice-header p {
                    margin: 5px 0;
                }
                .table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                .table th, .table td {
                    padding: 8px;
                    border: 1px solid #ddd;
                }
                .table th {
                    background-color: #f5f5f5;
                    text-align: left;
                }
                .text-end {
                    text-align: right;
                }
                .card {
                    margin-bottom: 20px;
                    border: 1px solid #eee;
                    border-radius: 4px;
                }
                .card-header {
                    background-color: #f5f5f5;
                    padding: 10px 15px;
                    border-bottom: 1px solid #eee;
                }
                .card-body {
                    padding: 15px;
                }
                .badge {
                    display: inline-block;
                    padding: 3px 7px;
                    font-size: 12px;
                    font-weight: bold;
                    line-height: 1;
                    color: white;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: baseline;
                    border-radius: 10px;
                }
                .bg-success {
                    background-color: #28a745;
                }
                .bg-danger {
                    background-color: #dc3545;
                }
                .bg-warning {
                    background-color: #ffc107;
                }
                .bg-secondary {
                    background-color: #6c757d;
                }
                .alert {
                    padding: 15px;
                    margin-bottom: 20px;
                    border: 1px solid transparent;
                    border-radius: 4px;
                }
                .alert-danger {
                    color: #721c24;
                    background-color: #f8d7da;
                    border-color: #f5c6cb;
                }
                @media print {
                    body {
                        padding: 0;
                    }
                    .invoice-container {
                        border: none;
                        padding: 0;
                    }
                    .no-print {
                        display: none !important;
                    }
                    .page-break {
                        page-break-after: always;
                    }
                }
            </style>
        </head>
        <body>
            <div class="invoice-container">
                ${invoiceContent}
            </div>
            <script>
                window.onload = function() {
                    setTimeout(function() {
                        window.print();
                        window.close();
                    }, 200);
                };
            </script>
        </body>
        </html>
    `);
    printWindow.document.close();
});
$(document).on('click', '#confirm-send-notification', async function () {
    const phoneNumber = $(this).data('browse');
    const $btn = $(this);
    const originalText = $btn.text();

    try {
        $btn.prop('disabled', true).html('<span class="ti ti-loader fa fa-spin"></span> Sending notification...');

        const response = await fetch('/web/v1/send-notification', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                phone: phoneNumber,
                textMessage: $('#notification-message').val()
            })
        });

        let result = {};
        try {
            result = await response.json();
        } catch {}

        if (response.ok) {
            toastr.success(result.message || 'Notification sent successfully');
        } else {
            toastr.error(result.message || 'Failed to send notification');
        }
    } catch (error) {
        toastr.error(error.message || 'An error occurred');
    } finally {
        $btn.prop('disabled', false).text(originalText);
    }
});
