$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip()

    $('.delete').click(function (e) {
        e.preventDefault();

        let button = $(this),
            link = button.attr('href');

        if (!button.hasClass('disabled')) {
            button.addClass('disabled');

            let def = $.Deferred();

            $.ajax({
                url: link,
                type: 'DELETE',
                success: function(result) {
                    window.location.replace(result);
                    def.resolve();
                },
                error: function(e) {
                    console.log(e);
                    def.reject();
                }
            });

            def.then(function () {
                button.removeClass('disabled');
            });
        }
    });
});