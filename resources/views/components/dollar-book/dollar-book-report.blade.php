<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header p-1">
          <p class="modal-title" id="staticBackdropLabel">Report</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="contractReport" action="{{ route("authenticate.contract.report",[':start',':end']) }}">
                <div class="form-group">
                    <label>Date Range</label>
                    <div>
                        <div class="input-daterange input-group" id="date-range">
                            <input type="text" class="form-control" name="start" placeholder="Start Date" />
                            <input type="text" class="form-control" name="end" placeholder="End Date" />
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-block btn-sm btn-primary">Submit</button>
            </form>
        </div>
      </div>
    </div>
</div>