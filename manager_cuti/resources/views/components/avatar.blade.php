@props([
    'user',
    'size' => 'w-10 h-10',
])

@if($user->avatar)
    <img src="{{ Storage::url($user->avatar) }}?v={{ time() }}" {{ $attributes->merge(['class' => $size . ' rounded-full object-cover']) }} alt="{{ $user->name }}">
@else
    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=0ea5e9&color=fff" {{ $attributes->merge(['class' => $size . ' rounded-full']) }} alt="{{ $user->name }}">
@endif