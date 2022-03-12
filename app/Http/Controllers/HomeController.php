<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // この時点でメモのデータを取得
        // $memos = Memo::select('*')
        $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->get();

            // dd($memos);

        return view('create', compact('memos'));
    }

    /**
     * メモ一覧
     */
    public function store(Request $request)  // postの場合 Requestファサードの インスタンス化
    {
        $posts = $request->all();
        // dd($posts);  // dd : dump die の略 メソッドの引数にとった値を展開して止める (データの確認)
        // dd(\Auth::id(), $posts);
        Memo::insert(['content' => $posts['content'], 'user_id' => \Auth::id()]);

        return redirect(route('home'));
    }


    public function edit($id)
    {
        // $memos = Memo::select('*')
        $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->get();

            $edit_memo = Memo::find($id);  // findで主キー

        return view('edit', compact('memos', 'edit_memo'));
    }


    /**
     * メモ更新
     */
    public function update(Request $request)  // postの場合 Requestファサードの インスタンス化
    {
        $posts = $request->all();
        // dd($posts);
        Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content']]);

        return redirect(route('home'));
    }


        /**
     * メモ削除
     */
    public function destroy(Request $request)  // postの場合 Requestファサードの インスタンス化
    {
        $posts = $request->all();
        // dd($posts);
        Memo::where('id', $posts['memo_id'])
            // ->delete();  // これだと物理削除
            ->update(['deleted_at' => date("Y-m-d H:i:s", time())]);

        return redirect(route('home'));
    }


}
