@extends('layouts.app')
@section('content')
<section>
    <style>
        .main-container { position: relative;padding: 20px;min-height: 700px; }
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
                                        <p><b>Invoice ID</b> - 1298</p>
                                        <p><b>Order date</b> - Jan 21st, 2017</p>
                                        <p><b>Payment status</b><span class="label label-danger">Due</span></p>
                                    </div>
                                </div>
                            </div>
                            <hr><br>
                            <div class="row">
                                <div class="col-md-12">
                                    {{--@foreach($order_details as $key=>$value)--}}
                                                    <div class="order-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <img src="{{ asset('/images/WSLogo.png') }}" alt="product-image">
                                                    <div class="item-info">
                                                        <p><a>12 Colors Makeup Bright Moisturizing Lip Gloss Lipstick Long Lasting Lip Gloss Cosmetics Longwear Not Fad Magic Lip Gloss #703</a></p>
                                                        <span><p>fafffsasf</p></span>
                                                        <span><p>fafffsasf</p></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="text-right">
                                                        <p><b>Warehouse</b> - 1298</p>
                                                        <p><b>Picked At</b> - Jan 21st, 2017</p>
                                                        <p><b>Packed At</b> - Jan 21st, 2017</p>
                                                        <p><b>Dispached At</b> - Jan 21st, 2017</p>
                                                        <p><b>Status</b> - Jan 21st, 2017</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>
                                        </div><br>
                                        {{--@endforeach--}}
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
