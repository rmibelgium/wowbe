<x-mail::message>
# Station créée

Votre station "{{ $site->name }}" a été créée avec succès.

- **ID:** `{{ $site->id }}`
- **ID court:** `{{ \App\Helpers\SiteHelper::getShortId($site) }}`
- **Clé d'authentification:** `{{ $site->auth_key }}`
</x-mail::message>
