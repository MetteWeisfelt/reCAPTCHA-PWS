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
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('admin.controlimage.index') }}">Control Images<span class="sr-only">(current)</span></a>
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
                    <h2 class="d-inline-block align-middle">Control Images</h2>
                    <button id="btnImageAdd" class="btn btn-primary float-right">Add</button>
                </div>
                <table id="table_images">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="categoryName" data-sortable="true">Category Name</th>
                            <th data-field="path" data-sortable="true">Path</th>
                            <th data-field="height" data-sortable="true">Height</th>
                            <th data-field="width" data-sortable="true">Width</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalImageStore" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Control Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" id="image_add_form" role="form" method="POST" action="">
                        @csrf
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id">
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="images">Image</label>
                            <div class="custom-file">
                                <label class="custom-file-label" for="images">Select a file</label>
                                <input type="file" id="images" name="images[]" class="custom-file-input" accept="image/*" multiple/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button id="btnAddImage" type="button" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalImagePreview" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="imagePreview" src="" class="w-100"/>
                </div>
                <div class="modal-footer">
                    <button id="btnImageDelete" type="button" class="btn btn-outline-danger">Delete</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var lastClickedImageId = -1;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#table_images').bootstrapTable({
            url: '/api/controlimage',
            pagination: true,
            search: false,
            showRefresh: false,
            showToggle: false,
            showFullscreen: false,
            showColumns: false,
            showPaginationSwitch : false,
            dataField: 'data',
            pageSize: 10,
            pageList: [5, 10, 25, 50, 100, 'All'],
            autoRefreshSilent: true,
            keyEvents: false,
            stickyHeader: true,
            multipleSearch: true,
            showMultiSort: true
        }).on("click-row.bs.table", function (row, element, field) {
            lastClickedImageId = element.id;
            $('#imagePreview').attr('src', '/storage/images/' + element.path);
            $('#modalImagePreview').modal();
        });

        /**
         * Creating and saving a image
         */

        $(document).on('click', '#btnImageAdd', function () {
            var modal = $('#modalImageStore');

            // remove validation errors
            modal.find(':input').removeClass('is-invalid');
            modal.find(':input').find('.invalid-feedback').remove();

            // set form field data
            modal.find('input[name=images]').val('');

            // show modal
            modal.modal();
        });

        $(document).on('click', '#btnAddImage', function () {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.controlimage.store') }}",
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData($("#image_add_form")[0]),
                success: function(data){
                    location.reload();
                },
                error: function (jqXhr) {
                    if (jqXhr.status === 401) {
                        $(location).prop('pathname', '/admin/login');
                    }

                    if (jqXhr.status === 422) {
                        var data = jqXhr.responseJSON;

                        $('#modalImageStore :input').removeClass('is-invalid');
                        $('#modalImageStore').find('.invalid-feedback').remove();

                        $.each(data.errors, function (key, value) {
                            $('#modalImageStore :input[name=' + key + ']')
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

        $(document).on('click', '#btnImageDelete', function () {
            if (confirm("Are you sure you want to delete this item?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('/admin/controlimage') }}" + "/" + lastClickedImageId,
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
