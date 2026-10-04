<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAMIS Service Contract - Arif Technology</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .contract-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1a2530 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }

        .logo {
            font-weight: 700;
            font-size: 1.8rem;
        }

        .logo span {
            color: var(--secondary);
        }

        .section-title {
            color: var(--primary);
            border-bottom: 2px solid var(--secondary);
            padding-bottom: 10px;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .pricing-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .pricing-card .card-header {
            background: var(--primary);
            color: white;
            text-align: center;
            font-weight: bold;
        }

        .price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--secondary);
        }

        .signature-area {
            border-top: 1px solid #ddd;
            padding-top: 30px;
            margin-top: 50px;
        }

        .highlight {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid var(--secondary);
            margin: 15px 0;
        }

        .contract-section {
            margin-bottom: 30px;
        }

        .contract-display {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 30px;
            margin-top: 30px;
            display: none;
            max-height: 600px;
            overflow-y: auto;
        }

        .contract-display h1, .contract-display h2, .contract-display h3 {
            color: var(--primary);
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin: 40px 0 20px 0;
            height: 1px;
        }

        .contract-footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 0.9em;
            color: #666;
        }

        .clause-number {
            font-weight: bold;
            color: var(--primary);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/aviation-public.css') }}">
</head>
<body class="operations-public">
<!-- Contract Header -->
<div class="contract-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="logo">ARIF<span> TECHNOLOGY</span></h1>
                <p class="lead mb-0">SAMIS Service Contract Generator</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0"><strong>Effective Date:</strong> <span id="currentDate"></span></p>
                <p class="mb-0"><strong>Contract ID:</strong> SAMIS-<span id="contractId"></span></p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Parties Section -->
    <div class="contract-section">
        <h2 class="section-title">PARTIES</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card pricing-card">
                    <div class="card-header">CONTRACTOR</div>
                    <div class="card-body">
                        <h5>Arif Technology</h5>
                        <p class="mb-1">Dar es Salaam, Tanzania</p>
                        <p class="mb-1">Email: info@arif.technology</p>
                        <p class="mb-0">Phone: +255 774 579 698</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card pricing-card">
                    <div class="card-header">CUSTOMER</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Institution Name *</label>
                            <input type="text" class="form-control" id="customerName" placeholder="Enter institution name" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerAddress" class="form-label">Address *</label>
                            <input type="text" class="form-control" id="customerAddress" placeholder="Enter institution address" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerEmail" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="customerEmail" placeholder="Enter institution email" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="customerPhone" placeholder="Enter institution phone">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Selection -->
    <div class="contract-section">
        <h2 class="section-title">SERVICE PLAN SELECTION</h2>
        <div class="row">
{{--            <div class="col-md-4">--}}
{{--                <div class="card pricing-card">--}}
{{--                    <div class="card-header">STARTER SCHOOLS</div>--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="price">TZS 1,000<span class="text-muted">/student/year</span></div>--}}
{{--                        <p class="card-text">For new & small schools</p>--}}
{{--                        <ul class="list-unstyled">--}}
{{--                            <li><i class="fas fa-check text-success"></i> Up to 100 students</li>--}}
{{--                            <li><i class="fas fa-check text-success"></i> Basic Student Management</li>--}}
{{--                            <li><i class="fas fa-check text-success"></i> Attendance Tracking</li>--}}
{{--                            <li><i class="fas fa-check text-success"></i> Simple Gradebook</li>--}}
{{--                        </ul>--}}
{{--                        <button class="btn btn-outline-primary w-100 select-plan" data-plan="starter">Select Plan</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-4">--}}
{{--                <div class="card pricing-card">--}}
{{--                    <div class="card-header">GROWING SCHOOLS</div>--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="price">TZS 1,500<span class="text-muted">/student/year</span></div>--}}
{{--                        <p class="card-text">For established & expanding schools</p>--}}
{{--                        <ul class="list-unstyled">--}}
{{--                            <li><i class="fas fa-check text-success"></i> Up to 500 students</li>--}}
{{--                            <li><i class="fas fa-check text-success"></i> All Starter Features</li>--}}
{{--                            <li><i class="fas fa-check text-success"></i> Financial Management</li>--}}
{{--                            <li><i class="fas fa-check text-success"></i> Examination Management</li>--}}
{{--                        </ul>--}}
{{--                        <button class="btn btn-primary w-100 select-plan" data-plan="growing">Select Plan</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="col-md-4">
                <div class="card pricing-card">
                    <div class="card-header">ESTABLISHED SCHOOLS</div>
                    <div class="card-body">
                        <div class="price">TZS 2,000<span class="text-muted">/student/year</span></div>
                        <p class="card-text">For large, well-established schools</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Unlimited students</li>
                            <li><i class="fas fa-check text-success"></i> All Growth Features</li>
                            <li><i class="fas fa-check text-success"></i> Complete Academic Management</li>
                            <li><i class="fas fa-check text-success"></i> Advanced Financial Management</li>
                            <li><i class="fas fa-check text-success"></i> Library Management</li>
                            <li><i class="fas fa-check text-success"></i> Hostel Management</li>
                            <li><i class="fas fa-check text-success"></i> Transport Management</li>
                            <li><i class="fas fa-check text-success"></i> Parent & Teacher Portals</li>
                            <li><i class="fas fa-times text-danger"></i> Student Portal</li>
                            <li><i class="fas fa-times text-danger"></i> E-Learning</li>
                            <li><i class="fas fa-times text-danger"></i> Event Management</li>
                            <li><i class="fas fa-times text-danger"></i> Admission System</li>
                            <li><i class="fas fa-times text-danger"></i> Website Inside</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100 select-plan" data-plan="established">Select Plan</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card pricing-card">
                    <div class="card-header">COLLEGES</div>
                    <div class="card-body">
                        <div class="price">TZS 450,000<span class="text-muted">/month</span></div>
                        <p class="card-text">For higher education institutions</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Unlimited students</li>
                            <li><i class="fas fa-check text-success"></i> Complete Academic Management</li>
                            <li><i class="fas fa-check text-success"></i> Advanced Financial Management</li>
                            <li><i class="fas fa-check text-success"></i> Library Management</li>
                            <li><i class="fas fa-check text-success"></i> Hostel Management</li>
                            <li><i class="fas fa-check text-success"></i> Transport Management</li>
                            <li><i class="fas fa-check text-success"></i> Student Portal</li>
                            <li><i class="fas fa-check text-success"></i> E-Learning</li>
                            <li><i class="fas fa-check text-success"></i> Event Management</li>
                            <li><i class="fas fa-check text-success"></i> Admission System</li>
                            <li><i class="fas fa-times text-danger"></i> Website Inside</li>
                            <li><i class="fas fa-times text-danger"></i> Research Management</li>
                            <li><i class="fas fa-times text-danger"></i> Multi-Campus Support</li>
                        </ul>
                        <button class="btn btn-outline-warning w-100 select-plan" data-plan="college">Select Plan</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card pricing-card">
                    <div class="card-header">UNIVERSITIES</div>
                    <div class="card-body">
                        <div class="price">TZS 750,000<span class="text-muted">/month</span></div>
                        <p class="card-text">For large university institutions</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Unlimited students</li>
                            <li><i class="fas fa-check text-success"></i> Complete Academic Management</li>
                            <li><i class="fas fa-check text-success"></i> Advanced Financial Management</li>
                            <li><i class="fas fa-check text-success"></i> Library Management</li>
                            <li><i class="fas fa-check text-success"></i> Hostel Management</li>
                            <li><i class="fas fa-check text-success"></i> Transport Management</li>
                            <li><i class="fas fa-check text-success"></i> Student Portal</li>
                            <li><i class="fas fa-check text-success"></i> E-Learning</li>
                            <li><i class="fas fa-check text-success"></i> Event Management</li>
                            <li><i class="fas fa-check text-success"></i> Admission System</li>
                            <li><i class="fas fa-check text-success"></i> Website Inside</li>
                            <li><i class="fas fa-check text-success"></i> Research Management</li>
                            <li><i class="fas fa-check text-success"></i> Multi-Campus Support</li>
                        </ul>
                        <button class="btn btn-outline-warning w-100 select-plan" data-plan="university">Select Plan</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Student Count Input -->
        <div class="mt-4" id="studentCountSection" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <h5>Student Enrollment Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="studentCount" class="form-label">Number of Students *</label>
                            <input type="number" class="form-control" id="studentCount" min="1" value="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Annual Cost</label>
                            <div class="alert alert-info">
                                <h4 id="annualCost">TZS 0</h4>
                                <small id="costBreakdown"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Actions -->
    <div class="contract-section text-center">
        <button class="btn btn-success btn-lg" id="generateContract">
            <i class="fas fa-file-contract"></i> Generate Complete Contract
        </button>
        <button class="btn btn-primary btn-lg ms-2" id="downloadPdf">
            <i class="fas fa-download"></i> Download as PDF
        </button>
    </div>

    <!-- Contract Display Area -->
    <div id="contractDisplay" class="contract-display">
        <!-- Contract content will be generated here -->
    </div>
</div>

<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">© 2023 Arif Technology. All rights reserved.</p>
        <p class="mb-0">Email: info@arif.technology | Phone: +255 774 579 698</p>
    </div>
</footer>

<script>
    // Set current date and contract ID
    document.getElementById('currentDate').textContent = new Date().toLocaleDateString();
    document.getElementById('contractId').textContent = Math.floor(100000 + Math.random() * 900000);

    // Plan selection functionality
    const planButtons = document.querySelectorAll('.select-plan');
    const studentCountSection = document.getElementById('studentCountSection');
    const studentCountInput = document.getElementById('studentCount');
    const annualCostDisplay = document.getElementById('annualCost');
    const costBreakdown = document.getElementById('costBreakdown');
    const contractDisplay = document.getElementById('contractDisplay');

    let selectedPlan = null;
    let pricePerStudent = 0;
    let planName = "";

    planButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            planButtons.forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary', 'btn-outline-warning');
            });

            // Add active class to selected button
            this.classList.remove('btn-outline-primary', 'btn-outline-warning');
            this.classList.add('btn-primary');

            selectedPlan = this.getAttribute('data-plan');

            // Set plan name for display
            switch(selectedPlan) {
                case 'starter':
                    planName = "Starter Schools Plan";
                    break;
                case 'growing':
                    planName = "Growing Schools Plan";
                    break;
                case 'established':
                    planName = "Established Schools Plan";
                    break;
                case 'college':
                    planName = "College Plan";
                    break;
                case 'university':
                    planName = "University Plan";
                    break;
            }

            // Show/hide student count section based on plan type
            if (['starter', 'growing', 'established'].includes(selectedPlan)) {
                studentCountSection.style.display = 'block';

                // Set price per student based on plan
                switch(selectedPlan) {
                    case 'starter':
                        pricePerStudent = 1000;
                        studentCountInput.max = 100;
                        studentCountInput.value = Math.min(studentCountInput.value, 100);
                        break;
                    case 'growing':
                        pricePerStudent = 1500;
                        studentCountInput.max = 500;
                        studentCountInput.value = Math.min(studentCountInput.value, 500);
                        break;
                    case 'established':
                        pricePerStudent = 2000;
                        studentCountInput.removeAttribute('max');
                        break;
                }

                updatePricingDisplay();
            } else {
                studentCountSection.style.display = 'none';

                // Set pricing for college/university plans
                if (selectedPlan === 'college') {
                    annualCostDisplay.textContent = 'TZS 350,000 / month';
                    costBreakdown.textContent = 'Flat monthly rate for colleges';
                } else if (selectedPlan === 'university') {
                    annualCostDisplay.textContent = 'TZS 650,000 / month';
                    costBreakdown.textContent = 'Flat monthly rate for universities';
                }
            }
        });
    });

    // Update pricing when student count changes
    studentCountInput.addEventListener('input', function() {
        if (['starter', 'growing', 'established'].includes(selectedPlan)) {
            updatePricingDisplay();
        }
    });

    function updatePricingDisplay() {
        const studentCount = parseInt(studentCountInput.value) || 0;
        const annualCost = studentCount * pricePerStudent;

        annualCostDisplay.textContent = `TZS ${annualCost.toLocaleString()}`;
        costBreakdown.textContent = `Based on ${studentCount} students at TZS ${pricePerStudent.toLocaleString()} per student per year`;
    }

    // Generate complete contract
    document.getElementById('generateContract').addEventListener('click', function() {
        if (!validateForm()) return;

        generateCompleteContract();
        contractDisplay.style.display = 'block';

        // Scroll to contract display
        contractDisplay.scrollIntoView({ behavior: 'smooth' });
    });

    // Download as PDF
    document.getElementById('downloadPdf').addEventListener('click', function() {
        if (!validateForm()) return;

        generateCompleteContract();
        downloadAsPdf();
    });

    function validateForm() {
        const customerName = document.getElementById('customerName').value;
        const customerAddress = document.getElementById('customerAddress').value;
        const customerEmail = document.getElementById('customerEmail').value;

        if (!customerName || !customerAddress || !customerEmail) {
            alert('Please fill in all required customer information (marked with *).');
            return false;
        }

        if (!selectedPlan) {
            alert('Please select a service plan first.');
            return false;
        }

        if (['starter', 'growing', 'established'].includes(selectedPlan)) {
            const studentCount = parseInt(studentCountInput.value);
            if (!studentCount || studentCount < 1) {
                alert('Please enter a valid student count.');
                return false;
            }

            if (selectedPlan === 'starter' && studentCount > 100) {
                alert('Starter plan is limited to 100 students. Please select Growing Schools plan for more students.');
                return false;
            }

            if (selectedPlan === 'growing' && studentCount > 500) {
                alert('Growing Schools plan is limited to 500 students. Please select Established Schools plan for more students.');
                return false;
            }
        }

        return true;
    }

    function generateCompleteContract() {
        const customerName = document.getElementById('customerName').value;
        const customerAddress = document.getElementById('customerAddress').value;
        const customerEmail = document.getElementById('customerEmail').value;
        const customerPhone = document.getElementById('customerPhone').value;
        const studentCount = parseInt(studentCountInput.value) || 0;
        const effectiveDate = new Date().toLocaleDateString();
        const contractId = document.getElementById('contractId').textContent;

        let pricingDetails = '';
        let paymentTerms = '';
        let totalCost = '';

        if (['starter', 'growing', 'established'].includes(selectedPlan)) {
            const annualCost = studentCount * pricePerStudent;
            totalCost = `TZS ${annualCost.toLocaleString()}`;
            pricingDetails = `
                    <p><span class="clause-number">4.1 PRICING MODEL.</span> Services are provided on a per-student subscription basis according to the ${planName}.</p>
                    <p><span class="clause-number">4.2 STUDENT COUNT.</span> Customer shall pay TZS ${pricePerStudent.toLocaleString()} per student per year for ${studentCount} students, totaling ${totalCost} annually.</p>
                    <p><span class="clause-number">4.3 STUDENT VERIFICATION.</span> Customer shall provide accurate student enrollment numbers at the beginning of each academic year. Pricing will be adjusted based on verified counts.</p>
                `;
            paymentTerms = `
                    <p><span class="clause-number">4.4 PAYMENT SCHEDULE.</span> Customer will be invoiced annually for the total amount of ${totalCost}. Payment is due within 30 days of invoice date. Customer may elect to pay in quarterly installments (25% each quarter).</p>
                `;
        } else {
            const monthlyCost = selectedPlan === 'college' ? 'TZS 350,000' : 'TZS 650,000';
            totalCost = `${monthlyCost} per month`;
            pricingDetails = `
                    <p><span class="clause-number">4.1 PRICING MODEL.</span> Services are provided on a flat monthly subscription basis according to the ${planName}.</p>
                    <p><span class="clause-number">4.2 MONTHLY FEE.</span> Customer shall pay ${monthlyCost} per month for unlimited students and full system access.</p>
                `;
            paymentTerms = `
                    <p><span class="clause-number">4.3 PAYMENT SCHEDULE.</span> Customer will be invoiced monthly. Payment is due within 30 days of invoice date.</p>
                `;
        }

        const contractHTML = `
                <div class="contract-content">
                    <div class="text-center mb-4" style="text-align: center;">
                        <h1 class="logo">ARIF<span> TECHNOLOGY</span></h1>
                        <h2>SOFTWARE SERVICE CONTRACT</h2>
                        <p><strong>Contract ID:</strong> SAMIS-${contractId} | <strong>Effective Date:</strong> ${effectiveDate}</p>
                    </div>

                    <div class="highlight">
                        <p>This SERVICE CONTRACT (this "Agreement"), effective as of ${effectiveDate}, is made and entered into by and between <strong>${customerName}</strong> ("Customer"), with offices located at ${customerAddress}, and <strong>Arif Technology</strong> ("Contractor"), with offices located at Dar es Salaam, Tanzania.</p>
                    </div>

                    <p>Whereas, Contractor and Customer desire to enter into a relationship in which Contractor will provide the Student Academic Management Integrated System (SAMIS) services as described herein.</p>

                    <p>Now, therefore, in consideration of the premises, and of the mutual promises and undertakings herein contained, the parties, intending to be legally bound, do hereby agree as follows:</p>

                    <h3>1. DEFINITIONS</h3>
                    <p><span class="clause-number">1.1 "Services"</span> means the SAMIS software services specified in this Agreement.</p>
                    <p><span class="clause-number">1.2 "Deliverables"</span> means the SAMIS software platform and associated documentation delivered to Customer under this Service Contract.</p>
                    <p><span class="clause-number">1.3 "Project"</span> means the combination of Services and Deliverables to be provided under this Agreement.</p>
                    <p><span class="clause-number">1.4 "Subscription Term"</span> means the period during which Customer is entitled to access and use the SAMIS services.</p>

                    <h3>2. SERVICES</h3>
                    <p><span class="clause-number">2.1 SERVICE DESCRIPTION.</span> Contractor shall provide the following SAMIS services:</p>
                    <ul>
                        <li>Access to the Advanced Student Management System (Shared Package)</li>
                        <li>24/7 technical support and system availability with 99.5% uptime guarantee</li>
                        <li>Regular system updates and maintenance</li>
                        <li>Customization consultations (up to 10 hours monthly)</li>
                        <li>Premium hosting with SSL security certificate</li>
                        <li>Daily system and database backups with 30-day retention</li>
                        <li>Unlimited user accounts and student records</li>
                    </ul>

                    <h3>3. TERM</h3>
                    <p><span class="clause-number">3.1 INITIAL TERM.</span> The initial term of this Agreement shall commence on the Effective Date and continue for one (1) year.</p>
                    <p><span class="clause-number">3.2 RENEWAL.</span> This Agreement shall automatically renew for successive one-year terms unless either party provides written notice of non-renewal at least sixty (60) days prior to the expiration of the then-current term.</p>

                    <h3>4. TERMS OF PAYMENT</h3>
                    ${pricingDetails}
                    ${paymentTerms}
                    <p><span class="clause-number">4.5 LATE PAYMENTS.</span> Invoices are due within 30 days of invoice date. Interest may be charged on all amounts unpaid after 30 days at the annual rate of 1.5% per month or the highest legal rate, whichever is lower.</p>
                    <p><span class="clause-number">4.6 TAXES.</span> The Project Price does not include and Customer is responsible for all taxes (except taxes on Contractor's income) tariffs, and any similar charges.</p>

                    <h3>5. SUBSCRIPTION TERMS</h3>
                    <p><span class="clause-number">5.1 AUTOMATIC RENEWAL.</span> This Agreement shall automatically renew for successive one-year terms unless either party provides written notice of non-renewal at least 60 days prior to the expiration of the then-current term.</p>
                    <p><span class="clause-number">5.2 PRICE ADJUSTMENTS.</span> Contractor may adjust pricing with 90 days written notice. Price changes will not take effect until the next billing cycle.</p>
                    <p><span class="clause-number">5.3 PRORATED REFUNDS.</span> In case of early termination, Customer shall receive a prorated refund for any prepaid services not yet rendered.</p>

                    <h3>6. ACCEPTANCE</h3>
                    <p><span class="clause-number">6.1 ACCEPTANCE TEST.</span> The SAMIS platform shall be deemed accepted by Customer upon completion of the following acceptance test:</p>
                    <p><span class="clause-number">6.2 TESTING PERIOD.</span> Customer shall have five (5) business days from initial system access to test the platform. If no written notice of non-conformity is provided within this period, the system shall be deemed accepted.</p>
                    <p><span class="clause-number">6.3 NON-CONFORMITY.</span> If Customer provides a written statement of nonconformities, Contractor will redeliver corrected Deliverables within a reasonable time.</p>

                    <h3>7. WARRANTIES AND REMEDIES</h3>
                    <p><span class="clause-number">7.1 SERVICE WARRANTY.</span> Contractor warrants that the SAMIS services will perform substantially as described in this Agreement for the duration of the subscription term.</p>
                    <p><span class="clause-number">7.2 SUPPORT WARRANTY.</span> Contractor warrants 24/7 system availability with uptime guarantee of 99.5%, excluding scheduled maintenance periods.</p>
                    <p><span class="clause-number">7.3 INTELLECTUAL PROPERTY WARRANTY.</span> Contractor warrants that to its knowledge the Deliverables do not infringe any intellectual property right held by a third party.</p>
                    <p><span class="clause-number">7.4 REMEDIES.</span> Customer's sole and exclusive remedy and Contractor's only obligation for breach of warranty will be, at Contractor's option, to correct any material errors in provision of Services.</p>
                    <p><span class="clause-number">7.5 DISCLAIMER.</span> Except for the warranties stated in this Section, Contractor DISCLAIMS ALL OTHER WARRANTIES, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.</p>

                    <h3>8. LIMITATION OF LIABILITY</h3>
                    <p><span class="clause-number">8.1 LIABILITY CAP.</span> The total liability of Contractor to Customer from any cause whatsoever, will be limited to the lesser of Customer's actual damages or the Project price paid to Contractor for those Services and Deliverables in a Project that are the subject of Customer's claim.</p>
                    <p><span class="clause-number">8.2 EXCLUDED DAMAGES.</span> In no event will either party be liable for SPECIAL, INDIRECT, CONSEQUENTIAL, OR INCIDENTAL DAMAGES, including but not limited to loss of profits, revenues, data or power.</p>
                    <p><span class="clause-number">8.3 TIME FOR CLAIMS.</span> All claims against Contractor must be brought within one (1) year after the cause of action arises.</p>

                    <h3>9. INDEMNIFICATION</h3>
                    <p><span class="clause-number">9.1 CUSTOMER INDEMNIFICATION.</span> Customer shall defend, indemnify, and save Contractor harmless, at Customer's own expense, against any action or suit brought for any loss, damage, expense or liability that may result by reason of Customer's use of the Deliverables.</p>
                    <p><span class="clause-number">9.2 CONTRACTOR INDEMNIFICATION.</span> Contractor shall defend, indemnify, and hold Customer harmless against claims that the Services infringe any third-party intellectual property rights.</p>

                    <h3>10. CONFIDENTIALITY</h3>
                    <p><span class="clause-number">10.1 CONFIDENTIAL INFORMATION.</span> Both parties acknowledge that during the course of performance, information of a confidential nature may be disclosed. Such information shall be considered confidential information ("Confidential Information").</p>
                    <p><span class="clause-number">10.2 NON-DISCLOSURE.</span> Neither party has the right to disclose the Confidential Information of the other, in whole or in part, to any third party without prior written consent.</p>
                    <p><span class="clause-number">10.3 PROTECTION.</span> Each party agrees to take all steps reasonable to protect the other's Confidential Information from unauthorized use and/or disclosure.</p>

                    <h3>11. TERMINATION</h3>
                    <p><span class="clause-number">11.1 TERMINATION FOR CAUSE.</span> Either party may terminate this Agreement for material breach upon 30 days written notice if the breach is not cured within that period.</p>
                    <p><span class="clause-number">11.2 EFFECT OF TERMINATION.</span> Upon termination, Customer's access to the SAMIS services will be discontinued, and a prorated refund will be issued for any prepaid subscription fees.</p>
                    <p><span class="clause-number">11.3 SURVIVAL.</span> Sections regarding Payment, Limitation of Liability, Indemnification, and Confidentiality shall survive termination.</p>

                    <h3>12. FORCE MAJEURE</h3>
                    <p><span class="clause-number">12.1 NON-LIABILITY.</span> Neither party shall be liable for failure to perform under this Agreement for any delay or failure in performance resulting from causes beyond its reasonable control.</p>

                    <h3>13. GENERAL TERMS</h3>
                    <p><span class="clause-number">13.1 GOVERNING LAW.</span> This Service Contract shall be construed in accordance with the laws of Tanzania.</p>
                    <p><span class="clause-number">13.2 NOTICES.</span> Notices to be given by either party under this Agreement shall be sent by certified mail or email to the addresses first set forth above.</p>
                    <p><span class="clause-number">13.3 ASSIGNMENT.</span> This Agreement may not be assigned by Customer without Contractor's consent.</p>
                    <p><span class="clause-number">13.4 ENTIRE AGREEMENT.</span> This Agreement constitutes the final and entire Agreement between Contractor and Customer and supersedes all prior agreements.</p>

                    <div class="signature-area">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>CONTRACTOR</h4>
                                <p>Arif Technology</p>
                                <div class="signature-line"></div>
                                <p>Name: _________________________</p>
                                <p>Title: _________________________</p>
                                <p>Date: _________________________</p>
                            </div>
                            <div class="col-md-6">
                                <h4>CUSTOMER</h4>
                                <p>${customerName}</p>
                                <div class="signature-line"></div>
                                <p>Name: _________________________</p>
                                <p>Title: _________________________</p>
                                <p>Date: _________________________</p>
                            </div>
                        </div>
                    </div>

                    <div class="contract-footer text-center">
                        <p>This document constitutes a legally binding contract. Please review carefully before signing.</p>
                        <p>Generated by Arif Technology SAMIS Contract System on ${new Date().toLocaleString()}</p>
                    </div>
                </div>
            `;

        contractDisplay.innerHTML = contractHTML;
    }

    function downloadAsPdf() {
        // In a real implementation, this would use jsPDF to create a PDF
        // For this example, we'll create a printable version

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
                <html>
                    <head>
                        <title>SAMIS Service Contract - ${document.getElementById('customerName').value}</title>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; margin: 40px; }
                            .clause-number { font-weight: bold; }
                            .signature-line { border-bottom: 1px solid #000; margin: 40px 0 20px 0; }
                            .contract-footer { margin-top: 50px; font-size: 0.9em; color: #666; text-align: center; }
                            @media print { body { margin: 0; } }
                        </style>
                    </head>
                    <body>
                        ${document.querySelector('.contract-content').innerHTML}
                    </body>
                </html>
            `);
        printWindow.document.close();
        printWindow.print();
    }
</script>
</body>
</html>
