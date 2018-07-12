@component('mail::message')
# Notificación de Denuncia

Simbol informa:
Estimado ha sido denunciado por el usuario XXX

@component('mail::button', ['url' => 'http://52.170.252.66/simbol/'])
Ir al enlace
@endcomponent

Muchas Gracias,<br>
{{ config('app.name') }}
@endcomponent
