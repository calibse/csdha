@use ('App/Services/Format')
This is an automated notification.

@switch ($step)
@case ('officers'):
    @switch ($status)
    @case ('returned')
The accomplishment report for "{{ $eventName }}" was returned by the President on
{{ Format::toPh($accomReport->returned_at)->format(config('app.date_format')) }} Asia/Manila.
        @break
    @endswitch
    @break
@case ('president'):
    @switch ($status)
    @case ('pending')
The accomplishment report for "{{ $eventName }}" was submitted by the officers on
{{ Format::toPh($accomReport->submitted_at)->format(config('app.date_format')) }} Asia/Manila.
        @break
    @endswitch
    @break
@case ('adviser'):
    @switch ($status)
    @case ('approved')
The accomplishment report for "{{ $eventName }}" was approved by the President on
{{ Format::toPh($accomReport->approved_at)->format(config('app.date_format')) }} Asia/Manila.
        @break
    @endswitch
    @break
@endswitch

Link to accomplishment report: {{ $url }}

Please do not reply to this message.

--
This message was sent automatically by CSDHA ({{ url() }}).
{{--
For assistance, visit [https://yourwebsite.com/support] or contact
[support@yourwebsite.com].
--}}
