@extends('layouts.app')
@section('content')
<section>
    <div class="card">
        <div class="card-header bg-info text-white">Settings</div>
        <form action="{{ route('warehouse.dev.setting')}}" method="post">
            {{ csrf_field()}}
            <div class="card-body">
                @if(isset($users->get_dev_setting))
                <div class="form-group">
                    <div class="col-md-6">
                        <button type="button" id="sync_product" class="btn btn-outline-success">Synchronize Products</button>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="warehouse_number">Warehouse Number</label>
                        <input class="form-control{{ $errors->has('warehouse_number') ? ' is-invalid' : '' }}" type="text" value="{{ isset($users->get_dev_setting->warehouse_number)?$users->get_dev_setting->warehouse_number:'' }}" name="warehouse_number" placeholder="Warehouse Number">
                        @if ($errors->has('warehouse_number'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('warehouse_number') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="account_key">Warehouse Account Key</label>
                        <input class="form-control{{ $errors->has('account_key') ? ' is-invalid' : '' }}" type="text" value="{{ isset($users->get_dev_setting->account_key)?$users->get_dev_setting->account_key:'' }}" name="account_key" placeholder="Warehouse Account Key">
                        @if ($errors->has('account_key'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('account_key') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="percentage_product">Percent of synchronized products</label>
                        <input class="form-control{{ $errors->has('percentage_product') ? ' is-invalid' : '' }}" type="text" value="{{ isset($users->get_dev_setting->percentage_product)?$users->get_dev_setting->percentage_product:'' }}" name="percentage_product" placeholder="Percent of synchronized products">
                        @if ($errors->has('percentage_product'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('percentage_product') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="col-md-6">
                    <button type="button" id="devSubmit" class="btn btn-outline-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '#devSubmit', function (e) {
            $(this).prop('disabled', true);
            setTimeout(function () {
                $('#loaderOverlay').show();
            }, 1000);
        });
        $(document).on('click', '#sync_product', function (e) {
            $(this).prop('disabled', true);
            var url = "{{ route('warehouse.product.sync') }}";
            window.location.href = url;
            $('#loaderOverlay').show();
        });
    });
</script>
@endpush
