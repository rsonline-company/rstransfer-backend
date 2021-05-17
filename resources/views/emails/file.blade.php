@component('mail::message')
# Link to pobrania pliku

<a href="">link</a>

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
