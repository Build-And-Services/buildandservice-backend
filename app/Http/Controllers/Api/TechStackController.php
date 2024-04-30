<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TechStackResource;
use App\Models\TechStack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TechStackController extends BaseController
{
    public function index()
    {
        try {
            $techStack = DB::table('tech_stacks')->select('*')->get();
            return $this->sendResponse(TechStackResource::collection($techStack), "Successfully get data", 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            $techStack = DB::table('tech_stacks')->where('id', $id)->first();
            return $this->sendResponse(new TechStackResource($techStack), "Successfully get data", 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'field' => 'required',
                'logo' => 'required|mimes:png,jpg,jpeg,webp'
            ]);

            $file = $request->file('logo');
            $fileName = $file->getClientOriginalName();
            $filePath = 'images/tech-stack/' . $fileName;
            $file->move('images/tech-stack', $fileName);

            $techStack = TechStack::create([
                'name' => $request->name,
                'field' => $request->field,
                'logo' => $filePath
            ]);


            return $this->sendResponse(new TechStackResource($techStack), "Tech stack successfully created", 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'field' => 'required',
                'logo' => 'required',
            ]);
            $techStack = TechStack::findOrFail($id);

            if ($request->hasFile('logo')) {
                if (file_exists($techStack->logo)) {
                    unlink($techStack->logo);
                }
                $file = $request->file('logo');
                $fileName = $file->getClientOriginalName();
                $filePath = 'images/tech-stack/' . $fileName;
                $file->move('images/tech-stack', $fileName);
            } else {
                $filePath = $techStack->logo;
            }

            $techStack->update([
                'name' => $request->name,
                'field' => $request->field,
                'logo' => $filePath
            ]);

            return $this->sendResponse(new TechStackResource($techStack), "Tech stack successfully update", 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $techStack = TechStack::findOrFail($id);
            // dd($techStack);
            if (file_exists($techStack->logo)) {
                unlink($techStack->logo);
            }
            $techStack->delete();
            return response()->json([
                "message" => 200
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }
}
