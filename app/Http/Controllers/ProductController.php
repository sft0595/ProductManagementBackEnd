<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Attrvalue;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('categories')->get();
        return ProductResource::collection($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'meta_description' => 'required',
            'category' => 'required',
            'attrvalues' => 'required',
            'variations' => 'required',
        ]);
        // making category title lowercase
        $categoryTitle = strtolower($request->category);
        $category = Category::where('title', $categoryTitle)->first();
        if ($category) {
            $productExist = Product::where('name', $request->name)->first();
            if (!$productExist) {
                $product = Product::create($request->only('name', 'meta_description'));
                if ($product) {
                    $product->categories()->attach($category);
                    foreach ($request->attrvalues as $title => $attrvalues) {
                        // making attribute title lowercase
                        $title = strtolower($title);
                        $attr = Attribute::where('title', $title)->first();
                        if ($attr) {
                            // making sure there is relationship between category and attributes
                            $attr->categories()->syncWithoutDetaching($category);
                            foreach ($attrvalues as $attrvalue) {
                                // making attribute value lowercase
                                $value = strtolower($attrvalue);
                                // checking if value already exist, if not adding it to the table
                                $attributeValue = Attrvalue::where('value', $value)->first();
                                if (!$attributeValue) {
                                    $attributeValue = $attr->attrvalues()->create(['value' => $value]);
                                }
                                $product->attrvalues()->syncWithoutDetaching($attributeValue);
                            }
                        } else {
                            // creating new attribute if not exist
                            $attr = Attribute::create(['title' => $title]);
                            $attr->categories()->syncWithoutDetaching($category);

                            foreach ($attrvalues as $attrvalue) {
                                // making attribute value lowercase
                                $value = strtolower($attrvalue);
                                // checking if value already exist, if not adding it to the table
                                $attributeValue = Attrvalue::where('value', $value)->first();
                                if (!$attributeValue) {
                                    $attributeValue = $attr->attrvalues()->create(['value' => $value]);
                                }
                                $product->attrvalues()->attach($attributeValue);
                            }
                        }
                    }

                    foreach ($request->variations as $variations) {
                        $prod_code = $variations['product_code'];
                        $prodCode_array = str_split($prod_code);
                        sort($prodCode_array);
                        $uniqueProdCode = $product->name . '_' . implode($prodCode_array); // abc
                        $varities = $product->varities()->create([
                            'productcode' => $uniqueProdCode,
                            'quantity' => $variations['quantity'],
                            'cost' => $variations['cost_price'],
                            'sell' => $variations['sell_price'],
                        ]);
                    };

                    if ($varities) {
                        $newProduct = $product->with(['categories', 'varities'])->first();
                        return response($newProduct, Response::HTTP_CREATED);
                    } else {
                        $product->destroy($product->id);
                        return response(['error' => 'Product can not be created'], Response::HTTP_NO_CONTENT);
                    }
                } else {
                    return response(['error' => 'Something went wrong! Please, try again later'], Response::HTTP_EXPECTATION_FAILED);
                }
            } else {
                return response(['error' => 'Product already exist'], Response::HTTP_CONFLICT);
            }
        } else {
            return response(['error' => 'category doesn\'t exist, please add the category'], Response::HTTP_BAD_REQUEST);
        }


        // // attributes with attribute values input will come like below
        // $arr = []; // array to keep unique product codes
        // $newArr = []; // array to replace $arr values by new input values 
        // $attributes = [
        //     'size' => ['l', 's', 'm'], 'color' => ['white', 'black'], 'fabric' => ['cotton', 'linen'],
        //     'style' => ['print', 'solid', 'stripe']
        // ];
        // // unique product code generation function by multiple input attribute values;
        // function codeGen($arr, $attr, $newArr)
        // {
        //     foreach ($arr as $arrItem) {
        //         foreach ($attr as $data) {
        //             array_push($newArr, $arrItem . '_' . $data);
        //         }
        //     }
        //     return $newArr;
        // }
        // $i = 0;
        // foreach ($attributes as $item) {
        //     $i++;
        //     if ($i <= 1) {
        //         foreach ($item as $value) {
        //             array_push($arr, $product->name . '_' . $value);
        //         }
        //     }
        //     if ($i > 1) {
        //         $arr = codeGen($arr, $item, $newArr);
        //     }
        // }
        // print_r($arr);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $productDetail = Product::with('categories', 'attrvalues', 'varities')->find($product->id);
        $product = new ProductResource($productDetail);
        return response($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->destroy($product->id);
    }
}
