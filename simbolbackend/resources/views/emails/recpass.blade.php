@component('mail::message')
# Recuperación de Contraseña

Simbol informa:
Por favor haga clic botón para avanzar con la recuperación de contraseña:


@component('mail::button', ['url' => 'http://52.170.252.66/simbol/recPass/recpass'])
Recuperar Contraseña
@endcomponent

Muchas Gracias,<br>
{{ config('app.name') }}
@endcomponent
