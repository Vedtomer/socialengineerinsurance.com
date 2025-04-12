   <!--  BEGIN SIDEBAR  -->
   <div class="sidebar-wrapper sidebar-theme">
       <nav id="sidebar">

           <div class="navbar-nav theme-brand flex-row  text-center">
               <div class="nav-logo">
                   <div class="nav-item  avatar avatar-indicators avatar-online">
                       <a href="{{ route('admin.dashboard') }}">
                           <img src="{{ asset('asset/admin/assets/img/small-logo.png') }}" class="rounded-circle"
                               alt="logo">
                       </a>
                   </div>
                   {{-- <div class="nav-item theme-text">
                       <a href="{{route("admin.dashboard")}}" class="nav-link"> SEI </a>
                   </div> --}}
               </div>

               <div class="nav-item sidebar-toggle">
                   <div class="btn-toggle sidebarCollapse">
                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                           fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                           stroke-linejoin="round" class="feather feather-chevrons-left">
                           <polyline points="11 17 6 12 11 7"></polyline>
                           <polyline points="18 17 13 12 18 7"></polyline>
                       </svg>
                   </div>
               </div>
           </div>

           <div class="profile-info">
               <div class="user-info">
                   <div class="profile-img  ">
                       <img src="{{ Auth::user()->profile_image }}" alt="avatar">
                   </div>
                   <div class="profile-content">
                       <h6 class="">{{ Auth::user()->name }}</h6>
                       <p class="">{{ Auth::user()->roles->pluck('name')[0] ?? '' }}</p>
                   </div>
               </div>
           </div>

           <ul class="list-unstyled menu-categories" id="accordionExample">

               <li class="menu {{ classActivePath('analytics') }}">
                   <a href="{{ route('admin.dashboard') }}" aria-expanded="false" class="dropdown-toggle">
                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-columns">
                               <path
                                   d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7m0-18H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7m0-18v18">
                               </path>
                           </svg>
                           <span>Dashboard</span>
                       </div>
                   </a>
               </li>

               <li class="menu {{ classActivePath('transaction') }}">
                   <a href="{{ route('admin.transaction') }}" aria-expanded="false" class="dropdown-toggle">
                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-user">
                               <path
                                   d="M20 21v-2a4 4 0 0 0-3-3.87M4 21v-2a4 4 0 0 1 3-3.87M16 3.13a4 4 0 1 1-8 0 4 4 0 0 1 8 0z" />
                           </svg>
                           <span>Account</span>
                       </div>
                   </a>
               </li>


               <li class="menu {{ classActivePath('dashboard') }}">
                   <a href="{{ route('admin.analytics') }}" aria-expanded="false" class="dropdown-toggle">
                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-columns">
                               <path
                                   d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7m0-18H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7m0-18v18">
                               </path>
                           </svg>
                           <span>Analytics</span>
                       </div>
                   </a>
               </li>



               <li class="menu">
                   <a href="#home" data-bs-toggle="collapse"
                       aria-expanded="{{ ariaExpanded('agent-list') || ariaExpanded('agent-code-management') ? 'true' : 'false' }}"
                       class="dropdown-toggle">

                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-monitor">
                               <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                               <line x1="8" y1="21" x2="16" y2="21"></line>
                               <line x1="12" y1="17" x2="12" y2="21"></line>
                           </svg>
                           <span>Manage Agent</span>
                       </div>
                       <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-chevron-right">
                               <polyline points="9 18 15 12 9 6"></polyline>
                           </svg>
                       </div>
                   </a>
                   <ul class="collapse submenu list-unstyled {{ ariaExpanded('agent-list') || ariaExpanded('agent-code-management') ? 'show' : '' }}"
                       id="home" data-bs-parent="#accordionExample">
                       <li class="menu {{ classActivePath('agent-list') }}">
                           <a href="{{ route('agent.list') }}">Listing </a>
                       </li>
                       <li class="menu {{ classActivePath('agent-code-management') }}">
                           <a href="{{ route('commission.management') }}">Code </a>
                       </li>
                   </ul>
               </li>

               <li class="menu">
                   <a href="#ecommerce" data-bs-toggle="collapse"
                       aria-expanded="{{ ariaExpanded('upload-policy') || ariaExpanded('policy-list') || ariaExpanded('policy-pdf-upload') ? 'true' : 'false' }}"
                       class="dropdown-toggle">
                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-shopping-cart">
                               <circle cx="9" cy="21" r="1"></circle>
                               <circle cx="20" cy="21" r="1"></circle>
                               <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                           </svg>
                           <span>Manage Policy</span>
                       </div>
                       <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-chevron-right">
                               <polyline points="9 18 15 12 9 6"></polyline>
                           </svg>
                       </div>
                   </a>
                   <ul class="collapse submenu list-unstyled {{ ariaExpanded('upload-policy') || ariaExpanded('policy-list') || ariaExpanded('policy-pdf-upload') ? 'show' : '' }}"
                       id="ecommerce" data-bs-parent="#accordionExample">

                       <li class=" {{ classActivePath('policy-list') }}">
                           <a href="{{ route('admin.policy_list') }}">Listing </a>
                       </li>

                       <li class=" {{ classActivePath('upload-policy') }}">
                           <a href="{{ route('admin.upload') }}">Import</a>
                       </li>


                   </ul>
               </li>

               <li class="menu">
                   <a href="#Reward" data-bs-toggle="collapse"
                       aria-expanded="{{ ariaExpanded('points-redemRequest') || ariaExpanded('points-redemption') ? 'true' : 'false' }}"
                       class=" dropdown-toggle">
                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-star">
                               <polygon
                                   points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                               </polygon>
                           </svg>
                           <span>Reward Manage</span>
                       </div>
                       <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-chevron-right">
                               <polyline points="9 18 15 12 9 6"></polyline>
                           </svg>
                       </div>
                   </a>
                   <ul class="collapse submenu list-unstyled {{ ariaExpanded('points-redemRequest') || ariaExpanded('points-redemption') ? 'show' : '' }}"
                       id="Reward" data-bs-parent="#accordionExample">
                       <li class=" {{ classActivePath('points-redemRequest') }}">
                           <a href="{{ route('admin.reward.request') }}"> Redem Request </a>
                       </li>
                       <li class=" {{ classActivePath('points-redemption') }}">
                           <a href="{{ route('admin.reward.index') }}"> Redem Proceeded </a>
                       </li>
                   </ul>
               </li>



               <li class="menu {{ classActivePath('sliders') }}">
                   <a href="{{ route('sliders.index') }}" aria-expanded="false" class="dropdown-toggle">
                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-layout">
                               <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                               <line x1="3" y1="9" x2="21" y2="9"></line>
                               <line x1="9" y1="21" x2="9" y2="9"></line>
                           </svg>
                           <span>App Slider</span>
                       </div>
                   </a>
               </li>

               <li class="menu {{ classActivePath('companies') }}">
                   <a href="{{ route('companies.index') }}" aria-expanded="false" class="dropdown-toggle">
                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-layout">
                               <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                               <line x1="3" y1="9" x2="21" y2="9"></line>
                               <line x1="9" y1="21" x2="9" y2="9"></line>
                           </svg>
                           <span>Company</span>
                       </div>
                   </a>
               </li>

               <li class="menu {{ classActivePath('claims') }}">
                   <a href="{{ route('claims.index') }}" aria-expanded="false" class="dropdown-toggle">
                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-layout">
                               <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                               <line x1="3" y1="9" x2="21" y2="9"></line>
                               <line x1="9" y1="21" x2="9" y2="9"></line>
                           </svg>
                           <span>Insurance Claim</span>
                       </div>
                   </a>
               </li>


               <li class="menu">
                   <a href="#Customers" data-bs-toggle="collapse"
                       aria-expanded="{{ ariaExpanded('customers') || ariaExpanded('customer-policies') ? 'true' : 'false' }}"
                       class="dropdown-toggle">

                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-monitor">
                               <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                               <line x1="8" y1="21" x2="16" y2="21"></line>
                               <line x1="12" y1="17" x2="12" y2="21"></line>
                           </svg>
                           <span>Customers</span>
                       </div>
                       <div>
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-chevron-right">
                               <polyline points="9 18 15 12 9 6"></polyline>
                           </svg>
                       </div>
                   </a>
                   <ul class="collapse submenu list-unstyled {{ ariaExpanded('customers') || ariaExpanded('customer-policies')  ? 'show' : '' }}"
                       id="Customers" data-bs-parent="#accordionExample">
                       <li class="menu {{ classActivePath('customers') }}">
                           <a href="{{ route('customers.index') }}"> Customer List </a>
                       </li>
                       <li class="menu {{ classActivePath('customer-policies') }}">
                           <a href="{{ route('customer-policies.index') }}">Customer Policy </a>
                       </li>
                       
                   </ul>
               </li>


               <li class="menu {{ classActivePath('WhatsappMessageLog') }}">
                   <a href="{{ route('WhatsappMessageLog') }}" aria-expanded="false" class="dropdown-toggle">
                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-layout">
                               <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                               <line x1="3" y1="9" x2="21" y2="9"></line>
                               <line x1="9" y1="21" x2="9" y2="9"></line>
                           </svg>
                           <span>Logs</span>
                       </div>
                   </a>
               </li>


               <li class="menu {{ classActivePath('app-activity') }}">
                   <a href="{{ route('admin.app-activity') }}" aria-expanded="false" class="dropdown-toggle">
                       <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                               stroke-linejoin="round" class="feather feather-layout">
                               <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                               <line x1="3" y1="9" x2="21" y2="9"></line>
                               <line x1="9" y1="21" x2="9" y2="9"></line>
                           </svg>
                           <span>App Activity</span>
                       </div>
                   </a>
               </li>

               <li class="menu {{ classActivePath('insurance-products') }}">
                <a href="{{ route('insurance-products.index') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-briefcase-medical fa-lx"></i>
                        <span>Insurance Product</span>
                    </div>
                </a>
            </li>


           </ul>

       </nav>

   </div>
   <!--  END SIDEBAR  -->
