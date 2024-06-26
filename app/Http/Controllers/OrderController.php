<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $result = Order::with(['orderItems'])->where('user_id', auth()->id())->paginate(10);

        return Inertia::render('EC/Order', [
            "pagedata" => $result,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_id = auth()->user()->id;
        $result = OrderItem::with(['product.image'])->where('order_id', $id)->where('user_id', $user_id)->get();

        return Inertia::render('EC/OrderDetail', [
            "data" => $result,
        ]);
    }
}
