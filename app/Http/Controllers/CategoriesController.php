<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\categories;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cat = categories::all();
        return response()->json([
            'cat' => $cat
        ]);
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
        // return response()->json($request->all());

        $validate = Validator::make($request->all(),[
            'uuid' => ['required','digits:3','unique:categories'],
            'name' =>  ['required', 'string', 'max:255'],
            'color' =>  ['required', 'string', 'max:255'],
            'description' =>  ['required', 'string', 'max:255'],
        ]);

        if($validate->fails())
        {
            return response()->json($validate->errors()->toJson(),400);
        }

        $cat = categories::create($request->all())->save();

        return response()->json([
            'message' => 'categories Created Successfully'
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cat = categories::find($id);
        if(!$cat)
        {
            return response()->json([
                'success' => false,
                'message' => 'Sorry id '.$id.' cannot be found'
            ],400);
        } else {
            return response()->json([
                'cat' => $cat
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $cat = categories::find($id);
        if(!$cat)
        {
            return response()->json([
                'success' => false,
                'message' => 'Sorry id'.$id.'cannot be found'
            ],400);
        }

        $cat_update = $cat->fill($request->all())->save();
        if($cat_update)
        {
            return response()->json([
                'success' => true,
                'message' => 'Categories Successfully Updated'
                ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Categories could not be updated'
            ],500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat_del = categories::find($id)->delete();
        if($cat_del)
        {
            return response()->json([
                'success' => true,
                'message' => 'Categories Successfully Deleted'
                ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Categories could not be Deleted'
            ],500);
        }
    }
}
