@component('mail::message')
# Notificación Nueva Postura

Simbol informa:<br>

<p>Se ha registrado una nueva postura.</p>

@component('mail::button', ['url' => env('APP_URL_FRONT').'/postures/'.$postura->id]) 
Ver información de postura
@endcomponent

@endcomponent