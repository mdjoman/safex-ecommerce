@extends('frontend.layouts.master')

@section('title', setting('site_name', 'SafeX Engineering') . ' - Contact Us')
@section('content')

<!-- ============================================
     ENHANCED CONTACT HERO SECTION
     ============================================ -->
<section class="about-section py-12 md:py-16 bg-gradient-to-r from-[#021447] to-[#111827] text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto bg-[#0658DC]/20 rounded-full flex items-center justify-center mb-4 sm:mb-6">
            <i class="fas fa-shield-alt text-2xl sm:text-3xl text-[#0658DC]"></i>
        </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">
                Terms &  <span class="text-[#0658DC]">Conditions</span>
            </h1>

        <p class="text-sm sm:text-base md:text-lg lg:text-xl text-white/80 max-w-3xl mx-auto leading-relaxed mb-6 sm:mb-8 px-2">
            {{ setting('terms_title', 'We are committed to providing high-quality engineering products and services to our customers. With years of experience in the industry, we ensure reliability and excellence in everything we do.') }}
        </p>
    </div>
</section>
<section class=" md:pb-16 bg-[#F4F7FA]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        {!! setting('terms_details', 'Terms & Conditions Details') !!}
    </div>
</section>
@endsection
