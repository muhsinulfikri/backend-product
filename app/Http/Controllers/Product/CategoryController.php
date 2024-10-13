<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index(){
        try {
            $category = Category::all('name', 'description');
            return new CategoryResource('success', 'success get all category', $category);
        } catch (Exception $e){
            return response()->json(new CategoryResource('failed', 'server error', null), 500);
        }
    }
    public function store(Request $request){
        $name = $request->input('name');
        $description = $request->input('description');
        $category = [
            'name' => $name,
            'description' => $description
        ];
        if (!$name){
            return response()->json(new CategoryResource('failed', 'isi kolom nama', null), 400);
        }
        try {
            Category::insert($category);
            return new CategoryResource('success', 'berhasil menambahkan kategori', $category);
        } catch (Exception $e){
            return response()->json(new CategoryResource('failed', 'gagal menambahkan kategori', null), 500);
        }
    }
    public function show($id_category){
        try {
            $category = Category::find($id_category);
            if (!$category){
                return response()->json(new CategoryResource('failed', 'kategori tidak ditemukan', null), 404);
            }
            return new CategoryResource('success', 'berhasil menampilkan kategori', $category);
        } catch (Exception $e){
            return response()->json(new CategoryResource('failed', 'kategori tidak ditemukan', null), 404);
        }
    }
    public function update(Request $request, $id_category){
        try {
        $name = $request->input('name');
        $description = $request->input('description');

        $category_update = [
            'name' => $name,
            'description' => $description
        ];
        $category = Category::findOrFail($id_category);

        if (!$category){
            return response()->json(new CategoryResource('failed', 'gagal update kategori', null), 400);
        }
            $category->update($category_update);
            return new CategoryResource('success', 'success update kategori', $category_update);
        } catch (Exception $e){
            return response()->json(new CategoryResource('failed', 'id tidak ditemukan', null),400);
        }
    }
    public function search($name){
        $category = Category::where('name', 'LIKE', "%{$name}%")->get();
        if ($category->isEmpty()){
            return response()->json(new CategoryResource('failed', 'kategori tidak ditemukan', null), 404);
        }
        return new CategoryResource('success', 'produk ditemukan ', $category);
    }
    public function destroy($id_category){
        $category = Category::findOrFail($id_category);
        try {
            if (!$category){
                return response()->json(new CategoryResource('failed', 'gagal menghapus kategori', $category), 404);
            }
            $category->delete();
            return new CategoryResource('success', 'berhasil menghapus kategori', null);
        } catch (Exception $e){
            return response()->json(new CategoryResource('failed', 'gagal menghapus kategory', 'masukkan id yang valid'), 404);
        }
    }
}
