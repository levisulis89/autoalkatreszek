<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $category->name }}</title>
</head>
<body>

<a href="/">← vissza</a>

<h1>{{ $category->name }}</h1>

<h2>Járművek (demo)</h2>
<ul>
@foreach ($vehicles as $v)
    <li>{{ $v->make }} {{ $v->model }} {{ $v->engine }}</li>
@endforeach
</ul>

</body>
</html>
