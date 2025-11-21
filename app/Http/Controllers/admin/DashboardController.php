<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ClaimRequest;
use App\Models\ContactLead;
use App\Models\Event;
use App\Models\EventOrder;
use App\Models\RaffleWinner;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $authUser = auth()->user();

        // ================= Admin Details =================
        $admin_details = [
            'organizer_count' => User::where('user_type', 3)
                ->where('status', 'active')
                // ->where('is_verified', 'true')
                ->count(),

            'user_count' => User::where('user_type', 5)
                ->where('status', 'active')
                // ->where('is_verified', 'true')
                ->count(),

            'buyers_count' => Ticket::join('events', 'tickets.event_id', '=', 'events.id')
                ->join('users as buyers', 'tickets.user_id', '=', 'buyers.id')
                ->where('buyers.user_type', 5)
                ->where('buyers.status', 'active')
                // ->where('buyers.is_verified', 'true')
                ->distinct('tickets.user_id')
                ->count('tickets.user_id'),

            'total_earning' => Ticket::where('status', 'paid')
                ->sum('price'),

            'total_tickets' => Ticket::count(),

            'total_winners' => RaffleWinner::count(),

            'total_events' => Event::where('is_publish', true)->where('status', 'active')->count(),

            'active_events' => Event::where('is_publish', true)
                ->where('status', 'active')
                ->where('is_finalized', 'false')
                ->count(),

            'past_events' => Event::where('is_publish', true)
                ->where('status', 'active')
                ->where('is_finalized', 'true')
                ->count(),

            'total_orders' =>  Ticket::select('event_id', 'user_id')
                ->where('status', 'paid')
                ->groupBy('event_id', 'user_id')
                ->get()
                ->count(),

            'total_claims' => ClaimRequest::count(),
            'pending_claims' => ClaimRequest::where('status', 'pending')->count(),
            'approved_claims' => ClaimRequest::where('status', 'approved')->count(),
            'contact_leads' => ContactLead::count(),
        ];

        // ================= Vendor/Organizer Details =================
        $vendor_details = [
            'organizer_count' => 0,
            'user_count' => 0,
            'buyers_count' => 0,
            'total_earning' => 0,
            'total_orders' => 0,
        ];

        // if ($authUser->hasRole('event-organizer')) {

        //     $vendor_details = [
        //         'organizer_count' => 1, // The organizer itself

        //         // Count of all active verified buyers
        //         'user_count' => User::where('user_type', 5)
        //             ->where('status', 'active')
        //             ->where('is_verified', 'true')
        //             ->count(),

        //         // Count of unique buyers for this organizer's events
        //         'organizer_buyers_count' => Ticket::join('events', 'tickets.event_id', '=', 'events.id')
        //             ->join('users as buyers', 'tickets.user_id', '=', 'buyers.id')
        //             ->where('events.created_by', $authUser->id) // Only this organizer's events
        //             ->where('buyers.user_type', 5)
        //             ->where('buyers.status', 'active')
        //             ->where('buyers.is_verified', 'true')
        //             ->distinct('tickets.user_id')
        //             ->count('tickets.user_id'),

        //         // Total earning for this organizer's events
        //         'total_earning' => Ticket::where('status', 'paid')
        //             ->whereHas('event', function ($query) use ($authUser) {
        //                 $query->where('created_by', $authUser->id);
        //             })
        //             ->sum('price'),

        //         // Total orders for this organizer's events
        //         'total_orders' => Ticket::select('event_id', 'user_id')
        //             ->where('status', 'paid')
        //             ->whereHas('event', function ($query) use ($authUser) {
        //                 $query->where('created_by', $authUser->id);
        //             })
        //             ->groupBy('event_id', 'user_id')
        //             ->get()
        //             ->count(),
        //     ];
        // }

        $ticketCount = EventOrder::sum('qty');
        $totalRevenue = EventOrder::sum('amount');

        return view('dashboard', compact('admin_details', 'vendor_details', 'ticketCount', 'totalRevenue'));
    }

    public function contactList()
    {
        return view('contact.index');
    }

    public function contactData()
    {
        $blogs = DB::table('contact_leads')->orderBy('created_at', 'desc');

        return DataTables::of($blogs)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                return view('contact.actions', compact('row'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    //     public function dashboard(Request $request)
    // {
    //     return view('dashboard');
    // }
}
