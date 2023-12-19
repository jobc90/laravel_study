<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{$article->id}}번 게시물
        </h2>
    </x-slot>
    <div class="container p-5 mx-auto">
        <div class="border rounded p-4">
            <p>{{ $article->body }}</p>
            <p>{{ $article->user->name }}</p>
            <p><a href="{{ route('articles.show', ['article'=>$article->id]) }}">{{ $article->created_at->diffForHumans() }}</a></p>

            <x-article-button-group :article=$article />
        </div>
        {{-- 댓글 영역 시작 --}}
        <div class="mt-3">
            {{-- 댓글 작성 폼 시작 --}}
            <form action="{{ route('comments.store' )}}" method="POST" class="flex">
                @csrf
                <input type="hidden" name="article_id" value="{{ $article->id }}" />
                <x-text-input name="body" class="mr-2"/>
                @error('body')
                    <x-input-error :messages=$messages />
                @enderror
                <x-primary-button class="inline-block rounded-full border-2 border-primary px-6 pb-[6px] pt-2 bg-white text-xs text-blue-600 border-blue-600 font-large uppercase leading-normal text-primary transition duration-150 ease-in-out hover:border-primary-600 hover:bg-neutral-500 hover:bg-opacity-10 hover:text-primary-600 focus:border-primary-600 focus:text-primary-600 focus:outline-none focus:ring-0 active:border-primary-700 active:text-primary-700 dark:hover:bg-neutral-100 dark:hover:bg-opacity-10 ">댓글 쓰기</x-primary-button>
            </form>
            {{-- 댓글 작성 폼 끝 --}}
            {{-- 댓글 목록 시작 --}}
            <div class="mt-5 space-y-3">
                @foreach ($article->comments as $comment)
                    <div>
                        <p class="text-xl">
                            {{ $comment->body }}
                        </p>
                        <p class="text-sm text-gray-500" >{{ $comment->user->name }} {{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
            {{-- 댓글 목록 끝 --}}
        </div>
        {{-- 댓글 영역 끝 --}}
    </div>
</x-app-layout>