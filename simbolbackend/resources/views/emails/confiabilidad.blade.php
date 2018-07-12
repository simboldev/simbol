@component('mail::message')
# Notificación de Confiabilidad

Simbol informa:
El usuario XXX solicita que confirmes la confiabilidad del amigo en común XXX

@component('mail::button', ['url' => 'http://52.170.252.66/simbol/'])
Ir al enlace
@endcomponent

Muchas Gracias,<br>
{{ config('app.name') }}
@endcomponent
