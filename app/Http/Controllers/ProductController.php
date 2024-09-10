<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\View\View;

use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    
    //
    public function index() : View
    {
        $products = Product::latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create() : View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'images'         => 'required|image|mimes:jpeg,jpg,png,img|max:2048',
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

        // if ($request->hasFile('images')) {


        //     //upload new image
        //     $images = $request->file('image');
        //     $images->storeAs('/public/products', $images->hashName());

        //     //delete
        //     Storage::delete('public/products/'.$product->images);

        //     $product->update([
        //         'image'                 => $image->hashName(),
        //         'title'                 => $request->title,
        //         'description'           => $request->description,
        //         'price'                 => $request->price,
        //         'stock'                 => $request->stock,
        //     ]);
        // } else {
        //     $product->update([
        //         'title'                 => $request->title,
        //         'description'           => $request->description,
        //         'price'                 => $request->price,
        //         'stock'                 => $request->stock,
        //     ]);
        // }

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
}
