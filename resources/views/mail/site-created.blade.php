<x-mail::message>
# Site created

Your site "{{ $site->name }}" has been created successfully.

**ID:** `{{ $site->id }}`  
**Authentication Key:** `{{ $site->auth_key }}`

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
