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
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('admin.category.index') }}">Categories<span class="sr-only">(current)</span></a>
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
        </div>
    </nav>
@endsection

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col">
                <div class="clear-fix">
                    <h2 class="d-inline-block align-middle">Categories</h2>
                    <button id="btnCategoryAdd" class="btn btn-primary float-right">Add</button>
                </div>
                <div class="container">
                @foreach ($categories as $category)
                    <div class="row" data-id="{{$category->id}}">
                        <div class="col-auto">
                            <a class="text-primary" data-toggle="collapse" href="#collapse{{ $loop->index }}" role="button" aria-expanded="false" aria-controls="collapse{{ $category->id }}">
                                <i class="fas fa-grip-lines" style="font-size: 22px"></i>
                            </a>
                        </div>
                        <div class="col">
                            {{ $category->name }} - {!! $category->question !!}
                        </div>
                        <div class="col-auto ml-auto">
                            <button id="btnCategoryAddSubcategory" class="btn btn-link"><i class="fas fa-plus text-primary"></i></button>
                            <button id="btnCategoryEdit" class="btn btn-link"><i class="fas fa-tools text-secondary"></i></button>
                            <button id="btnCategoryDelete" class="btn btn-link"><i class="fas fa-trash-alt text-danger"></i></button>
                        </div>
                    </div>
                    <div class="row collapse" id="collapse{{ $loop->index }}">
                        <div class="container">
                        @foreach ($category->subcategories as $subcategory)
                            <div class="row" data-id="{{$subcategory->id}}">
                                <div class="col" style="margin-left: 70px">
                                    {{ $subcategory->name }} - {!! $subcategory->question !!}
                                </div>
                                <div class="col-auto ml-auto">
                                    <button id="btnSubcategoryEdit" class="btn btn-link"><i class="fas fa-tools text-secondary"></i></button>
                                    <button id="btnSubcategoryDelete" class="btn btn-link"><i class="fas fa-trash-alt text-danger"></i></button>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCategoryStore" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" id="category_add_form" role="form" method="POST" action="">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Typ the name ...">
                        </div>
                        <div class="form-group">
                            <label for="question">Question</label>
                            <input type="text" class="form-control" id="question" name="question" placeholder="Typ the question ...">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button id="btnAddCategory" type="button" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCategoryUpdate" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" id="category_edit_form" role="form" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <input type="hidden" class="form-control" id="id" name="id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Typ the name ...">
                        </div>
                        <div class="form-group">
                            <label for="question">Question</label>
                            <input type="text" class="form-control" id="question" name="question" placeholder="Typ the question ...">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button id="btnSaveCategory" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSubcategoryStore" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Subcategory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" id="subcategory_add_form" role="form" method="POST" action="">
                        @csrf
                        <input type="hidden" class="form-control" id="category_id" name="category_id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Typ the name ...">
                        </div>
                        <div class="form-group">
                            <label for="question">Question</label>
                            <input type="text" class="form-control" id="question" name="question" placeholder="Typ the question ...">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button id="btnAddSubcategory" type="button" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSubcategoryUpdate" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" id="subcategory_edit_form" role="form" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <input type="hidden" class="form-control" id="id" name="id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Typ the name ...">
                        </div>
                        <div class="form-group">
                            <label for="question">Question</label>
                            <input type="text" class="form-control" id="question" name="question" placeholder="Typ the question ...">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button id="btnSaveSubcategory" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        /**
         * Creating and saving a category
         */

        $(document).on('click', '#btnCategoryAdd', function () {
            var modal = $('#modalCategoryStore');

            // remove validation errors
            modal.find(':input').removeClass('is-invalid');
            modal.find(':input').find('.invalid-feedback').remove();

            // set form field data
            modal.find('input[name=name]').val('');
            modal.find('input[name=question]').val('');

            // show modal
            modal.modal();
        });

        $(document).on('click', '#btnAddCategory', function () {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.category.store') }}",
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData($("#category_add_form")[0]),
                success: function(data){
                    location.reload();
                },
                error: function (jqXhr) {
                    if (jqXhr.status === 401) {
                        $(location).prop('pathname', '/admin/login');
                    }

                    if (jqXhr.status === 422) {
                        var data = jqXhr.responseJSON;

                        $('#modalCategoryStore :input').removeClass('is-invalid');
                        $('#modalCategoryStore').find('.invalid-feedback').remove();

                        $.each(data.errors, function (key, value) {
                            $('#modalCategoryStore :input[name=' + key + ']')
                                .addClass('is-invalid')
                                .after('<div class="invalid-feedback">' + value[0] + '</div>');
                        });
                    }
                }
            });
        });

        /**
         * Creating and saving a subcategory
         */

        $(document).on('click', '#btnCategoryAddSubcategory', function () {
            var modal = $('#modalSubcategoryStore');

            // get the id of the category
            var id = $(this).parents('.row').attr('data-id');

            // remove validation errors
            modal.find(':input').removeClass('is-invalid');
            modal.find(':input').find('.invalid-feedback').remove();

            // set form field data
            modal.find('input[name=category_id]').val(id);
            modal.find('input[name=name]').val('');
            modal.find('input[name=question]').val('');

            // show modal
            modal.modal();
        });

        $(document).on('click', '#btnAddSubcategory', function () {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.subcategory.store') }}",
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData($("#subcategory_add_form")[0]),
                success: function(data){
                    location.reload();
                },
                error: function (jqXhr) {
                    if (jqXhr.status === 401) {
                        $(location).prop('pathname', '/admin/login');
                    }

                    if (jqXhr.status === 422) {
                        var data = jqXhr.responseJSON;

                        $('#modalSubcategoryStore :input').removeClass('is-invalid');
                        $('#modalSubcategoryStore').find('.invalid-feedback').remove();

                        $.each(data.errors, function (key, value) {
                            $('#modalSubcategoryStore :input[name=' + key + ']')
                                .addClass('is-invalid')
                                .after('<div class="invalid-feedback">' + value[0] + '</div>');
                        });
                    }
                }
            });
        });

        /**
         * Editing and saving a category
         */

        $(document).on('click', '#btnCategoryEdit', function () {
            // get the id of the category
            var id = $(this).parents('.row').attr('data-id');

            // retrieve form fields data
            $.ajax({
                type: "POST",
                url: "{{ route('admin.category.data') }}",
                cache: false,
                data: {"_token": "{{ csrf_token() }}", category_id: id},
                success: function(data){
                    var modal = $('#modalCategoryUpdate');

                    // remove validation errors
                    modal.find(':input').removeClass('is-invalid');
                    modal.find(':input').find('.invalid-feedback').remove();

                    // set form field data
                    modal.find('input[name=id]').val(id);
                    modal.find('input[name=name]').val(data.name);
                    modal.find('input[name=question]').val(data.question);

                    // show modal
                    modal.modal();
                }
            });
        });

        $(document).on('click', '#btnSaveCategory', function () {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.category.update', -1) }}",
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData($("#category_edit_form")[0]),
                success: function(data){
                    location.reload();
                },
                error: function (jqXhr) {
                    if (jqXhr.status === 401) {
                        $(location).prop('pathname', '/admin/login');
                    }

                    if (jqXhr.status === 422) {
                        var data = jqXhr.responseJSON;

                        $('#modalCategoryUpdate :input').removeClass('is-invalid');
                        $('#modalCategoryUpdate').find('.invalid-feedback').remove();

                        $.each(data.errors, function (key, value) {
                            $('#modalCategoryUpdate :input[name=' + key + ']')
                                .addClass('is-invalid')
                                .after('<div class="invalid-feedback">' + value[0] + '</div>');
                        });
                    }
                }
            });
        });

        /**
         * Editing and saving a subcategory
         */

        $(document).on('click', '#btnSubcategoryEdit', function () {
            // get the id of the subcategory
            var id = $(this).parents('.row').attr('data-id');

            // retrieve form fields data
            $.ajax({
                type: "POST",
                url: "{{ route('admin.subcategory.data') }}",
                cache: false,
                data: {"_token": "{{ csrf_token() }}", subcategory_id: id},
                success: function(data){
                    var modal = $('#modalSubcategoryUpdate');

                    // remove validation errors
                    modal.find(':input').removeClass('is-invalid');
                    modal.find(':input').find('.invalid-feedback').remove();

                    // set form field data
                    modal.find('input[name=id]').val(id);
                    modal.find('input[name=name]').val(data.name);
                    modal.find('input[name=question]').val(data.question);

                    // show modal
                    modal.modal();
                }
            });
        });

        $(document).on('click', '#btnSaveSubcategory', function () {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.subcategory.update', -1) }}",
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData($("#subcategory_edit_form")[0]),
                success: function(data){
                    location.reload();
                },
                error: function (jqXhr) {
                    if (jqXhr.status === 401) {
                        $(location).prop('pathname', '/admin/login');
                    }

                    if (jqXhr.status === 422) {
                        var data = jqXhr.responseJSON;

                        $('#modalSubcategoryUpdate :input').removeClass('is-invalid');
                        $('#modalSubcategoryUpdate').find('.invalid-feedback').remove();

                        $.each(data.errors, function (key, value) {
                            $('#modalSubcategoryUpdate :input[name=' + key + ']')
                                .addClass('is-invalid')
                                .after('<div class="invalid-feedback">' + value[0] + '</div>');
                        });
                    }
                }
            });
        });

        /**
         * Deleting a category
         */

        $(document).on('click', '#btnCategoryDelete', function () {
            if (confirm("Are you sure you want to delete this item?")) {
                // get the id of the category
                var id = $(this).parents('.row').attr('data-id');

                $.ajax({
                    type: "POST",
                    url: "{{ url('/admin/category') }}" + "/" +id,
                    data: {
                        _token: '{{csrf_token()}}',
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            }
        });

        /**
         * Deleting a subcategory
         */

        $(document).on('click', '#btnSubcategoryDelete', function () {
            if (confirm("Are you sure you want to delete this item?")) {
                // get the id of the category
                var id = $(this).parents('.row').attr('data-id');

                $.ajax({
                    type: "POST",
                    url: "{{ url('/admin/subcategory') }}" + "/" +id,
                    data: {
                        _token: '{{csrf_token()}}',
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            }
        });

    });
</script>
@endsection
