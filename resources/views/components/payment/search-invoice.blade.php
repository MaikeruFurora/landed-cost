<div class="m-4">
    <div class="row mx-2 justify-content-between">
        <div class="col-4">
            Invoice details
        </div>
        <div class="col-4">
            <form action="{{ $action }}" id="{{ $id }}" autocomplete="off">@csrf
                <div class="form-group">
                    <div class="input-group input-group-sm">
                        <input type="search" name="search" class="form-control" placeholder="Search Invoice No.">
                        <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <table  class="table adjust table-bordered table-sm dt-responsive nowrap adjust table-hover" 
            style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:12px" 
            id="invoiceTable"
            data-url="{{ $url }}"
            >
        <thead>
            <tr>
                <th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                <th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                <th>Invoice No.</th>
                <th>Item Description</th>
                <th>QTY</th>
                <th>QTY(KLS)</th>
                <th>QTY(MT)</th>
                <th>FCL</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>