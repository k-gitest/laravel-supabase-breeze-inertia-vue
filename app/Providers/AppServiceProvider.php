<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use App\Models\User;
use App\Models\TodoList;
use Illuminate\Support\Facades\Gate;
//use App\Services\SupabaseStorageService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      //$this->app->bind('SbStorage', SupabaseStorageService::class);

    }

    /**
     * アプリケーションの全サービスの初期起動処理
     */
    public function boot(UrlGenerator $url): void
    {
      $url->forceScheme('https');
      $this->app['request']->server->set('HTTPS','on');

      // Gateの定義　update-todo-listはポリシーネーム
      Gate::define('update-todo-list', function (User $user, TodoList $todoList){
        return $user->id === $todoList->user_id;
      });
      //クラスコールバック配列でポリシーから呼び出すことも可能
      //Gate::define('update-todo-list', [TodoList::class, 'update']);
      
    }
}
