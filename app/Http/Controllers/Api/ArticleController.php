<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;

class ArticleController extends BaseController
{
    public function index()
    {
        try {
            $article = DB::table('articles')->latest()->get();
            return $this->sendResponse(ArticleResource::collection($article), 'Successfully get data', 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }

    public function show($slug)
    {
        try {
            $article = Article::findOrFail($slug);
            // $article = DB::table('articles')->where('slug', $slug)->first();
            return $this->sendResponse(new ArticleResource($article), 'Successfully get data', 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'thumbnail' => 'required|mimes:png,jpg,jpeg,webp',
                'link' => 'required',
                'description' => 'required',
                // 'slug' => 'required',
            ]);

            $file = $request->file('thumbnail');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::random(10) . '.' . $fileExtension;
            $filePath = 'images/article/' . $fileName;
            $file->move('images/article', $fileName);


            $article = Article::create([
                'title' => $request->title,
                'thumbnail' => url($filePath),
                'link' => $request->link,
                'description' => $request->description,
                // 'slug' => $request->slug,
            ]);
            return $this->sendResponse(new ArticleResource($article), 'Successfully created data', 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
                'thumbnail' => 'required|mimes:png,jpg,jpeg,webp',
                'link' => 'required',
                'description' => 'required',
                // 'slug' => 'required'
            ]);

            $article = Article::findOrFail($id);

            if ($request->hasFile('thumbnail')) {
                $fileUrl = $article->thumbnail;
                $filePath = parse_url($fileUrl, PHP_URL_PATH);
                $filePath = ltrim($filePath, '/');

                if (file_exists(( $filePath))) {
                    unlink(( $filePath));
                }
                $file = $request->file('thumbnail');
                $fileExtension = $file->getClientOriginalExtension();
                $fileName = time() . '_' . Str::random(10) . '.' . $fileExtension;
                $filePath = 'images/article/' . $fileName;
                $file->move('images/article', $fileName);
            } else {
                $filePath = $article->thumbnail;
            }

            $article->update([
                'title' => $request->title,
                'thumbnail' => url($filePath),
                'link' => $request->link,
                'description' => $request->description,
                // 'slug' => $request->slug,
            ]);
            return $this->sendResponse(new ArticleResource($article), 'Successfully update data', 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $article = Article::findOrFail($id);
            $fileUrl = $article->thumbnail;
            $filePath = parse_url($fileUrl, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/');

            if (file_exists(( $filePath))) {
                unlink(( $filePath));
            }

            $article->delete();
            return response()->json([
                "message" => "Successfully delete data",
                "status" => 200
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }
}
