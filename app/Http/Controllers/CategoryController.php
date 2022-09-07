<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $category = Category::get()->toTree();
        return  $category;
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
        //adding parent node if parent is empty
        $request->validate(
            [
                "title" => 'required',
            ]
        );
        $category = Category::create([
            'title' => $request->title,
        ]);
        if ($request->parent) {
            $parent = Category::find($request->parent);
            if ($parent) {
                $parent->appendNode($category);
            } else {
                return response(['message' => 'no parent category found'], Response::HTTP_ACCEPTED);
            }
        }
        return response([$category], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //products by category
        $categories = $category->whereAncestorOrSelf($category)->get();
        $products = [];
        foreach ($categories as $branch) {
            $branch_prod = $branch->products()->get();
            if (count($branch_prod) > 0) {
                array_push($products, $branch_prod);
            }
        }
        foreach ($products as $prod) {
            return $prod;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'title' => 'required'
        ]);
        $category = $category->update(
            $request->only('title')
        );

        //  return response([$category], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $products = $category->products()->get();
        foreach ($products as $product) {
            $product->categories()->detach($category->id);
        }
        //deleting node
        $category->delete();
        $bool = Category::isBroken();
        return response(['message' => 'node deleted successfully', $bool], Response::HTTP_NO_CONTENT);
    }
}
