$(document).ready(function () {
    $(document).on('click', '.js-page-navigation__item', function (e) {
        e.preventDefault();

        let page = $(this).data('page');

        $.ajax({
            method: 'GET',
            url: '/public-ajax/users/list/',
            dataType: 'json',
            data: {page: page}
        }).done(function (result) {
            if (result.status) {
                $('.js-users-list').html(result.html);
            } else {
                console.warn(result.errors);
            }
        });
    });
});