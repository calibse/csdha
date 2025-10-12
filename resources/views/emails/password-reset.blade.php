Hi {{ $user->first_name }},

We received a request to reset your CSDHA password.
Go to the link below to create a new one:

{!! $url !!}

This link will expire in {{ $duration }}.
If you didn’t request this, you can safely ignore this email. 

Thanks,
the CSDHA Team

