define(['jquery'], function($) {
    var BeuserFastswitch = {};

    BeuserFastswitch.init = function() {
        $('#beuser-fastswitch-search-mask').on('keyup', function() {
            var search = $('#beuser-fastswitch-search-mask').val();

            if (search.length >= 3) {
                var ajaxUrl = TYPO3.settings.ajaxUrls['beuser_fastswitch_backend_userlookup'];
                ajaxUrl += '&search=' + search;

                $('#beuser-fastswitch-ajax-result').html('');

                $.get(ajaxUrl, {
                    format: 'html'
                }).done(function(data) {
                    $('#beuser-fastswitch-ajax-result').html(data);
                });
            }
        });
    };

    BeuserFastswitch.init();

    return BeuserFastswitch;
});