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
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus
                reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione
                porro voluptate odit minima.</p>
        </div>
        <div class="tab-pane fade" id="panel_api" role="tabpanel">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus
                reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione
                porro voluptate odit minima.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus
                reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione
                porro voluptate odit minima.</p>

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
