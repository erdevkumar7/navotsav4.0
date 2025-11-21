  <div class="form-check text-center form-switch form-switch-success form-switch-md">
      <input type="checkbox" class="form-check-input verified-check" data-claim-id="{{ $row->id }}"
          @checked($row->status == 'approved')>
  </div>
