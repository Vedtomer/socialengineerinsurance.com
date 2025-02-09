
@extends('pages.website.layouts.app')


@section('content')





<section class="about-us-section" style="padding-top: 60px; padding-bottom: 60px;"> {{-- Added padding for better spacing --}}
    <div class="w-layout-blockcontainer main-container w-container" style="max-width: 960px;"> {{-- Adjust max-width as needed --}}
        <div class="about-us-wrapper">
            <div class="about-us-header" style="text-align: center; margin-bottom: 40px;"> {{-- Centered and added margin --}}
                <h1 data-w-id="fee1da3b-23dd-7373-10e7-2b557b414e70"
                class="page-title " >About Social Engineer Insurance</h1> {{-- Example styling - adjust font --}}
                <p class="about-us-subtitle" style="font-size: 1.1em; color: #777;"> {{-- Example styling - adjust font & color --}}
                    Securing Your Future with Tailored Insurance Solutions. At Social Engineer Insurance, we're dedicated to providing innovative and personalized coverage that puts your safety and peace of mind first.
                </p>
            </div>

            <div class="our-mission-section" style="margin-bottom: 50px;"> {{-- Added margin --}}
                <h2 class="section-title" style="font-size: 2em; font-weight: 600; margin-bottom: 20px; color: #333;">Our Mission</h2> {{-- Example styling - adjust font & color --}}
                <p class="section-description" style="font-size: 1.1em; line-height: 1.6; color: #555;"> {{-- Example styling - adjust font & line-height --}}
                    Empowering You with Protection. Social Engineer Insurance is on a mission to make comprehensive and customized insurance accessible to everyone. We are deeply committed to securing your future, offering specialized plans from electric rickshaw coverage to vital life and health protection.
                </p>
            </div>

            <div class="why-choose-us-section" style="margin-bottom: 50px;"> {{-- Added margin --}}
                <h2 class="section-title" style="font-size: 2em; font-weight: 600; margin-bottom: 20px; color: #333;">Why Choose Social Engineer Insurance?</h2> {{-- Example styling - adjust font & color --}}
                <ul class="why-choose-us-list" style="list-style: none; padding-left: 0;"> {{-- Removed default list style --}}
                    <li style="margin-bottom: 15px; font-size: 1.1em; color: #555;"> {{-- Added margin & font styling --}}
                        <strong style="font-weight: 600; color: #333;">Your Advocate, Not Ours:</strong> As an independent provider, we work solely for you, ensuring you get the most favorable terms and ideal insurance solutions.
                    </li>
                    <li style="margin-bottom: 15px; font-size: 1.1em; color: #555;"> {{-- Added margin & font styling --}}
                        <strong style="font-weight: 600; color: #333;">Specialized Expertise:</strong> We are specialists in electric rickshaw, life, and health insurance, offering deep knowledge and tailored plans.
                    </li>
                    <li style="font-size: 1.1em; color: #555;"> {{-- Added font styling --}}
                        <strong style="font-weight: 600; color: #333;">You're at the Heart of Everything:</strong> Our customer-centric approach means your unique needs drive our plan design, ensuring personalized coverage.
                    </li>
                </ul>
            </div>

            <div class="team-details-content-wrap" style="align-items: center; margin-bottom: 60px;"> {{-- Added margin --}}
                <div id="w-node-_8b166095-ca7c-830e-4eba-fe2fc62b8504-7d56c38a" class="team-details-image-block">
                    <img alt="Team Member Image" loading="lazy"
                         src="{{ asset('asset/website/images/gourav.png') }}"
                         sizes="(max-width: 479px) 89vw, (max-width: 767px) 93vw, (max-width: 991px) 668px, (max-width: 1439px) 478px, 511px"
                         srcset="{{ asset('asset/website/images/gourav.png') }} 512w"
                         class="team-member-image team-member-details-image" />
                    <div class="image-overlay-wrap">
                        <div class="image-overlay-grid">
                            <div class="image-overlay-mask mask-one"></div>
                            <div class="image-overlay-mask mask-two"></div>
                            <div class="image-overlay-mask mask-three"></div>
                            <div class="image-overlay-mask mask-four"></div>
                        </div>
                    </div>
                </div>
                <div id="w-node-aff92f30-3536-f71c-92a5-5ad90bd39e9e-7d56c38a" class="team-details-content-block" style="padding-left: 30px;"> {{-- Added padding --}}
                    <div class="team-member-info-block">
                        <h2 class="team-member-info-title" style="font-size: 2em; font-weight: 600; margin-bottom: 15px; color: #333;">Gourav Bhalla</h2> {{-- Example styling - adjust font & color --}}
                        <p class="team-member-summary" style="font-size: 1.1em; line-height: 1.6; color: #555;"> {{-- Example styling - adjust font & line-height --}}
                            Gourav Bhalla is the founder and driving force behind Social Engineer Insurance. With over 12 years of experience in the insurance industry, Gourav has a deep understanding of the complexities of insurance needs.
                            <br><br>
                            Before starting his own business three years ago, Gourav worked in various roles across the insurance sector, where he honed his skills in customer service, claims management, and policy development. His goal is to make insurance accessible and straightforward for everyone.
                        </p>
                    </div>
                </div>
            </div>

            <div class="core-values-section" style="margin-bottom: 50px;"> {{-- Added margin --}}
                <h2 class="section-title" style="font-size: 2em; font-weight: 600; margin-bottom: 20px; color: #333;">Our Core Values</h2> {{-- Example styling - adjust font & color --}}
                <ul class="core-values-list" style="list-style: none; padding-left: 0;"> {{-- Removed default list style --}}
                    <li style="margin-bottom: 15px; font-size: 1.1em; color: #555;"> {{-- Added margin & font styling --}}
                        <strong style="font-weight: 600; color: #333;">Integrity:</strong> We build lasting trust through complete transparency and unwavering ethical practices.
                    </li>
                    <li style="margin-bottom: 15px; font-size: 1.1em; color: #555;"> {{-- Added margin & font styling --}}
                        <strong style="font-weight: 600; color: #333;">Customer First:</strong> Your needs are paramount – we prioritize them in every decision and solution we offer.
                    </li>
                    <li style="font-size: 1.1em; color: #555;"> {{-- Added font styling --}}
                        <strong style="font-weight: 600; color: #333;">Innovative Solutions:</strong> We are committed to delivering forward-thinking and highly customized insurance for a changing world.
                    </li>
                </ul>
            </div>

            <div class="testimonials-section" style="margin-bottom: 60px; padding: 40px; background-color: #f9f9f9; border-radius: 8px; text-align: center;"> {{-- Added styling for box --}}
                <h2 class="section-title" style="font-size: 2em; font-weight: 600; margin-bottom: 20px; color: #333;">What Our Clients Say</h2> {{-- Example styling - adjust font & color --}}
                <p class="testimonial" style="font-size: 1.1em; line-height: 1.6; color: #555; font-style: italic; margin-bottom: 20px;"> {{-- Example styling - adjust font & line-height --}}
                    "Social Engineer Insurance has been a game-changer for me. Their tailored electric rickshaw insurance plan gave me the peace of mind I needed. Highly recommend!" – <strong style="font-weight: 600; font-style: normal; color: #333;">Ved Tomer</strong>
                </p>
                <p class="testimonial" style="font-size: 1.1em; line-height: 1.6; color: #555; font-style: italic;"> {{-- Example styling - adjust font & line-height --}}
                    "The team at Social Engineer Insurance truly cares. They guided me through every step of my life insurance policy. Great service!" – <strong style="font-weight: 600; font-style: normal; color: #333;">Neetu Mahal</strong>
                </p>
            </div>

            <div class="contact-section" style="text-align: center;"> {{-- Centered text --}}
                <h2 class="section-title" style="font-size: 2em; font-weight: 600; margin-bottom: 20px; color: #333;">Ready to Get Started?</h2> {{-- Example styling - adjust font & color --}}
                <p class="section-description" style="font-size: 1.1em; line-height: 1.6; color: #555; margin-bottom: 30px;"> {{-- Example styling - adjust font & line-height & margin --}}
                    Take the first step towards securing your future. Contact us today to explore personalized insurance solutions and discover how Social Engineer Insurance can protect what matters most to you.
                </p>
                <a href="{{ route('contact-us') }}" class="secondary-button w-button">Get in Touch</a> {{-- Example button styling --}}
            </div>
        </div>
    </div>
</section>
@endsection
