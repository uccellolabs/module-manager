@if ($value)
    @php($date = new \Carbon\Carbon($value))
    {{ $date->format('d/m/Y H:i:s') }}
@endif
