@component('mail::message')
# Notificación de Seguimiento

Simbol informa:
Que su contraparte activo casilla de verificación de operación
Por favor verifique el tracking de su operación

Saludos Cordiales

@component('mail::button', ['url' => 'http://simbol.dev'])
Acceder
@endcomponent

Muchas Gracias,<br>
{{ config('app.name') }}
@endcomponent
