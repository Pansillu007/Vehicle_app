<x-mail::message>
# Service Reminder

**{{ $vehicle->name }}** ({{ $vehicle->number_plate }})

{{ $statusMessage }}

<x-mail::button :url="$url">
View Vehicle
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
