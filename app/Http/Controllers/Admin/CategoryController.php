<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
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
            'name' => 'required|max:100|min:2'
        ]);

        $data = $request->all();

        $newCategory = new Category();
        $newCategory->fill($data);

        $slug = $this->getUniqueSlug($newCategory->name);
        $newCategory->slug = $slug;
        $newCategory->save();

        return redirect()->route('admin.category.index')->with('create', 'category created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataCategory = Category::findOrFail($id);
        return view('admin.categories.show', compact('dataCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataCategory = Category::findOrFail($id);
        return view('admin.categories.edit', compact('dataCategory'));
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
        $request->validate([
            'name' => 'required|max:100|min:2'
        ]);

        $dataCategory = Category::findOrFail($id);
        $data = $request->all();

        if ($dataCategory->name !== $data['name']) {
            $data['slug'] = $this->getUniqueSlug($data['name']);
        }

        $dataCategory->update($data);
        $dataCategory->save();

        return redirect()->route('admin.category.edit', ['category' => $id])->with('update', 'Category Updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataCategory = Category::findOrFail($id);
        $dataCategory->delete();
        return redirect()->route('admin.category.index', ['category' => $dataCategory])->with('status', 'Category deleted');

    }


    protected function getUniqueSlug($name){

        $slug = Str::slug($name, '-');

        $checkSlug = Category::where('slug', $slug)->first();

        $count = 1;

        while ($checkSlug) {
            $slug = Str::slug($name, '-' . $count, '-');
            $count++;
            $checkSlug = Category::where('slug', $slug)->first();
        }

        return $slug;
    }
}
