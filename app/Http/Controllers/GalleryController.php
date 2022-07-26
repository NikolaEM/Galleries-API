<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\Gallery;
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

    public function show(Gallery $gallery){

        return response()->json($gallery);
    }

    public function store(CreateGalleryRequest $request){
        $data = $request->validated();

        $gallery = Gallery::create([
            'user_id' => Auth::user()->id,
            'title' => $data['title'],
            'description' => $data['description']
        ]);

        return response()->json($gallery);
    }

    public function update( UpdateGalleryRequest $request, $id){
        $data = $request->validated();
        $gallery = Gallery::findOrFail($id);
        $gallery->update($data);
        
       
        return response()->json($gallery);
    }

    public function delete(Gallery $gallery){
       
        $gallery->delete();
       
        return response(null, 204);
    }
}