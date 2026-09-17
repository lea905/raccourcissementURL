<x-mail::message>
# Bonjour,

Vos liens courts suivants ont été supprimés en raison d'une inactivité de plus de {{ config('app.link_inactive_days', 30) }} jours :

<x-mail::table>
| Lien d'origine | Code court | Date de création |
| :--- | :--- | :--- |
@foreach ($links as $link)
| {{ $link->original_url }} | {{ url($link->short_code) }} | {{ $link->created_at->format('d/m/Y') }} |
@endforeach
</x-mail::table>

Merci d'utiliser notre application !

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
