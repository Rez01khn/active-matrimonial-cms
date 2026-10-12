<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    /**
     * Member: Consultations Index & Booking Form
     */
    public function index()
    {
        $user = Auth::user();

        // Latest active/scheduled meeting if any
        $upcoming_meeting = Consultation::where('user_id', $user->id)
            ->where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->orderBy('scheduled_at', 'asc')
            ->first();

        // Any pending meeting request (waiting for admin to schedule)
        $pending_request = Consultation::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        // Past / Completed discussion history
        $history = Consultation::where('user_id', $user->id)
            ->where(function ($query) use ($user) {
                $query->whereIn('status', ['completed', 'cancelled'])
                      ->orWhereNotNull('discussion_history');
            })
            ->where('id', '!=', optional($upcoming_meeting)->id)
            ->latest()
            ->get();

        // All user consultations
        $all_consultations = Consultation::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('frontend.member.consultations.index', compact(
            'user',
            'upcoming_meeting',
            'pending_request',
            'history',
            'all_consultations'
        ));
    }

    /**
     * Member: Store new consultation request
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:30',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_slot' => 'required|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        Consultation::create([
            'user_id' => Auth::id(),
            'phone' => $request->phone,
            'preferred_date' => $request->preferred_date,
            'preferred_slot' => $request->preferred_slot,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        flash(translate('Your matchmaker consultation request has been submitted successfully.'))->success();
        return back();
    }

    /**
     * Admin: Listing & management
     */
    public function admin_index(Request $request)
    {
        $status = $request->status;
        $search = $request->search;

        $consultations = Consultation::with('user')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('phone', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($uq) use ($search) {
                          $uq->where('first_name', 'like', "%{$search}%")
                             ->orWhere('last_name', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(15);

        return view('admin.consultations.index', compact('consultations', 'status', 'search'));
    }

    /**
     * Admin: Update meeting schedule or discussion history
     */
    public function admin_update(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,scheduled,completed,cancelled',
            'scheduled_at' => 'nullable|date',
            'meeting_link' => 'nullable|string|max:255',
            'discussion_history' => 'nullable|string',
        ]);

        $consultation->status = $request->status;

        if ($request->filled('scheduled_at')) {
            $consultation->scheduled_at = $request->scheduled_at;
        }

        if ($request->has('meeting_link')) {
            $consultation->meeting_link = $request->meeting_link;
        }

        if ($request->has('discussion_history')) {
            $consultation->discussion_history = $request->discussion_history;
        }

        $consultation->save();

        flash(translate('Consultation updated successfully.'))->success();
        return back();
    }

    /**
     * Admin: Destroy consultation
     */
    public function admin_destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        flash(translate('Consultation deleted successfully.'))->success();
        return back();
    }
}
