<header class="header-section">
    <div data-animation="over-left" data-collapse="medium" data-duration="400" data-easing="ease" data-easing2="ease"
        role="banner" class="navbar-menu-container w-nav">

        <div class="w-layout-blockcontainer main-container w-container">
            <div class="navbar-wrapper">

                <a href="{{ route('homepage') }}" class="navbar-brand w-nav-brand">
                    <img src="{{ asset('asset/website/images/logo.png') }}" loading="lazy" alt="Logo"
                        class="brand-logo" />
                </a>



                <nav role="navigation" class="nav-menu-wrapper w-nav-menu">
                    <ul role="list" class="nav-menu-list-wrap w-list-unstyled">
                        <li class="mobile-menu-logo"><a href="{{ route('homepage') }}"
                                class="navbar-brand w-nav-brand"><img src="{{ asset('asset/website/images/logo.png') }}"
                                    loading="lazy" alt="" class="brand-logo" /></a></li>
                        {{-- <li class="nav-list-item">
                            <div data-hover="false" data-delay="0" data-w-id="ae4b32c0-8975-07e2-c09e-90c8dffb18bd" class="nav-dropdown w-dropdown">
                                <div class="nav-dropdown-toggle-wrap w-dropdown-toggle">
                                    <div data-w-id="ae4b32c0-8975-07e2-c09e-90c8dffb18bf" class="nav-dropdown-toggle">
                                        <div class="nav-dropdown-icon w-icon-dropdown-toggle"></div>
                                        <div class="nav-link">Home</div>
                                        <div class="nav-bottom-shape"></div>
                                    </div>
                                </div>
                                <nav class="nav-dropdown-list w-dropdown-list">
                                    <div class="nav-dropdown-link-wrap"><a href="/home/home-one" class="nav-dropdown-link w-dropdown-link">Home One</a><a href="/home/home-two" aria-current="page" class="nav-dropdown-link w-dropdown-link w--current">Home Two</a>
                                    </div>
                                </nav>
                            </div>
                        </li> --}}
                        <li data-w-id="694ef9c2-e27d-7080-1c57-14bcb98cd969" class="nav-list-item margin-top"><a
                                href="/" class="nav-link">Home</a>
                            <div class="nav-bottom-shape"></div>
                        </li>
                        <li data-w-id="694ef9c2-e27d-7080-1c57-14bcb98cd969" class="nav-list-item margin-top"><a
                                href="/about" class="nav-link">About</a>
                            <div class="nav-bottom-shape"></div>
                        </li>
                        <li class="nav-list-item">
                            <div data-hover="false" data-delay="0" data-w-id="694ef9c2-e27d-7080-1c57-14bcb98cd96e"
                                class="nav-dropdown w-dropdown">
                                <div class="nav-dropdown-toggle-wrap w-dropdown-toggle">
                                    <div data-w-id="694ef9c2-e27d-7080-1c57-14bcb98cd970" class="nav-dropdown-toggle">
                                        <div class="nav-dropdown-icon w-icon-dropdown-toggle"></div>
                                        <div class="nav-link">Insurance</div>
                                        <div class="nav-bottom-shape"></div>
                                    </div>
                                </div>
                                <nav class="nav-dropdown-list w-dropdown-list">
                                    <div class="nav-dropdown-link-wrap">
                                        <div class="insurance-list-wrapp w-dyn-list">
                                            <div role="list" class="insurance-list w-dyn-items">
                                                <div role="listitem" class="insurance-list-item w-dyn-item"><a
                                                        href="{{route('e_rickshaw_insurance')}}"
                                                        class="nav-dropdown-link w-dropdown-link">E-Rickshaw Insurance</a></div>
                                                {{-- <div role="listitem" class="insurance-list-item w-dyn-item"><a
                                                        href="/insurance/auto-insurance"
                                                        class="nav-dropdown-link w-dropdown-link">Auto
                                                        Insurance</a></div>
                                                <div role="listitem" class="insurance-list-item w-dyn-item"><a
                                                        href="/insurance/health-insurance"
                                                        class="nav-dropdown-link w-dropdown-link">Health
                                                        Insurance</a></div>
                                                <div role="listitem" class="insurance-list-item w-dyn-item"><a
                                                        href="/insurance/travel-insurance"
                                                        class="nav-dropdown-link w-dropdown-link">Travel
                                                        Insurance</a></div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </li>
                        {{-- <li class="nav-list-item">
                            <div data-hover="false" data-delay="0" data-w-id="efd43221-6175-cb41-1e5a-ec85546a7fc7"
                                class="nav-dropdown w-dropdown">
                                <div data-w-id="efd43221-6175-cb41-1e5a-ec85546a7fc8"
                                    class="nav-dropdown-toggle-wrap w-dropdown-toggle">
                                    <div data-w-id="efd43221-6175-cb41-1e5a-ec85546a7fc9" class="nav-dropdown-toggle">
                                        <div class="nav-dropdown-icon w-icon-dropdown-toggle"></div>
                                        <div class="nav-link">Pages</div>
                                        <div class="nav-bottom-shape"></div>
                                    </div>
                                </div>
                                <nav class="nav-dropdown-list all-pages w-dropdown-list">
                                    <div class="nav-dropdown-flex">
                                        <div class="nav-dropdown-column">
                                            <div class="nav-heading">Pages</div><a href="/team"
                                                class="nav-dropdown-link w-dropdown-link">Our Team</a><a href="/price"
                                                class="nav-dropdown-link w-dropdown-link">Pricing</a><a href="/career"
                                                class="nav-dropdown-link w-dropdown-link">Career</a><a href="/claim"
                                                class="nav-dropdown-link w-dropdown-link">Claims</a><a
                                                href="/contact-us/contact"
                                                class="nav-dropdown-link w-dropdown-link">Contact Us V1 </a><a
                                                href="/contact-us/contact-two"
                                                class="nav-dropdown-link w-dropdown-link">Contact Us V2</a><a
                                                href="/user-verification/sign-up"
                                                class="nav-dropdown-link w-dropdown-link">Sign Up</a><a
                                                href="/user-verification/sign-in"
                                                class="nav-dropdown-link w-dropdown-link">Sign In</a><a
                                                href="/user-verification/forgot-password"
                                                class="nav-dropdown-link w-dropdown-link">Forgot Password</a>
                                        </div>
                                        <div class="nav-dropdown-column">
                                            <div class="nav-heading">CMS Pages</div><a
                                                href="https://insurbes.webflow.io/career/product-designer"
                                                class="nav-dropdown-link w-dropdown-link">Career Details</a><a
                                                href="https://insurbes.webflow.io/insurance/home-insurance"
                                                class="nav-dropdown-link w-dropdown-link">Pricing Details</a><a
                                                href="https://insurbes.webflow.io/team-members/stive-jackson"
                                                class="nav-dropdown-link w-dropdown-link">Team Details</a><a
                                                href="https://insurbes.webflow.io/blog-posts/the-home-insurance-is-an-expensive-investment"
                                                class="nav-dropdown-link w-dropdown-link">Blog Details</a>
                                            <div class="nav-heading margin-top">Utility Pages</div><a
                                                href="https://insurbes.webflow.io/404"
                                                class="nav-dropdown-link w-dropdown-link">404 Page</a><a
                                                href="https://insurbes.webflow.io/401"
                                                class="nav-dropdown-link w-dropdown-link">Password
                                                Protected</a>
                                        </div>
                                        <div class="nav-dropdown-column">
                                            <div class="nav-heading"><strong class="bold-text">Template
                                                    Info</strong></div><a href="/template-info/style-guide"
                                                class="nav-dropdown-link w-dropdown-link">Style Guide</a><a
                                                href="/template-info/licenses"
                                                class="nav-dropdown-link w-dropdown-link">Licenses</a><a
                                                href="/template-info/changelog"
                                                class="nav-dropdown-link w-dropdown-link">Changelog</a>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </li> --}}
                        <li data-w-id="694ef9c2-e27d-7080-1c57-14bcb98cd97d" class="nav-list-item margin-top">
                            <a href="{{route('contact-us')}}" class="nav-link">Contact Us</a>
                            <div class="nav-bottom-shape"></div>
                        </li>
                        <li data-w-id="694ef9c2-e27d-7080-1c57-14bcb98cd97d" class="nav-list-item margin-top">
                            <a href="https://blog.socialengineerinsurance.com/" class="nav-link" target="_blank">Blog</a>
                            <div class="nav-bottom-shape"></div>
                        </li>
                        <li class="mobile-menu-button">
                            <div class="nav-button-wrapper mobile-button-wrap">
                                {{-- <a href="#"class="login-link-text">Login</a>
                                <a href="#"class="primary-button padding-minus w-button">Get Insurance</a> --}}
                                <div data-w-id="d8b7b9a4-9846-4f96-d345-53159bb9640c" style="opacity:1" class="sub-title-wrap">
                                    <img src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b79812ab0af18673227bc2_Badge%20Logo.png"
                                        loading="lazy" alt="Welcome Shape" class="hero-sub-title-image" />
                                    <div class="hero-sub-title">Welcome to Social Engineer Insurance</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="nav-button-wrapper">
                    {{-- <a href="/price" class="primary-button padding-minus w-button">Get Insurance</a> --}}
                    <div data-w-id="d8b7b9a4-9846-4f96-d345-53159bb9640c" style="opacity:1" class="sub-title-wrap">
                        <img src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b79812ab0af18673227bc2_Badge%20Logo.png"
                            loading="lazy" alt="Welcome Shape" class="hero-sub-title-image" />
                        <div class="hero-sub-title">Welcome to Social Engineer Insurance</div>
                    </div>
                </div>
                <div class="menu-button w-nav-button">
                    <div class="w-icon-nav-menu"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-box-shadow"></div>
</header>
