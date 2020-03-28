@component('mail::message')
# {{ config('auth.verification.strings.subject') }}

{{ config('auth.verification.strings.heading') }}

@component('mail::button', ['url' => $url]){{ config('auth.verification.strings.action') }}@endcomponent

@component('mail::panel')
If you did not perform this action, please send us an email at <a href="mailto:{{ config('admin.mails.support.address') }}" target="_blank" title="{{ config('admin.mails.support.from') }}" aria-label="{{ config('admin.mails.support.from') }}">{{ config('admin.mails.support.address') }}</a>
@endcomponent

<p id="footer-notice">Please do not reply to this email. This address is for the system's automated processes only. If you have any questions, please contact the <a href="mail:to{{ config('admin.mails.admin.address') }}" target="_blank" title="{{ config('admin.mails.admin.from') }}" aria-label="{{ config('admin.mails.admin.address') }}">Administration</a>.</p>

Thanks,<br><strong>{{ config('mail.from.name') }}</strong>
@endcomponent
