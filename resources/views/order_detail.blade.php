@extends('layouts.app')
@section('content')
<section>
    <style>
        .main-container { position: relative;padding: 20px;min-height: 700px; }
        .row.gutter img { max-width: 75px; }
        .invoice p { margin: 0 0 4px; }
        .panel { margin-bottom: 16px;background-color: #fff;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid #c9d9ea; }
        .panel-body { padding: 15px;background: #fff;-webkit-border-radius: 0 0 2px 2px;-moz-border-radius: 0 0 2px 2px; }
        .invoice address.from { border-left: 3px solid #3eb15b;padding: 0 10px 0 20px; }
        .invoice address.to { border-left: 3px solid #3a86c8;padding: 0 10px 0 20px; }
        .order-item { border-top-left-radius: .25rem;border-top-right-radius: .25rem;padding: .75rem 1.25rem;margin-bottom: -1px;background-color: #fff;border: 1px solid rgba(0,0,0,.125); }
    </style>
    <div class="main-container">
        <div class="row gutter">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="invoice">
                    <div class="panel no-margin">
                        <div class="panel-body">
                            <div class="row gutter">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                    <a href="{{ route('dashboard') }}"><img src="{{ asset('/images/WSLogo.png') }}" alt="logo"></a>
                                </div>
                                <div class="col-md-9 col-sm-8 col-xs-12">
                                    <div class="text-right">
                                        <p><b>Invoice ID</b> - 1298</p>
                                        <p><b>Order date</b> - Jan 21st, 2017</p>
                                        <p><b>Payment status</b><span class="label label-danger">Due</span></p>
                                    </div>
                                </div>
                            </div>
                            <hr><br><br>
                            <div class="row gutter">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <address class="from">
                                        <h4><b>Billing Address</b></h4>
                                        <abbr title="email">E-mail:</abbr><a href="mailto:#" data-original-title="" title="">shawn@heroadmin.com</a><br>
                                        <abbr title="Phone">Phone:</abbr> (123) 333-444-555<br>
                                        <abbr title="Fax">Fax:</abbr> (123) 333-444-555
                                    </address>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <address class="to">
                                        <h4><b>Shipping Address</b></h4>
                                        <abbr title="email">E-mail:</abbr><a href="mailto:#" data-original-title="" title="">shawn@new-admin.com</a><br>
                                        <abbr title="Phone">Phone:</abbr> (000) 111-222-555<br>
                                        <abbr title="Fax">Fax:</abbr> (000) 333-444-555
                                    </address>
                                </div>
                            </div><br>
                            <div class="row gutter">
                                <div class="col-md-12">
                                    <!--                                    <div class="table-responsive">
                                                                            <table class="table table-striped table-bordered table-middle">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th style="width:50%">Product Name</th>
                                                                                        <th style="width:10%">Warehouse</th>
                                                                                        <th style="width:10%">Picked At</th>
                                                                                        <th style="width:10%">Packed At</th>
                                                                                        <th style="width:10%">Dispatched At</th>
                                                                                        <th style="width:10%">Status</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($order_details as $key=>$value)
                                                                                    <tr>
                                                                                        <td>{{ $value->product_name }}</td>
                                                                                        <td><b>{{ $value->warehouse }}</b></td>
                                                                                        <td>{{ $value->picked }}</td>
                                                                                        <td>{{ $value->packed }}</td>
                                                                                        <td><b>{{ $value->dispatched }}</b></td>
                                                                                        <td><b>{{ $value->item_status }}</b></td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>-->

                                   <div class="row order-item">
               <div class="col-md-6">
                   <h3>{{ $value->product_name }}</h3>
                
                </div>
               <div class="col-md-6">
                <span>{{ $value->warehouse }}</span>
                
                </div>
            </div>



                                </div>
                            </div>
                            <div class="row gutter">
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                    <p><b>Note:</b> Lorem ipsum dolor sit amet, consectetur adipiscing elit. In varius convallis odio, pharetra maximus nisi imperdiet ut. Aliquam in accumsan velit, sit amet varius maurist libero nunc, mattis a vulputate eu, maximus sed massa.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</section>
@endsection
