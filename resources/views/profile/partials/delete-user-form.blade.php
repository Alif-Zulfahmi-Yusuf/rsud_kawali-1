<section class="space-y-6">
    <header>

        <p class="mt-1 text-sm text-muted">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button class="btn btn-danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        {{ __('Delete Account') }}
    </button>

    <div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true"
        x-show="$errors->userDeletion->isNotEmpty()">
        <div class="modal-dialog">
            <form method="post" action="{{ route('profile.destroy') }}" class="modal-content p-4">
                @csrf
                @method('delete')

                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">{{ __('Are you sure you want to delete your account?') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="mt-1 text-sm text-muted">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>

                    <div class="mt-3">
                        <label for="password" class="form-label sr-only">{{ __('Password') }}</label>
                        <input id="password" name="password" type="password" class="form-control"
                            placeholder="{{ __('Password') }}" />
                        @if ($errors->userDeletion->has('password'))
                        <div class="text-danger mt-1">{{ $errors->userDeletion->first('password') }}</div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        x-on:click="$dispatch('close')">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
                </div>
            </form>
        </div>
    </div>
</section>