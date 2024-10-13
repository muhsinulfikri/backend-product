<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index(){
        $product = Product::all('id_product','name', 'price', 'quantity', 'description');
        return new ProductResource('success', 'success get all data', $product);
    }
    public function store(Request $request){
        $name = $request->input('name');
        $category_id = $request->input('category_id');
        $price = $request->input('price');
        $quantity = $request->input('quantity');
        $description = $request->input('description');
        $product = [
            'name' => $name,
            'category_id' => $category_id,
            'price' => $price,
            'quantity' => $quantity,
            'description' => $description
        ];
        if (!$name){
            return response()->json(new ProductResource('failed', 'isi kolom nama', null),400);
        } elseif (!$category_id){
            return response()->json(new ProductResource('failed', 'isi kolom kategory', null),400);
        } elseif (!$price){
            return response()->json(new ProductResource('failed', 'isi kolom harga', null),400);
        } elseif (!$quantity){
            return response()->json(new ProductResource('failed', 'isi kolom kuantitas', null),400);
        }
        try {
            Product::insert($product);
            return new ProductResource('success', 'berhasil menambahkan produk', $product);
        } catch (Exception $e) {
            return response()->json(new ProductResource('failed', 'gagal menambahkan produk', null),404);
        }
    }
    public function show($id_product){
        try {
            // Use findOrFail to retrieve the product or fail
            $product = Product::find($id_product);
            if (!$product){
                return response()->json(new ProductResource('failed', 'Produk tidak ditemukan', null), 404);
            }
            // If found, return success response with product data
            return new ProductResource('success', 'Berhasil menampilkan produk', $product);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // If the product is not found, return a 404 response
            return response()->json(new ProductResource('failed', 'Produk tidak ditemukan', null), 404);
        }
    }
    public function update(Request $request, $id_product){
        $name = $request->input('name');
        $price = $request->input('price');
        $quantity = $request->input('quantity');
        $description = $request->input('description');

        $product_update = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'description' => $description
        ];
        $product = Product::find($id_product);
        if (!$product){
            return response()->json(new ProductResource('failed', 'gagal update produk', null), 400);
        }
        try {
            if (!$name){
                return response()->json(new ProductResource('failed', 'nama tidak boleh kosong', null),400);
            } elseif (!$price){
                return response()->json(new ProductResource('failed', 'harga tidak boleh kosong', null),400);
            } elseif (!$quantity){
                return response()->json(new ProductResource('failed', 'kuantitas tidak boleh kosong', null),400);
            }
            $product->update($product_update);
            return new ProductResource('success', 'success update produk', $product_update);
        } catch (Exception $e){
            return new ProductResource('failed', 'mohon jangan update kategori', null);
        }
    }
    public function search($name){
        $product = Product::where('name', 'LIKE', "%{$name}%")->get();
        if ($product->isEmpty()){
            return response()->json(new ProductResource('failed', 'produk tidak ditemukan', null), 404);
        }
        return new ProductResource('success', 'produk ditemukan ', $product);
    }
    public function destroy($id_product){
        $product = Product::find($id_product);
        try {
            if (!$product){
                return response()->json(new ProductResource('failed', 'gagal menghapus produk', $product), 404);
            }
            $product->delete();
            return new ProductResource('success', 'berhasil menghapus produk', null);
        } catch (Exception $e){
            return response()->json(new CategoryResource('failed', 'gagal menghapus produk', 'masukkan id yang valid'), 404);
        }
    }
}
