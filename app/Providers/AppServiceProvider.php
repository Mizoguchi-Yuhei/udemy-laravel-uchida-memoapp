<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Memo;
use App\Models\Tag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // すべてのメソッドが呼ばれる前に先に呼び出されるメソッド
        view()->composer('*', function ($view) {
            // Memoモデルのメソッドを呼び出し
            // インスタンス化が必要
            $memo_model = new Memo();
            // メモ取得
            $memos = $memo_model->getMyMemo();

            // タグ一覧
            $tags = Tag::where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->get();

            $view->with('memos', $memos)->with('tags', $tags);
        });
    }
}
