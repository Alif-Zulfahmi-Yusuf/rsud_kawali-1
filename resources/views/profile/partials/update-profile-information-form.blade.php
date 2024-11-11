<section>
    <header>
        <p class="mt-1 text-sm text-muted">
            {{ __("Update your account's profile information, email address, and profile image.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-4">
        @csrf
        @method('patch')

        <!-- Grid layout -->
        <div class="row">
            <!-- Profile Image Column -->
            <div class="col-md-4 text-center">
                <!-- Display existing profile image if available -->
                <div class="mb-3">
                    <img src="{{ $user->image ? url('storage/' . $user->image) : url('storage/images/default.png') }}"
                        alt="Profile Image" class="img-thumbnail mb-3" width="150">
                </div>

                <!-- Profile Image Field -->
                <div class="form-group">
                    <label for="image" class="form-label">{{ __('Update Profile Image') }}</label>
                    <input type="file" class="form-control" id="image" name="image">
                    @if($errors->has('image'))
                    <div class="text-danger mt-1">{{ $errors->first('image') }}</div>
                    @endif
                </div>
            </div>

            <!-- Form Fields Column -->
            <div class="col-md-8">
                <!-- Name Field -->
                <div class="form-group mb-3">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}"
                        required autofocus>
                    @if($errors->has('name'))
                    <div class="text-danger mt-1">{{ $errors->first('name') }}</div>
                    @endif
                </div>

                <!-- Email Field -->
                <div class="form-group mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ old('email', $user->email) }}" required>
                    @if($errors->has('email'))
                    <div class="text-danger mt-1">{{ $errors->first('email') }}</div>
                    @endif

                    <!-- Email Verification -->
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-muted">
                            {{ __('Your email address is unverified.') }}
                            <button form="send-verification"
                                class="btn btn-link p-0">{{ __('Click here to re-send the verification email.') }}</button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                        <p class="text-success mt-2">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- NIP Field -->
                <div class="form-group mb-3">
                    <label for="nip" class="form-label">{{ __('NIP') }}</label>
                    <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip', $user->nip) }}">
                    @if($errors->has('nip'))
                    <div class="text-danger mt-1">{{ $errors->first('nip') }}</div>
                    @endif
                </div>

                <!-- Pangkat Field -->
                <div class="form-group mb-3">
                    <label for="pangkat" class="form-label">{{ __('Pangkat') }}</label>
                    <select class="form-control" id="pangkat" name="pangkat_id">
                        <option value="" disabled selected>{{ __('Select Pangkat') }}</option>
                        @foreach ($pangkats as $pangkat)
                        <option value="{{ $pangkat->id }}" {{ $user->pangkat_id == $pangkat->id ? 'selected' : '' }}>
                            {{ $pangkat->name }}
                        </option>
                        @endforeach
                    </select>
                    @if($errors->has('pangkat_id'))
                    <div class="text-danger mt-1">{{ $errors->first('pangkat_id') }}</div>
                    @endif
                </div>

                <!-- Unit Kerja Field -->
                <div class="form-group mb-3">
                    <label for="unit_kerja" class="form-label">{{ __('Unit Kerja') }}</label>
                    <input type="text" class="form-control" id="unit_kerja" name="unit_kerja"
                        value="{{ old('unit_kerja', $user->unit_kerja) }}">
                    @if($errors->has('unit_kerja'))
                    <div class="text-danger mt-1">{{ $errors->first('unit_kerja') }}</div>
                    @endif
                </div>

                <!-- TMT Jabatan Field -->
                <div class="form-group mb-3">
                    <label for="tmt_jabatan" class="form-label">{{ __('TMT Jabatan') }}</label>
                    <input type="date" class="form-control" id="tmt_jabatan" name="tmt_jabatan"
                        value="{{ old('tmt_jabatan', $user->tmt_jabatan) }}">
                    @if($errors->has('tmt_jabatan'))
                    <div class="text-danger mt-1">{{ $errors->first('tmt_jabatan') }}</div>
                    @endif
                </div>

                <!-- Save Button -->
                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

                    @if (session('status') === 'profile-updated')
                    <p class="text-success mb-0">{{ __('Saved.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </form>
</section>