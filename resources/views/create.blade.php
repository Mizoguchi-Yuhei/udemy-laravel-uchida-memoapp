@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            新規メモ作成
        </div>
        <form class="card-body my-card-body" action="{{ route('store') }}" method="POST">
            {{-- route('store') で /storeに --}}
            @csrf
            {{-- 新規メモ入力欄 --}}
            <div class="form-group">
                <textarea class="form-control" name="content" rows="3" placeholder="ここにメモを入力"></textarea>
            </div>

            {{-- バリデーションエラー表示 --}}
            @error('content')
                <div class="alert alert-danger p-2 m-2">
                    {{-- {{ $message }} --}}
                    メモ内容を入力してください。
                </div>
            @enderror

            {{-- タグ欄 --}}
            @foreach ($tags as $tag)
                <div class="form-check form-check-inline my-2">
                    <input type="checkbox" class="form-check-input" name="tags[]" id="{{ $tag['id'] }}"
                        value="{{ $tag['id'] }}">
                    <label for="{{ $tag['id'] }}" class="form-check-label">{{ $tag['name'] }}</label>
                </div>
            @endforeach

            {{-- 新規タグ入力欄 --}}
            <input type="text" class="form-control w-50 mt-3" name="new_tag" placeholder="新しいタグを入力" />

            <button type="submit" class="btn btn-primary my-3">保存</button>
        </form>
    </div>
@endsection
