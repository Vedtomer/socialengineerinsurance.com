<!-- Overview Section -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <h5 class="card-title m-0 d-flex align-items-center">
                        <i class="fas fa-chart-pie text-primary me-2"></i>
                        Policy Overview
                    </h5>
                </div>
                <div class="card-body pb-4">
                    <div class="row g-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="card border-0 shadow-sm h-100 bg-light bg-opacity-50 rounded-4">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary text-white p-3 rounded-3 me-3">
                                            <i class="fas fa-file-contract fa-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-0 small">Total Policies</p>
                                            <h3 class="fw-bold mb-0">{{ $analytics['total_policies'] }}</h3>
                                        </div>
                                    </div>
                                    <div class="progress mt-auto" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3 col-sm-6">
                            <div class="card border-0 shadow-sm h-100 bg-light bg-opacity-50 rounded-4">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info text-white p-3 rounded-3 me-3">
                                            <i class="fas fa-rupee-sign fa-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-0 small">Total Premium</p>
                                            <h3 class="fw-bold mb-0">₹{{ $analytics['total_premium'] }}</h3>
                                        </div>
                                    </div>
                                    <div class="progress mt-auto" style="height: 6px;">
                                        <div class="progress-bar bg-info" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="card border-0 shadow-sm h-100 bg-light bg-opacity-50 rounded-4">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info text-white p-3 rounded-3 me-3">
                                            <i class="fas fa-rupee-sign fa-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-0 small">Total Payout</p>
                                            <h3 class="fw-bold mb-0">₹{{ $analytics['total_payout'] }}</h3>
                                        </div>
                                    </div>
                                    <div class="progress mt-auto" style="height: 6px;">
                                        <div class="progress-bar bg-info" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <h5 class="card-title m-0 d-flex align-items-center">
                        <i class="fas fa-wallet text-success me-2"></i>
                        Premium & Tax
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <p class="text-muted mb-0 small">Total Premium</p>
                            <h4 class="text-primary mb-0 fw-bold">₹{{ $analytics['total_premium'] }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-file-invoice-dollar text-primary"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <p class="text-muted mb-0 small">Total NET Amount</p>
                            <h5 class="text-success mb-0">₹{{ $analytics['total_net_amount'] }}</h5>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-money-bill-wave text-success"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total GST</p>
                            <h5 class="text-info mb-0">₹{{ $analytics['total_gst'] }}</h5>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-percent text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission Summary -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <h5 class="card-title m-0 d-flex align-items-center">
                        <i class="fas fa-hand-holding-usd text-warning me-2"></i>
                        Commission Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <p class="text-muted mb-0 small">Total Commission</p>
                            <h5 class="mb-0">₹{{ $analytics['total_commission'] }}</h5>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-coins text-warning"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <p class="text-muted mb-0 small">Adjusted Commission</p>
                            <h6 class="mb-0">₹{{ $analytics['total_commission_deducted'] }}</h6>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-2 rounded-circle">
                            <i class="fas fa-calculator text-danger"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <p class="text-muted mb-0 small">Commission Adjust Later</p>
                            <h6 class="mb-0">₹{{ $analytics['total_commission_will_adjustment'] }}</h6>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-2 rounded-circle">
                            <i class="fas fa-clock text-secondary"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Net Commission Payable</p>
                            <h5 class="text-primary fw-bold mb-0">₹{{ $analytics['Net_Commission_Payable_Agent'] }}</h5>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-check-circle text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Due Amount -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <h5 class="card-title m-0 d-flex align-items-center">
                        <i class="fas fa-hand-holding-usd text-danger me-2"></i>
                        Market Due Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <p class="text-muted mb-0 small">Total Due Amount</p>
                            <h4 class="text-danger mb-0 fw-bold">₹{{ $analytics['total_amount_due_agents'] }}</h4>
                            <p class="text-muted small mt-1 fst-italic">(Amount to be collected from market)</p>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-funnel-dollar text-danger"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total Due Paid</p>
                            <h5 class="text-success mb-0">₹{{ $analytics['total_amount_paid_agents'] }}</h5>
                            <p class="text-muted small mt-1 fst-italic">(Amount collected from market)</p>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-check-double text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>