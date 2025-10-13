@php
    /** @var \App\Dto\Mail\ForgetPasswordMailDto $data */
@endphp

<x-mail::message>
    # Password Reset Request

    Use the OTP below to reset your password.
    This code is valid for **3 hours**.

    **Your OTP:** {{ $data->token }}


    If you didn’t request a password reset, you can safely ignore this message.


    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>