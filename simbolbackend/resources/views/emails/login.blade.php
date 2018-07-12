@component('mail::message')
# Notificación de acceso

Simbol informa:
Que se ha generado el siguiente verification token: para acceder a la aplicación.

Saludos Cordiales

@component('mail::button', ['url' => 'http://52.170.252.66/simbol/'])
Acceder
@endcomponent

Muchas Gracias,<br>
{{ config('app.name') }}
@endcomponent
