<!-- Modal -->
<div class="modal fade" id="companyModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header p-2  ">
        <b class="modal-title" id="companyModalLabel">Company List</b>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" name="companyname" placeholder="Company name" aria-label="Company name">
            <input type="hidden" name="comID">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary btn-sm" type="button" name="btnAddCom"><i class="fas fa-plus-circle"></i></button>
              <button class="btn btn-outline-danger btn-sm" type="button" name="btnMinusCom"><i class="fas fa-minus-circle"></i></button>
            </div>
          </div>
          <small class="text-danger err-companyname"></small>
        <table class="table table-bordered table-sm mt-3" style="font-size:12px">
            <thead class="thead-dark">
                <tr>
                    <td>Company Name</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" class="text-center">No data available</td>
                </tr>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>