<x-mail::message>
# Station aangemaakt

Uw station "{{ $site->name }}" is succesvol aangemaakt.

- **ID:** `{{ $site->id }}`
- **Korte ID:** `{{ \App\Helpers\SiteHelper::getShortId($site) }}`
- **Authenticatiesleutel:** `{{ $site->auth_key }}`
</x-mail::message>
