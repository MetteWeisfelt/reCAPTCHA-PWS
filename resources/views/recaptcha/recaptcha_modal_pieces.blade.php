<div class="container recaptcha-container">
    <p>{!! $subcategory->question !!}</p>
    <div class="row no-gutters">
        <div class="col-4">
            <div>
                <img id="image0" class="recaptcha-image" width="145" height="145"/>
            </div>
        </div>
        <div class="col-4">
            <div>
                <img id="image1" class="recaptcha-image" width="145" height="145"/>
            </div>
        </div>
        <div class="col-4">
            <div>
                <img id="image2" class="recaptcha-image" width="145" height="145"/>
            </div>
        </div>
        <div class="col-4">
            <div>
                <img id="image3" class="recaptcha-image" width="145" height="145"/>
            </div>
        </div>
        <div class="col-4">
            <div>
                <img id="image4" class="recaptcha-image" width="145" height="145"/>
            </div>
        </div>
        <div class="col-4">
            <div>
                <img id="image5" class="recaptcha-image" width="145" height="145"/>
            </div>
        </div>
        <div class="col-4">
            <div>
                <img id="image6" class="recaptcha-image" width="145" height="145"/>
            </div>
        </div>
        <div class="col-4">
            <div>
                <img id="image7" class="recaptcha-image" width="145" height="145"/>
            </div>
        </div>
        <div class="col-4">
            <div>
                <img id="image8" class="recaptcha-image" width="145" height="145"/>
            </div>
        </div>
    </div>
</div>
<script>

    var canvas = document.createElement('canvas'),
        ctx = canvas.getContext('2d'),
        parts = [],
        img = new Image();

    img.onload = split;

    function split() {
        var w = img.width / 3,
            h = img.height / 3;

        for (var i = 0; i < 9; i++) {
            var x = -(i % 3 * w),
                y = -(i < 3 ? 0 : (i < 6 ? h : h * 2));

            canvas.width = w;
            canvas.height = h;
            ctx.drawImage(this, x, y, w*3, h*3);
            parts.push(canvas.toDataURL());

            var image = document.getElementById("image" + i);
            image.src = parts[i];
        }
    }

    img.src = "/storage/images/{{$image->path}}";

</script>
