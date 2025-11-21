@can('suspend organizer')
    <div class="form-check text-center form-switch form-switch-danger form-switch-md">
        <input type="checkbox" class="form-check-input suspend-check" data-user-id="{{ $user->id }}"
            {{ $user->status == 'suspended' ? 'checked' : '' }}>
    </div>
@else
    <div class="form-check text-center form-switch form-switch-danger form-switch-md">
        <input type="checkbox" class="form-check-input " data-user-id="" {{ $user->status == 'suspended' ? 'checked' : '' }}
            @disabled(true)>
    </div>
@endcan
