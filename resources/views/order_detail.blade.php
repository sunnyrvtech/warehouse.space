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
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-middle">
                                            <thead>
                                                <tr>
                                                    <th style="width:10%">Sl.No.</th>
                                                    <th style="width:20%">Product Name</th>
                                                    <th style="width:40%">Description</th>
                                                    <th style="width:10%">Quantity</th>
                                                    <th style="width:10%">VAT</th>
                                                    <th style="width:10%">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><b>#001</b></td>
                                                    <td>Macbook Pro</td>
                                                    <td><b>Best Selling Admin Dashboard</b></td>
                                                    <td><span class="btn btn-info btn-xs">18</span></td>
                                                    <td>14.50%</td>
                                                    <td><b>50.00$</b></td>
                                                </tr>
                                                <tr>
                                                    <td><b>#002</b></td>
                                                    <td>Playstation</td>
                                                    <td><b>Top selling admin template</b></td>
                                                    <td><span class="btn btn-danger btn-xs">21</span></td>
                                                    <td>14.50%</td>
                                                    <td><b>7130.00$</b></td>
                                                </tr>
                                                <tr>
                                                    <td><b>#003</b></td>
                                                    <td>Canon D700</td>
                                                    <td><b>Best Dashboard Design</b></td>
                                                    <td><span class="btn btn-success btn-xs">14</span></td>
                                                    <td>14.50%</td>
                                                    <td><b>9220.00$</b></td>
                                                </tr>
                                                <tr>
                                                    <td><b>#004</b></td>
                                                    <td>iPhone 7</td>
                                                    <td><b>Horizontal Menu Admin Dashboard</b></td>
                                                    <td><span class="btn btn-info btn-xs">34</span></td>
                                                    <td>14.50%</td>
                                                    <td><b>11220.00$</b></td>
                                                </tr>
                                                <tr>
                                                    <td><b>#005</b></td>
                                                    <td>Harley Davidson</td>
                                                    <td><b>Colorful Dashboard Design</b></td>
                                                    <td><span class="btn btn-warning btn-xs">77</span></td>
                                                    <td>14.50%</td>
                                                    <td><b>3450.00$</b></td>
                                                </tr>
                                                <tr>
                                                    <td class="total" colspan="5">Subtotal</td>
                                                    <td><b>38000.00$</b></td>
                                                </tr>
                                                <tr>
                                                    <td class="total" colspan="5">Tax (10%)</td>
                                                    <td><b>4100.00$</b></td>
                                                </tr>
                                                <tr>
                                                    <td class="total" colspan="5">Discount</td>
                                                    <td><b>15%</b></td>
                                                </tr>
                                                <tr>
                                                    <td class="total" colspan="5">Total</td>
                                                    <td><h3><b>$42,000</b></h3></td>
                                                </tr>
                                            </tbody>
                                        </table>
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
