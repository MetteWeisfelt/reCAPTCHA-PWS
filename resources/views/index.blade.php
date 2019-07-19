@extends('layouts.app')

@section('navbar')
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('index') }}">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pws.index') }}">PWS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('recaptcha.index') }}">reCAPTCHA</a>
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
            <div class="col">
                Index
            </div>
        </div>
    </div>
@endsection