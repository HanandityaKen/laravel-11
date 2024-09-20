<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function role() 
    {
        // $user = auth()->guard('api')->user();
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 401);
        }

        $role = $user->role;

        return response()->json([
            'success' => true,
            'role' => $role
        ], 200);

    }

    public function getProductsData()
    {
        // kalo milih2
        // $products = Product::select('id', 'title', 'description', 'price', 'stock', 'file')->get();

        //kalo butuh semua data tanpa milih
        $products = Product::all();

        $role = Auth::guard('api')->user()->role;

        return DataTables::of($products)
            ->addColumn('image', function($row) {
                return '<img src="' . asset('storage/products/' . $row->images) . '" style="width: 100px; height: auto;">';
            })
            ->addColumn('description', function($row) {
                return $row->description; // Pastikan HTML dirender dengan benar
            })
            ->addColumn('actions', function($row) use($role) {
                if ($role === 'admin') {
                    return '<a href="' . route('products.show', $row->id) . '" class="btn btn-sm btn-dark">Show</a>
                    <a href="' . route('products.edit', $row->id) . '" class="btn btn-warning btn-sm">Edit</a>
                    <form action="' . route('products.destroy', $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>';
                }

                return '<a href="' . route('products.show', $row->id) . '" class="btn btn-sm btn-dark">Show</a>';
                // <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-dark">SHOW</a>
            })
            ->rawColumns(['image','description','actions'])
            ->make(true);

    }
}
