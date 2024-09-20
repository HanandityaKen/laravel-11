<?php

namespace App\Http\Controllers;

use App\Models\Product;

use App\Models\User;

use Illuminate\View\View;

use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;

use Yajra\DataTables\Facades\DataTables;


class ProductController extends Controller
{
    
    //
    public function index() : View
    {
        return view('products.index');
    }

    public function create() : View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'images'        => 'required|image|mimes:jpeg,jpg,png,img|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric',
            'file'          => 'required|mimes:pdf|max:5120',
        ]);

        $images = $request->file('images');
        $images->storeAs('public/products', $images->hashName());

        $file = $request->file('file');
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension(); // UUID dengan ekstensi file
        $file->storeAs('public/products/file', $fileName);

        Product::create([
            'images'            => $images->hashName(),
            'title'             => $request->title,
            'description'       => $request->description,
            'price'             => $request->price,
            'stock'             => $request->stock,
            'file'              => $fileName,
        ]);

        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id): View
    {
        $product = Product::findOrFail($id);

        return view('products.show', compact('product'));
    }

    public function edit(string $id): View
    {

        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id): RedirectResponse
    {

        $request->validate([
            'images'        => 'image|mimes:jpeg,jpg,png,img|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric',
            'file'          => 'mimes:pdf|max:5120',
        ]);

        $product = Product::findOrFail($id);

        if ($request->file('images')) {
            // delete
            Storage::delete('public/products/'.$product->images);

            $images = $request->file('images');
            $images->storeAs('/public/products', $images->hashName());

            $product->images = $images->hashName();

        }

        if ($request->file('file')) {
            Storage::delete('public/products/file/'.$product->file);

            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension(); // UUID dengan ekstensi file
            $file->storeAs('public/products/file', $fileName);

            $product->file = $fileName;

        }

        $product->update([
            'title'                 => $request->title,
            'description'           => $request->description,
            'price'                 => $request->price,
            'stock'                 => $request->stock,
        ]);

        return redirect()->route('products.index')->with(['success' => 'Data Berhasil DIubah!']);

    }

    public function destroy($id): RedirectResponse
    {

        $product = Product::findOrFail($id);

        Storage::delete('public/products/'. $product->images);

        Storage::delete('public/products/file'. $product->file);

        $product->delete();

        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    // public function getProductsData()
    // {
    //     // kalo milih2
    //     // $products = Product::select('id', 'title', 'description', 'price', 'stock', 'file')->get();

    //     //kalo butuh semua data tanpa milih
    //     $products = Product::all();

    //     $role = Auth::user()->role;

    //     return DataTables::of($products)
    //         ->addColumn('image', function($row) {
    //             return '<img src="' . asset('storage/products/' . $row->images) . '" style="width: 100px; height: auto;">';
    //         })
    //         ->addColumn('description', function($row) {
    //             return $row->description; // Pastikan HTML dirender dengan benar
    //         })
    //         ->addColumn('actions', function($row) use($role) {
    //             if ($role === 'admin') {
    //                 return '<a href="' . route('products.show', $row->id) . '" class="btn btn-sm btn-dark">Show</a>
    //                 <a href="' . route('products.edit', $row->id) . '" class="btn btn-warning btn-sm">Edit</a>
    //                 <form action="' . route('products.destroy', $row->id) . '" method="POST" style="display:inline;">
    //                     ' . csrf_field() . method_field('DELETE') . '
    //                     <button type="submit" class="btn btn-danger btn-sm">Delete</button>
    //                 </form>';
    //             }

    //             return '<a href="' . route('products.show', $row->id) . '" class="btn btn-sm btn-dark">Show</a>';
    //             // <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-dark">SHOW</a>
    //         })
    //         ->rawColumns(['image','description','actions'])
    //         ->make(true);

    // }
}
