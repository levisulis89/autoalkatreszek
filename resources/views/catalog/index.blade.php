<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Autóalkatrész Katalógus</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; }
        ul { list-style: none; padding-left: 1rem; }
        li { margin: .25rem 0; }
        a { text-decoration: none; color: #ff8c00; }
    </style>
</head>
<body>

<h1>Katalógus</h1>

<ul>
@foreach ($categories as $brand)
    <li>
        <strong>{{ $brand->name }}</strong>
        <ul>
            @foreach ($brand->children as $model)
                <li>
                    {{ $model->name }}
                    <ul>
                        @foreach ($model->children as $gen)
                            <li>
                                <a href="{{ route('catalog.category', $gen) }}">
                                    {{ $gen->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </li>
@endforeach
</ul>

</body>
</html>
