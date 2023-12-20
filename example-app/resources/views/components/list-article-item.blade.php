<div class="background-white border rounded mb-3 p-3">
    <p>{{ $article->body }}</p>
    {{-- <p>{{ $article->created_at->format('Y년 m월 d일 H시') }}</p> --}}
    <p>
        <a href="{{ route('profile', ['user' => $article->user->username]) }}">
            {{ $article->user->name }}
        </a>
    </p>
    <p class="text-sm text-gray-500">
        <a href="{{ route('articles.show', ['article'=>$article->id]) }}">{{ $article->created_at->diffForHumans() }}
            <span class="ml-2">   
                댓글 {{ $article->comments_count}} 
                @if ($article->recent_comments_exists) (new!)@endif
            </span>
        </a>
    </p>
    
    <x-article-button-group :article=$article />
</div>