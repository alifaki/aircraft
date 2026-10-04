<!-- PDF Viewer Modal -->
<div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-labelledby="pdfViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="modal-title text-white" id="pdfViewerModalLabel">PDF Viewer</h5>
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group me-2" role="group">
                        <button class="btn btn-sm btn-light pdf-btn" id="zoom-in" title="Zoom In">
                            <i class="ti ti-zoom-in"></i>
                        </button>
                        <button class="btn btn-sm btn-light pdf-btn" id="zoom-out" title="Zoom Out">
                            <i class="ti ti-zoom-out"></i>
                        </button>
                        <button class="btn btn-sm btn-light pdf-btn" id="fit-width" title="Fit Width">
                            <i class="ti ti-arrows-maximize"></i>
                        </button>
                        <button class="btn btn-sm btn-light pdf-btn" id="print-pdf" title="Print PDF">
                            <i class="ti ti-printer"></i> Print
                        </button>
                    </div>
                    <div class="btn-group me-2" role="group">
                        <button class="btn btn-sm btn-light pdf-btn" id="prev-page" title="Previous Page">
                            <i class="ti ti-chevron-left"></i>
                        </button>
                        <span class="text-white mx-2" id="page-info">Page: 1/1</span>
                        <button class="btn btn-sm btn-light pdf-btn" id="next-page" title="Next Page">
                            <i class="ti ti-chevron-right"></i>
                        </button>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body p-0 bg-dark">
                <div id="pdf-container" class="d-flex justify-content-center align-items-start h-100">
                    <canvas id="pdf-canvas" class="mx-auto"></canvas>
                </div>
            </div>

            <div class="modal-footer bg-dark text-white">
                <div class="d-flex justify-content-between w-100">
                    <span id="pdf-title" class="fw-bold"></span>
                    <span id="pdf-metadata" class="text-muted"></span>
                    <span id="pdf-page-range" class="text-info"></span>
                </div>
            </div>
        </div>
    </div>
</div>

@push("styles")
<style>
    #pdf-container {
        overflow: auto;
        transition: all 0.3s ease;
    }
    #pdf-canvas {
        border: 1px solid #444;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
        max-width: 100%;
        transition: margin-top 0.3s ease, transform 0.3s ease;
    }
    .pdf-btn {
        border-radius: 6px !important;
        border: 1px solid #ccc !important;
        background-color: #f8f9fa !important;
        color: #333 !important;
        transition: all 0.2s ease;
        margin-right: 3px; 
    }
    .pdf-btn:hover {
        background-color: #e9ecef !important;
        transform: translateY(-1px);
    }
    .pdf-btn.active {
        background-color: #007bff !important;
        color: white !important;
    }
    #page-info {
        font-weight: 600;
    }
</style>
@endpush

@push("scripts")
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

