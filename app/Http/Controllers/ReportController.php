<?php

namespace App\Http\Controllers;

use App\Models\BlotterRecord;
use App\Models\CertificateRequest;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $this->authorize('view-reports');

        $data = [
            'total_residents'      => Resident::count(),
            'total_voters'         => Resident::voters()->count(),
            'total_pwd'            => Resident::pwd()->count(),
            'total_requests'       => CertificateRequest::count(),
            'pending_requests'     => CertificateRequest::pending()->count(),
            'approved_requests'    => CertificateRequest::approved()->count(),
            'open_blotter'         => BlotterRecord::open()->count(),
            'resolved_blotter'     => BlotterRecord::resolved()->count(),
            'residents_by_zone'    => $this->residentsByZone(),
            'requests_by_type'     => $this->requestsByType(),
            'requests_by_month'    => $this->requestsByMonth(),
            'blotter_by_type'      => $this->blotterByType(),
        ];

        return view('reports.index', compact('data'));
    }

    public function residents(Request $request)
    {
        $this->authorize('view-reports');

        $data = [
            'by_zone'        => $this->residentsByZone(),
            'by_gender'      => $this->residentsByGender(),
            'by_civil_status'=> $this->residentsByCivilStatus(),
            'by_age_group'   => $this->residentsByAgeGroup(),
            'voters'         => Resident::voters()->count(),
            'pwd'            => Resident::pwd()->count(),
            'indigenous'     => Resident::where('is_indigenous', true)->count(),
            'solo_parent'    => Resident::where('is_solo_parent', true)->count(),
            'fourps'         => Resident::where('is_4ps', true)->count(),
        ];

        return view('reports.residents', compact('data'));
    }

    public function certificates(Request $request)
    {
        $this->authorize('view-reports');

        $year = $request->get('year', now()->year);

        $data = [
            'by_type'          => $this->requestsByType($year),
            'by_status'        => $this->requestsByStatus($year),
            'by_month'         => $this->requestsByMonth($year),
            'avg_turnaround'   => $this->avgTurnaround($year),
            'total'            => CertificateRequest::whereYear('created_at', $year)->count(),
        ];

        return view('reports.certificates', compact('data', 'year'));
    }

    public function blotter(Request $request)
    {
        $this->authorize('view-reports');

        $year = $request->get('year', now()->year);

        $data = [
            'by_type'      => $this->blotterByType($year),
            'by_status'    => $this->blotterByStatus($year),
            'by_month'     => $this->blotterByMonth($year),
            'total'        => BlotterRecord::whereYear('created_at', $year)->count(),
            'resolved'     => BlotterRecord::resolved()->whereYear('created_at', $year)->count(),
            'open'         => BlotterRecord::open()->whereYear('created_at', $year)->count(),
        ];

        return view('reports.blotter', compact('data', 'year'));
    }

    // --- Private Helpers ---

    private function residentsByZone(): array
    {
        return Resident::select('zone', DB::raw('count(*) as total'))
            ->groupBy('zone')
            ->orderBy('zone')
            ->pluck('total', 'zone')
            ->toArray();
    }

    private function residentsByGender(): array
    {
        return Resident::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender')
            ->toArray();
    }

    private function residentsByCivilStatus(): array
    {
        return Resident::select('civil_status', DB::raw('count(*) as total'))
            ->groupBy('civil_status')
            ->pluck('total', 'civil_status')
            ->toArray();
    }

    private function residentsByAgeGroup(): array
    {
        return Resident::whereNotNull('birthdate')
            ->get()
            ->groupBy(function ($resident) {
                $age = $resident->birthdate->age;
                if ($age < 18) {
                    return 'Minor (0-17)';
                }
                if ($age <= 35) {
                    return 'Young Adult (18-35)';
                }
                if ($age <= 59) {
                    return 'Adult (36-59)';
                }
                return 'Senior (60+)';
            })
            ->map(fn ($group) => $group->count())
            ->toArray();
    }

    private function requestsByType(?int $year = null): array
    {
        $query = CertificateRequest::select('certificate_type', DB::raw('count(*) as total'))
            ->groupBy('certificate_type');

        if ($year) $query->whereYear('created_at', $year);

        return $query->pluck('total', 'certificate_type')->toArray();
    }

    private function requestsByStatus(?int $year = null): array
    {
        $query = CertificateRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status');

        if ($year) $query->whereYear('created_at', $year);

        return $query->pluck('total', 'status')->toArray();
    }

    private function requestsByMonth(?int $year = null): array
    {
        $year  = $year ?? now()->year;

        return CertificateRequest::whereYear('created_at', $year)
            ->get()
            ->groupBy(fn ($request) => (int) $request->created_at->format('n'))
            ->map(fn ($group) => $group->count())
            ->sortKeys()
            ->toArray();
    }

    private function avgTurnaround(?int $year = null): ?float
    {
        $query = CertificateRequest::whereNotNull('approved_at');
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        $items = $query->get();
        if ($items->isEmpty()) {
            return null;
        }

        $avgHours = $items->avg(function ($item) {
            return $item->approved_at->diffInHours($item->created_at);
        });

        return round($avgHours, 2);
    }

    private function blotterByType(?int $year = null): array
    {
        $query = BlotterRecord::select('incident_type', DB::raw('count(*) as total'))
            ->groupBy('incident_type');

        if ($year) $query->whereYear('created_at', $year);

        return $query->pluck('total', 'incident_type')->toArray();
    }

    private function blotterByStatus(?int $year = null): array
    {
        $query = BlotterRecord::select('status', DB::raw('count(*) as total'))
            ->groupBy('status');

        if ($year) $query->whereYear('created_at', $year);

        return $query->pluck('total', 'status')->toArray();
    }

    private function blotterByMonth(?int $year = null): array
    {
        $year = $year ?? now()->year;

        return BlotterRecord::whereYear('created_at', $year)
            ->get()
            ->groupBy(fn ($record) => (int) $record->created_at->format('n'))
            ->map(fn ($group) => $group->count())
            ->sortKeys()
            ->toArray();
    }
}