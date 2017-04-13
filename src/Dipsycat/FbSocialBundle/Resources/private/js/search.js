$('#search-btn').click(function () {

});
$('#search-text').keyup(function () {
    var url = Routing.generate('dipsycat_fb_social_friend_search');
    var searchText = $(this).val();
    var data = {
        'search_text': searchText
    };
    
    if (searchText.length == 1) {
        $.ajax({
            type: 'get',
            url: url,
            data: data
        })
            .done(function (response) {
                if (response.result == 'success') {
                    var users = response.data;
                    $('datalist#users').empty();
                    for (var item in users) {
                        $('datalist#users').append('<option data-id="' + item + '" value="' + users[item] + '"></option>');
                    }
                }
            });
    }

});
