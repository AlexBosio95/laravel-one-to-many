<?php

namespace App\Http\Controllers\Admin;

use App\posts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = posts::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create');
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
            'name' => 'required|max:100|min:2',
            'content' => 'required|max:65535|min:2',
            'tag' => 'required|max:255|min:2'
        ]);

        $data = $request->all();

        
        $newPost = new posts();
        
        $newPost->fill($data);
        
        $tag = $this->tagToHashtag($newPost->tag);
        $slug = $this->getUniqueSlug($newPost->name);

        $newPost->tag = $tag;
        $newPost->slug = $slug;


        $newPost->save();

        return redirect()->route('admin.posts.index')->with('create', 'Item created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = posts::findOrFail($id);
        return view('admin.posts.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = posts::findOrFail($id);
        return view('admin.posts.edit', compact('data'));
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
            'name' => 'required|max:100|min:2',
            'content' => 'required|max:65535|min:2',
            'tag' => 'required|max:255|min:2'

        ]);

        $post = posts::findOrFail($id);
        $data = $request->all();

        if ($post->name !== $data['name']) {
            $data['slug'] = $this->getUniqueSlug($data['name']);
        }

        if ($post->tag !== $data['tag']) {
            $data['tag'] = $this->tagToHashtag($data['tag']);
        }

        $post->update($data);
        $post->save();

        return redirect()->route('admin.posts.edit', ['post' => $post])->with('update', 'Item updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = posts::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.posts.index', ['post' => $post])->with('status', 'Item deleted');
    }

    protected function getUniqueSlug($name){

        $slug = Str::slug($name, '-');

        $checkSlug = posts::where('slug', $slug)->first();

        $count = 1;

        while ($checkSlug) {
            $slug = Str::slug($name, '-' . $count, '-');
            $count++;
            $checkSlug = posts::where('slug', $slug)->first();
        }

        return $slug;
    }

    protected function tagToHashtag($currentTag){

        $mytag = explode(' ', $currentTag);
        $arrayTag = [];

        foreach ($mytag as $value) {
            $arrayTag[] = Str::start($value, '#');
        };

        return implode(' ', $arrayTag);

    }
}
