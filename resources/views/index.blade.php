@extends('layouts.app')
@section('content')
<section>
    <div class="card">
        <div class="card-header bg-info text-white">Installation Instructions</div>
        <div class="card-body">
            <div class="instruction-content">
                <p>Ship your products faster and cheaper by storing them closer to your customers.</p>
                <p>Use Warehouse.Space distribution centers to reduce your shipping costs. We pick, pack and dispatch products to your customers with same day delivery in a number of major cities, and next day for many more.</p>
                <p>Sign up at <a href="https://warehouse.space">Warehouse.Space</a> fo free, and get your license key and warehouse number and start shipping to customers world wide at local postal rates.</p>
                <p>Warehouse.Space - Your international order fulfillment partner.</p>
                <p>Use our warehouses in Europe, Asia, Oceania and America to reduce your shipping costs.</p>
                <!--<p>We pick, pack and dispatch products with next day delivery.</p>-->
            </div>
        </div>
        <div class="card-header bg-info text-white">Requirements</div>
        <div class="card-body">
            <div class="instruction-content">
                <p>To help us pick, pack and dispatch the correct products we need your help.Every product in our warehouse has a barcode. If the product doesn’t have one when you ship it to one of our warehouses, then we will add a barcode to the product packaging.This barcode is what we scan to confirm we have the correct product your customer has ordered.</p>
                <p>To help us with this process we need you to ensure that every product you have in your store has a Barcode value defined that matches the barcode on the product. The barcode must be unique per product, including variants.We are unable to receive a product into our warehouse that you have not defined its barcode value.</p>
                <p>Thanks for your assistance in helping us, ship your products perfectly every time.
                    to the left of this text, please take a screen shot of the barcode input section of the product page in shopify.</p>
            </div>
        </div>
        <!--<div class="card-footer">Support:-</div>-->
    </div>
</section>
@endsection
@push('scripts')
@endpush
