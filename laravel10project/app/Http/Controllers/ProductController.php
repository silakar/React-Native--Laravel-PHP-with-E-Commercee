<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // All Product
        $products = Product::all();
      
        // Return Json Response
        return response()->json([
           'products' => $products
        ],200);
    }

  
    public function create()
    {
        //
    }

    
    public function store(ProductStoreRequest $request)
    {
        try {
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
      
            // Create Product
            Product::create([
                'name' => $request->name,
                'image' => $imageName,
                'description' => $request->description
            ]);
      
            // Save Image in Storage folder
            Storage::disk('public')->put($imageName, file_get_contents($request->image));
      
            // Return Json Response
            return response()->json([
                'message' => "Product successfully created."
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }



    public function show($id)
    {
       // Product Detail 
       $product = Product::find($id);
       if(!$product){
         return response()->json([
            'message'=>'Product Not Found.'
         ],404);
       }
      
       // Return Json Response
       return response()->json([
          'product' => $product
       ],200);
    }

    

    public function edit(string $id)
    {
        
    }


    public function update(ProductStoreRequest $request, $id)
    {
        try {
            // Find product
            $product = Product::find($id);
            if(!$product){
              return response()->json([
                'message'=>'Product Not Found.'
              ],404);
            }
             echo "request:$request->name";
             echo "description : $request->description";
             $product->name=$request->name;
             $product->description=$request->description;

             if($request->image){
                $storage=Storage::disk('public');

                if($storage->exists($product->image))
                $storage->delete($poruct->image);

                $imageName=Str::random(32).".".$request->image->getClientOriginalExtension();
                $product->image=$imageName;

                $storage->put($imageName,file_get_contents($request->image));
             }

             $product->save();


            //Return json Response
            return response()->json([
                'message'=>"Product seccessfully update."],200);
        

        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }

    
    public function destroy(string $id)
    {
        $product=Product::find($id);
        if(!$product){
            return response()->json([
                'message'=>'Product Not Found.'
            ],404);
        }
         // Public storage
         $storage = Storage::disk('public');
      
         // Iamge delete
         if($storage->exists($product->image))
             $storage->delete($product->image);
       
         // Delete Product
         $product->delete();
       
         // Return Json Response
         return response()->json([
             'message' => "Product successfully deleted."
         ],200);
    }
}
