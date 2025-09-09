<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\SignupInvitation;
use Throwable;

class SendSignupInvite implements ShouldQueue
{
    use Queueable;

    private SignupInvitation $signupInvite;

    public function __construct(public SignupInvitation $signupInvite)
    {
        $this->signupInvite = $signupInvite;
    }

    public function handle(): void
    {
        $signupInvite = $this->signupInvite;
        $url = url('http://' . config('custom.user_domain') . 
            (str_starts_with(config('custom.user_domain'), '127.') ? ':8000' 
                : null) . 
            route('user.invitation', [
                'invite-code' => $signupInvite->invite_code
            ], false));
        Mail::to($signupInvite->email)->send(new SignupInvitationMail($url));
        $signupInvite->email_sent = true;
        $signupInvite->save();
    }

    public function failed(?Throwable $exception): void
    {
        $signupInvite = $this->signupInvite;
        $signupInvite->email_sent = false;
        $signupInvite->save();
    }
}
