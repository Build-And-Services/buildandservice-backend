<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_name' => $this->client_name,
            'client_type' => $this->client_type,
            'phone_number' => $this->phone_number,
            'project_title' => $this->project_title,
            'project_type' => $this->project_type,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'project_description' => $this->project_description,
            'techstack_detail' => $this->techstack_detail,
            'file_requirement' => $this->file_requirement,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
