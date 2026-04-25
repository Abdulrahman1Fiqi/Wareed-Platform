<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodRequest;

class RequestController extends Controller
{
     public function index()
    {
        $requests = BloodRequest::with('hospital')
            ->latest()
            ->paginate(15);

        return view('admin.requests.index', compact('requests'));
    }

    public function show(BloodRequest $bloodRequest)
    {
        $bloodRequest->load('hospital', 'donorResponses.donor');
        return view('admin.requests.show', compact('bloodRequest'));
    }
}
