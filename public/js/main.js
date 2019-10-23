$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('.delete').click(function (e) {
        e.preventDefault();

        let $button = $(this),
            link = $button.attr('href');

        if (!$button.hasClass('disabled')) {
            $button.addClass('disabled');

            $.ajax({
                url: link,
                type: 'DELETE',
                success: function(result) {
                    window.location.replace(result);
                },
                error: function(e) {
                    console.error(e);
                    $button.removeClass('disabled');
                }
            });
        }
    });

    // Fix for bootstrap input[type='file']
    $("input[type='file']").on('change', function(){
        let file = $(this).val().split('\\'),
            fileName = file[file.length - 1];

        $(this).next('.custom-file-label').html(fileName);
    });
});
