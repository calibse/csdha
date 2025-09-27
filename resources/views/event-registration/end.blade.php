<x-layout.event-registration-form class="event-registration" :$eventName :$step :$completeSteps :$routes>
    <p>Here is your QR Code.</p>
    <p class="qr-code"><img src={{ $qrCodeRoute }}></p>
</x-layout>
