<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = match ($user->role) {
            'admin'     => $this->adminStats(),
            'staff'     => $this->staffStats(),
            'signatory' => $this->signatoryStats(),
            'resident'  => $this->residentStats($user),
            default     => [],
        };

        return view('dashboard', compact('data'));
    }

    private function adminStats(): array
    {
        return [
            'total_residents'   => Resident::count(),
            'pending_requests'  => CertificateRequest::pending()->count(),
            'approved_requests' => CertificateRequest::approved()->count(),
            'fees_collected'    => CertificateRequest::whereIn('status', ['approved', 'shipped', 'on_delivery', 'released'])->sum('fee'),
        ];
    }

    private function staffStats(): array
    {
        return [
            'total_residents'  => Resident::count(),
            'pending_requests' => CertificateRequest::pending()->count(),
            'approved_requests'=> CertificateRequest::approved()->count(),
            'total_requests'   => CertificateRequest::count(),
        ];
    }

    private function signatoryStats(): array
    {
        return [
            'pending_requests'        => CertificateRequest::pending()->count(),
            'approved_requests'       => CertificateRequest::approved()->count(),
            'total_requests'          => CertificateRequest::count(),
        ];
    }

    private function residentStats(User $user): array
    {
        $resident = $user->ensureResidentProfile();

        return [
            'my_pending_requests'  => CertificateRequest::where('resident_id', $resident->id)->pending()->count(),
            'my_total_requests'    => CertificateRequest::where('resident_id', $resident->id)->count(),
            'my_approved_requests' => CertificateRequest::where('resident_id', $resident->id)->approved()->count(),
        ];
    }
}