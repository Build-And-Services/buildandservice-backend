<?php

namespace App\Http\Controllers\Api;

use App\Models\Team;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;

class TeamController extends BaseController
{
    public function index()
    {
        try {
            $team = DB::table('teams')->select('*')->get();
            return $this->sendResponse(TeamResource::collection($team), "Successfully get data", 200);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            $team = DB::table('teams')->where('id', $id)->select('*')->get();
            return $this->sendResponse(TeamResource::collection($team), "Successfully get data", 200);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,webp',
                'github' => 'required',
                'linkedin' => 'required',
                'instagram' => 'required',
            ]);
            // $name = $request->name;
            // $description = $request->description;
            // $github = $request->github;
            // $linkedin = $request->linkedin;
            // $instagram = $request->instagram;
            // $web_porto = $request->web_porto;

            $file = $request->file('image');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::random(10) . '.' . $fileExtension;
            $filePath = 'images/team/' . $fileName;
            $file->move('images/team', $fileName);


            // $team = DB::insert('INSERT INTO teams (name, description, image, github, linkedin, instagram, web_porto) VALUES (?, ?, ?, ?, ?, ?)', [$name, $description, $filePath, $github, $linkedin, $instagram, $web_porto]);

            $team = Team::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => url($filePath),
                'github' => $request->github,
                'linkedin' => $request->linkedin,
                'instagram' => $request->instagram,
                'web_porto' => $request->web_porto,
            ]);

            return $this->sendResponse(new TeamResource($team), 'Team created successfully', 201);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'github' => 'required',
                'linkedin' => 'required',
                'instagram' => 'required',
            ]);

            // $team = DB::table('teams')->where('id', $id);
            $team = Team::findOrFail($id);

            // dd($team);

            if ($request->hasFile('image')) {
                $fileUrl = $team->image;
                $filePath = parse_url($fileUrl, PHP_URL_PATH);
                $filePath = ltrim($filePath, '/');

                if (file_exists(( $filePath))) {
                    unlink(( $filePath));
                }
                $file = $request->file('image');
                $fileExtension = $file->getClientOriginalExtension();
                $fileName = time() . '_' . Str::random(10) . '.' . $fileExtension;
                $filePath = 'images/team/' . $fileName;
                $file->move('images/team', $fileName);
            }else {
                $filePath =  $team->image;
            }
            // dd($team);

            $team->update([
                'name' => $request->name,
                'description' => $request->description,
                'image' => url($filePath),
                'github' => $request->github,
                'linkedin' => $request->linkedin,
                'instagram' => $request->instagram,
                'web_porto' => $request->web_porto,
            ]);
            return $this->sendResponse(new TeamResource($team), 'Team updated successfully', 202);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            // $team = DB::table('teams')->where('id', $id)->get();
            $team = Team::findOrFail($id);

            $fileUrl = $team->image;
            $filePath = parse_url($fileUrl, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/');

            if (file_exists(( $filePath))) {
                unlink(( $filePath));
            }
            $team->delete();
            return response()->json([
                "message" => "Successfully delete data",
                "status" => 200,
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }
}
