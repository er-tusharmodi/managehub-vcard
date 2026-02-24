@extends('client.layouts.app')

@section('title', 'Profile Settings')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Profile Settings</h4>
            <p class="text-muted small mb-0">Manage your account information and security</p>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>Profile updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>Password updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="mdi mdi-email-check-outline me-2"></i>Verification email sent.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold"><i class="mdi mdi-account-circle-outline me-2 text-primary"></i>Profile Information</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Update your account's profile information and email address.</p>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label class="form-label">Profile Photo</label>
                            <div class="d-flex align-items-center gap-3">
                                <img
                                    src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : asset('backendtheme/assets/images/users/user-11.jpg') }}"
                                    alt="Profile photo"
                                    class="rounded-circle border"
                                    style="width: 64px; height: 64px; object-fit: cover;"
                                >
                                <div class="flex-grow-1">
                                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                                    <small class="text-muted">JPG, PNG, or WEBP up to 2MB.</small>
                                    @error('profile_photo') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autocomplete="name">
                            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="small text-muted mb-2">Your email address is unverified.</p>
                                    <button form="send-verification" class="btn btn-sm btn-outline-primary" type="submit">
                                        <i class="mdi mdi-email-sync-outline me-1"></i>Resend verification email
                                    </button>
                                </div>
                            @endif
                        </div>

                        <button class="btn btn-primary" type="submit">
                            <i class="mdi mdi-content-save me-1"></i>Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold"><i class="mdi mdi-lock-outline me-2 text-warning"></i>Update Password</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Use a strong password to keep your account secure.</p>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="update_password_current_password" class="form-label">Current Password</label>
                            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                            @error('current_password', 'updatePassword') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="update_password_password" class="form-label">New Password</label>
                            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password">
                            @error('password', 'updatePassword') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
                            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <button class="btn btn-warning" type="submit">
                            <i class="mdi mdi-key-change me-1"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold"><i class="mdi mdi-alert-outline me-2 text-danger"></i>Delete Account</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="mdi mdi-delete-outline me-1"></i>Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Account Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <p class="mb-3">This action cannot be undone. Please enter your password to confirm.</p>
                        <div>
                            <label for="delete_password" class="form-label">Password</label>
                            <input id="delete_password" name="password" type="password" class="form-control" placeholder="Enter password">
                            @error('password', 'userDeletion') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
