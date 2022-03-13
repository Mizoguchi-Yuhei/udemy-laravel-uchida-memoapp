<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Memo;

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
            // メモ一覧
            $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->get();

            $view->with('memos', $memos);
        });
    }
}
