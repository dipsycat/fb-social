$(document).ready(function(){
    $('.claims tr').click(function(){
        window.location = $(this).data('href');
        return false;
    });
});