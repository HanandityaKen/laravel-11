<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;

use Illuminate\View\View;

class UserController extends Controller
{
    public function index() : View
    {
        $products = Product::latest()->paginate(10);

        return view('users.index', compact('products'));
    }
}
