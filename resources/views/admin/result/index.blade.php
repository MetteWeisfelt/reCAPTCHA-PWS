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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('recaptcha.index') }}">reCAPTCHA</a>
                </li>
            </ul>
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
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('admin.result.index') }}">Result<span class="sr-only">(current)</span></a>
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
        </div>
    </nav>
@endsection

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col">
                <h2>Result</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Category / Count / Selected / Total Selected / Category Chance / Total Chance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($images as $image)
                            <tr>
                                <td><img data-id="{{$image->id}}" class="table-image" style="cursor: pointer" src="/storage/images/{{$image->path}}" height="80" width="80"/></td>
                                <td>
                                    @if (array_key_exists($image->id, $imageResult))
                                        @foreach ($imageResult[$image->id] as $result)
                                            {{$result['categoryName']}} / {{$result['count']}} / {{$result['selected']}} / {{$result['totalSelected']}} / {{number_format($result['categoryPercentage'], 2, '.', ',')}}% / {{number_format($result['totalPercentage'], 2, '.', ',')}}%<br/>
                                        @endforeach
                                    @else
                                        No data available
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalImagePieces" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Image Pieces</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="containerImagePieces" class="modal-body"></div>
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

        $(document).ready(function() {

            let imageId = 0;

            $(document).on("click", "img.table-image", function () {
                imageId = $(this).attr("data-id")

                $.ajax({
                    type: "POST",
                    url: "{{ route("admin.result.image.pieces") }}",
                    cache: false,
                    data: {"_token": "{{ csrf_token() }}", "image": imageId},
                    success: function(data){
                        $("#containerImagePieces").html(data);
                        $("#modalImagePieces").modal();
                    }
                });
            });

            $(document).on("click", ".recaptcha-image", function () {
                $(".recaptcha-image").removeClass("active");
                $(this).toggleClass("active");

                $.ajax({
                    type: "POST",
                    url: "{{ route("admin.result.image.piece.result") }}",
                    cache: false,
                    data: {"_token": "{{ csrf_token() }}", "image": imageId, "x": $(this).attr("data-x"), "y": $(this).attr("data-y")},
                    success: function(data){
                        $("#textPieceResult").html(data);
                    }
                });
            });

        });
    </script>
@endsection
