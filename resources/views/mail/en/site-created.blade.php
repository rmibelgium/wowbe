<x-mail::message>
# Site created

Your site "{{ $site->name }}" has been created successfully.

- **ID:** `{{ $site->id }}`
- **Short ID:** `{{ \App\Helpers\SiteHelper::getShortId($site) }}`
- **Authentication Key:** `{{ $site->auth_key }}`
</x-mail::message>
