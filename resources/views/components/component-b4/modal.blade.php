<div class="modal fade" id="{{ $identify }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true" data-focus-on="input:first">
    <div class="modal-dialog {{ $center?'modal-dialog-centered':'' }} modal-{{ $size ?? 'sm' }}">
      <div class="modal-content">
        <div class="modal-header p-2">
          <p class="modal-title" id="modalFreight">{{ $title ?? 'Component Modal' }}</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{ $slot }}
        </div>
        <div class="modal-footer p-1">
          @if ($close)
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
          @endif
          @if ($save)
            <button type="button" class="btn btn-sm btn-primary">Save</button>
          @endif
        </div>
      </div>
    </div>
</div>