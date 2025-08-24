@extends('layouts.app')

@section('content')
    <div class="sm:grid sm:grid-cols-3 sm:gap-10">
        <aside class="mt-4">
            {{-- ユーザー情報 --}}
            @include('users.card')
        </aside>
        <div class="sm:col-span-2 mt-4">
            {{-- 投稿フォーム --}}
            @include('tasklist.form')
            {{-- 投稿一覧 --}}
            @include('tasklist.tasklist')
        </div>
    </div>
@endsection