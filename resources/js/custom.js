$(document).ready(function() {

    /*
     * Bootstrap Custom File Input...
     */

    // set filename in filepicker when user (un)selects a file
    $(document).on('change', 'input[type="file"].custom-file-input', function() {
        var fileName = $(this).val().split('\\').pop();
        if (fileName !== '') {
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        }
        else {
            $(this).siblings('.custom-file-label').removeClass('selected').html('Select a file');
        }
    });

});
