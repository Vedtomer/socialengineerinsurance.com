<!-- resources/views/home.blade.php -->

@extends('pages.website.layouts.app')

{{-- @section('title', 'Home') --}}

@section('content')
    <section class="hero-section">
        <div class="w-layout-blockcontainer main-container w-container">
            <div class="hero-section-wrapper align-c">
                <div class="hero-content-block">
                    <div class="hero-header-block">
                        {{-- <div data-w-id="d8b7b9a4-9846-4f96-d345-53159bb9640c" style="opacity:1"
                            class="sub-title-wrap"><img
                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b79812ab0af18673227bc2_Badge%20Logo.png"
                                loading="lazy" alt="Welcome Shape" class="hero-sub-title-image" />
                            <div class="hero-sub-title">Welcome to Social Engineer Insurance</div>
                        </div> --}}
                        <h1 data-w-id="d8b7b9a4-9846-4f96-d345-53159bb96410" style="opacity:1" class="hero-heading">Get the
                            Best E-Rickshaw
                            <span class="heading-highlight-text"> Insurance</span> in Minutes
                        </h1>
                    </div>
                    {{-- <p data-w-id="d8b7b9a4-9846-4f96-d345-53159bb96415" style="opacity:1"
                        class="hero-excerpt">At Social Engineer Insurance, we prioritize your needs by offering customized insurance solutions. Whether it's electric rickshaw insurance or life and health coverage, we ensure you get the best protection with tailored plans that fit your requirements. Secure your future with confidence—let us find the best coverage for you.

                        .</p> --}}
                    {{-- <div data-w-id="d8b7b9a4-9846-4f96-d345-53159bb96417" style="opacity:1"
                        class="newsletter-form-wrap">
                        <div class="newsletter-form-block w-form">
                            <form id="newsletter-email-form" name="email-form" data-name="Email Form"
                                method="get" class="newsletter-email-form"
                                data-wf-page-id="65b60c5eef338f6b2401686d"
                                data-wf-element-id="d8b7b9a4-9846-4f96-d345-53159bb96419"><input
                                    class="newsletter-input-field w-input" maxlength="256" name="User-Email"
                                    data-name="User Email" placeholder="Enter Your Email..." type="email"
                                    id="User-Email" required="" />
                                <div class="newsletter-button-wrap"><input type="submit"
                                        data-wait="Please wait..." class="primary-button w-button"
                                        value="Get A Quote" /></div>
                            </form>
                            <div class="w-form-done">
                                <div class="success-message-text">Thank you! Your submission has been received!
                                </div>
                            </div>
                            <div class="w-form-fail">
                                <div class="error-message-text">Oops! Something went wrong while submitting the
                                    form.</div>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div id="w-node-d8b7b9a4-9846-4f96-d345-53159bb96423-2401686d" class="hero-image-block">
                    <div data-w-id="d8b7b9a4-9846-4f96-d345-53159bb96425" style="opacity:1"
                        class="hero-image-inner relative"><img src="{{ asset('asset/website/images/sei.jpg') }}"
                            loading="lazy"
                            style="-webkit-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                            sizes="(max-width: 479px) 100vw, (max-width: 767px) 96vw, (max-width: 991px) 690px, (max-width: 1279px) 440px, (max-width: 1439px) 545px, (max-width: 1919px) 536.375px, 550px"
                            alt="Hero Image"
                            srcset="https://assets-global.website-files.com/65b60c5def338f6b24016820/65c1fe9345f9f64f0a6e42c0_Hero%20Image-p-500.jpg 500w, {{ asset('asset/website/images/sei.jpg') }} 532w"
                            class="hero-image-version-two" />
                        {{-- <div data-w-id="664c949b-1f5e-4059-65f6-4412eb0a40a5" style="opacity:1"
                            class="hero-customer-image-wrap">
                            <img src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65c1ffb7139efde81a0a2022_Happy%20Customer.png"
                                loading="lazy" alt="Hero Customer Image" class="hero-customer-image" />
                        </div> --}}
                        <div class="image-overlay-wrap">
                            <div data-w-id="7d53ccbd-3af6-6f1d-8a3d-d78e1f7c0070" class="image-overlay-grid">
                                <div id="w-node-_7d53ccbd-3af6-6f1d-8a3d-d78e1f7c0071-1f7c006f"
                                    class="image-overlay-mask mask-one"></div>
                                <div class="image-overlay-mask mask-two"></div>
                                <div class="image-overlay-mask mask-three"></div>
                                <div class="image-overlay-mask mask-four"></div>
                            </div>
                        </div>
                    </div>
                    <div data-w-id="d8b7b9a4-9846-4f96-d345-53159bb96429" style="opacity:1"
                        class="hero-shape-image lower-shape-image"><img
                            src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b867606ba5973c24a6a24e_Hero%20Shape.png"
                            loading="lazy" data-w-id="d8b7b9a4-9846-4f96-d345-53159bb9642a" alt="Hero Shape"
                            class="circle-shape-image width-70px" /></div>
                </div>
            </div>
        </div>
    </section>



    {{-- <section class="brand-logo-section">
        <div data-w-id="dddc6fa6-c78f-9861-5eb6-f699e8f01939" style="opacity:1" class="brand-logo-flex">
            <div style="-webkit-transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                class="brand-logo-wrap">
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edcbebb9ab36f96c571_Logo%201.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edbe8570b9ea32dfdb7_Logo%202.png"
                        loading="lazy" alt="Brad Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edc75f511dd88feab05_Logo%203.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edbe020cd817c5259ce_Logo%204.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edbe14e07b15487abe2_Logo%205.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
            </div>
            <div style="-webkit-transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                class="brand-logo-wrap">
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edcbebb9ab36f96c571_Logo%201.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edbe8570b9ea32dfdb7_Logo%202.png"
                        loading="lazy" alt="Brad Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edc75f511dd88feab05_Logo%203.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edbe020cd817c5259ce_Logo%204.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edbe14e07b15487abe2_Logo%205.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
            </div>
            <div style="-webkit-transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0px, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                class="brand-logo-wrap">
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edcbebb9ab36f96c571_Logo%201.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edbe8570b9ea32dfdb7_Logo%202.png"
                        loading="lazy" alt="Brad Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edc75f511dd88feab05_Logo%203.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edbe020cd817c5259ce_Logo%204.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
                <div class="brand-logo-block"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ed8edbe14e07b15487abe2_Logo%205.png"
                        loading="lazy" alt="Brand Logo" class="brand-logo" /></div>
            </div>
        </div>
        <div class="brand-logo-side-overlay"></div>
        <div class="brand-logo-side-overlay right-side-overlay"></div>
    </section> --}}

    <section>
        <div class="w-layout-blockcontainer main-container w-container">
            <div class="about-us-wrapper">
                <div class="page-heading-wrap">
                    <div data-w-id="eba3cd07-734f-a87f-7656-6ee84e4cabda"
                        style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;"
                        class="page-intro-wrap about-intro-max-width">
                        <div class="page-intro-text" style="margin-top: 0px !important">At Social Engineer Insurance, we
                            prioritize your needs by offering customized insurance solutions. Whether it's electric rickshaw
                            insurance or life and health coverage, we ensure you get the best protection with tailored plans
                            that fit your requirements. Secure your future with confidence—let us find the best coverage for
                            you.</div>
                    </div>
                </div>

                <img src="https://cdn.prod.website-files.com/65b60c5def338f6b24016820/65b867606ba5973c24a6a24e_Hero%20Shape.png"
                    loading="lazy"
                    style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(-226.472deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1; will-change: transform;"
                    data-w-id="b215a556-e1e0-029c-c25c-8b53bd731021" alt="Circle Image"
                    class="about-us-circle-shape-image circle-shape-image size-decrease">
            </div>
        </div>
    </section>



    <section class="unique-feature-section section-y-axis-gap">
        <div class="w-layout-blockcontainer main-container w-container">
            <div class="unique-feature-wrapper">
                <div class="section-heading-wrap center">
                    <div class="section-title-wrap unique-feature-title-max-width">
                        <h2 data-w-id="5edc5d3f-39a7-08fd-f2f5-6db71fdc5bbe" style="opacity:1" class="section-title">What
                            Makes Us <span class="heading-highlight-text">Different</span> From Others?</h2>
                    </div>
                    <div class="section-sub-title-wrap unique-feature-sub-title-max-width">
                        <div data-w-id="5edc5d3f-39a7-08fd-f2f5-6db71fdc5bc4" style="opacity:1" class="section-sub-title">
                            Social Engineer Insurance Insurance companies provide a sense of security and peace, ensuring
                            coverage during life’s uncertainties.</div>
                    </div>
                </div>
                <div class="unique-feature-grid">
                    <div data-w-id="34897b49-3320-2bdc-6fb3-31ab8028c585" style="opacity:1" class="unique-feature-card">
                        <div class="unique-feature-image-block"><img
                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b9d0a308217ff5cd6478fa_Makes%20Us%20Different%20Image%20One.png"
                                loading="lazy" alt="What Different Image" class="unique-feature-image" />
                        </div>
                        <div class="unique-feature-content-block">
                            <h3 class="unique-feature-title">Certified Platform</h3>
                            <div class="unique-feature-excerpt">Feel everything will be granted secure and more
                                then safely.</div>
                        </div>
                    </div>
                    <div data-w-id="34897b49-3320-2bdc-6fb3-31ab8028c58d" style="opacity:1" class="unique-feature-card">
                        <div class="unique-feature-image-block"><img
                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b9d26df1501f2c18dd05dc_Makes%20Us%20Different%20Image%20Two.png"
                                loading="lazy" alt="What Different Image" class="unique-feature-image" />
                        </div>
                        <div class="unique-feature-content-block">
                            <h3 class="unique-feature-title">Easy Claim Process</h3>
                            <div class="unique-feature-excerpt">Feel everything will be granted secure and more
                                then safely.</div>
                        </div>
                    </div>
                    <div data-w-id="34897b49-3320-2bdc-6fb3-31ab8028c595" style="opacity:1" class="unique-feature-card">
                        <div class="unique-feature-image-block"><img
                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b9d2a0a1f1aa34965db483_Makes%20Us%20Different%20Image%20Three.png"
                                loading="lazy" alt="What Different Image" class="unique-feature-image" />
                        </div>
                        <div class="unique-feature-content-block">
                            <h3 class="unique-feature-title">Digital Insurance</h3>
                            <div class="unique-feature-excerpt">Feel everything will be granted secure and more
                                then safely.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section data-w-id="ace7a893-17cb-0173-dc5e-fccf6517ac33" class="why-choose-us-section section-bottom-gap">
        <div class="w-layout-blockcontainer main-container w-container">
            <div class="why-choose-us-wrapper">
                <div data-w-id="ace7a893-17cb-0173-dc5e-fccf6517ac36" style="opacity:1"
                    class="why-choose-us-image-block">
                    <div data-w-id="ace7a893-17cb-0173-dc5e-fccf6517ac37" class="why-choose-us-image-wrap version-two">
                        <img class="why-choose-us-image" src="{{ asset('asset/website/images/sei1.jpeg') }}"
                            width="698" height="" alt="Why Choose Us Image"
                            style="-webkit-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                            sizes="(max-width: 479px) 100vw, (max-width: 767px) 95vw, (max-width: 991px) 690px, (max-width: 1279px) 362.34375px, (max-width: 1439px) 442.875px, (max-width: 1919px) 554px, 617px"
                            loading="lazy"
                            srcset="{{ asset('asset/website/images/sei1.jpeg') }} 500w, {{ asset('asset/website/images/sei1.jpeg') }} 600w" />
                        <div class="image-overlay-wrap">
                            <div data-w-id="7d53ccbd-3af6-6f1d-8a3d-d78e1f7c0070" class="image-overlay-grid">
                                <div id="w-node-_7d53ccbd-3af6-6f1d-8a3d-d78e1f7c0071-1f7c006f"
                                    class="image-overlay-mask mask-one"></div>
                                <div class="image-overlay-mask mask-two"></div>
                                <div class="image-overlay-mask mask-three"></div>
                                <div class="image-overlay-mask mask-four"></div>
                            </div>
                        </div>
                    </div>
                    {{-- <img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65c1b7caa0a0c5f27cd098f9_Infurrance%20Profit%20Imag.png"
                        loading="lazy" style="opacity:1" data-w-id="ace7a893-17cb-0173-dc5e-fccf6517ac39"
                        alt="Why Choose Us Profit Image" class="why-choose-us-profit-image change-position" /> --}}
                    <div class="why-choose-us-shape-one change-position">

                    </div>
                    <div class="why-choose-us-shape-two change-position"></div>
                </div>
                <div class="why-choose-us-content-block">
                    <div class="section-heading-wrap">
                        <div class="section-title-wrap why-choose-us-title-max-width">
                            <h2 data-w-id="d53cb367-8994-1cab-bbf1-6e18bc8a315d" style="opacity:1" class="section-title">
                                What Makes Our <span class="heading-highlight-text">Services</span> Reliable?</h2>
                        </div>
                        <div class="section-sub-title-wrap why-choose-us-sub-title-max-width">
                            <div data-w-id="d53cb367-8994-1cab-bbf1-6e18bc8a3163" style="opacity:1"
                                class="section-sub-title">Insurance companies offer peace of mind and financial security,
                                protecting you during life’s unpredictable moments.</div>
                        </div>
                    </div>
                    <div data-w-id="ace7a893-17cb-0173-dc5e-fccf6517ac45" style="opacity:1" class="why-choose-us-grid">
                        <div class="why-choose-us-card">
                            <div class="why-choose-us-icon-wrap"><img
                                    src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65c09dbb933fadb8f2fa18d1_Smart%20Watch.svg"
                                    loading="lazy" alt="Why Choose Us Card Logo" class="why-choose-us-icon" /></div>
                            <div class="why-choose-us-card-content">
                                <h3 class="why-choose-us-card-title">Smart Match</h3>
                                <p class="why-choose-us-card-summary">We match your unique profile tothe
                                    bestpre-qualified rates.</p>
                            </div>
                        </div>
                        <div class="why-choose-us-card">
                            <div class="why-choose-us-icon-wrap"><img
                                    src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65c09dcede98f1b59a699780_Certified.svg"
                                    loading="lazy" alt="Why Choose Us Card Logo" class="why-choose-us-icon" /></div>
                            <div class="why-choose-us-card-content">
                                <h3 class="why-choose-us-card-title">Certified Insurance</h3>
                                <p class="why-choose-us-card-summary">Don’t worry, we have gone through the
                                    certified process.</p>
                            </div>
                        </div>
                        <div class="why-choose-us-card">
                            <div class="why-choose-us-icon-wrap"><img
                                    src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65c09ddc1fc24121af18ba87_25%20%2B%20Year%20Experience.svg"
                                    loading="lazy" alt="Why Choose Us Card Logo" class="why-choose-us-icon" /></div>
                            <div class="why-choose-us-card-content">
                                <h3 class="why-choose-us-card-title">12+ Years Experience</h3>
                                <p class="why-choose-us-card-summary">More than 12+ years we have insurance
                                    dedicate it to you.</p>
                            </div>
                        </div>
                        <div class="why-choose-us-card">
                            <div class="why-choose-us-icon-wrap"><img
                                    src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65c09dec27e8c9b1e47c3089_Chat%20.svg"
                                    loading="lazy" alt="Why Choose Us Card Logo" class="why-choose-us-icon" /></div>
                            <div class="why-choose-us-card-content">
                                <h3 class="why-choose-us-card-title">24/7 Support</h3>
                                <p class="why-choose-us-card-summary">We&#x27;re always there for you 24/7 for
                                    your satisfaction.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section data-w-id="fefa0a19-0653-41a5-ddc2-9a090a72fc8f" style="opacity:1" class="fun-fact-section">
        <div class="w-layout-blockcontainer main-container w-container">
            <div class="funfact-wrapper">
                <div class="section-heading-wrap align-left">
                    <h3 class="section-title">Our Proud <span class="heading-highlight-text">Achievements</span></h3>
                    <div class="section-sub-title align-left">We’ve proudly served clients across the nation with reliable
                        and trusted services for over a decade.</div>
                </div>
                <div class="funfact-content-block">
                    <div class="single-funfact-wrap">
                        <div data-w-id="fe7488cd-87e1-0978-cb29-99e5268bb50f" class="funfact-number-block">
                            <div style="-webkit-transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                                class="funfact-number-wrap upper-movement">
                                <div class="funfact-number">5</div>
                                <div class="funfact-number">6</div>
                                <div class="funfact-number">2</div>
                                <div class="funfact-number">7</div>
                                <div class="funfact-number">2</div>
                            </div>
                            <div style="-webkit-transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                                class="funfact-number-wrap lower-movement">
                                <div class="funfact-number">5</div>
                                <div class="funfact-number">9</div>
                                <div class="funfact-number">4</div>
                                <div class="funfact-number">6</div>
                                <div class="funfact-number">0</div>
                            </div>
                            <div class="funfact-heading-text">k</div>
                        </div>
                        <div class="number-text-label">E-Rickshaw Insurance</div>
                    </div>
                    <div class="single-funfact-wrap">
                        <div data-w-id="e6e872bc-6efa-f3dc-8502-d9cbc41c6bc5" class="funfact-number-block">
                            <div style="-webkit-transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                                class="funfact-number-wrap upper-movement">
                                <div class="funfact-number">1</div>
                                <div class="funfact-number">5</div>
                                <div class="funfact-number">6</div>
                                <div class="funfact-number">3</div>
                                <div class="funfact-number">1</div>
                            </div>
                            <div style="-webkit-transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                                class="funfact-number-wrap lower-movement">
                                <div class="funfact-number">0</div>
                                <div class="funfact-number">9</div>
                                <div class="funfact-number">8</div>
                                <div class="funfact-number">4</div>
                                <div class="funfact-number">2</div>
                            </div>
                            <div class="funfact-heading-text">+</div>
                        </div>
                        <div class="number-text-label">Total Experience</div>
                    </div>
                    <div class="single-funfact-wrap">
                        <div data-w-id="7a8249df-87ef-35a1-d47f-b4ecebd97bc8" class="funfact-number-block">
                            <div style="-webkit-transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                                class="funfact-number-wrap upper-movement">
                                <div class="funfact-number">10</div>
                                <div class="funfact-number">5</div>
                                <div class="funfact-number">4</div>
                                <div class="funfact-number">3</div>
                                <div class="funfact-number">3</div>
                            </div>
                            <div style="-webkit-transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                                class="funfact-number-wrap lower-movement">
                                <div class="funfact-number">5</div>
                                <div class="funfact-number">9</div>
                                <div class="funfact-number">8</div>
                                <div class="funfact-number">7</div>
                                <div class="funfact-number">00</div>
                            </div>
                            <div class="funfact-heading-text">+</div>
                        </div>
                        <div class="number-text-label">Clients</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="impact-number-bg-shape-wrap"><img
                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65c83c0c0ad0b8ff0f7387cf_Impact%20number%20BG%20shape.svg"
                loading="lazy" alt="Impact number BG Shape" class="fun-fact-bg-shape" /></div>
    </section>
    <section class="insurance-section section-y-axis-gap increase-section-gap">
        <div class="w-layout-blockcontainer main-container w-container">
            <div class="insurance-wrapper">
                <div class="section-heading-wrap center">
                    <div class="section-title-wrap insurance-title-max-width">
                        <h2 data-w-id="1a7d18fd-d572-e5c8-2794-5b89573c657e" style="opacity:1" class="section-title">We
                            Provide Insurance <span class="heading-highlight-text">Solutions</span> to Meet Your Needs</h2>
                    </div>
                    <div class="section-sub-title-wrap insurance-sub-title-max-width">
                        <div data-w-id="1a7d18fd-d572-e5c8-2794-5b89573c6584" style="opacity:1"
                            class="section-sub-title">Insurance companies are dedicated to protecting individuals and
                            businesses from the financial challenges of unexpected events and risks
                            .</div>
                    </div>
                </div>
                <div class="insurance-content-wrap">
                    <div data-w-id="ebbe2022-5a73-e986-ee7e-a253abc55302" style="opacity:1" class="insurance-shape-wrap">
                        <a href="#pricePlan" class="insurance-shape-link w-inline-block"><img
                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b88503f7188521c90f4f19_Scroll%20Now.png"
                                loading="lazy"
                                style="-webkit-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0deg) skew(0, 0);-moz-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0deg) skew(0, 0);-ms-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0deg) skew(0, 0);transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0deg) skew(0, 0)"
                                alt="Insurance Circle Shape" class="insurance-scroll-now-image" />
                            <div class="insurance-arrow-wrap"><img
                                    src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b8852137777e39a60385d5_Scroll%20Now%20Arrow.png"
                                    loading="lazy" data-w-id="ebbe2022-5a73-e986-ee7e-a253abc55305" alt="Insurance Shape"
                                    class="insurance-arrow-image" /></div>
                        </a>
                    </div>
                    <div class="insurance-card-wrap">
                        <div class="insurance-collection-wrap w-dyn-list">
                            <div role="list" class="insurance-collection-list w-dyn-items">
                                <div role="listitem" class="insurance-collection-items w-dyn-item">
                                    <div data-w-id="ebbe2022-5a73-e986-ee7e-a253abc5530a"
                                        style="background-color:#caf2f8;opacity:1" class="insurance-card">
                                        <div class="insurance-card-header-block">
                                            <div class="insurance-header-wrap">
                                                <div class="insurance-saving-title">Specialist in </div>
                                                <div class="insurance-title-wrap"><a href="/insurance/home-insurance"
                                                        class="insurance-card-title-link w-inline-block">
                                                        <div class="insurance-card-title">E-Rickshaw Insurance</div>
                                                    </a><img alt="Insurance Title Bg Image" loading="lazy"
                                                        src="https://assets-global.website-files.com/65b60c5eef338f6b2401687d/65bb37038f8e27fb82d9e644_Insurance%20House%20Text%20bg.png"
                                                        class="insurance-title-bg-shape-image absolute-bottom" />
                                                </div>
                                            </div>
                                            <div class="insurance-logo-wrap"><img alt="Insurance Logo" loading="lazy"
                                                    src="https://assets-global.website-files.com/65b60c5eef338f6b2401687d/65c09bd15894473eedf478d4_house-03.svg"
                                                    class="insurance-logo" /></div>
                                        </div>
                                        <p class="insurance-summary"> India's Trusted Leader in E-Rickshaw Insurance –
                                            Tailored plans for complete protection, ensuring peace of mind for every
                                            journey.
                                        </p>
                                        <div class="insurance-button-wrap"><a href="{{ route('e_rickshaw_insurance') }}"
                                                class="secondary-button w-button">Learn More Now</a></div><img
                                            alt="Insurance BG Image" loading="lazy"
                                            src="https://assets-global.website-files.com/65b60c5eef338f6b2401687d/65c09c356000266e5d847832_Home%20Bg.svg"
                                            class="insurance-bg-shape-image" />
                                    </div>
                                </div>

                                <div role="listitem" class="insurance-collection-items w-dyn-item">
                                    <div data-w-id="ebbe2022-5a73-e986-ee7e-a253abc5530a"
                                        style="background-color:#EBD7FD;opacity:1" class="insurance-card">
                                        <div class="insurance-card-header-block">
                                            <div class="insurance-header-wrap">
                                                <div class="insurance-saving-title">20% Savings</div>
                                                <div class="insurance-title-wrap"><a href="/insurance/auto-insurance"
                                                        class="insurance-card-title-link w-inline-block">
                                                        <div class="insurance-card-title">Two-Wheeler Insurance</div>
                                                    </a><img alt="Insurance Title Bg Image" loading="lazy"
                                                        src="https://assets-global.website-files.com/65b60c5eef338f6b2401687d/65bb3d3f91da7449af64e618_Auto%20Insurance%20Text%20Bg.png"
                                                        class="insurance-title-bg-shape-image absolute-bottom" />
                                                </div>
                                            </div>
                                            <div class="insurance-logo-wrap"><img alt="Insurance Logo" loading="lazy"
                                                    src="{{ asset('asset/website/images/sei.gif') }}"
                                                    class="insurance-logo" /></div>
                                        </div>
                                        <p class="insurance-summary">Safeguard your bike or scooter with comprehensive coverage, protecting against accidents, theft, and unforeseen damages. Enjoy peace of mind with plans that ensure your two-wheeler stays road-ready and secure.                                            .</p>
                                        <div class="insurance-button-wrap"><a href="{{route('two_wheeler_insurance')}}"
                                                class="secondary-button w-button">Learn More Now</a></div><img
                                            alt="Insurance BG Image" loading="lazy"
                                            src="https://assets-global.website-files.com/65b60c5eef338f6b2401687d/65c09c477e4ae2e81d26c72c_Auto%20Bg.svg"
                                            class="insurance-bg-shape-image" />
                                    </div>
                                </div>

                                <div role="listitem" class="insurance-collection-items w-dyn-item">
                                    <div data-w-id="ebbe2022-5a73-e986-ee7e-a253abc5530a"
                                        style="background-color:#FFDCCD;opacity:1" class="insurance-card">
                                        <div class="insurance-card-header-block">
                                            <div class="insurance-header-wrap">
                                                <div class="insurance-saving-title">30% Savings</div>
                                                <div class="insurance-title-wrap"><a href="/insurance/health-insurance"
                                                        class="insurance-card-title-link w-inline-block">
                                                        <div class="insurance-card-title">Health Insurance
                                                        </div>
                                                    </a><img alt="Insurance Title Bg Image" loading="lazy"
                                                        src="https://assets-global.website-files.com/65b60c5eef338f6b2401687d/65bb4e9743e0160a5c606df6_Health%20Insurance%20Title%20Bg.png"
                                                        class="insurance-title-bg-shape-image absolute-bottom" />
                                                </div>
                                            </div>
                                            <div class="insurance-logo-wrap"><img alt="Insurance Logo" loading="lazy"
                                                    src="https://assets-global.website-files.com/65b60c5eef338f6b2401687d/65c09beb31cd6303a7a9931e_Health.svg"
                                                    class="insurance-logo" /></div>
                                        </div>
                                        <p class="insurance-summary">Comprehensive Health Protection – Secure your future with tailored plans for your well-being. Enjoy peace of mind with coverage designed to meet your health needs.
                                        </p>
                                        <div class="insurance-button-wrap"><a href="{{route('health_insurance')}}"
                                                class="secondary-button w-button">Learn More Now</a></div><img
                                            alt="Insurance BG Image" loading="lazy"
                                            src="https://assets-global.website-files.com/65b60c5eef338f6b2401687d/65c09cd9734a4f3325ae5453_Health%20Bg.svg"
                                            class="insurance-bg-shape-image" />
                                    </div>
                                </div>

                                <div role="listitem" class="insurance-collection-items w-dyn-item">
                                    <div data-w-id="ebbe2022-5a73-e986-ee7e-a253abc5530a"
                                        style="background-color:#F5FAD4;opacity:1" class="insurance-card">
                                        <div class="insurance-card-header-block">
                                            <div class="insurance-header-wrap">
                                                <div class="insurance-saving-title">25% Savings</div>
                                                <div class="insurance-title-wrap"><a href="/insurance/travel-insurance"
                                                        class="insurance-card-title-link w-inline-block">
                                                        <div class="insurance-card-title">Private Car Insurance
                                                        </div>
                                                    </a><img alt="Insurance Title Bg Image" loading="lazy"
                                                        src="https://assets-global.website-files.com/65b60c5eef338f6b2401687d/65bb4f79f67961724fd14a33_Travel%20Insurance%20Title%20Bg.png"
                                                        class="insurance-title-bg-shape-image absolute-bottom" />
                                                </div>
                                            </div>
                                            <div class="insurance-logo-wrap"><img alt="Insurance Logo" loading="lazy"
                                                    src="https://assets-global.website-files.com/65b60c5eef338f6b2401687d/65c09bf61fc24121af17b6d3_Travel.svg"
                                                    class="insurance-logo" /></div>
                                        </div>
                                        <p class="insurance-summary">Protect your car and travel with confidence. Our plans cover accidental damages, theft, and liability, ensuring you and your vehicle stay secure on every journey. Choose comprehensive protection tailored to your needs.                                            .</p>
                                        <div class="insurance-button-wrap"><a href="{{route('private_car_insurance')}}"
                                                class="secondary-button w-button">Learn More Now</a></div><img
                                            alt="Insurance BG Image" loading="lazy"
                                            src="https://assets-global.website-files.com/65b60c5eef338f6b2401687d/65c09ce8de98f1b59a68f93f_Travel%20Bg.svg"
                                            class="insurance-bg-shape-image" />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div data-w-id="ebbe2022-5a73-e986-ee7e-a253abc5531c" style="opacity:1"
                        class="insurance-button-wrapper"><a href="{{route('insurance')}}" class="primary-button w-button">View
                            All Insurance</a></div>
                </div>
            </div>
        </div>
    </section>

    {{-- <section data-w-id="6693c89a-2b93-3cb7-9fd7-2719f78cffa2" class="app-section">
        <div class="app-section-wrapper">
            <div class="app-content-block">
                <div class="app-content">
                    <div class="app-heading-wrap">
                        <h2 data-w-id="6693c89a-2b93-3cb7-9fd7-2719f78cffa7" style="opacity:1" class="app-title">Take a
                            Look at the Insurbes Mobile App</h2>
                        <div data-w-id="6693c89a-2b93-3cb7-9fd7-2719f78cffa9" style="opacity:1" class="app-sub-title">
                            Insurbes companies work to sense more security and peace they
                            have insurance coverage</div>
                    </div>
                    <div data-w-id="6693c89a-2b93-3cb7-9fd7-2719f78cffab" style="opacity:1" class="app-link-wrap"><a
                            href="https://play.google.com/store/apps?hl=en&amp;gl=US" class="app-link w-inline-block"><img
                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b9dd51ca197f45ab2c2c81_Badge%20Android.png"
                                loading="lazy" alt="Google Play Image" class="google-play-image" /></a><a
                            href="https://www.apple.com/store" class="app-link w-inline-block"><img
                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b9dd5ef59582dbae96f809_Badge%20iOS.png"
                                loading="lazy" alt="App Store" class="app-store-image" /></a></div>
                </div><img
                    src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65b9e2616abe9183e00cfcd4_App%20Bg%20Image.png"
                    loading="lazy" alt="App BG Shape" class="app-content-bg-image" />
            </div>
            <div class="app-image-block">
                <div class="app-image-inner"><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65d47d01fd1f245d0864faaa_Primary%20Mobile%20Photo.png"
                        loading="lazy" alt="App Mobile Image" class="app-primary-image" /><img
                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65d47d17f2491d6b51bdceb9_Secondary%20Mobile%20Photo.png"
                        loading="lazy" alt="App Mobile Image" class="app-secondary-image" /></div>
            </div>
        </div>
    </section> --}}

    <section id="pricePlan" class="insurance-price-section section-y-axis-gap increase-padding">
        <div class="w-layout-blockcontainer main-container w-container">
            <div class="insurance-price-wrapper">
                <div class="section-heading-wrap center">
                    <div class="section-title-wrap insurance-price-title-max-width">
                        <h2 data-w-id="1d0150f3-c970-f95a-51d2-c5de322322b7" style="opacity:1" class="section-title">
                            We’ve Got an Awesome <span class="heading-highlight-text">Price</span> Plan For Your Insurance
                        </h2>
                    </div>
                    <div class="section-sub-title-wrap insurance-price-sub-title-max-width">
                        <div data-w-id="1d0150f3-c970-f95a-51d2-c5de322322bc" style="opacity:1"
                            class="section-sub-title">Insurance companies need to maintain financial stability
                            to fulfill their obligations to policyholders.</div>
                    </div>
                </div>
                {{-- <div class="insurance-price-content-wrap">
                    <div data-current="Tab 1" data-easing="ease" data-duration-in="300" data-duration-out="100"
                        class="insurance-tabs w-tabs">
                        <div data-w-id="63e7e0cc-3d52-8875-5bb3-93177ee4250f" class="insurance-tab-toggle w-tab-menu"><a
                                data-w-tab="Tab 1" class="monthly-tab-link w-inline-block w-tab-link w--current">
                                <div class="month-tab">Monthly</div>
                                <div class="tab-circle"></div>
                            </a><a data-w-tab="Tab 2" class="annually-tab-link w-inline-block w-tab-link">
                                <div class="annual-image-wrap"><img width="268" loading="lazy"
                                        alt="Annual Save Image"
                                        src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ba30768436713a5fe91dd8_Saveings.png"
                                        class="annual-image" /></div>
                                <div class="annual-tab">Annually</div>
                            </a></div>
                        <div class="insurance-tab-content-block w-tab-content">
                            <div data-w-tab="Tab 1" class="insurance-tab-month-content w-tab-pane w--tab-active">
                                <div class="insurance-month-content-wrap">
                                    <div data-w-id="63e7e0cc-3d52-8875-5bb3-93177ee42531"
                                        class="insurance-pricing-list-wrapper w-dyn-list">
                                        <div role="list" class="insurance-pricing-list w-dyn-items">
                                            <div role="listitem" class="insurance-pricing-item w-dyn-item">
                                                <div class="insurance-pricing-content">
                                                    <div class="insurance-checkbox-and-name-wrap">
                                                        <div data-w-id="d30f04ae-c39a-d3b2-56f7-76df1c8ab816"
                                                            class="insurance-checkbox checkbox-one"><img width="20"
                                                                loading="lazy" alt="Check Icon "
                                                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ba392c8601d5eadb4f07d7_Check%20Icon.png"
                                                                class="insurance-check-icon" /></div>
                                                        <div class="insurance-name-and-savings">
                                                            <div class="insurance-name">Home Insurance</div>
                                                            <div class="insurance-savings">5% Savings</div>
                                                        </div>
                                                    </div>
                                                    <div class="insurance-total-price-wrap">
                                                        <div class="insurance-total-text">Total</div>
                                                        <div class="insurance-total-price">$160.56</div>
                                                    </div>
                                                    <div class="insurance-button"><a href="/insurance/home-insurance"
                                                            class="secondary-button padding-minus w-button">Purchase
                                                            Now</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-w-id="63e7e0cc-3d52-8875-5bb3-93177ee42546"
                                        class="insurance-pricing-list-wrapper w-dyn-list">
                                        <div role="list" class="insurance-pricing-list w-dyn-items">
                                            <div role="listitem" class="insurance-pricing-item w-dyn-item">
                                                <div class="insurance-pricing-content">
                                                    <div class="insurance-checkbox-and-name-wrap">
                                                        <div data-w-id="36ce8def-bd3c-8a49-bdb7-6505a6e5331e"
                                                            class="insurance-checkbox checkbox-two"><img loading="lazy"
                                                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ba392c8601d5eadb4f07d7_Check%20Icon.png"
                                                                alt="Check Icon " class="insurance-check-icon" /></div>
                                                        <div class="insurance-name-and-savings">
                                                            <div class="insurance-name">Auto Insurance</div>
                                                            <div class="insurance-savings">10% Savings</div>
                                                        </div>
                                                    </div>
                                                    <div class="insurance-total-price-wrap">
                                                        <div class="insurance-total-text">Total</div>
                                                        <div class="insurance-total-price">$370.56</div>
                                                    </div>
                                                    <div class="insurance-button"><a href="/insurance/auto-insurance"
                                                            class="secondary-button padding-minus w-button">Purchase
                                                            Now</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-w-id="63e7e0cc-3d52-8875-5bb3-93177ee4255b"
                                        class="insurance-pricing-list-wrapper w-dyn-list">
                                        <div role="list" class="insurance-pricing-list w-dyn-items">
                                            <div role="listitem" class="insurance-pricing-item w-dyn-item">
                                                <div class="insurance-pricing-content">
                                                    <div class="insurance-checkbox-and-name-wrap">
                                                        <div data-w-id="eafe44a7-36a1-c289-c49e-b64af2d01c6c"
                                                            class="insurance-checkbox checkbox-three"><img loading="lazy"
                                                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ba392c8601d5eadb4f07d7_Check%20Icon.png"
                                                                alt="Check Icon " class="insurance-check-icon" /></div>
                                                        <div class="insurance-name-and-savings">
                                                            <div class="insurance-name">Health Insurance</div>
                                                            <div class="insurance-savings">15% Savings</div>
                                                        </div>
                                                    </div>
                                                    <div class="insurance-total-price-wrap">
                                                        <div class="insurance-total-text">Total</div>
                                                        <div class="insurance-total-price">$465.20</div>
                                                    </div>
                                                    <div class="insurance-button"><a href="/insurance/health-insurance"
                                                            class="secondary-button padding-minus w-button">Purchase
                                                            Now</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-w-id="63e7e0cc-3d52-8875-5bb3-93177ee4251c"
                                        class="insurance-pricing-list-wrapper w-dyn-list">
                                        <div role="list" class="insurance-pricing-list w-dyn-items">
                                            <div role="listitem" class="insurance-pricing-item w-dyn-item">
                                                <div class="insurance-pricing-content">
                                                    <div class="insurance-checkbox-and-name-wrap">
                                                        <div data-w-id="63e7e0cc-3d52-8875-5bb3-93177ee42521"
                                                            class="insurance-checkbox checkbox-four"><img loading="lazy"
                                                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ba392c8601d5eadb4f07d7_Check%20Icon.png"
                                                                alt="Check Icon " class="insurance-check-icon" /></div>
                                                        <div class="insurance-name-and-savings">
                                                            <div class="insurance-name">Travel Insurance</div>
                                                            <div class="insurance-savings">20% Savings</div>
                                                        </div>
                                                    </div>
                                                    <div class="insurance-total-price-wrap">
                                                        <div class="insurance-total-text">Total</div>
                                                        <div class="insurance-total-price">$520.40</div>
                                                    </div>
                                                    <div class="insurance-button"><a href="/insurance/travel-insurance"
                                                            class="secondary-button padding-minus w-button">Purchase
                                                            Now</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-w-tab="Tab 2" class="insurance-tab-annual-content w-tab-pane">
                                <div class="insurance-annual-content-wrap">
                                    <div data-w-id="63e7e0cc-3d52-8875-5bb3-93177ee42572"
                                        class="insurance-pricing-list-wrapper w-dyn-list">
                                        <div role="list" class="insurance-pricing-list w-dyn-items">
                                            <div role="listitem" class="insurance-pricing-item w-dyn-item">
                                                <div class="insurance-pricing-content">
                                                    <div class="insurance-checkbox-and-name-wrap">
                                                        <div data-w-id="b2450baf-95ca-80e7-c250-9c4f2bd00aa5"
                                                            class="insurance-checkbox checkbox-one"><img loading="lazy"
                                                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ba392c8601d5eadb4f07d7_Check%20Icon.png"
                                                                alt="Check Icon " class="insurance-check-icon" /></div>
                                                        <div class="insurance-name-and-savings">
                                                            <div class="insurance-name">Home Insurance</div>
                                                            <div class="insurance-savings">5% Savings</div>
                                                        </div>
                                                    </div>
                                                    <div class="insurance-total-price-wrap">
                                                        <div class="insurance-total-text">Total</div>
                                                        <div class="insurance-total-price">$160.56</div>
                                                    </div>
                                                    <div class="insurance-button"><a href="/insurance/home-insurance"
                                                            class="secondary-button padding-minus w-button">Purchase
                                                            Now</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-w-id="63e7e0cc-3d52-8875-5bb3-93177ee42587"
                                        class="insurance-pricing-list-wrapper w-dyn-list">
                                        <div role="list" class="insurance-pricing-list w-dyn-items">
                                            <div role="listitem" class="insurance-pricing-item w-dyn-item">
                                                <div class="insurance-pricing-content">
                                                    <div class="insurance-checkbox-and-name-wrap">
                                                        <div data-w-id="dfcca466-293a-9662-a378-fad4160c6500"
                                                            class="insurance-checkbox checkbox-two"><img loading="lazy"
                                                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ba392c8601d5eadb4f07d7_Check%20Icon.png"
                                                                alt="Check Icon " class="insurance-check-icon" /></div>
                                                        <div class="insurance-name-and-savings">
                                                            <div class="insurance-name">Auto Insurance</div>
                                                            <div class="insurance-savings">10% Savings</div>
                                                        </div>
                                                    </div>
                                                    <div class="insurance-total-price-wrap">
                                                        <div class="insurance-total-text">Total</div>
                                                        <div class="insurance-total-price">$370.56</div>
                                                    </div>
                                                    <div class="insurance-button"><a href="/insurance/auto-insurance"
                                                            class="secondary-button padding-minus w-button">Purchase
                                                            Now</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-w-id="63e7e0cc-3d52-8875-5bb3-93177ee4259c"
                                        class="insurance-pricing-list-wrapper w-dyn-list">
                                        <div role="list" class="insurance-pricing-list w-dyn-items">
                                            <div role="listitem" class="insurance-pricing-item w-dyn-item">
                                                <div class="insurance-pricing-content">
                                                    <div class="insurance-checkbox-and-name-wrap">
                                                        <div data-w-id="45670251-491c-d063-dc3c-57a437301190"
                                                            class="insurance-checkbox checkbox-three"><img loading="lazy"
                                                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ba392c8601d5eadb4f07d7_Check%20Icon.png"
                                                                alt="Check Icon " class="insurance-check-icon" /></div>
                                                        <div class="insurance-name-and-savings">
                                                            <div class="insurance-name">Health Insurance</div>
                                                            <div class="insurance-savings">15% Savings</div>
                                                        </div>
                                                    </div>
                                                    <div class="insurance-total-price-wrap">
                                                        <div class="insurance-total-text">Total</div>
                                                        <div class="insurance-total-price">$465.20</div>
                                                    </div>
                                                    <div class="insurance-button"><a href="/insurance/health-insurance"
                                                            class="secondary-button padding-minus w-button">Purchase
                                                            Now</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-w-id="63e7e0cc-3d52-8875-5bb3-93177ee425b1"
                                        class="insurance-pricing-list-wrapper w-dyn-list">
                                        <div role="list" class="insurance-pricing-list w-dyn-items">
                                            <div role="listitem" class="insurance-pricing-item w-dyn-item">
                                                <div class="insurance-pricing-content">
                                                    <div class="insurance-checkbox-and-name-wrap">
                                                        <div data-w-id="b48a83e2-1082-e8a9-a24c-bf0432e32c00"
                                                            class="insurance-checkbox checkbox-four"><img loading="lazy"
                                                                src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65ba392c8601d5eadb4f07d7_Check%20Icon.png"
                                                                alt="Check Icon " class="insurance-check-icon" /></div>
                                                        <div class="insurance-name-and-savings">
                                                            <div class="insurance-name">Travel Insurance</div>
                                                            <div class="insurance-savings">20% Savings</div>
                                                        </div>
                                                    </div>
                                                    <div class="insurance-total-price-wrap">
                                                        <div class="insurance-total-text">Total</div>
                                                        <div class="insurance-total-price">$520.40</div>
                                                    </div>
                                                    <div class="insurance-button"><a href="/insurance/travel-insurance"
                                                            class="secondary-button padding-minus w-button">Purchase
                                                            Now</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-w-id="63e7e0cc-3d52-8875-5bb3-93177ee425c6" class="insurance-included-services-block">
                        <div class="insurance-included-services-wrap">
                            <div class="insurance-service-collection-list-wrap service-collection-one w-dyn-list">
                                <div role="list" class="insurance-service-collection-list w-dyn-items">
                                    <div role="listitem" class="insurance-service-collection-items w-dyn-item">
                                        <div class="insurance-service-content service-item-one">
                                            <div class="insurance-services-rich-text w-richtext">
                                                <h3>What Service Included?</h3>
                                                <ul role="list">
                                                    <li>Comprehensive Coverage</li>
                                                    <li>Smart Home Integration</li>
                                                    <li>Deductible Choices</li>
                                                    <li>Phone or Email Consultation</li>
                                                    <li>Discount Programs</li>
                                                    <li>24/7 Always Support</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="insurance-service-collection-list-wrap service-collection-two w-dyn-list">
                                <div role="list" class="insurance-service-collection-list w-dyn-items">
                                    <div role="listitem" class="insurance-service-collection-items w-dyn-item">
                                        <div class="insurance-service-content service-item-two">
                                            <div class="insurance-services-rich-text w-richtext">
                                                <h3>What Service Included?</h3>
                                                <ul role="list">
                                                    <li>Discount Programs</li>
                                                    <li>Annual Policy Reviews</li>
                                                    <li>Digital Security Coverage</li>
                                                    <li>Phone or Email Consultation</li>
                                                    <li>Customizable Add-ons</li>
                                                    <li>24/7 Always Support</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="insurance-service-collection-list-wrap service-collection-three w-dyn-list">
                                <div role="list" class="insurance-service-collection-list w-dyn-items">
                                    <div role="listitem" class="insurance-service-collection-items w-dyn-item">
                                        <div class="insurance-service-content service-itmes-three">
                                            <div class="insurance-services-rich-text w-richtext">
                                                <h3>What Service Included?</h3>
                                                <ul role="list">
                                                    <li>Claims Assistance</li>
                                                    <li>Customizable Add-ons</li>
                                                    <li>Comprehensive Coverage</li>
                                                    <li>Deductible Choices</li>
                                                    <li>Phone or Email Consultation</li>
                                                    <li>24/7 Always Support</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="insurance-service-collection-list-wrap service-collection-four w-dyn-list">
                                <div role="list" class="insurance-service-collection-list w-dyn-items">
                                    <div role="listitem" class="insurance-service-collection-items w-dyn-item">
                                        <div class="insurance-service-content service-items-four">
                                            <div class="insurance-services-rich-text w-richtext">
                                                <h3>What Service Included?</h3>
                                                <ul role="list">
                                                    <li>Comprehensive Coverage</li>
                                                    <li>Customizable Add-ons</li>
                                                    <li>Deductible Choices</li>
                                                    <li>Phone or Email Consultation</li>
                                                    <li>Discount Programs</li>
                                                    <li>24/7 Always Support</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>

@include('pages/website/section/testimonial')
@include('pages/website/section/faq')



    {{-- <section data-w-id="8f2ded18-e9f9-1588-8d22-a340a1e98855" class="cta-section cta-section-gap">
        <div class="w-layout-blockcontainer main-container w-container">
            <div class="cta-wrapper">
                <div class="section-heading-wrap center">
                    <div class="section-title-wrap">
                        <h2 class="section-title">Get <span class="heading-highlight-text">Insurance</span>
                            Now</h2>
                    </div>
                    <div class="section-sub-title-wrap cta-sub-title-max-width">
                        <div class="section-sub-title">Insurbes companies work to sense more security and
                            peace they have insurance coverage.</div>
                    </div>
                </div>
                <div class="cta-newsletter-form-wrap">
                    <div class="newsletter-form-block w-form">
                        <form id="email-form" name="email-form" data-name="Email Form" method="get"
                            class="newsletter-form center" data-wf-page-id="65b60c5eef338f6b2401686d"
                            data-wf-element-id="8f2ded18-e9f9-1588-8d22-a340a1e98863"><input
                                class="newsletter-input-field w-input" maxlength="256" name="Email-3"
                                data-name="Email 3" placeholder="Enter Your Email..." type="email" id="Email-3"
                                required="" /><input type="submit" data-wait="Please wait..."
                                class="primary-button w-button" value="Get A Quote" /></form>
                        <div class="w-form-done">
                            <div class="success-message-text">Thank you! Your submission has been received!
                            </div>
                        </div>
                        <div class="w-form-fail">
                            <div class="error-message-text">Oops! Something went wrong while submitting the
                                form.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div><img
            src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65bf81a70870ab43d825e7e6_CTA%20Bg%20Shape.png"
            loading="lazy" alt="CTA Bg Shape Image" class="cta-bg-shape-image" /><img
            src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65cdb3ff5d14df92f80ad5ae_Contact%20Circle%20Shape.svg"
            loading="lazy" data-w-id="8f2ded18-e9f9-1588-8d22-a340a1e9886d" alt="CTA Circle Shape Image"
            class="cta-circle-shape-image" />
    </section> --}}
@endsection
