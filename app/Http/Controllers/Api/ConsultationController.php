<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ConsultationController extends BaseController
{
    public function index()
    {
        try {
            $consult = DB::table('consultations')->get();
            return $this->sendResponse(ConsultationResource::collection($consult), "Successfully get data", 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            $consult = DB::table('consultations')->where('id', $id)->first();
            return $this->sendResponse(new ConsultationResource($consult), "Successfully get data", 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'questions' => 'required'
            ]);

            $consult = Consultation::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'questions' => $request->questions,
                'answer' => $request->answer,
            ]);

            return $this->sendResponse(new ConsultationResource($consult), "Successfully created data", 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'questions' => 'required',
            ]);

            $consult = Consultation::findOrFail($id);
            $consult->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'questions' => $request->questions,
                'answer' => $request->answer,
            ]);
            return $this->sendResponse(new ConsultationResource($consult), "Successfully update data", 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $consult = DB::table('consultations')->where('id', $id);
            $consult->delete();
            return response()->json([
                'message' => 'Successfully delete data',
                'status' => 200
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }
}
