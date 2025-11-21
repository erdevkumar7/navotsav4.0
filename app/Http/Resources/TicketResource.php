<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'ticket_number'  => $this->ticket_number,
            'sold_tickets' => $this->sold_tickets,
            'total_price' => $this->total_price,
            'is_finalize' => $this->event->is_finalized,
            'is_winner' => DB::table('raffle_winners')->where('event_id', $this->event->id)->where('ticket_number', $this->ticket_number)->exists(),
            'event'       => $this->event?->title,
            'event_id'    => $this->event?->id,
            'event_banner'       => asset('storage/' . $this->event?->banners[0]->banner),
            'price' => floatval($this->price),
            'package_id' => $this->package_id,
            'payment_method' => $this->payment_method,
            'status'       => $this->status,
            'buyer_phone' => $this->buyer_phone ?: 'N/A',
            'is_claimed' => DB::table('claim_requests')
            ->where('event_id', $this->event->id)
            ->where('user_id', auth('sanctum')->id())
            ->where('ticket_number', $this->ticket_number)->exists(),
            'purchase_at' => format_datetime($this->created_at->format('Y-m-d')),
            'qr_code_url' => !empty($this->qr_code_data) ? 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode(json_encode($this->qr_code_data)) : null,
        ];
    }
}
