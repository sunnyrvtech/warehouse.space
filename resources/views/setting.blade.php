@extends('layouts.app')
@section('content')
<section>
    <!-- Nav tabs -->
    <div class="tabs-wrapper">
        <ul class="nav classic-tabs tabs-cyan" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#panel_dev" role="tab" aria-selected="false">Developer Setting</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#panel_api" role="tab" aria-selected="false">Api Setting</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#panel_sync" role="tab" aria-selected="false">Resynchronize Products</a>
            </li>
        </ul>
    </div>
    <!-- Tab panels -->
    <div class="tab-content card">


        <div class="tab-pane fade in show active" id="panel_dev" role="tabpanel">
            <form action="{{ route('warehouse.dev.setting')}}" method="post">
                {{ csrf_field()}}
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="wsdl_url">WSDL URI</label>
                        <input class="form-control{{ $errors->has('wsdl_url') ? ' is-invalid' : '' }}" type="text" value="{{ isset($users->get_dev_setting->wsdl_url)?$users->get_dev_setting->wsdl_url:'' }}" name="wsdl_url" placeholder="WSDL URI">
                        @if ($errors->has('wsdl_url'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('wsdl_url') }}</strong>
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
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="page_size">Page Size</label>
                        <input class="form-control{{ $errors->has('page_size') ? ' is-invalid' : '' }}" type="text" value="{{ isset($users->get_dev_setting->page_size)?$users->get_dev_setting->page_size:'' }}" name="page_size" placeholder="Page Size">
                        @if ($errors->has('page_size'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('page_size') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="Offset">Offset</label>
                        <input class="form-control{{ $errors->has('offset') ? ' is-invalid' : '' }}" type="text" name="offset" value="{{ isset($users->get_dev_setting->offset)?$users->get_dev_setting->offset:'' }}" placeholder="Offset">
                        @if ($errors->has('offset'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('offset') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="save">
                </div>
            </form>
        </div>
        <div class="tab-pane fade" id="panel_api" role="tabpanel">
             <form action="{{ route('warehouse.api.setting')}}" method="post">
                {{ csrf_field()}}
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="material_bulk">MaterialBulk enabled</label>
                        <select class="form-control" name="material_bulk">
                            <option @if(isset($users->get_api_setting->material_bulk) && $users->get_api_setting->material_bulk == 1)selected @endif value="1">Yes</option>
                            <option @if(isset($users->get_api_setting->material_bulk) && $users->get_api_setting->material_bulk == 0)selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="OrderStatus">ChangeOrderStatus enabled</label>
                        <select class="form-control" name="order_status">
                            <option @if(isset($users->get_api_setting->order_status) && $users->get_api_setting->order_status == 1)selected @endif value="1">Yes</option>
                            <option @if(isset($users->get_api_setting->order_status) && $users->get_api_setting->order_status == 0)selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="orderDetail">OrderDetail enabled</label>
                        <select class="form-control" name="order_detail">
                            <option @if(isset($users->get_api_setting->order_detail) && $users->get_api_setting->order_detail == 1)selected @endif value="1">Yes</option>
                            <option @if(isset($users->get_api_setting->order_detail) && $users->get_api_setting->order_detail == 0)selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="completedOrderItems">CompletedOrderItems enabled</label>
                        <select class="form-control" name="order_item_complete">
                            <option @if(isset($users->get_api_setting->order_item_complete) && $users->get_api_setting->order_item_complete == 1)selected @endif value="1">Yes</option>
                            <option @if(isset($users->get_api_setting->order_item_complete) && $users->get_api_setting->order_item_complete == 0)selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="deleteCompletedOrderItems">DeleteCompletedOrderItems enabled</label>
                        <select class="form-control" name="delete_order_item_complete">
                            <option @if(isset($users->get_api_setting->delete_order_item_complete) && $users->get_api_setting->delete_order_item_complete == 1)selected @endif value="1">Yes</option>
                            <option @if(isset($users->get_api_setting->delete_order_item_complete) && $users->get_api_setting->delete_order_item_complete == 0)selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="stockItems">StockItems enabled</label>
                        <select class="form-control" name="stock_item">
                            <option @if(isset($users->get_api_setting->stock_item) && $users->get_api_setting->stock_item == 1)selected @endif value="1">Yes</option>
                            <option @if(isset($users->get_api_setting->stock_item) && $users->get_api_setting->stock_item == 0)selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="deleteStockItems">DeleteStockItems enabled</label>
                        <select class="form-control" name="stock_item_delete">
                            <option @if(isset($users->get_api_setting->stock_item_delete) && $users->get_api_setting->stock_item_delete == 1)selected @endif value="1">Yes</option>
                            <option @if(isset($users->get_api_setting->stock_item_delete) && $users->get_api_setting->stock_item_delete == 0)selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="getShipmentRate">GetShipmentRate enabled</label>
                        <select class="form-control" name="ship_rate">
                            <option @if(isset($users->get_api_setting->ship_rate) && $users->get_api_setting->ship_rate == 1)selected @endif value="1">Yes</option>
                            <option @if(isset($users->get_api_setting->ship_rate) && $users->get_api_setting->ship_rate == 0)selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="shippingWarehouseOptions">ShippingWarehouseOptions enabled</label>
                        <select class="form-control" name="warehouse_option">
                            <option @if(isset($users->get_api_setting->warehouse_option) && $users->get_api_setting->warehouse_option == 1)selected @endif value="1">Yes</option>
                            <option @if(isset($users->get_api_setting->warehouse_option) && $users->get_api_setting->warehouse_option == 1)selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="getOrderTrackingInfo">getOrderTrackingInfo enabled</label>
                        <select class="form-control" name="track_order">
                            <option @if(isset($users->get_api_setting->track_order) && $users->get_api_setting->track_order == 1)selected @endif value="1">Yes</option>
                            <option @if(isset($users->get_api_setting->track_order) && $users->get_api_setting->track_order == 0)selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="getStock">getStock enabled</label>
                        <select class="form-control" name="stock">
                            <option @if(isset($users->get_api_setting->stock) && $users->get_api_setting->stock == 1)selected @endif value="1">Yes</option>
                            <option @if(isset($users->get_api_setting->stock) && $users->get_api_setting->stock == 0)selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="save">
                </div>
            </form>
        </div>

        <div class="tab-pane fade" id="panel_sync" role="tabpanel">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus
                reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione
                porro voluptate odit minima.</p>
        </div>
    </div>
</section>
@endsection
@push('scripts')
@endpush
