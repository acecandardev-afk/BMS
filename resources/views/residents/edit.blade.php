@extends('layouts.app')

@section('title', 'Edit Resident')

@section('content')

<div class="py-4" style="max-width: 56rem;">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('residents.show', $resident) }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg class="w-5 h-5" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="h5 mb-0 bc-page-title">Edit Resident</h2>
            <p class="small text-muted mb-0">{{ $resident->full_name }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('residents.update', $resident) }}"
          enctype="multipart/form-data"
          class="d-flex flex-column gap-4">
        @csrf
        @method('PUT')

        <!-- Personal Information -->
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                Personal Information
            </h3>
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-secondary">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name', $resident->first_name) }}" required
                           class="form-control form-control-sm {{ $errors->has('first_name') ? 'is-invalid' : '' }}">
                    @error('first_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-secondary">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $resident->middle_name) }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-secondary">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name', $resident->last_name) }}" required
                           class="form-control form-control-sm {{ $errors->has('last_name') ? 'is-invalid' : '' }}">
                    @error('last_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-secondary">Suffix</label>
                    <select name="suffix" class="form-select form-select-sm">
                        <option value="">None</option>
                        @foreach(['Jr.', 'Sr.', 'I', 'II', 'III', 'IV', 'V'] as $suffix)
                            <option value="{{ $suffix }}" {{ old('suffix', $resident->suffix) === $suffix ? 'selected' : '' }}>
                                {{ $suffix }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-secondary">Birthdate <span class="text-danger">*</span></label>
                    <input type="date" name="birthdate" value="{{ old('birthdate', $resident->birthdate?->format('Y-m-d')) }}" required
                           class="form-control form-control-sm {{ $errors->has('birthdate') ? 'is-invalid' : '' }}">
                    @error('birthdate')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-secondary">Birthplace</label>
                    <input type="text" name="birthplace" value="{{ old('birthplace', $resident->birthplace) }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-secondary">Gender <span class="text-danger">*</span></label>
                    <select name="gender" required class="form-select form-select-sm {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                        <option value="">Select...</option>
                        <option value="male" {{ old('gender', $resident->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $resident->gender) === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-secondary">Civil Status <span class="text-danger">*</span></label>
                    <select name="civil_status" required class="form-select form-select-sm {{ $errors->has('civil_status') ? 'is-invalid' : '' }}">
                        <option value="">Select...</option>
                        @foreach(['single', 'married', 'widowed', 'separated', 'annulled'] as $status)
                            <option value="{{ $status }}" {{ old('civil_status', $resident->civil_status) === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('civil_status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-secondary">Nationality</label>
                    <input type="text" name="nationality" value="{{ old('nationality', $resident->nationality) }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-secondary">Religion</label>
                    <input type="text" name="religion" value="{{ old('religion', $resident->religion) }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-secondary">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $resident->occupation) }}"
                           class="form-control form-control-sm">
                </div>

            </div>
        </div>

        <!-- Address & Contact -->
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                Address & Contact
            </h3>
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Zone <span class="text-danger">*</span></label>
                    <select name="zone" required class="form-select form-select-sm {{ $errors->has('zone') ? 'is-invalid' : '' }}">
                        <option value="">Select zone...</option>
                        @foreach(range(1, 10) as $zone)
                            <option value="Zone {{ $zone }}" {{ old('zone', $resident->zone) === "Zone $zone" ? 'selected' : '' }}>
                                Zone {{ $zone }}
                            </option>
                        @endforeach
                    </select>
                    @error('zone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Household</label>
                    <select name="household_id" class="form-select form-select-sm">
                        <option value="">No household</option>
                        @foreach($households as $household)
                            <option value="{{ $household->id }}"
                                {{ old('household_id', $resident->household_id) == $household->id ? 'selected' : '' }}>
                                {{ $household->household_number }} — {{ $household->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">Address <span class="text-danger">*</span></label>
                    <input type="text" name="address" value="{{ old('address', $resident->address) }}" required
                           class="form-control form-control-sm {{ $errors->has('address') ? 'is-invalid' : '' }}">
                    @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $resident->contact_number) }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Email</label>
                    <input type="email" name="email" value="{{ old('email', $resident->email) }}"
                           class="form-control form-control-sm">
                </div>

            </div>
        </div>

        <!-- Classifications -->
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                Classifications
            </h3>
            <div class="row g-3">
                @foreach([
                    ['name' => 'voter_status',   'label' => 'Registered Voter'],
                    ['name' => 'is_pwd',         'label' => 'PWD'],
                    ['name' => 'is_indigenous',  'label' => 'Indigenous'],
                    ['name' => 'is_solo_parent', 'label' => 'Solo Parent'],
                    ['name' => 'is_4ps',         'label' => '4Ps Beneficiary'],
                ] as $item)
                <div class="col-6 col-sm-4 col-lg-2">
                    <div class="form-check">
                        <input type="checkbox" name="{{ $item['name'] }}" value="1" id="chk_{{ $item['name'] }}"
                               {{ old($item['name'], $resident->{$item['name']}) ? 'checked' : '' }}
                               class="form-check-input">
                        <label for="chk_{{ $item['name'] }}" class="form-check-label small text-secondary">{{ $item['label'] }}</label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Photo & Remarks -->
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                Photo & Remarks
            </h3>
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Photo</label>
                    @if($resident->photo)
                        <div class="mb-2">
                        <img src="{{ $resident->photo_url }}"
                             class="rounded object-fit-cover border" style="width: 5rem; height: 5rem;">
                    </div>
                    @endif
                    <input type="file" name="photo" accept="image/*" class="form-control form-control-sm">
                    @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Remarks</label>
                    <textarea name="remarks" rows="3" class="form-control form-control-sm">{{ old('remarks', $resident->remarks) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-bc-primary btn-sm rounded-pill">
                Save Changes
            </button>
            <a href="{{ route('residents.show', $resident) }}" class="btn btn-outline-secondary btn-sm">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
