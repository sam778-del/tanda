<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Tanda')
<img src="{{ asset('logo/s-logo.png') }}" class="logo" alt="Tanda">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
