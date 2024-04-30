<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class OrderController extends BaseController
{
    public function index()
    {
        try {
            $orders = Order::latest()->get();
            return $this->sendResponse(OrderResource::collection($orders), 'Successfully get data', 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 400);
        }
    }
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'client_name' => 'required',
                'client_type' => 'required|in:MAHASISWA,INSTANSI,PRIBADI',
                'phone_number' => 'required|numeric',
                'project_title' => 'required',
                'project_type' => 'required|in:WEB,MOBILE,MACHINE LEARNING,CONSULT,DESIGN',
                'min_price' => 'required|integer',
                'max_price' => 'required|integer',
                'project_description' => 'required',
                'techstack_detail' => 'required',
                'file_requirement' => 'required|file|mimes:pdf|max:5120',
            ]);

            $file = $request->file('file_requirement');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::random(10) . '.' . $fileExtension;
            $filePath = 'file/orders/' . $fileName;
            $file->move('file/orders', $fileName);

            $order = Order::create([
                'client_name' => $request->client_name,
                'client_type' => $request->client_type,
                'phone_number' => $request->phone_number,
                'project_title' => $request->project_title,
                'project_type' => $request->project_type,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'project_description' => $request->project_description,
                'techstack_detail' => $request->techstack_detail,
                'file_requirement' => url($filePath),
            ]);

            DB::commit();
            return $this->sendResponse(new OrderResource($order), 'Order created successfully', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage(), 400);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'status' => 'required|in:ACCEPT,REQUEST,CANCEL'
            ]);

            $order = Order::findOrFail($id);
            $order->update([
                'status' => $request->status
            ]);

            DB::commit();
            return $this->sendResponse(new OrderResource($order), 'Order updated successfully', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage(), 400);
        }
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $order = Order::findOrFail($id);

            $fileUrl = $order->file_requirement;
            $filePath = parse_url($fileUrl, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/');

            if (file_exists(($filePath))) {
                unlink(($filePath));
            }

            $order->delete();
            DB::commit();
            return $this->sendResponse(new OrderResource($order), 'Successfully deleted order', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage(), 400);
        }
    }
}
