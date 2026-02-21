@props(['tenant', 'title' => null, 'description' => null, 'image' => null])

@include('layouts.public', [
    'tenant' => $tenant,
    'title' => $title,
    'description' => $description,
    'image' => $image,
    'slot' => $slot,
])
