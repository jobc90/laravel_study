<html>
    <head>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="container p-5">
            <h1 class="text-2xl mb-5">글목록</h1>
            @foreach($articles as $article)
                <div class="background-white border rounded mb-3 p-3">
                    <p><{{ $article->body }}></p>
                    <p><{{ $article->created_at }}></p>
                </div>
            @endforeach

            {{-- @for($i=0; $i < $articles->count(); $i++)
            <div class="background-white border rounded mb-3 p-3">
                @if($i === 1)
                    @continue;
                @endif
                <p>{{ $i }}</p>
                <p>{{ $articles[$i]->body }}</p>
                <p>{{ $articles[$i]->created_at }}</p>
            </div>
            @endfor --}}
        </div>
    </body>
</html>