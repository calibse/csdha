<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AccomReportPolicy
{
    public function viewAny(User $user): Response
    {
        Response::allow();
    }

    public function view(User $user, Event $event): Response
    {
        Response::allow();
    }

    public function submit(User $user, Event $event): Response
    {
        Response::allow();
    }

    public function return(User $user, Event $event): Response
    {
        Response::allow();
    }

    public function approve(User $user, Event $event): Response
    {
        Response::allow();
    }


}
