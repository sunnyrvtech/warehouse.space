@extends('layouts.app')
@section('content')
<section>
    <div class="card">
        <div class="card-header bg-info text-white">Order Details</div>

    </div>

    <div class="top-bar clearfix">
        <div class="container-fluid">
            <div class="row gutter">
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <h3 class="page-title">Invoice</h3></div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <ul class="tasks pull-right clearfix">
                        <li><a href="tasks.html"><div class="task-num">21</div><p class="task-type">Tasks</p></a></li>
                        <li><a href="tasks.html"><div class="task-num">15</div><p class="task-type">Completed</p></a></li>
                        <li><a href="tasks.html"><div class="task-num">6</div><p class="task-type">Pending</p></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="row gutter">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="invoice">
                    <div class="panel no-margin">
                        <div class="panel-body">
                            <div class="row gutter">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                    <a href="#"><img src="img/logo.png" alt="Bluemoon Logo" class="logo"></a>
                                </div>
                                <div class="col-md-9 col-sm-8 col-xs-12">
                                    <div class="right-text">
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
                            <div class="row gutter">
                                <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                                    <div class="btn-group">
                                        <button type="button" class="btn-lg btn btn-success"><i class="icon-print"></i></button>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                    <div class="btn btn-info btn-lg btn-block">Pay Now</div>
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
