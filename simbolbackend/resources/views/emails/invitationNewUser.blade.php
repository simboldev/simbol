<div>
@component('mail::message')
  <div class="color_purple font_bold text-center">
    <h2 class="color_purple font_bold text-center">¡Hola {{ $user->nombres }} {{$user->apellidos }}!</h2>
  </div>
  <div class="color_purple text-center">
    <div>
      <p class="color_purple text-center">
        Te invitamos a conocer nuestra plataforma Simbol, para que formes parte de en un selecto club de finanzas, que te permitirá agilizar todas tus actividades de cambio de divisas con personas de confianza.
      </p>
      <p class="color_purple text-center">
        Para iniciar sesión por primera vez deberás ingresar con el usuario y contraseña proporcionados, posteriormente Simbol te pedirá cambiar tu clave por una de tu preferencia.
      </p>
    </div>
    <div>
      <p class="color_purple text-center">Ingresa con los siguientes datos:</p>
      <p class="color_purple text-center">Usuario: {{ $user->username }}</p>
      <p class="color_purple text-center">Contraseña: {{ $user->password }}`</p>
    </div>
  </div>
  <div>
    @component('mail::button', ['url' => 'https://simbol.club/#!/', 'color' => 'purple-light-simbol'])
    Ingresar
    @endcomponent
  </div>
@endcomponent
</div>