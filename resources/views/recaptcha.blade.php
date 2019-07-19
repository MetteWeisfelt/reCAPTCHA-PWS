@extends('layouts.app')

@section('navbar')
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pws.index') }}">PWS</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('recaptcha.index') }}">reCAPTCHA<span class="sr-only">(current)</span></a>
                </li>
            </ul>
            @auth
                @if (auth()->user()->isAdmin())
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.category.index') }}">Categories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.image.index') }}">Images</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.controlimage.index') }}">Control Images</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.result.index') }}">Result</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user mr-2"></i>Account</a>
                            <div class="dropdown-menu" aria-labelledby="navbarUserDropdown">
                                <p class="dropdown-header">{{Auth::user()->name}}</p>
                                <div class="dropdown-item">Profile</div>
                                <div class="dropdown-divider"></div>
                                <form action="{{route('logout')}}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i>Uitloggen</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                @endif
            @endauth
        </div>
    </nav>
@endsection

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-6">
                <p>Tekst</p>
            </div>
            <div class="col-6">
                <button id="btnRecaptcha" class="btn btn-secondary btn-lg">Go reCAPTCHA!</button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRecaptcha" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">reCaptcha</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="containerRecaptcha" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="btnNext" type="button" class="btn btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        var totalSeconds = 0;
        setInterval(setTime, 10);

        function setTime() {
            totalSeconds = ++totalSeconds;
        }

        var emptyImages = {
            0: {
                "timing": 0,
                "selected": false,
                "x": 0,
                "y": 0
            },
            1: {
                "timing": 0,
                "selected": false,
                "x": 1,
                "y": 0
            },
            2: {
                "timing": 0,
                "selected": false,
                "x": 2,
                "y": 0
            },
            3: {
                "timing": 0,
                "selected": false,
                "x": 0,
                "y": 1
            },
            4: {
                "timing": 0,
                "selected": false,
                "x": 1,
                "y": 1
            },
            5: {
                "timing": 0,
                "selected": false,
                "x": 2,
                "y": 1
            },
            6: {
                "timing": 0,
                "selected": false,
                "x": 0,
                "y": 2
            },
            7: {
                "timing": 0,
                "selected": false,
                "x": 1,
                "y": 2
            },
            8: {
                "timing": 0,
                "selected": false,
                "x": 2,
                "y": 2
            },
        };

        var images;

        $(document).ready(function() {

             $(document).on("click", "#btnRecaptcha", function () {
                newRecaptcha();
             });

            $(document).on("click", ".recaptcha-image", function () {
                $(this).toggleClass("active");

                var index = $("img").index(this);
                images[index]["selected"] = true;
                images[index]["timing"] = totalSeconds / 100;
            });

             $(document).on("click", "#btnNext", function () {
                 $.ajax({
                     type: "POST",
                     url: "{{ route("recaptcha.result") }}",
                     cache: false,
                     data: {
                         "_token": "{{ csrf_token() }}",
                         "imageSelections": images
                     },
                     success: function(){
                         newRecaptcha();
                     }
                 });
             });

        });

        function newRecaptcha()
        {
            images = JSON.parse(JSON.stringify(emptyImages));

            $.ajax({
                type: "POST",
                url: "{{ route("recaptcha.get") }}",
                cache: false,
                data: {"_token": "{{ csrf_token() }}"},
                success: function(data){
                    $("#containerRecaptcha").html(data);
                    $("#modalRecaptcha").modal();
                    totalSeconds = 0;
                }
            });
        }

    </script>
@endsection