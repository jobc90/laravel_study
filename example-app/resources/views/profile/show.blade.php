<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h1 class="text-center text-5xl ">{{ $user->name }}</h1>
            <div class="flex flex-row place-content-center">
                <div class="text-center text-2xl mr-5">
                    게시물 {{ $user->articles->count() }}
                    구독자 {{ $user->followers->count() }}
                </div>
            @if(Auth::id() != $user->id)
                <div>
                    @if (Auth::user()->isFollowing($user))
                        <form method="POST" action="{{ route('unfollow', ['user' => $user->username]) }}">
                            @csrf
                            @method('delete')
                            <x-danger-button>구독취소</x-danger-button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('follow', ['user' => $user->username]) }}">
                            @csrf
                            <x-primary-button>구독하기</x-primary-button>
                        </form>
                    @endif
                </div>
            @endif
            </div>
            <div>
                @forelse ($user->articles as $article)
                    <x-list-article-item :article=$article />
                @empty
                    <p>게시물이 없습니다.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
