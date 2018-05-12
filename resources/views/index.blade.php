<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark default-color fixed-top">
            <div class="container">
                <a class="ws-logo" href="#">
                    <img src="{{ asset('/images/WSLogo.png') }}" alt="logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="javascript:void(0);">Link1
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);">Link2</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);">Link3</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);">Link4</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="main-wrapper-content">
            <div class="container">
                <section>
                    <h1 class="text-center">Warehouse Dashboard</h1>
                    <!-- Nav tabs -->
                    <div class="tabs-wrapper">
                        <ul class="nav classic-tabs tabs-cyan" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#panel_install" role="tab" aria-selected="false">Installation Instruction</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panel_dev" role="tab" aria-selected="false">Developer Setting</a>
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
                        <div class="tab-pane fade in show active" id="panel_install" role="tabpanel">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus
                                reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione
                                porro voluptate odit minima.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus
                                reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione
                                porro voluptate odit minima.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus
                                reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione
                                porro voluptate odit minima.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus
                                reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione
                                porro voluptate odit minima.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus
                                reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione
                                porro voluptate odit minima.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus
                                reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione
                                porro voluptate odit minima.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus
                                reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione
                                porro voluptate odit minima.</p>
                        </div>
                        <div class="tab-pane fade in" id="panel_dev" role="tabpanel">
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
            </div>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
