<?php

namespace App\Http\Controllers;

use App\Tag;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return response()->json(['status' => 'ok', 'tags' => $tags->toArray(), 'count' => $tags->count()])->setStatusCode(200);
    }
}
