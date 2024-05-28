<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
        $favorite = Favorite::with('product.category')->get();

        return Inertia::render('EC/FavoriteIndex', [
            'data' => $favorite,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request){
            $request->validate([
                'product_id' => 'required|integer',
            ]);

            Favorite::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->product_id,
            ]);
        });

    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite, $id): RedirectResponse
    {
        DB::transaction(function () use ($id){
            $user_id = auth()->user()->id;
            $favorite = Favorite::where('user_id', $user_id)->find($id);
            $favorite->delete();
        });

        return redirect()->route('favorite.index')->with('success', 'お気に入りから削除しました');
    }
}
