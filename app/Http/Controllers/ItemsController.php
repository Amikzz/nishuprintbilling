<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Http\Requests\StoreItemsRequest;
use App\Http\Requests\UpdateItemsRequest;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemsRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Items $items): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Items $items): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemsRequest $request, Items $items): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Items $items): void
    {
        //
    }
}
