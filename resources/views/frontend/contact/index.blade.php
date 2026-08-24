@extends('frontend.layouts.master')

@section('title', setting('site_name', 'SafeX Engineering') . ' - Contact Us')

@push('styles')
    <link rel="stylesheet" href="{{asset('/frontend/contact.css')}}">
@endpush

@section('content')

<!-- ============================================
     CONTACT HERO SECTION
     ============================================ -->
<section class="contact-hero">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-block bg-white/10 backdrop-blur-sm rounded-full px-4 py-1.5 mb-4">
                <span class="text-white text-sm font-semibold">
                    <i class="fas fa-headset mr-2"></i> Get in Touch
                </span>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">
                Contact <span class="text-[#0658DC]">Us</span>
            </h1>
            <p class="text-white/80 text-base sm:text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                Have questions about our products or services? We're here to help! Reach out to us through any of the channels below.
            </p>
        </div>
    </div>
</section>

<!-- ============================================
     CONTACT INFO CARDS
     ============================================ -->
<section class="py-12 -mt-8 relative z-10">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Address -->
            <div class="contact-card">
                <div class="icon-wrapper">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>Visit Our Office</h4>
                <p>
                    {{ $contact->address ?? setting('address', 'House #12, Road #5, Sector #3, Uttara, Dhaka-1230, Bangladesh') }}
                </p>
            </div>

            <!-- Phone -->
            <div class="contact-card">
                <div class="icon-wrapper">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h4>Call Us</h4>
                <p>
                    <a href="tel:{{ $contact->phone ?? setting('phone', '+8801712345678') }}">
                        {{ $contact->phone ?? setting('phone', '+880 1712-345678') }}
                    </a>
                    @if($contact->phone_alt ?? false)
                        <br>
                        <a href="tel:{{ $contact->phone_alt }}" style="font-size: 13px;">
                            {{ $contact->phone_alt }}
                        </a>
                    @endif
                    <br>
                    <small style="color: #9CA3AF; font-size: 12px;">{{ $contact->respond_time }} </small>
                </p>
            </div>

            <!-- Email -->
            <div class="contact-card">
                <div class="icon-wrapper">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4>Email Us</h4>
                <p>
                    <a href="mailto:{{ $contact->email ?? setting('email', 'info@safeengineering.com') }}">
                        {{ $contact->email ?? setting('email', 'info@safeengineering.com') }}
                    </a>
                    @if($contact->email_alt ?? false)
                        <br>
                        <a href="mailto:{{ $contact->email_alt }}" style="font-size: 13px;">
                            {{ $contact->email_alt }}
                        </a>
                    @endif
                    <br>
                    <small style="color: #9CA3AF; font-size: 12px;">We respond within 24hrs</small>
                </p>
            </div>

            <!-- Social -->
            <div class="contact-card">
                <div class="icon-wrapper">
                    <i class="fas fa-share-alt"></i>
                </div>
                <h4>Connect With Us</h4>
                <div class="social-icons">
                    @if($contact->facebook_url ?? false)
                        <a href="{{ setting('facebook_page', '#') }}" target="_blank" class="facebook" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if($contact->twitter_url ?? false)
                        <a href="{{ setting('twitter_handle', '#') }}" target="_blank" class="twitter" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if($contact->linkedin_url ?? false)
                        <a href="{{ setting('linkedin_page', '#') }}" target="_blank" class="linkedin" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif
                    @if($contact->instagram_url ?? false)
                        <a href="{{ setting('instagram_page', '#') }}" target="_blank" class="instagram" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if($contact->youtube_url ?? false)
                        <a href="{{ setting('youtube_channel', '#') }}" target="_blank" class="youtube" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif
                    @if( setting('whatsapp_number', '0123456789') ?? false)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', setting('whatsapp_number', '0123456789')) }}"
                           target="_blank"
                           class="whatsapp"
                           title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     CONTACT FORM & MAP SECTION
     ============================================ -->
