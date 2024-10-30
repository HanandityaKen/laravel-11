<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function role() 
    {
        // $user = auth()->guard('api')->user();
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 401);
        }

        $role = $user->role;

        return response()->json([
            'role' => $role
        ], 200);

    }

    // public function getDataShow(Request $request, $id)
    // {
    //     $product = Product::findOrFail($id);

    //     return response()->json([
    //         'message'   => 'Data dikirim',
    //         'products' => [
    //             'images' => $product->images,
    //             'title' => $product->title,
    //             'description' => $product->description,
    //             'stock' => $product->stock,
    //             'price' => $product->price,
    //         ],
    //     ], 200);
    // }

    public function getData(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'message' => 'Data dikirim',
            'products' => $product,
        ], 200);
    }

    public function getProductsData(Request $request)
    {
        // kalo milih2
        // $products = Product::select('id', 'title', 'description', 'price', 'stock', 'file')->get();

        // kalo butuh semua data tanpa milih
        // $products = Product::all();

        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        $search = $request->input('search');

        
        $products = Product::where('title', 'like', '%' . $search . '%')->orderBy($sortBy, $sortDirection)->paginate($perPage);

        $role = Auth::guard('api')->user()->role;

        return response()->json([
            'data' => $products->map(function ($product) use ($role) {
                return [
                    'id'            => $product->id,
                    'images'        => '<img src="' . asset('storage/products/' . $product->images) . '" style="width: 100px; height: auto;">',
                    'title'         => $product->title,
                    'price'         => 'Rp. ' . number_format($product->price, 2, ',', '.'),
                    'description'   => $product->description,
                    'stock'         => $product->stock,
                    'file'          => $product->file,
                    'actions'       => $this->getActionButtons($product, $role)
                ];
            }),
            'pagination' => [
                'total'         => $products->total(),
                'per_page'      => $products->perPage(),
                'current_page'  => $products->currentPage(),
                'last_page'     => $products->lastPage(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ]
        ], 200);

        // test produk kosong untuk uji coba
        // $products = collect();  // Empty collection, or use: Product::where('id', -1)->paginate($perPage);

        // return response()->json([
        //     'data' => $products,
        //     'pagination' => [
        //         'total'         => 0,
        //         'per_page'      => $perPage,
        //         'current_page'  => 1,
        //         'last_page'     => 1,
        //         'next_page_url' => null,
        //         'prev_page_url' => null,
        //     ]
        // ]);


        // return DataTables::of($products)
        //     ->addColumn('image', function($row) {
        //         return '<img src="' . asset('storage/products/' . $row->images) . '" style="width: 100px; height: auto;">';
        //     })
        //     ->addColumn('description', function($row) {
        //         return $row->description; // Pastikan HTML dirender dengan benar
        //     })
        //     ->addColumn('actions', function($row) use($role) {
        //         if ($role === 'admin') {
        //             return '<a href="' . route('products.show', $row->id) . '" class="btn btn-sm btn-dark">Show</a>
        //             <a href="' . route('products.edit', $row->id) . '" class="btn btn-warning btn-sm">Edit</a>
        //             <button type="submit" class="btn btn-danger btn-sm delete-product" data-id="' . $row->id .'">Delete</button>';
        //         }
        //         return '<a href="' . route('products.show', $row->id) . '" class="btn btn-sm btn-dark">Show</a>';
        //     })
        //     ->rawColumns(['image','description','actions'])
        //     ->make(true);

        // $products = Product::all();

        // $role = Auth::guard('api')->user()->role;

        // return response()->json([
        //     'message' => 'Data fetched successfully',
        //     'data' => DataTables::of($products)
        //         ->addColumn('image', function($row) {
        //             return '<img src="' . asset('storage/products/' . $row->images) . '" style="width: 100px; height: auto;">';
        //         })
        //         ->addColumn('description', function($row) {
        //             return $row->description; 
        //         })
        //         ->addColumn('actions', function($row) use($role) {
        //             if ($role === 'admin') {
        //                 return '<a href="' . route('products.show', $row->id) . '" class="btn btn-sm btn-dark">Show</a>
        //                 <a href="' . route('products.edit', $row->id) . '" class="btn btn-warning btn-sm">Edit</a>
        //                 <button type="submit" class="btn btn-danger btn-sm delete-product" data-id="' . $row->id .'">Delete</button>';
        //             }
        //             return '<a href="' . route('products.show', $row->id) . '" class="btn btn-sm btn-dark">Show</a>';
        //         })
        //         ->rawColumns(['image', 'description', 'actions'])
        //         ->make(true)->getData()
        // ], 200);

    }

    private function getActionButtons($product, $role) {
        if ($role === 'admin') {
            return '
                <a href="'. route('products.show', $product->id) .'" class="btn btn-sm btn-dark">Show</a>
                <a href="'. route('products.edit', $product->id) .'" class="btn btn-warning btn-sm">Edit</a>
                <button type="submit" class="btn btn-danger btn-sm delete-product" data-id="' . $product->id .'">Delete</button>
            ';
        }

        return '<a href="'. route('products.show', $product->id) .'" class="btn btn-sm btn-dark">Show</a>';
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'images'        => 'required|image|mimes:jpeg,jpg,png|max:2048',
    //         'title'         => 'required|min:5',
    //         'description'   => 'required|min:10',
    //         'price'         => 'required|numeric',
    //         'stock'         => 'required|numeric',
    //         'file'          => 'required|mimes:pdf|max:5120',
    //     ]);
    //     try {
            
    //         $images = $request->file('images');
    //         $images->storeAs('public/products', $images->hashName());
    
    //         $file = $request->file('file');
    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension(); // UUID dengan ekstensi file
    //         $file->storeAs('public/products/file', $fileName);
    
    //         $product = Product::create([
    //             'images'            => $images->hashName(),
    //             'title'             => $request->title,
    //             'description'       => $request->description,
    //             'price'             => $request->price,
    //             'stock'             => $request->stock,
    //             'file'              => $fileName,
    //         ]);
    
    //         return response()->json([
    //             'message' => 'Data Berhasil Disimpan!',
    //             'data' => $product,
    //         ], 201); // HTTP status 201 Created
    
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Data Gagal Disimpan!',
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        Log::info('Updating product', $request->all());

        $request->validate([
            'images'        => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric',
            'file'          => 'required|mimes:pdf,doc,docx|max:5120',
        ]);

        try {
            // Simpan produk tanpa image dan file terlebih dahulu
            $product = Product::create([
                'title'        => $request->title,
                'description'  => $request->description,
                'price'        => $request->price,
                'stock'        => $request->stock,
                'images'       => 'temp.jpg', // Nilai sementara untuk kolom image
            ]);

            // Menyimpan image dengan menambahkan ID produk
            // $image = $request->file('image');
            // $imageName = $product->id . '_' . $image->hashName();
            // $image->storeAs('public/products', $imageName);
            $images = $request->file('images');
            $imagesName = $product->id . '_' . $images->hashName();
            $images->storeAs('public/products', $imagesName);

            $product->update(['images' => $imagesName]);


            // Menyimpan file dengan nama yang mengandung UUID
            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/products/documents', $fileName);

            // Update produk dengan nama file image dan dokumen
            $product->update([
                'images' => $imagesName,
                'file'  => $fileName,
            ]);

            return response()->json([
                'message' => 'Data Berhasil Disimpan!',
                'data'    => $product,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error storing product: ' . $e->getMessage());

            return response()->json([
                'message' => 'Data Gagal Disimpan!',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function update (Request $request, $id)
    {
        // Log::info('Updating product', $request->all());
        Log::info('Updating product', ['id' => $id, 'request_data' => $request->all()]);

        $request->validate([
            'images'        => 'image|mimes:jpeg,jpg,png,img|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric',
            'file'          => 'mimes:pdf|max:5120',
        ]);

        $product = Product::findOrFail($id);

        // if ($request->file('images')) {
        //     // delete
        //     Storage::delete('public/products/'.$product->images);

        //     $images = $request->file('images');
        //     $images->storeAs('/public/products', $images->hashName());

        //     $product->images = $images->hashName();

        // }

        if ($request->file('images')) {
            // Hapus gambar lama
            Storage::delete('public/products/' . $product->images);

            $images = $request->file('images');
            $imagesName = $product->id . '_' . $images->hashName(); // Menambahkan ID produk pada nama file
            $images->storeAs('public/products', $imagesName);

            $product->images = $imagesName;
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

        return response()->json([
            'message'   => 'Data berhasil diperbarui',
            'data'      => $product,      
        ], 200);
    }

    public function destroy($id)
    {
        $product =  Product::findOrFail($id);

        Storage::delete('public/products/'. $product->images);
        Storage::delete('public/products/file'. $product->file);

        $product->delete();

        return response()->json([
            'message'   => 'Data berhasil dihapus'
        ], 200);
    }
}


