@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            メモ編集
            <form action="{{ route('destroy') }}" method="POST">
                @csrf
                <input type="hidden" name="memo_id" value="{{ $edit_memo['id'] }}" />
                <button type="submit" class="card-body btn btn-danger">削除</button>
            </form>
        </div>
        <form class="card-body" action="{{ route('update') }}" method="POST">
            {{-- route('store') で /storeに --}}
            @csrf
            <input type="hidden" name="memo_id" value="{{ $edit_memo['id'] }}" />
            <div class="form-group">
                <textarea class="form-control" name="content" rows="3" placeholder="ここにメモを入力">{{ $edit_memo['content'] }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary my-2">更新</button>
        </form>
    </div>
@endsection
