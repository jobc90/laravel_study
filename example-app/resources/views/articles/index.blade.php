<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('글목록') }}
        </h2>
    </x-slot>
    <div class="container p-5">
        @foreach($articles as $article)
            <div class="background-white border rounded mb-3 p-3">
                <p>{{ $article->body }}</p>
                {{-- <p>{{ $article->created_at->format('Y년 m월 d일 H시') }}</p> --}}
                <p>{{ $article->user->name }}</p>
                <p><a href="{{ route('articles.show', ['article'=>$article->id]) }}">{{ $article->created_at->diffForHumans() }}</a></p>
                <div class="flex flex-row mt-2">
                    @can('update', $article)
                    <p class="mr-1">
                        <a 
                        href="{{ route('articles.edit', ['article'=>$article->id]) }}"
                        class="button rounded bg-blue-300 px-2 py-1 text-xs text-white"
                        >
                        수정
                        </a>
                    </p>
                    @endcan
                    @can('delete', $article)
                    <form action="{{ route('articles.destroy', ['article'=>$article->id])}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="py-1 px-2 bg-red-400 text-white rounded text-xs">삭제</button>
                    </form>
                    @endcan
                </div>
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
    {{-- laravel pagination으로 페이지 나누기도 자동으로 가능 --}}
    <div class="container p-5">
        {{ $articles->links() }}
    </div>
    {{-- <ul>
        @for($i=0; $i < $totalCount/$perPage; $i++)
        <li><a href="/articles?page={{$i+1}}&per_page={{$perPage}}">{{$i+1}}</li>
        @endfor
    </ul> --}}

    {{-- <div class="container p-5">
        <h1 class="text-2xl mb-5">글목록</h1>
        @foreach($results as $result)
            <div class="background-white border rounded mb-3 p-3">
                <p>{{ $result->body }}</p>
                {{ dd($result) }}
            </div>
        @endforeach
    </div> --}}
</x-app-layout>