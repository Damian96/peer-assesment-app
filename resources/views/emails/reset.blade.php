@php
/**
 * @var string $url
 */
@endphp

@component('mail::message')
# Click the button below to reset your password

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

@component('mail::panel')
If you did not register, please send us an email at <a href="mailto:{{ config('admin.mails.support.address') }}" target="_blank" title="{{ config('admin.mails.admin.from') }}" aria-label="{{ config('admin.mails.support.from') }}">{{ config('admin.mails.support.address') }}</a>
@endcomponent

@component('vendor.mail.html.footer-notice')@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
