<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\BlotterRecord;
use App\Models\Resident;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Resident::class);

        $query = Resident::with('household')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('zone')) {
            $query->byZone($request->zone);
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'voter'      => $query->voters(),
                'pwd'        => $query->pwd(),
                'deleted'    => $query->onlyTrashed(),
                default      => null,
            };
        }

        $residents  = $query->paginate(15)->withQueryString();
        $households = Household::orderBy('household_number')->get();

        return view('residents.index', compact('residents', 'households'));
    }

    public function create()
    {
        $this->authorize('create', Resident::class);
        $households = Household::orderBy('household_number')->get();
        return view('residents.create', compact('households'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Resident::class);

        $validated = $this->validateResident($request);

        if ($request->hasFile('photo')) {
            $disk = config('filesystems.upload_disk');
            $validated['photo'] = $request->file('photo')->store('residents/photos', $disk);
        }

        $resident = Resident::create($validated);

        ActivityLogService::logCreate($resident, "Created resident: {$resident->full_name}");

        return redirect()->route('residents.show', $resident)
            ->with('success', 'The resident has been added.');
    }

    public function show(Resident $resident)
    {
        $this->authorize('view', $resident);
        $resident->load('household', 'certificateRequests', 'user');
        $blotterRecords = BlotterRecord::where('complainant_id', $resident->id)
            ->orWhere('respondent_id', $resident->id)
            ->latest()
            ->get();

        return view('residents.show', compact('resident', 'blotterRecords'));
    }

    public function edit(Resident $resident)
    {
        $this->authorize('update', $resident);
        $households = Household::orderBy('household_number')->get();
        return view('residents.edit', compact('resident', 'households'));
    }

    public function update(Request $request, Resident $resident)
    {
        $this->authorize('update', $resident);

        $validated  = $this->validateResident($request, $resident->id);
        $oldValues  = $resident->only(array_keys($validated));

        if ($request->hasFile('photo')) {
            $disk = config('filesystems.upload_disk');
            if ($resident->photo) {
                Storage::disk($disk)->delete($resident->photo);
            }
            $validated['photo'] = $request->file('photo')->store('residents/photos', $disk);
        }

        $resident->update($validated);

        ActivityLogService::logUpdate($resident, "Updated resident: {$resident->full_name}", $oldValues);

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Your changes have been saved.');
    }

    public function destroy(Resident $resident)
    {
        $this->authorize('delete', $resident);

        ActivityLogService::logDelete($resident, "Deleted resident: {$resident->full_name}");
        $resident->delete();

        return redirect()->route('residents.index')
            ->with('success', 'The record has been removed.');
    }

    public function restore(int $id)
    {
        $resident = Resident::withTrashed()->findOrFail($id);
        $this->authorize('restore', $resident);

        $resident->restore();

        ActivityLogService::log('restore', "Restored resident: {$resident->full_name}", $resident);

        return redirect()->route('residents.index')
            ->with('success', 'The record has been restored.');
    }

    public function myProfile()
    {
        $resident = Auth::user()->ensureResidentProfile();

        return view('residents.my-profile', compact('resident'));
    }

    public function updateMyProfile(Request $request)
    {
        if ($request->input('email') === '') {
            $request->merge(['email' => null]);
        }

        $resident = Auth::user()->ensureResidentProfile();
        $user     = Auth::user();

        $validated = $request->validate([
            'birthdate'      => ['nullable', 'date', 'before:today'],
            'birthplace'     => ['nullable', 'string', 'max:255'],
            'gender'         => ['nullable', 'in:male,female'],
            'civil_status'   => ['nullable', 'in:single,married,widowed,separated,annulled'],
            'nationality'    => ['nullable', 'string', 'max:100'],
            'zone'           => ['nullable', 'string', 'max:50'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'email'          => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'address'        => ['nullable', 'string', 'max:500'],
            'occupation'     => ['nullable', 'string', 'max:255'],
        ]);

        $oldValues = $resident->only(array_keys($validated));
        $resident->update($validated);

        if (array_key_exists('email', $validated) && filled($validated['email']) && $validated['email'] !== $user->email) {
            $user->update(['email' => $validated['email']]);
        }

        ActivityLogService::logUpdate($resident, "Resident updated own profile: {$resident->full_name}", $oldValues);

        return back()->with('success', 'Your profile has been updated.');
    }

    private function validateResident(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'household_id'   => ['nullable', 'exists:households,id'],
            'first_name'     => ['required', 'string', 'max:100'],
            'middle_name'    => ['nullable', 'string', 'max:100'],
            'last_name'      => ['required', 'string', 'max:100'],
            'suffix'         => ['nullable', 'string', 'max:10'],
            'birthdate'      => ['required', 'date', 'before:today'],
            'birthplace'     => ['nullable', 'string', 'max:255'],
            'gender'         => ['required', 'in:male,female'],
            'civil_status'   => ['required', 'in:single,married,widowed,separated,annulled'],
            'nationality'    => ['nullable', 'string', 'max:100'],
            'religion'       => ['nullable', 'string', 'max:100'],
            'occupation'     => ['nullable', 'string', 'max:255'],
            'zone'           => ['required', 'string', 'max:50'],
            'address'        => ['required', 'string', 'max:500'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'email'          => ['nullable', 'email', 'max:255'],
            'voter_status'   => ['boolean'],
            'is_indigenous'  => ['boolean'],
            'is_pwd'         => ['boolean'],
            'is_solo_parent' => ['boolean'],
            'is_4ps'         => ['boolean'],
            'photo'          => ['nullable', 'image', 'max:2048'],
            'remarks'        => ['nullable', 'string'],
        ]);
    }
}