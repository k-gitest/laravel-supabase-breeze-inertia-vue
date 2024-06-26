<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Log;

class AdminCategoryController extends Controller
{  
    /**
     * 一覧画面表示
     */
    public function index(): Response
    {
      $category = Category::all();

      return inertia::render('EC/Admin/CategoryIndex', [
        "data" => $category,
      ]);
    }

    /**
     * 登録フォーム画面表示
     */
    public function create(): Response
    {
      $categoryData = Category::all();
      return Inertia::render('EC/Admin/CategoryRegister', [
          "data" => $categoryData,
      ]);
    }

    /**
     * 登録処理->DB
     */
    public function store(AdminCategoryRequest $request): RedirectResponse
    {
      try{
        DB::transaction(function () use ($request) {
          Category::create($request->validated());
        });
        Log::info('Category create succeeded');
      }
      catch (\Exception $e){
        report($e);
        return false;
      }

      return redirect()->route('admin.category.index');
    }

    /**
     * 編集フォーム画面表示
     */
    public function edit(Request $request): Response
    {
      $request->validate([
        'id' => 'required|string|exists:categories,id',
      ]);
      
      $category = Category::findOrFail($request->id);

      return inertia::render('EC/Admin/CategoryEdit', [
        "data" => $category,
      ]);
    }

    /**
     * 編集処理->DB
     */
    public function update(AdminCategoryRequest $request): RedirectResponse|bool
    {
      try{
        $category = DB::transaction(function () use ($request) {
          $category = Category::lockForUpdate()->findOrFail($request->id);
          $category->fill($request->validated());
          
          if ($category->isDirty()) {
              $category->save();
          }
          return $category;
        });
        Log::info('Category update succeeded');
      }
      catch(\Exception $e){
        report($e);
        return false;
      }

      return redirect()->route('admin.category.edit', $category)->with('success', '更新しました');
    }

    /**
     * 削除処理->DB
     */
    public function destroy(Request $request): RedirectResponse|bool
    {
      $request->validate([
        'id' => 'required|string|exists:categories,id',
      ]);
      
      try{
        DB::transaction(function () use ($request) {
          $category = Category::lockForUpdate()->findOrFail($request->id);
          $category->delete();
        });
        Log::info('Category delete succeeded');
      }
      catch(\Exception $e){
        report($e);
        return false;
      }

      return redirect()->route('admin.category.index')->with('success', '削除しました');
    }
}