document.addEventListener("DOMContentLoaded", function () {
    let pdfDoc = null,
        pageNum = 1,
        pageRendering = false,
        pageNumPending = null,
        scale = 1.2,
        isFitWidth = false,
        previousScale = 1.2,
        requestedPages = null, // Array of pages to show
        currentPdfUrl = null;

    const canvas = document.getElementById('pdf-canvas');
    const ctx = canvas.getContext('2d');
    const container = document.getElementById('pdf-container');
    const fitBtn = document.getElementById('fit-width');

    // Function to parse page range (e.g., "1-4,5,3")
    function parsePageRange(pageRange) {
        if (!pageRange) return null;
        
        const pages = new Set();
        const parts = pageRange.split(',');
        
        for (const part of parts) {
            const trimmedPart = part.trim();
            if (trimmedPart.includes('-')) {
                // Handle range
                const [start, end] = trimmedPart.split('-').map(num => parseInt(num.trim()));
                if (!isNaN(start) && !isNaN(end) && start <= end) {
                    for (let i = start; i <= end; i++) {
                        pages.add(i);
                    }
                }
            } else {
                // Handle single page
                const page = parseInt(trimmedPart);
                if (!isNaN(page)) {
                    pages.add(page);
                }
            }
        }
        
        return Array.from(pages).sort((a, b) => a - b);
    }

    // Function to get the actual page number based on requested pages
    function getActualPageNumber(displayPageNum) {
        if (!requestedPages || requestedPages.length === 0) {
            return displayPageNum;
        }
        return requestedPages[displayPageNum - 1];
    }

    // Function to get display page number
    function getDisplayPageNumber(actualPageNum) {
        if (!requestedPages || requestedPages.length === 0) {
            return actualPageNum;
        }
        return requestedPages.indexOf(actualPageNum) + 1;
    }

    function adjustCanvasMargin() {
        const baseMargin = 100;
        const dynamicMargin = Math.max(50, baseMargin / scale);
        canvas.style.marginTop = `${dynamicMargin}px`;
    }

    function renderPage(num) {
        pageRendering = true;
        
        // Get the actual PDF page number
        const actualPageNum = getActualPageNumber(num);
        
        if (actualPageNum < 1 || actualPageNum > pdfDoc.numPages) {
            console.error('Invalid page number:', actualPageNum);
            return;
        }

        pdfDoc.getPage(actualPageNum).then(function(page) {
            const viewport = page.getViewport({ scale });
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            const renderTask = page.render({ canvasContext: ctx, viewport });

            renderTask.promise.then(function() {
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
                
                // Update page info
                const totalDisplayPages = requestedPages ? requestedPages.length : pdfDoc.numPages;
                document.getElementById('page-info').textContent = 
                    `Page: ${num}/${totalDisplayPages} (Actual: ${actualPageNum})`;
                adjustCanvasMargin();
            });
        });
    }

    function queueRenderPage(num) {
        if (pageRendering) pageNumPending = num;
        else renderPage(num);
    }

    function zoomIn() {
        isFitWidth = false;
        fitBtn.classList.remove('active');
        scale = Math.min(scale + 0.1, 5);
        queueRenderPage(pageNum);
    }

    function zoomOut() {
        isFitWidth = false;
        fitBtn.classList.remove('active');
        scale = Math.max(scale - 0.1, 0.5);
        queueRenderPage(pageNum);
    }

    function toggleFitWidth() {
        if (!isFitWidth) {
            previousScale = scale;
            const actualPageNum = getActualPageNumber(pageNum);
            pdfDoc.getPage(actualPageNum).then(page => {
                const viewport = page.getViewport({ scale: 1 });
                scale = (container.clientWidth - 40) / viewport.width;
                isFitWidth = true;
                fitBtn.classList.add('active');
                queueRenderPage(pageNum);
            });
        } else {
            scale = previousScale;
            isFitWidth = false;
            fitBtn.classList.remove('active');
            queueRenderPage(pageNum);
        }
    }

    // Print functionality
    function printPDF() {
        if (!pdfDoc) return;

        const printWindow = window.open('', '_blank');
        const totalDisplayPages = requestedPages ? requestedPages.length : pdfDoc.numPages;
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Print PDF - ${document.getElementById('pdf-title').textContent}</title>
                <style>
                    body { margin: 0; padding: 20px; }
                    .pdf-page { margin-bottom: 20px; page-break-after: always; }
                    .pdf-page:last-child { page-break-after: auto; }
                    .page-header { text-align: center; margin-bottom: 10px; font-size: 12px; color: #666; }
                    @media print {
                        body { padding: 0; }
                        .pdf-page { margin-bottom: 0; }
                    }
                </style>
            </head>
            <body>
                <div id="pdf-content"></div>
            </body>
            </html>
        `);

        // Render all requested pages for printing
        const renderPages = [];
        const pagesToRender = requestedPages || Array.from({length: pdfDoc.numPages}, (_, i) => i + 1);
        
        pagesToRender.forEach((pageNumber, index) => {
            renderPages.push(
                pdfDoc.getPage(pageNumber).then(page => {
                    const viewport = page.getViewport({ scale: 1.5 });
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    
                    return page.render({ canvasContext: ctx, viewport }).promise.then(() => {
                        return {
                            image: canvas.toDataURL('image/png'),
                            pageNum: pageNumber,
                            displayNum: index + 1
                        };
                    });
                })
            );
        });

        Promise.all(renderPages).then(renderedPages => {
            let contentHtml = '';
            renderedPages.forEach(page => {
                contentHtml += `
                    <div class="pdf-page">
                        <div class="page-header">
                            Page ${page.displayNum} of ${totalDisplayPages} (Original: ${page.pageNum}) | 
                            ${document.getElementById('pdf-title').textContent}
                        </div>
                        <img src="${page.image}" style="width: 100%; height: auto;">
                    </div>
                `;
            });
            
            printWindow.document.getElementById('pdf-content').innerHTML = contentHtml;
            printWindow.document.close();
            
            // Trigger print after content is loaded
            setTimeout(() => {
                printWindow.print();
                // printWindow.close(); // Uncomment if you want to auto-close after print
            }, 500);
        });
    }

    // Event Listeners
    document.getElementById('prev-page').addEventListener('click', () => { 
        if (pageNum > 1) { 
            pageNum--; 
            queueRenderPage(pageNum); 
        } 
    });
    
    document.getElementById('next-page').addEventListener('click', () => { 
        const totalDisplayPages = requestedPages ? requestedPages.length : pdfDoc.numPages;
        if (pageNum < totalDisplayPages) { 
            pageNum++; 
            queueRenderPage(pageNum); 
        } 
    });
    
    document.getElementById('zoom-in').addEventListener('click', zoomIn);
    document.getElementById('zoom-out').addEventListener('click', zoomOut);
    fitBtn.addEventListener('click', toggleFitWidth);
    document.getElementById('print-pdf').addEventListener('click', printPDF);

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ($('#pdfViewerModal').is(':visible')) {
            switch(e.key) {
                case 'ArrowLeft': 
                    if (pageNum > 1) pageNum--, queueRenderPage(pageNum); 
                    break;
                case 'ArrowRight': 
                    const totalDisplayPages = requestedPages ? requestedPages.length : pdfDoc.numPages;
                    if (pageNum < totalDisplayPages) pageNum++, queueRenderPage(pageNum); 
                    break;
                case '+': 
                case '=': 
                    e.preventDefault(); 
                    zoomIn(); 
                    break;
                case '-': 
                    e.preventDefault(); 
                    zoomOut(); 
                    break;
                case '0': 
                    e.preventDefault(); 
                    toggleFitWidth(); 
                    break;
                case 'p': 
                case 'P': 
                    if (e.ctrlKey || e.metaKey) {
                        e.preventDefault();
                        printPDF();
                    }
                    break;
            }
        }
    });

    // Load PDF dynamically - Updated for both view types
    $(document).on('click', '.view-pdf', function() {
        const pdfUrl = $(this).data('url');
        const title = $(this).data('title') || 'Untitled PDF';
        const metadata = $(this).data('metadata') || {};
        const isRequestedPageView = $(this).hasClass('show-only-requested-page');
        
        // Get printing_pages from the table row
        const printingPages = $(this).closest('tr').find('td:eq(2)').text().trim();
        
        // Parse requested pages if this is the "Print" button and pages exist
        if (isRequestedPageView && printingPages) {
            requestedPages = parsePageRange(printingPages);
        } else {
            requestedPages = null; // Show all pages
        }

        $('#pdf-title').text(title);
        $('#pdf-metadata').text('Loading PDF...');
        
        // Show page range info if applicable
        if (requestedPages && requestedPages.length > 0) {
            $('#pdf-page-range').text(`Showing pages: ${printingPages}`);
        } else {
            $('#pdf-page-range').text('');
        }

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#333';
        ctx.font = '16px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Loading PDF...', canvas.width / 2, canvas.height / 2);

        $('#pdfViewerModal').modal('show');

        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
            pdfDoc = pdf;
            pageNum = 1;
            scale = 1.2;
            currentPdfUrl = pdfUrl;
            
            // Validate requested pages
            if (requestedPages) {
                requestedPages = requestedPages.filter(page => page >= 1 && page <= pdf.numPages);
                if (requestedPages.length === 0) {
                    requestedPages = null;
                    $('#pdf-page-range').text('No valid pages in range');
                }
            }
            
            renderPage(pageNum);
            
            const metaInfo = [];
            if (metadata.author) metaInfo.push(`Author: ${metadata.author}`);
            if (metadata.publisher) metaInfo.push(`Publisher: ${metadata.publisher}`);
            $('#pdf-metadata').text(metaInfo.join(' | '));
            
        }).catch(function(error) {
            toastr.error('Failed to load PDF: ' + error.message);
            $('#pdf-metadata').text('Error loading PDF');
        });
    });

    // Cleanup when modal closes
    $('#pdfViewerModal').on('hidden.bs.modal', function() {
        if (pdfDoc) {
            pdfDoc.destroy();
            pdfDoc = null;
        }
        isFitWidth = false;
        fitBtn.classList.remove('active');
        requestedPages = null;
        currentPdfUrl = null;
    });
});
</script>
@endpush
