<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $record = Category::latest()
        ->when($request->has('name'), function($query) use ($request){
            return $query->where('name','like','%' . $request->query('name') . '%');
        })
        ->get(['id', 'name', 'created_at']);

        return $record;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request -> validate([
            'name'=>[
                'required',
                'string',
                Rule::unique('categories')->where(function($query){
                    return $query->where('deleted_at',null);
                }),
            ],
        ]);

        $record = Category::create([
            'name' => $request->input('name'),
            'fk_created_by' => auth()->user()->id,
        ]);

        return $record;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category->load([
            'createdBy' => function($query){
                $query->select('id','name');
            },
            'updatedBy' => function($query){
                $query->select('id','name');
            }
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {   
        $request -> validate([
            'name' => [
                'required',
                'string',
                Rule::unique('categories')->where(function($query){
                    return $query->where('deleted_at',null);
                })->ignore($category->id),
            ]
        ]);

        $category->update([
            'name'=>$request->input('name'),
            'fk_updated_by'=>auth()->user()->id,
        ]);

        return $category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response([],204);
    }
}
