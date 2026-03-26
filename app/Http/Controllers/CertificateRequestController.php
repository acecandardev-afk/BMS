<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Models\Resident;
use App\Services\ActivityLogService;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateRequestController extends Controller
{
    public function __construct(protected CertificateService $certificateService) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', CertificateRequest::class);

        $query = CertificateRequest::with(['resident', 'requester', 'signatory'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resident', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('resident_id')) {
            $query->where('resident_id', $request->resident_id);
        }

        $requests = $query->paginate(15)->withQueryString();
        $types    = CertificateRequest::TYPES;
        $statuses = CertificateRequest::STATUSES;

        return view('certificate-requests.index', compact('requests', 'types', 'statuses'));
    }

    public function create()
    {
        $this->authorize('create', CertificateRequest::class);
        $resident = Auth::user()->ensureResidentProfile();
        $types    = CertificateRequest::TYPES_RESIDENT_SELF_SERVICE;

        return view('certificate-requests.create', compact('resident', 'types'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', CertificateRequest::class);

        $validated = $request->validate([
            'certificate_type' => [
                'required',
                'in:' . implode(',', array_keys(CertificateRequest::TYPES_RESIDENT_SELF_SERVICE)),
            ],
            'purpose'   => ['required', 'string', 'max:500'],
            'or_number' => ['nullable', 'string', 'max:100'],
        ]);

        $resident = Auth::user()->ensureResidentProfile();

        $certRequest = CertificateRequest::create([
            ...$validated,
            'resident_id'   => $resident->id,
            'requested_by'  => Auth::id(),
            'status'        => CertificateRequest::STATUS_PENDING,
        ]);

        ActivityLogService::logCreate($certRequest, "Certificate request created: {$certRequest->type_name} for {$resident->full_name}");

        return redirect()->route('my.requests')
            ->with('success', 'Your request has been submitted. We will notify you once it is processed.');
    }

    public function show(CertificateRequest $certificateRequest)
    {
        $this->authorize('view', $certificateRequest);
        $certificateRequest->load(['resident', 'requester', 'processor', 'signatory']);
        return view('certificate-requests.show', compact('certificateRequest'));
    }

    public function approve(Request $request, CertificateRequest $certificateRequest)
    {
        $this->authorize('approve', $certificateRequest);

        $request->validate([
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $certificateRequest->update([
            'status'       => CertificateRequest::STATUS_APPROVED,
            'signatory_id' => Auth::id(),
            'approved_at'  => now(),
            'remarks'      => $request->remarks,
        ]);

        ActivityLogService::logApprove($certificateRequest, "Approved certificate request #{$certificateRequest->id}: {$certificateRequest->type_name}");

        return back()->with('success', 'The request has been approved.');
    }

    public function reject(Request $request, CertificateRequest $certificateRequest)
    {
        $this->authorize('reject', $certificateRequest);

        $request->validate([
            'remarks' => ['required', 'string', 'max:500'],
        ]);

        $certificateRequest->update([
            'status'       => CertificateRequest::STATUS_REJECTED,
            'signatory_id' => Auth::id(),
            'rejected_at'  => now(),
            'remarks'      => $request->remarks,
        ]);

        ActivityLogService::logReject($certificateRequest, "Rejected certificate request #{$certificateRequest->id}: {$certificateRequest->type_name}");

        return back()->with('success', 'The request has been declined.');
    }

    public function release(Request $request, CertificateRequest $certificateRequest)
    {
        $this->authorize('release', $certificateRequest);

        $certificateRequest->update([
            'status'       => CertificateRequest::STATUS_RELEASED,
            'processed_by' => Auth::id(),
            'released_at'  => now(),
        ]);

        ActivityLogService::log('release', "Released certificate request #{$certificateRequest->id}: {$certificateRequest->type_name}", $certificateRequest);

        return back()->with('success', 'The certificate has been released to the resident.');
    }

    public function print(CertificateRequest $certificateRequest)
    {
        $this->authorize('print', $certificateRequest);

        $certificateRequest->update(['printed_at' => now()]);

        ActivityLogService::logPrint($certificateRequest, "Printed certificate #{$certificateRequest->id}: {$certificateRequest->type_name}");

        $pdf = $this->certificateService->generate($certificateRequest);

        return $pdf->stream("certificate_{$certificateRequest->id}.pdf");
    }

    public function myRequests(Request $request)
    {
        $resident = Auth::user()->ensureResidentProfile();

        $query = CertificateRequest::where('resident_id', $resident->id)->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(10)->withQueryString();
        $statuses = CertificateRequest::STATUSES;

        return view('certificate-requests.my-requests', compact('requests', 'statuses'));
    }
}