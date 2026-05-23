<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    @if(file_exists(public_path('build/.vite/manifest.json')))
        @php
            $manifest = json_decode(file_get_contents(public_path('build/.vite/manifest.json')), true);
            $entry = $manifest['resources/js/app.js'];
            $jsFile = $entry['file'] ?? null;
            $cssFiles = $entry['css'] ?? [];
        @endphp
        @foreach($cssFiles as $css)
            <link rel="stylesheet" href="/build/{{ $css }}">
        @endforeach
    @else
        <script type="module" src="http://localhost:5173/@vite/client"></script>
        <script type="module" src="http://localhost:5173/resources/js/app.js"></script>
    @endif
</head>
<body class="bg-gray-50 antialiased">
    <div id="app"></div>
    @if(file_exists(public_path('build/.vite/manifest.json')))
        <script type="module" src="/build/{{ $jsFile }}"></script>
    @endif
</body>
</html>
