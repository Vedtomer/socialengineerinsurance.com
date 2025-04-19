<!-- Counts Overview Section -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-primary h-100">
            <div class="card-body text-center">
                <h5 class="card-title text-primary">Total Policies</h5>
                <div class="display-4 fw-bold">{{ $policyCounts['total'] }}</div>
                
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success h-100">
            <div class="card-body text-center">
                <h5 class="card-title text-success">Total Agents</h5>
                <div class="display-4 fw-bold">{{ $userCounts['agents'] }}</div>
                
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info h-100">
            <div class="card-body text-center">
                <h5 class="card-title text-info">Total Customers</h5>
                <div class="display-4 fw-bold">{{ $userCounts['customers'] }}</div>
              
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning h-100">
            <div class="card-body text-center">
                <h5 class="card-title text-warning">Insurance Products</h5>
                <div class="display-4 fw-bold">{{ $productCounts }}</div>
                <p class="text-muted mb-0">Companies: {{ $companyCounts }}</p>
            </div>
        </div>
    </div>
</div>