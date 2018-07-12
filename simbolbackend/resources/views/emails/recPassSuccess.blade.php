@component('mail::message')
# Notificación de Recuperación de Contraseña

Simbol informa:
Que la recuperación de contraseña se ha realizado de forma exitosa

@component('mail::button', ['url' => 'http://52.170.252.66/simbol/'])
Ir
@endcomponent

Muchas Gracias,<br>
{{ config('app.name') }}
@endcomponent
