@php
    // Store all testimonial data in an array (Indian customers and clients)
    $testimonials = [
        [
            'class' => 'clint-one',
            'rating' => 5,
            'feedback_title' => 'That is awesome!',
            'feedback_summary' => 'The personalized attention and creative solutions you provided were a game-changer for our business.',
            'client_image' => 'https://assets-global.website-files.com/65b60c5def338f6b24016820/65e6eacf60326ed2290a300d_Client%203.png',
            'client_name' => 'Ramandip',
            'client_designation' => 'Manager Dashmesh Auto',
        ],
        [
            'class' => 'client-two',
            'rating' => 5,
            'feedback_title' => 'Absolutely impressive',
            'feedback_summary' => 'The personalized attention and creative solutions you provided were a game-changer for our business.',
            'client_image' => 'https://assets-global.website-files.com/65b60c5def338f6b24016820/65e6eacf60326ed2290a300d_Client%203.png',
            'client_name' => 'Ankit Arora',
            'client_designation' => 'DC Energies in Ludhiana',
        ],
        [
            'class' => 'client-three',
            'rating' => 5,
            'feedback_title' => 'It\'s really wonderful.',
            'feedback_summary' => 'The personalized attention and creative solutions you provided were a game-changer for our business.',
            'client_image' => 'https://assets-global.website-files.com/65b60c5def338f6b24016820/65e6eacf60326ed2290a300d_Client%203.png',
            'client_name' => 'Pankaj Chawla',
            'client_designation' => 'Executive & Founder of Insurezone Consultancy',
        ],
        [
            'class' => 'clint-one',
            'rating' => 5,
            'feedback_title' => 'That is awesome!',
            'feedback_summary' => 'The personalized attention and creative solutions you provided were a game-changer for our business.',
            'client_image' => 'https://assets-global.website-files.com/65b60c5def338f6b24016820/65bb13ab3c4304404d69d9fd_Jacqueline%20Rosa.png',
            'client_name' => 'Neetu Mahal',
            'client_designation' => 'Founder of Saga Softwares',
        ],
        // [
        //     'class' => 'client-two',
        //     'rating' => 5,
        //     'feedback_title' => 'Absolutely impressive',
        //     'feedback_summary' => 'The personalized attention and creative solutions you provided were a game-changer for our business.',
        //     'client_image' => 'https://assets-global.website-files.com/65b60c5def338f6b24016820/65bb1c61677f0f4790f3fc28_Sophia%20Flora.png',
        //     'client_name' => 'Vikram Mehta',
        //     'client_designation' => '',
        // ],
        // [
        //     'class' => 'client-three',
        //     'rating' => 5,
        //     'feedback_title' => 'It\'s really wonderful.',
        //     'feedback_summary' => 'The personalized attention and creative solutions you provided were a game-changer for our business.',
        //     'client_image' => 'https://assets-global.website-files.com/65b60c5def338f6b24016820/65bb1c70114f68d1c132f915_Jessica%20Albe.png',
        //     'client_name' => 'Ananya Reddy',
        //     'client_designation' => '',
        // ],
    ];
@endphp

<section class="testimonial-section">
    <div class="w-layout-blockcontainer main-container w-container">
        <div class="testimonial-wrapper">
            <div class="section-heading-wrap section-heading-max-width">
                <div class="section-title-wrap">
                    <h2 data-w-id="e4a04614-71b9-1845-f8cd-1ae395cb8f2b" class="section-title">
                        Let’s Check Our Happy <span class="heading-highlight-text">Clients</span> Reviews
                    </h2>
                </div>
                <div class="section-sub-title-wrap testimonial-sub-title-max-width">
                    <div data-w-id="e4a04614-71b9-1845-f8cd-1ae395cb8f31" class="section-sub-title">
                        These insurance providers say our centralized platform planning and monitoring insurance lifecycle.
                    </div>
                </div>
            </div>
            <div class="testimonial-content-wrap">
                <div data-delay="4000" data-animation="slide" class="testimonial-slider w-slider"
                    data-autoplay="false" data-easing="ease" data-hide-arrows="false" data-disable-swipe="false"
                    data-autoplay-limit="0" data-nav-spacing="3" data-duration="500" data-infinite="true">
                    <div data-w-id="e4a04614-71b9-1845-f8cd-1ae395cb8f35" class="testmonial-slider-mask w-slider-mask">
                        @foreach ($testimonials as $testimonial)
                            <div class="testimonial-slider-item w-slide">
                                <div class="client-feedback-card {{ $testimonial['class'] }}">
                                    <div class="client-rating-block">
                                        @for ($i = 0; $i < $testimonial['rating']; $i++)
                                            <img src="https://assets-global.website-files.com/65b60c5def338f6b24016820/65bb1027f73cc18c946bc558_Star.png"
                                                loading="lazy" alt="Star Icon" class="client-rating-star-icon" />
                                        @endfor
                                    </div>
                                    <div class="client-feedback-block">
                                        <div class="client-feedback-title">{{ $testimonial['feedback_title'] }}</div>
                                        <p class="client-feedback-summary">{{ $testimonial['feedback_summary'] }}</p>
                                    </div>
                                    <div class="client-info-block">
                                        <img src="{{ $testimonial['client_image'] }}" loading="lazy" alt="Client Image" class="client-image" />
                                        <div class="client-name-and-title-wrap">
                                            <div class="client-name">{{ $testimonial['client_name'] }}</div>
                                            <div class="client-designation">{{ $testimonial['client_designation'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div data-w-id="e4a04614-71b9-1845-f8cd-1ae395cb8fae" class="slider-left-arrow w-slider-arrow-left">
                        <div class="left-arrow-icon w-embed">
                            <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.3332 10H4.33325" stroke="#070707" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M9.66675 2L1.66675 10L9.66675 18" stroke="#070707" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                    <div data-w-id="e4a04614-71b9-1845-f8cd-1ae395cb8fb0" class="slider-right-arrow w-slider-arrow-right">
                        <div class="right-arrow-icon w-embed">
                            <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.66675 10H17.6667" stroke="#070707" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12.3333 2L20.3333 10L12.3333 18" stroke="#070707" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="slider-nav w-slider-nav w-round w-num"></div>
                </div>
            </div>
        </div>
    </div>
</section>
