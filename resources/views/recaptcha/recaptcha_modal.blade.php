<div class="container recaptcha-container">
    <p>{!! $category->question !!}</p>
    <div class="row no-gutters">
        @foreach($images as $image)
            <div class="col-4">
                <div>
                    <img class="recaptcha-image" src="/storage/images/{{$image->path}}" width="145" height="145"/>
                </div>
            </div>
        @endforeach
    </div>
</div>
