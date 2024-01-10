<!-- Modal -->
<div class="modal fade" id="dutiesModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="dutiesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header p-2">
        <b class="modal-title" id="dutiesModalLabel">Filter Data</b>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-1">
        <form action="{{ route('authenticate.report.export') }}" autocomplete="off" id="exportForm">
          <div class="form-group">
              <label for="">Type of report</label>
              <select name="type" id="type" class="custom-select custom-select-sm">
                  <option value="dutiesReport">Duties Report</option>
                  <option value="dollarReport">Dollar Report</option>
                  <option value="fundReport">Fund Transfer</option>
                  <option value="projectedCostReport" id="{{ route('authenticate.preview',[':name',':from',':to']) }}">Landed Cost Tabsheet</option>
                  <option value="dollarBook">Dollar Expense Per PARTICULAR</option>
              </select>
          </div>
          <div class="form-group company_details">
              <label for="">Company</label>
              <select name="company_id" class="custom-select custom-select-sm">
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->companyname }}</option>
                    @endforeach
              </select>
          </div>
          <div class="form-group itemName_details">
            <label for="">Item</label>
            <select name="itemName" class="form-control" style="width:100%;height:50%">
            </select>
          </div>
          <div class="input-daterange" id="report-range-modal">
            <div class="form-group">
                <label for="">Date From</label>
                <input type="search" class="form-control form-control-sm" name="from" required>
            </div>
            <div class="form-group">
                <label for="">To</label>
                <input type="search" class="form-control form-control-sm" name="to" required>
            </div>
          </div>
          <div class="form-group">
              <button type="submit" class="btn btn-sm btn-success btn-block"><i class="fas fa-cloud-download-alt"></i> Download Excel File</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>