@php
$analytics = getCustomerPolicyAnalytics();
@endphp

<div class="row g-2 mb-3"> {{-- Reduced gutter and margin --}}
    @php
        $cards = [
            // Card 1: Customer Overview (Customers, Total Policies, App Users)
            [
                'route' => 'customers.index', 
                'bg' => 'bg-soft-primary', 'icon_bg' => 'bg-primary', 'icon' => 'feather-users', 
                'title' => 'Total Customers', 
                'value' => $analytics['totalCustomers'], 
                'subtitle1' => 'Policies', 'subvalue1' => $analytics['totalPolicies'], 
                'subtitle2' => 'App Users', 'subvalue2' => $analytics['totalAppActiveUsers'] ?? 'N/A'
            ],
            // Card 2: Active Business (Active, Pending)
            [
                'route' => ['customer-policies.index', ['status' => 'active']], 
                'bg' => 'bg-soft-success', 'icon_bg' => 'bg-success', 'icon' => 'feather-shield', 
                'title' => 'Active Policies', 
                'value' => $analytics['activePoliciesCount'],
                'subtitle1' => 'Pending', 'subvalue1' => $analytics['pendingPoliciesCount'],
                 // Empty subtitle2 to keep alignment if needed, or omit
            ],
            // Card 3: Attention Required (Expired, Cancelled)
            [
                'route' => ['customer-policies.index', ['status' => 'expired']], 
                'bg' => 'bg-soft-danger', 'icon_bg' => 'bg-danger', 'icon' => 'feather-alert-triangle', 
                'title' => 'Expired Policies', 
                'value' => $analytics['expiredPoliciesCount'],
                'subtitle1' => 'Cancelled', 'subvalue1' => $analytics['cancelledPoliciesCount'],
            ],
            // Card 4: Upcoming Expiry (This Month, 7 Days)
            [
                'route' => ['customer-policies.index', ['expiry' => 'this_month']], 
                'bg' => 'bg-soft-info', 'icon_bg' => 'bg-info', 'icon' => 'feather-calendar', 
                'title' => 'Exp. This Month', 
                'value' => $analytics['policiesExpiringThisMonthCount'],
                'subtitle1' => 'In 7 Days', 'subvalue1' => $analytics['policiesExpiringIn7DaysCount'],
            ],
        ];
    @endphp

    @foreach($cards as $card)
    <div class="col-xl-3 col-md-6 col-sm-6">
        <a href="{{ is_array($card['route']) ? route($card['route'][0], $card['route'][1]) : route($card['route']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden {{ $card['bg'] }}">
                <div class="card-body p-2 position-relative"> {{-- Reduced padding --}}
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 {{ $card['icon_bg'] }} rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="feather {{ $card['icon'] }} text-white" style="font-size: 16px;"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="text-muted fw-normal mb-0" style="font-size: 0.75rem;">{{ $card['title'] }}</h6>
                            <h4 class="fw-bold mb-0" style="font-size: 1.1rem;">{{ $card['value'] }}</h4>
                        </div>
                    </div>
                    {{-- Always show footer section for consistency height --}}
                    <div class="d-flex justify-content-between mt-2 pt-1 border-top" style="border-top-color: rgba(0,0,0,0.05) !important;">
                        <div>
                            @if(isset($card['subtitle1']))
                                <span class="d-block text-muted" style="font-size: 0.65rem;">{{ $card['subtitle1'] }}</span>
                                <span class="fw-medium" style="font-size: 0.8rem;">{{ $card['subvalue1'] }}</span>
                            @else
                                <span class="d-block text-muted" style="font-size: 0.65rem;">&nbsp;</span>
                                <span class="fw-medium" style="font-size: 0.8rem;">&nbsp;</span>
                            @endif
                        </div>
                        <div>
                            @if(isset($card['subtitle2']))
                                <span class="d-block text-muted" style="font-size: 0.65rem;">{{ $card['subtitle2'] }}</span>
                                <span class="fw-medium" style="font-size: 0.8rem;">{{ $card['subvalue2'] }}</span>
                            @else
                                <span class="d-block text-muted" style="font-size: 0.65rem;">&nbsp;</span>
                                <span class="fw-medium" style="font-size: 0.8rem;">&nbsp;</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>