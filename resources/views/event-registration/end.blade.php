<x-layout.event-registration-form class="event-registration multi-step-form" :$event :$step :$completeSteps :$routes>
    <p>Here is your QR Code.</p>
    <p class="qr-code"><img src={{ $qrCode }}></p>
</x-layout>
