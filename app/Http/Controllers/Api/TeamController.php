<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
            $fileName = $file->getClientOriginalName();
            $filePath = 'images/team/' . $fileName;
            $file->move('images/team', $fileName);

            // $team = DB::insert('INSERT INTO teams (name, description, image, github, linkedin, instagram, web_porto) VALUES (?, ?, ?, ?, ?, ?)', [$name, $description, $filePath, $github, $linkedin, $instagram, $web_porto]);

            $team = Team::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $filePath,
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
                if (file_exists($team->image)) {
                    unlink($team->image);
                }
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $filePath = 'images/team/' . $fileName;
                $file->move('images', $fileName);
            }else {
                $filePath =  $team->image;
            }
            // dd($team);

            $team->update([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $filePath,
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
            if (file_exists(( $team->image))) {
                unlink(( $team->image));
            }
            $team->delete();
            return $this->sendResponse(new TeamResource($team), 'Successfully deleted team', 203);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }
}
