@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset('img/logo_simbol_rgb.png') }}" alt="{{ config('app.name') }} Logo" width="300">
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Si tienes alguna duda o quieres conocer más sobre nuestra idea comunícate con nosotros a: {{ config('app.contact_mail') }}
        @endcomponent
    @endslot
@endcomponent
