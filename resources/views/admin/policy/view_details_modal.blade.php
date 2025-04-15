<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel"
        aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="viewDetailsModalLabel">
                    <i class="fa-solid fa-file-invoice me-2"></i>Policy Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <!-- Policy Information Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0">
                                        <i class="fa-solid fa-shield-halved text-primary me-2"></i>Policy Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box me-3 bg-light-primary rounded-circle p-2">
                                                    <i class="fa-solid fa-hashtag text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small">Policy Number</div>
                                                    <div class="fw-bold" id="modal-policy-no">POL/2023/001</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box me-3 bg-light-info rounded-circle p-2">
                                                    <i class="fa-solid fa-car-burst text-info"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small">Policy Type</div>
                                                    <div class="fw-bold" id="modal-policy-type">Auto Insurance</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box me-3 bg-light-success rounded-circle p-2">
                                                    <i class="fa-solid fa-calendar-days text-success"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small">Policy Date</div>
                                                    <div class="fw-bold" id="modal-policy-date">Apr 15, 2025</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box me-3 bg-light-danger rounded-circle p-2">
                                                    <i class="fa-solid fa-building text-danger"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small">Insurance Company</div>
                                                    <div class="fw-bold" id="modal-company">Royal Sundaram</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer & Agent Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0">
                                        <i class="fa-solid fa-user text-primary me-2"></i>Customer Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar bg-light-primary rounded-circle p-2 me-3">
                                            <i class="fa-solid fa-user-tie fs-4 text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Customer Name</div>
                                            <div class="h5 mb-0" id="modal-customer-name">John Doe</div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box me-3 bg-light-info rounded-circle p-2">
                                            <i class="fa-solid fa-credit-card text-info"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Payment By</div>
                                            <div class="badge bg-primary" id="modal-payment">Agent Paid</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0">
                                        <i class="fa-solid fa-user-tag text-primary me-2"></i>Agent Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar bg-light-success rounded-circle p-2 me-3">
                                            <i class="fa-solid fa-user-gear fs-4 text-success"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Agent Name</div>
                                            <div class="h5 mb-0" id="modal-agent">Michael Johnson</div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box me-3 bg-light-warning rounded-circle p-2">
                                            <i class="fa-solid fa-percent text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Commission</div>
                                            <div class="badge bg-success" id="modal-commission"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Financial Details Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0">
                                        <i class="fa-solid fa-money-bill-wave text-primary me-2"></i>Financial Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4 col-6">
                                            <div class="financial-item p-3 rounded bg-light-primary">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-muted small">Premium</span>
                                                    <i class="fa-solid fa-indian-rupee-sign text-primary"></i>
                                                </div>
                                                <h5 class="mb-0 fw-bold" id="modal-premium">₹25,000</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <div class="financial-item p-3 rounded bg-light-success">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-muted small">Net Amount</span>
                                                    <i class="fa-solid fa-sack-dollar text-success"></i>
                                                </div>
                                                <h5 class="mb-0 fw-bold" id="modal-net-amount">₹22,500</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <div class="financial-item p-3 rounded bg-light-warning">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-muted small">GST</span>
                                                    <i class="fa-solid fa-receipt text-warning"></i>
                                                </div>
                                                <h5 class="mb-0 fw-bold" id="modal-gst">₹3,600</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <div class="financial-item p-3 rounded bg-light-info">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-muted small">Discount</span>
                                                    <i class="fa-solid fa-tag text-info"></i>
                                                </div>
                                                <h5 class="mb-0 fw-bold" id="modal-discount">10%</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <div class="financial-item p-3 rounded bg-light-danger">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-muted small">Payout</span>
                                                    <i class="fa-solid fa-hand-holding-dollar text-danger"></i>
                                                </div>
                                                <h5 class="mb-0 fw-bold" id="modal-payout">15%</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="download-policy" class="btn btn-success">
                    <i class="fa-solid fa-download me-2"></i>Download Policy
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Additional CSS for the modal -->
<style>
.bg-light-primary {
    background-color: rgba(13, 110, 253, 0.1);
}
.bg-light-success {
    background-color: rgba(25, 135, 84, 0.1);
}
.bg-light-danger {
    background-color: rgba(220, 53, 69, 0.1);
}
.bg-light-warning {
    background-color: rgba(255, 193, 7, 0.1);
}
.bg-light-info {
    background-color: rgba(13, 202, 240, 0.1);
}
.icon-box {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}
.avatar {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.financial-item {
    transition: all 0.3s ease;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.financial-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>

<!-- Button to open modal with improved design -->


