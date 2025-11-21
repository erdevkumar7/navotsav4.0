<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Jobs\FBNotificationJob;
use App\Models\ClaimRequest;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClaimPrizeController extends Controller
{
    public function claimRequests()
    {
        return view('claime.index');
    }

    public function claimData(Request $request)
    {

        $claims = ClaimRequest::with(['event', 'user'])->latest();

        return DataTables::of($claims)
            ->addIndexColumn()
            ->addColumn('event', function ($ticket) {
                return $ticket->event->title ?? 'N/A';
            })
            ->addColumn('user', function ($ticket) {
                return $ticket->user->name ?? 'N/A';
            })
            ->editColumn('status', fn($row) => view('claime.partials.status-switch', compact('row'))->render())
            ->rawColumns(['status'])
            ->editColumn('created_at', function ($ticket) {
                return format_datetime($ticket->created_at);
            })
            ->make(true);
    }

    public function approveRequest($claimId)
    {
        $claimRow = ClaimRequest::find($claimId);
        $isNotify = false;
        $msg = "Updated successfully!";
        if ($claimRow->status == 'pending') {
            $claimRow->status = 'approved';
            $msg = "Approved successfully!";
            $isNotify = true;
        } else {
            $claimRow->status = 'pending';
        }
        $claimRow->save();

        if ($isNotify) {
            if ($claimRow->user_id) {
                $title = "Claim Request Approved";
                $body = "Your prize claim for ticket #{$claimRow->ticket_number} has been approved!
Our team will contact you soon with the next steps.";
                FBNotificationJob::dispatch($title, $body, [$claimRow->user_id]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => $msg
        ]);
    }
}
