jQuery(document).ready(function($) {

    $(document).on('click', '.f13-email-ajax', function(e) {
        e.preventDefault();

        var target = '#'+$(this).data('target');
        var url = $(this).data('action');

        var proceed = true;

        if ($(this).data('confirm')) {
            proceed = confirm($(this).data('confirm'));
        }

        if ($(this).data('method')) {
            var method = $(this).data('method');
        } else {
            var method = 'POST';
        }

        if (proceed) {
            $(target).append('<div class="f13-data-loading"></div>');

            var data = {};
            var query = url.split('?').pop();
            query = query.replace(/.*?\?/, "");
            query = query.replace(/_&_/, "_%26_");
            query = query.split('&');
            $.each( query, function( index, value ) {
                if (value.includes('=')) {
                    var split_input = value.split('=');
                    data[split_input[0]] = split_input[1];
                }
            });

            $.ajax({
                type : 'POST',
                url : url,
                data : data,
            }).done(function(data) {
                $(target).html(data);
            }).error(function(data) {
                alert('An error occured.');
                $('#f13-data-loading').remove();
            });
        }
    });

    $(document).on('submit', '.f13-email-ajax-form', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var target = "#"+$(this).data('target');

        var url = $(this).data('action');

        $(target).prepend('<div class="f13-data-loading"></div>')

        $.ajax({
            type : 'POST',
            url : url,
            data : formData,
            processData: false,
            contentType: false,
        }).done(function(data) {
            $(target).html(data);
        }).error(function(data) {
            alert('An error occured.');
            $('.f13-data-loading').remove();
        })
    });

    $(document).on('click', '.f13-email-tabs a', function() {
        $('.f13-email-tabs .selected').removeClass('selected');
        $(this).addClass('selected');
    });
});