@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://cdn.imgbin.com/20/14/2/imgbin-education-logo-pre-school-others-1BvX5FZBJVj1UcRbWjYxcRSKg.jpg" class="logo" alt="LMS Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
