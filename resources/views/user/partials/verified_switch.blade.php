@can('verify organizer ')
    <div class="form-check text-center form-switch form-switch-success form-switch-md">
        <input type="checkbox" class="form-check-input verified-check" data-user-id="{{ $user->id }}"
            {{ $user->is_verified ? 'checked' : '' }}>
    </div>
@else
    <div class="form-check text-center form-switch form-switch-success form-switch-md">
        <input type="checkbox" class="form-check-input" data-user-id="" {{ $user->is_verified ? 'checked' : '' }}
            @disabled(true)>
    </div>
@endcan
