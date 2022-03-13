@extends('layouts.app')

@section('javascript')
    <script src="/js/confirm.js"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            メモ編集
            <form action="{{ route('destroy') }}" method="POST" id="delete-form">
                @csrf
                <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
                <a href="" style="color: inherit;"><i class="fas fa-trash mr-3" onclick="deleteHandle(event);"></i></a>
            </form>
        </div>
        <form class="card-body my-card-body" action="{{ route('update') }}" method="POST">
            {{-- route('store') で /storeに --}}
            @csrf
            {{-- メモIDを取得 --}}
            <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />

            {{-- メモ表示欄 --}}
            <div class="form-group">
                <textarea class="form-control" name="content" rows="3"
                    placeholder="ここにメモを入力">{{ $edit_memo[0]['content'] }}</textarea>
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
                    {{-- 三項演算子 if分を1行で {{ 条件 ? trueだったら : falseだったら }} --}}
                    <input type="checkbox" class="form-check-input" name="tags[]" id="{{ $tag['id'] }}"
                        value="{{ $tag['id'] }}" {{ in_array($tag['id'], $include_tags) ? 'checked' : '' }}>
                    {{-- もし$include_tagsにループ(foreach)で回っているタグのidが含まれれば、checkedを書く --}}
                    <label for="{{ $tag['id'] }}" class="form-check-label">{{ $tag['name'] }}</label>
                </div>
            @endforeach
            {{-- 新規タグ入力欄 --}}
            <input type="text" class="form-control w-50 mt-3" name="new_tag" placeholder="新しいタグを入力" />

            <button type="submit" class="btn btn-primary my-3">更新</button>
        </form>
    </div>
@endsection
