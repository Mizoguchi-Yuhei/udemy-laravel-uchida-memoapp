<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
use Illuminate\Support\Facades\DB;

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
        // タグ一覧
        $tags = Tag::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();
        // dd($tags);

        return view('create', compact('tags'));
    }

    /**
     * メモ一覧
     */
    public function store(Request $request)  // postの場合 Requestファサードの インスタンス化
    {
        $posts = $request->all();
        // バリデーション
        $request->validate([
            'content' => 'required',
        ]);
        // dd($posts);  // dd : dump die の略 メソッドの引数にとった値を展開して止める (データの確認)

        // ここからトランザクション開始
        DB::transaction(function () use ($posts) {
            // メモIDをインサートして取得
            $memo_id = Memo::insertGetId(['content' => $posts['content'], 'user_id' => \Auth::id()]);

            $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])
                ->exists();
            // 新規タグが入力されているかチェック
            // 新規タグが既にtagsテーブルに存在するかチェック
            if ((!empty($posts['new_tag']) || $posts['new_tag'] === "0") && !$tag_exists) {
                // 新規タグが既に存在しなければ、tagsテーブルにインサートしてそのIDを取得
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                // memo_tagsにインサートしてメモとタグを紐づける
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }

            // 既存タグが紐づけられた場合 memo_tagsにインサート
            if (!empty($posts['tags'][0])) {
                foreach ($posts['tags'] as $tag) {
                    MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag]);
                }
            }
        });
        // ここまででトランザクション終了

        return redirect(route('home'));
    }


    public function edit($id)
    {
        $edit_memo = Memo::select('memos.*', 'tags.id AS tag_id')
            ->leftjoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
            ->leftjoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
            ->where('memos.user_id', '=', \Auth::id())
            ->where('memos.id', '=', $id)
            ->whereNull('memos.deleted_at')
            ->get();

        // 取得したメモのタグを取得
        $include_tags = [];
        foreach ($edit_memo as $e_memo) {
            array_push($include_tags, $e_memo['tag_id']);
        }

        return view('edit', compact('edit_memo', 'include_tags'));
    }


    /**
     * メモ更新
     */
    public function update(Request $request)  // postの場合 Requestファサードの インスタンス化
    {
        $posts = $request->all();
        // バリデーション
        $request->validate([
            'content' => 'required',
        ]);

        // トランザクション開始
        DB::transaction(function () use ($posts) {
            Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content']]);

            // 一旦メモとタグの紐づけを削除
            MemoTag::where('memo_id', '=', $posts['memo_id'])->delete();
            // 再度メモとタグの紐づけ
            foreach ($posts['tags'] as $tag) {
                MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag]);
            }
            // もし新しいタグの入力があればインサートして紐づけ
            $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])
                ->exists();
            // 新規タグが入力されているかチェック
            // 新規タグが既にtagsテーブルに存在するかチェック
            if ((!empty($posts['new_tag']) || $posts['new_tag'] === "0") && !$tag_exists) {
                // 新規タグが既に存在しなければ、tagsテーブルにインサートしてそのIDを取得
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                // memo_tagsにインサートしてメモとタグを紐づける
                MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag_id]);
            }
        });
        // トランザクション終了

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
