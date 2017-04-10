$('form[name="user"] input[name="user[avatar]"]').change(function () {
    previewImage(this);
});
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.downloaded-image').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}