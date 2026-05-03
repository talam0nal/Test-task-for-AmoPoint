<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\LotteryUsers;

class LotteryResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'cost' => $this->cost,
            'expired_at' => $this->expired_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'city' => $this->city,
            'members_count' => $this->users_count,
            'image' => $this->image->path ?? null,
            'show_in_slider' => $this->show_in_slider,
            'slider_image' => $this->slider_image,
            'tickets_count' => LotteryUsers::where('user_id', auth()->id())->where('lottery_id', $this->id)->value('count') ?? 0,
            'prizes' => $this->prizes->map(function ($prize) {
                return [
                    'id'    => $prize->id,
                    'name'  => $prize->name,
                    'image' => $prize->image->path ?? null,
                ];
            }),
        ];
    }
}
