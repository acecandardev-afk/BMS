<?php

namespace App\Http\Controllers;

use App\Models\BlotterRecord;
use App\Models\CertificateRequest;
use App\Models\ChatMessage;
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

        $unreadMessages = ChatMessage::query()
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('dashboard', compact('data', 'unreadMessages'));
    }

    private function adminStats(): array
    {
        return [
            'total_residents'   => Resident::count(),
            'pending_requests'  => CertificateRequest::pending()->count(),
            'approved_requests' => CertificateRequest::approved()->count(),
            'open_blotter'      => BlotterRecord::open()->count(),
        ];
    }

    private function staffStats(): array
    {
        return [
            'total_residents'         => Resident::count(),
            'pending_requests'        => CertificateRequest::pending()->count(),
            'approved_requests'       => CertificateRequest::approved()->count(),
            'total_requests'          => CertificateRequest::count(),
            'open_blotter'            => BlotterRecord::open()->count(),
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