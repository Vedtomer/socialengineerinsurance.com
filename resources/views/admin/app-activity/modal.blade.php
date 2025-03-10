<div class="collapse mb-4" id="filterSection">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <select class="form-select form-select-sm">
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option>This month</option>
                                <option>Custom range</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">User Type</label>
                            <select class="form-select form-select-sm">
                                <option>All Users</option>
                                <option>Customers</option>
                                <option>Agents</option>
                            </select>
                        </div>
                        {{-- <div class="col-md-3">
                            <label class="form-label">Activity Type</label>
                            <select class="form-select form-select-sm">
                                <option>All Activities</option>
                                <option>Login</option>
                                <option>Message</option>
                                <option>Transaction</option>
                            </select>
                        </div> --}}
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm px-4">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary Cards Row -->
        <div class="row g-4 mb-4">
            <!-- Total Users Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-primary">
                    <!-- Added bg-soft-primary -->
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-soft-primary rounded-circle ">
                                <img src="{{ asset('asset/admin/images/icon/1.png') }}" alt="Agent Icon" >
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="text-muted fw-normal mb-0">Total Users</h5>
                                <h2 class="fw-bold mb-0">{{ $totalUsersCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Users Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-success">
                    <!-- Added bg-soft-success -->
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-soft-success rounded-circle ">
                                <img src="{{ asset('asset/admin/images/icon/2.png') }}" alt="Agent Icon" >
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="text-muted fw-normal mb-0">Active Users</h5>
                                <h2 class="fw-bold mb-0">{{ $activeUsersCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Agents Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-info">
                    <!-- Added bg-soft-info -->
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-soft-info rounded-circle ">
                                <img src="{{ asset('asset/admin/images/icon/3.png') }}" alt="Agent Icon" >
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="text-muted fw-normal mb-0">Active Agents</h5>
                                <h2 class="fw-bold mb-0">{{ $activeAgentsCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Customers Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-warning">
                    <!-- Added bg-soft-warning -->
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-soft-warning rounded-circle ">
                                <img src="{{ asset('asset/admin/images/icon/4.png') }}" alt="Agent Icon" >
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="text-muted fw-normal mb-0">Active Customers</h5>
                                <h2 class="fw-bold mb-0">{{ $activeCustomersCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>