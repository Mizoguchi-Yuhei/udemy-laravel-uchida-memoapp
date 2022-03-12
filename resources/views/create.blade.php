@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        新規メモ作成
    </div>
    <form class="card-body" action="{{route('store')}}" method="POST">
        {{-- route('store') で /storeに --}}
        @csrf
        <div class="form-group">
            <textarea class="form-control" name="content" rows="3" placeholder="ここにメモを入力"></textarea>
        </div>
        <button type="submit" class="btn btn-primary my-2">保存</button>
    </form>
</div>
@endsection
