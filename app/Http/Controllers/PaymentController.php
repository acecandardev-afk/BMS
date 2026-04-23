<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-reports');

        $query = CertificateRequest::with(['resident'])
            ->whereIn('status', [
                CertificateRequest::STATUS_APPROVED,
                CertificateRequest::STATUS_SHIPPED,
                CertificateRequest::STATUS_ON_DELIVERY,
                CertificateRequest::STATUS_RELEASED,
            ])
            ->whereNotNull('fee')
            ->where('fee', '>', 0)
            ->latest('released_at')
            ->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('or_number', 'like', "%{$search}%")
                    ->orWhereHas('resident', function ($residentQuery) use ($search) {
                        $residentQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function show(CertificateRequest $certificateRequest)
    {
        $this->authorize('view-reports');

        abort_unless(
            in_array($certificateRequest->status, [
                CertificateRequest::STATUS_APPROVED,
                CertificateRequest::STATUS_SHIPPED,
                CertificateRequest::STATUS_ON_DELIVERY,
                CertificateRequest::STATUS_RELEASED,
            ], true)
                && (float) $certificateRequest->fee > 0,
            404
        );

        $certificateRequest->load(['resident', 'requester', 'processor', 'signatory']);

        return view('payments.show', compact('certificateRequest'));
    }
}
