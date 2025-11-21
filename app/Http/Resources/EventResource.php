<?php

namespace App\Http\Resources;

use App\Models\FavouriteEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $wonTicket = DB::table('raffle_winners')->where('event_id', $this->id)->first()?->ticket_number;
        return [

            'id'          => $this->id,
            'visiblity'   => $this->visiblity,
            'contest_no'  => $this->contest_no,
            'title'       => $this->title,
            'is_favourite' => auth('sanctum')->check()
                ? FavouriteEvent::where([
                    'event_id' => $this->id,
                    'user_id'  => auth('sanctum')->id()
                ])->exists()
                : false,

            'description' => $this->description,
            'cause'       => $this->cause,
            'ticket_price' => floatval($this->ticket_price),
            'max_tickets_per_user' => $this->max_tickets_per_user,
            'start_date'  => format_datetime($this->start_date),
            'end_date'    => format_datetime($this->end_date),
            'draw_time'   => Carbon::parse($this->draw_time)->format('M d Y '),
            'rules'       => $this->rules ? asset('storage/' . $this->rules) : null,
            'banners'     => $this->banners->map(fn($b) => asset('storage/' . $b->banner)),
            'total_tickets' => $this->ticket_quantity,
            'remain_tickets' => ($this->ticket_quantity - $this->sold_tickets),
            'multiple_price' => $this->multiple_price,
            'prices' => collect($this->multiplePrices)->map(function ($price) {
                return [
                    "id" => $price->id,
                    "quantity" => $price->quantity,
                    "price" => floatval($price->price)
                ];
            }),
            // organizer info
            'organizer'   => [
                'id'    => $this->creator?->id,
                'name'  => $this->creator?->name,
                'email' => $this->creator?->email,
            ],

            'is_finalize' => $this->is_finalized,
            'winner_type' => $this->winner_type,
            'won_ticket' => $wonTicket,
            'is_claimed' => (auth('sanctum')->check() && !empty($wonTicket))
                ? DB::table('claim_requests')
                ->where('event_id', $this->id)
                ->where('user_id', auth('sanctum')->id())
                ->where('ticket_number', $wonTicket)->exists() : false,
            'created_at'  => $this->created_at->toDateTimeString(),
            'updated_at'  => $this->updated_at->toDateTimeString(),

        ];
    }
}