<section class="pb-12 md:pb-16 bg-[#F4F7FA]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
            <!-- Contact Form -->
            <div class="contact-form-wrapper">
                <div style="margin-bottom: 24px;">
                    <h3 class="text-2xl font-bold text-[#021447] mb-2">
                        Send Us a <span style="color: #0637A1;">Message</span>
                    </h3>
                    <p class="text-[#6B7280] text-sm">
                        Fill in the form below and we'll get back to you as soon as possible.
                    </p>
                </div>

                @if(session('success'))
                    <div class="alert-success-custom">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Success!</strong> {{ session('success') }}
                        </div>
                        <button type="button" class="close" onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-danger-custom">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>Error!</strong> {{ session('error') }}
                        </div>
                        <button type="button" class="close" onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="form-label">
                                Full Name <span style="color: #CC2717;">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   placeholder="John Doe"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="form-label">
                                Email Address <span style="color: #CC2717;">*</span>
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   placeholder="john@example.com"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel"
                               class="form-control @error('phone') is-invalid @enderror"
                               id="phone"
                               name="phone"
                               placeholder="+880 1712-345678"
                               value="{{ old('phone') }}">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <label for="message" class="form-label">
                            Your Message <span style="color: #CC2717;">*</span>
                        </label>
                        <textarea class="form-control @error('message') is-invalid @enderror"
                                  id="message"
                                  name="message"
                                  placeholder="Write your message here..."
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit mt-4">
                        <i class="fas fa-paper-plane"></i>
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Map & Working Hours -->
            <div>
                <div class="map-section">
                    @php
                        // Check if we have a map URL from the contact model
                        $mapUrl = $contact->google_map_embed_url ?? setting('google_map_embed_url', '');

                        // If the map URL is empty, use a default embed URL
                        if (empty($mapUrl)) {
                            // Default map URL (you can change this to your location)
                            $mapUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.438622596459!2d90.3925459!3d23.7508127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8b3f2a6d3b7%3A0x8b1f8b9d9f8b9d9f!2sDhaka!5e0!3m2!1sen!2sbd!4v1700000000000';
                        }

                        // Check if the URL is already a full embed URL or just the src
                        if (strpos($mapUrl, '<iframe') !== false) {
                            // If it's a full iframe embed code, extract the src
                            preg_match('/src="([^"]+)"/', $mapUrl, $matches);
                            if (!empty($matches[1])) {
                                $mapSrc = $matches[1];
                            } else {
                                $mapSrc = $mapUrl;
                            }
                        } else {
                            $mapSrc = $mapUrl;
                        }
                    @endphp

                    @if(!empty($mapSrc))
                        <iframe
                            src="{{ $mapSrc }}"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Our Location on Google Maps"
                        ></iframe>
                    @else
                        <div class="map-placeholder">
                            <i class="fas fa-map-marked-alt"></i>
                            <p>Map Location Not Set</p>
                            <small>Please add your Google Map embed URL in the settings</small>
                        </div>
                    @endif
                </div>

                <!-- Working Hours -->
                <div class="working-hours">
                    <h4>
                        <i class="fas fa-clock"></i>
                        Business Hours
                    </h4>
                    @if($contact->working_hours ?? false)
                        @php
                            $workingHours = is_string($contact->working_hours) ? json_decode($contact->working_hours, true) : $contact->working_hours;
                        @endphp
                        @if(is_array($workingHours) && count($workingHours) > 0)
                            <div class="hours-grid">
                                @foreach($workingHours as $day => $hours)
                                    <span class="day">{{ $day }}</span>
                                    <span class="{{ strtolower($hours) == 'closed' ? 'closed' : 'time' }}">{{ $hours }}</span>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     FAQ SECTION
     ============================================ -->
<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 md:mb-12">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#021447]">
                Frequently Asked <span style="color: #0637A1;">Questions</span>
            </h2>
            <p class="text-[#6B7280] text-sm md:text-base mt-4">
                Find quick answers to common queries about our products and services.
            </p>
        </div>

        <div class="max-w-3xl mx-auto">
            @php
                $faqs = [
                    [
                        'question' => 'What types of engineering products do you offer?',
                        'answer' => 'We offer a wide range of engineering products including construction materials, safety equipment, industrial tools, and precision instruments. Our product range is continuously expanding to meet industry demands.'
                    ],
                    [
                        'question' => 'Do you offer bulk discounts for large orders?',
                        'answer' => 'Yes, we offer competitive bulk discounts for wholesale orders. Please contact our sales team directly for customized pricing based on your order quantity and requirements.'
                    ],
                    [
                        'question' => 'How can I track my order?',
                        'answer' => 'Once your order is shipped, you will receive a tracking number via email. You can use this number to track your shipment on our website or through the courier service\'s tracking portal.'
                    ],
                    [
                        'question' => 'What is your return policy?',
                        'answer' => 'We offer a 30-day return policy for all unopened products in their original packaging. For defective products, we provide free replacements within 7 days of delivery. Contact our support team for assistance.'
                    ]
                ];
            @endphp

            @foreach($faqs as $index => $faq)
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <h4>{{ $faq['question'] }}</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        {{ $faq['answer'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success alert after 5 seconds
        const alert = document.querySelector('.alert-success-custom');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            }, 5000);
        }

        // Auto-hide error alert after 5 seconds
        const errorAlert = document.querySelector('.alert-danger-custom');
        if (errorAlert) {
            setTimeout(() => {
                errorAlert.style.transition = 'opacity 0.5s ease';
                errorAlert.style.opacity = '0';
                setTimeout(() => {
                    errorAlert.style.display = 'none';
                }, 500);
            }, 5000);
        }
    });

    // FAQ Toggle Function
    function toggleFaq(element) {
        // Close all other FAQs
        document.querySelectorAll('.faq-item').forEach(el => {
            if (el !== element) {
                el.classList.remove('active');
            }
        });

        // Toggle current FAQ
        element.classList.toggle('active');
    }
</script>
@endpush
