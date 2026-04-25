<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\HospitalApproved;
use App\Mail\HospitalRejected;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $hospitals = Hospital::when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);

        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function show(Hospital $hospital)
    {
        $hospital->load('bloodRequests');
        return view('admin.hospitals.show', compact('hospital'));
    }

    public function approve(Hospital $hospital)
    {
        $hospital->update([
            'status'      => 'approved',
            'approved_by' => auth()->user()->id,
            'approved_at' => now(),
        ]);

        Mail::to($hospital->email)->send(new HospitalApproved($hospital));

        return back()->with('success', $hospital->name . ' has been approved.');
    }

    public function reject(Hospital $hospital)
    {
        $hospital->update(['status' => 'rejected']);

        Mail::to($hospital->email)->send(new HospitalRejected($hospital));

        return back()->with('success', $hospital->name . ' has been rejected.');
    }
}