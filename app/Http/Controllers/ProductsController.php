<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Add a new product in the database.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $request['slug'] = Str::slug($fields['name']);
        $product = Product::create($request->all());

        return response([
            'message' => "Product {$product->name} added successfully.",
        ], 201);
    }

    /**
     * Display the product of the given id.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response([
                'message' => "Product not found with the id: {$id}",
            ], 404);
        }

        return response([
            'message' => "Product found",
            'product' => $product
        ], 200);
    }
}
