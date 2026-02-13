<x-mail::message>
# Olá

Estamos a enviar-lhe este email para notificar de que o arquivo solicitado está pronto para download.

{{ $message }}

<x-mail::button :url="$link">
    Descarregar
</x-mail::button>

Obrigado,<br>
{{ config('company.name') }}
</x-mail::message>
