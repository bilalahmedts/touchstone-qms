@component('mail::message')
# Hello {{$notifiable->name}}

This is to notify you that evaluation of this agent has been submitted by the QA team. Here are the details:

Record ID: {{ $audit->record_id }}
Agent Name: {{ $audit->associate->name }}
Phone: {{ $audit->phone }}
Customer Name: {{ $audit->customer_name }}
Alert Type: @if($audit->review_priority == 1) Critical @else Normal @endif

Evaluation Notes: {{ $audit->notes }}

You can view the details by visiting QMS application.

@component('mail::button', ['url' => route('voice-evaluation-reviews.show', $audit) . '?accesskey=' . $notifiable->access_key])
Touchstone QMS
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
