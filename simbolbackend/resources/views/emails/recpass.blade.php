@component('mail::message')
# Recuperaci�n de Contrase�a

Simbol informa:
Por favor haga clic bot�n para avanzar con la recuperaci�n de contrase�a:


@component('mail::button', ['url' => 'http://52.170.252.66/simbol/recPass/recpass'])
Recuperar Contrase�a
@endcomponent

Muchas Gracias,<br>
{{ config('app.name') }}
@endcomponent
