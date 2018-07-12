@component('mail::message')
# Notificación de Cancelación de Operación

Simbol informa:
Que la operaci�n ha sido cancelada.

@component('mail::button', ['url' => 'http://52.170.252.66/simbol/'])
Acceder
@endcomponent

Muchas Gracias,<br>
{{ config('app.name') }}
@endcomponent
