@use ('App/Services/Format')
This is an automated notification.

@switch ($step)
@case ('officers'):
    @switch ($status)
    @case ('returned')
The GPOA activity "{{ $name }}" was returned by the President on
{{ Format::toPh($activity->president_returned_at)->format(config('app.date_format')) }} Asia/Manila.
        @break
    @endswitch
    @break
@case ('president'):
    @switch ($status)
    @case ('pending')
The GPOA activity "{{ $name }}" was submitted by the officers on
{{ Format::toPh($activity->officers_submitted_at)->format(config('app.date_format')) }} Asia/Manila.
        @break
    @case ('returned')
The GPOA activity "{{ $name }}" was returned by the Adviser on
{{ Format::toPh($activity->adviser_returned_at)->format(config('app.date_format')) }} Asia/Manila.
        @break
    @case ('rejected')
The GPOA activity "{{ $name }}" was rejected by the President on
{{ Format::toPh($activity->president_rejected_at)->format(config('app.date_format')) }} Asia/Manila.
        @break
    @endswitch
    @break
@case ('adviser'):
    @switch ($status)
    @case ('pending')
The GPOA activity "{{ $name }}" was submitted by the President on
{{ Format::toPh($activity->president_submitted_at)->format(config('app.date_format')) }} Asia/Manila.
        @break
    @case ('rejected')
The GPOA activity "{{ $name }}" was rejected by the Adviser on
{{ Format::toPh($activity->adviser_rejected_at)->format(config('app.date_format')) }} Asia/Manila.
        @break
    @case ('approved')
The GPOA activity "{{ $name }}" was approved by the Adviser on
{{ Format::toPh($activity->adviser_approved_at)->format(config('app.date_format')) }} Asia/Manila.
        @break
    @endswitch
    @break
@endswitch

Link to GPOA Activity: {{ $url }}

Please do not reply to this message.

--
This message was sent automatically by CSDHA ({{ url() }}).
{{--
For assistance, visit [https://yourwebsite.com/support] or contact
[support@yourwebsite.com].
--}}
