@component('mail::message')
  <p class="color_purple font_bold text-center">Notificación Nueva Postura</p>

  <p class="color_purple text-center">Simbol informa:</p>

  <p class="color_purple text-center">Se ha registrado una nueva postura.</p>

  @component('mail::button', ['url' => config('app.url_front').'/postures/'.$postura->id, 'color' => 'purple-light-simbol']) 
    Ver información de postura
  @endcomponent
@endcomponent
