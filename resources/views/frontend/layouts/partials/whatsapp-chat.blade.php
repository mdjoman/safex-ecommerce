@php
    $whatsappNumber = setting('whatsapp_number', '+8801712345678');
    $whatsappNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
    $whatsappUrl = 'https://wa.me/' . $whatsappNumber;
    $message = urlencode('Hello! I would like to know more about your products.');
@endphp

<a href="{{ $whatsappUrl }}?text={{ $message }}"
   target="_blank"
   rel="noopener noreferrer"
   class="whatsapp-float"
   aria-label="Chat on WhatsApp">
    <i class="fab fa-whatsapp text-3xl"></i>
</a>

<style>
    .whatsapp-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 999;
        background-color: #25d366;
        color: white;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
        transition: all 0.3s ease;
        animation: whatsappPulse 2s infinite;
    }

    .whatsapp-float:hover {
        transform: scale(1.1);
        color: white;
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
        background-color: #20b85f;
    }

    @keyframes whatsappPulse {
        0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(37, 211, 102, 0); }
        100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
    }

    /* Responsive */
    @media (max-width: 640px) {
        .whatsapp-float {
            width: 50px;
            height: 50px;
            bottom: 20px;
            right: 20px;
        }
        .whatsapp-float i {
            font-size: 24px;
        }
    }
</style>

<!-- WhatsApp Chat Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Track WhatsApp click
        const whatsappBtn = document.querySelector('.whatsapp-float');
        if (whatsappBtn) {
            whatsappBtn.addEventListener('click', function() {
                // You can add analytics tracking here
                console.log('WhatsApp chat initiated');

                // If you want to track with Google Analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'conversion', {
                        'send_to': 'AW-XXXXXXXXX/XXXXXXXXX',
                    });
                }
            });
        }
    });
</script>
