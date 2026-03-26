<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Resident;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    public function index(Request $request)
    {
        $query = Household::withCount('members')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('household_number', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('zone')) {
            $query->byZone($request->zone);
        }

        $households = $query->paginate(15)->withQueryString();

        return view('households.index', compact('households'));
    }

    public function create()
    {
        $residents = Resident::orderBy('last_name')->get();
        return view('households.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'household_number'  => ['required', 'string', 'max:50', 'unique:households,household_number'],
            'head_resident_id'  => ['nullable', 'exists:residents,id'],
            'zone'              => ['required', 'string', 'max:50'],
            'address'           => ['required', 'string', 'max:500'],
            'remarks'           => ['nullable', 'string'],
        ]);

        $household = Household::create($validated);

        ActivityLogService::logCreate($household, "Created household: {$household->household_number}");

        return redirect()->route('households.show', $household)
            ->with('success', 'The household has been added.');
    }

    public function show(Household $household)
    {
        $household->load('members', 'head');
        return view('households.show', compact('household'));
    }

    public function edit(Household $household)
    {
        $residents = Resident::orderBy('last_name')->get();
        return view('households.edit', compact('household', 'residents'));
    }

    public function update(Request $request, Household $household)
    {
        $validated = $request->validate([
            'household_number'  => ['required', 'string', 'max:50', "unique:households,household_number,{$household->id}"],
            'head_resident_id'  => ['nullable', 'exists:residents,id'],
            'zone'              => ['required', 'string', 'max:50'],
            'address'           => ['required', 'string', 'max:500'],
            'remarks'           => ['nullable', 'string'],
        ]);

        $oldValues = $household->only(array_keys($validated));
        $household->update($validated);

        ActivityLogService::logUpdate($household, "Updated household: {$household->household_number}", $oldValues);

        return redirect()->route('households.show', $household)
            ->with('success', 'Your changes have been saved.');
    }

    public function destroy(Household $household)
    {
        ActivityLogService::logDelete($household, "Deleted household: {$household->household_number}");
        $household->delete();

        return redirect()->route('households.index')
            ->with('success', 'The record has been removed.');
    }
}