<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Attrvalue;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            'category' => 'required',
            'attrvalues' => 'required',
        ]);
        // making category title lowercase
        $categoryTitle = strtolower($request->category);
        foreach ($request->attrvalues as $title => $attrvalues) {

            // making attribute title lowercase
            $title = strtolower($title);

            // finding the category and attribute 
            $category = Category::where('title', $categoryTitle)->first();

            if ($category) {
                $attr = Attribute::where('title', $title)->first();
                if ($attr) {
                    // making sure there is relationship between category and attributes
                    if (!$attr->categories->contains($category->id)) {
                        $attr->categories()->attach($category);
                    }
                    foreach ($attrvalues as $attrvalue) {

                        // making attribute value lowercase
                        $value = strtolower($attrvalue);

                        // checking if value already exist, if not adding it to the table
                        $valueExist = Attrvalue::where('value', $value)->first();
                        if (!$valueExist) {
                            $attrvalues = $attr->attrvalues()->create(['value' => $value]);
                        }
                    }
                } else {

                    // creating new attribute if not exist
                    $attr = Attribute::create(['title' => $title]);
                    $attr->categories()->attach($category);
                    foreach ($request->attrvalues as $attrvalue) {

                        // making attribute value lowercase
                        $value = strtolower($attrvalue);
                        $valueExist = Attrvalue::where('value', $value)->first();
                        if (!$valueExist) {
                            $attrvalues[] = $attr->attrvalues()->create(['value' => $value]);
                        }
                    }
                    return response([$attr, 'message' => 'attribute created'], Response::HTTP_CREATED);
                }
            } else {
                return response(['error' => 'category doesn\'t exist, please add the category'], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
