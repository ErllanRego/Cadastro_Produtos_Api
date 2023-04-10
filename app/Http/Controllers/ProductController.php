<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function list(){
        try{
            $products = Produto::join('categories', 'produtos.category_id', 'categories.id')
            ->select('produtos.*', 'categories.name as category')
            ->get();

            return response()->json([
                'status' => true,
                'products' => $products
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        
    }

    public function list_categories(){
        try{
            $categories = Categories::select('id', 'name')->get();

            return response()->json([
                'status' => true,
                'products' => $categories
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        
    }

    public function create(Request $request){
        try{
            
            $validateProduct = Validator::make($request->all(), 
            [
                'name' => 'required',
                'price' => 'required',
                'description' => 'required',
                'image_b64' => 'required',
                'category_id' => 'required',
            ]);

            if($validateProduct->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateProduct->errors()
                ], 401);
            }

            $ValidateCategory = Categories::find($request->category_id);

            if($ValidateCategory == null){
                return response()->json([
                    'status' => false,
                    'message' => 'This category id do not exist'
                ], 500);
            }
            

            $product = Produto::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'image_b64' => $request->image_b64,
                'category_id' => $request->category_id,
            ]);

            return response()->json([
                'status' => true,
                'product' => $product
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request, $id_product){
        try{
            
            $validateProduct = Validator::make($request->all(), 
            [
                'name' => 'required',
                'price' => 'required',
                'description' => 'required',
                'category_id' => 'required',
            ]);

            if($validateProduct->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateProduct->errors()
                ], 401);
            }

            $ValidateCategory = Categories::find($request->category_id);

            if($ValidateCategory == null){
                return response()->json([
                    'status' => false,
                    'message' => 'This category id do not exist'
                ], 500);
            }
            

            $product = Produto::find($id_product);
            $product->name = $request->name;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->category_id = $request->category_id;

            if($request->image_b64 != null){
                $product->image_b64 = $request->image_b64;
            }
            

            return response()->json([
                'status' => true,
                'product' => $product
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function delete($id_product){
        try{

            $product = Produto::find($id_product);

            if($product == null){
                return response()->json([
                    'status' => false,
                    'message' => 'Product not find'
                ], 404);
            }

            $product->delete();
            
            return response()->json([
                'status' => true,
                'message' => 'Product deleted'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
