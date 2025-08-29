<x-layout.multi-step-form class="event-registration" :$eventName :$formTitle title="Registration Successful" :$end>
    <p>Here is your QR Code.</p>
    <p class="qr-code"><img src={{ $qrCodeRoute }}></p>
</x-layout.multi-step-form>
