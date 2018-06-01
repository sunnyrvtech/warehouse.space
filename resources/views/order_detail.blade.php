@extends('layouts.app')
@section('content')
<section>
    <style>
        .main-container { position: relative;min-height: 700px; }
        .row img.logo { max-width: 75px; }
        .invoice p { margin: 0 0 4px; }
        .panel { margin-bottom: 16px;background-color: #fff;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid #c9d9ea; }
        .panel-body { padding: 15px;background: #fff;-webkit-border-radius: 0 0 2px 2px;-moz-border-radius: 0 0 2px 2px; }
        .order-item { border-top-left-radius: .25rem;border-top-right-radius: .25rem;padding: .75rem 1.25rem;margin-bottom: -1px;background-color: #fff;border: 1px solid rgba(0,0,0,.125); }
        .order-item .item-info { display: inline-block;vertical-align: middle;max-width: 70%; }
    </style>
    <div class="main-container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="invoice">
                    <div class="panel no-margin">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                    <a href="{{ route('dashboard') }}"><img class="logo" src="{{ asset('/images/WSLogo.png') }}" alt="logo"></a>
                                </div>
                                <div class="col-md-9 col-sm-8 col-xs-12">
                                    <div class="text-right">
                                        {{--
                                        <p><b>Invoice ID</b> - {{ $order_details->order_id }}</p>
                                        <p><b>Order date</b> - {{ $order_details->order_date }}</p>
                                        <p><b>Payment status</b><span class="label label-danger"> - {{ $order_details->payment_status }}</span></p>
                                        --}}
                                        <p><b>Invoice ID</b> - 465475477</p>
                                        <p><b>Order date</b> - Jan 21st, 2017</p>
                                        <p><b>Payment status</b><span class="label label-danger"> - paid</span></p>
                                        
                                    </div>
                                </div>
                            </div>
                            <hr><br>
                            <div class="row">
                                <div class="col-md-12">
                                    {{--@foreach($order_details->items as $key=>$value)
                                    <div class="order-item">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <!--<img src="{{ asset('/images/WSLogo.png') }}" alt="product-image">-->
                                                <div class="item-info">
                                                    <p><a target="_blank" href="{{ $value->product_link }}">{{ $value->product_name }}</a></p>
                                                    <span><p>{{ $value->variant_title }}</p></span>
                                                    <span><p>{{ $value->sku }}</p></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-right">
                                                    <p><b>Warehouse</b> - {{ $value->warehouse }}</p>
                                                    <p><b>Picked At</b> - {{ $value->picked }}</p>
                                                    <p><b>Packed At</b> - {{ $value->packed }}</p>
                                                    <p><b>Dispached At</b> - {{ $value->dispatched }}</p>
                                                    <p><b>Status</b> - {{ $value->item_status }}</p>
                                                </div>
                                            </div>
                                            <!--<div class="col-md-3"><iframe width="230" height="140" src="https://www.youtube.com/embed/ddzU-rkzKF0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>-->
                                        </div>
                                    </div><br>
                                    @endforeach--}}
                                    <div class="order-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!--<img src="{{ asset('/images/WSLogo.png') }}" alt="product-image">-->
                                                <div class="item-info">
                                                    <p><a target="_blank" href="gdgd">hdh</a></p>
                                                    <span><p>fddhdhfd</p></span>
                                                    <span><p>fdhfdfdhd</p></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-right">
                                                    <p><b>Warehouse</b> - gsgfgfg</p>
                                                    <p><b>Picked At</b> - Jan 21st, 2017</p>
                                                    <p><b>Packed At</b> - Jan 21st, 2017</p>
                                                    <p><b>Dispached At</b> - Jan 21st, 2017</p>
                                                    <p><b>Status</b> - gfdrereret</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3"><iframe width="230" height="140" src="https://www.youtube.com/embed/ddzU-rkzKF0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>
                                        </div>
                                    </div><br>
                                </div>
                            </div>
                            <div class="row">
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
