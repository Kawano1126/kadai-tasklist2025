<div class="mt-4">
    @if (isset($tasklists))
        <ul class="list-none">
            @foreach ($tasklists as $tasklist)
                <li class="flex items-start gap-x-2 mb-4">
                    {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                    <div class="avatar">
                        <div class="w-12 rounded">
                            <img src="{{ Gravatar::get($tasklist->user->email) }}" alt="" />
                        </div>
                    </div>
                    <div>
                        <div>
                            {{-- 投稿の所有者のユーザー詳細ページへのリンク --}}
                            <a class="link link-hover text-info" href="{{ route('users.show', $tasklist->user->id) }}">{{ $tasklist->user->name }}</a>
                            <span class="text-muted text-gray-500">posted at {{ $tasklist->created_at }}</span>
                        </div>
                        <div>
                            {{-- 投稿内容 --}}
                            <p class="mb-0">{!! nl2br(e($tasklist->content)) !!}</p>
                        </div>
                        <div>
                            @if (Auth::id() == $tasklist->user_id)
                                {{-- 投稿削除ボタンのフォーム --}}
                                <form method="POST" action="{{ route('tasklist.destroy', $tasklist->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-error btn-sm normal-case"
                                        onclick="return confirm('Delete id = {{ $tasklist->id }} ?')">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
        {{ $tasklist->links() }}
    @endif
</div>