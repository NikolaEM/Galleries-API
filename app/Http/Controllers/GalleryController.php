<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\Gallery;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    public function index(Request $request){
        $userId = $request->query('userId', '');
        $term = $request->query('term', '');
        $galleries = Gallery::searchByTerm($term, $userId)->latest()->paginate(10);

        return response()->json($galleries);
    }

    public function show($id){

        $gallery = Gallery::with(['images', 'user', 'comments', 'comments.user'])->find($id);
        return response()->json($gallery);
    }

    public function store(CreateGalleryRequest $request){
        $data = $request->validated();
        
        $gallery = Gallery::create([
            'user_id' => Auth::user()->id,
            'title' => $data['title'],
            'description' => $data['description']
        ]);

       

        $imagesArr = [];
        foreach($data['images'] as $image) {
            $imagesArr[] = Image::create([
                'gallery_id' => $gallery->id,
                'url' => $image['url']
            ]);
        }
        $gallery->load('images', 'user', 'comments', 'comments.user');

        return response()->json($gallery);
    }

    public function update( UpdateGalleryRequest $request, $id){
        $data = $request->validated();
        $gallery = Gallery::findOrFail($id);
        $gallery->update($data);

        $imagesArr = [];
        foreach($request['images'] as $image) {
            $imagesArr[] = Image::create([
                'gallery_id' => $gallery->id,
                'url' => $image
            ]);
        }
        
        $gallery->load('images', 'user', 'comments', 'comments.user');
        
        return response()->json($gallery);
    }

    public function delete($id){
       
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();
        return response(null, 204);
    }
}