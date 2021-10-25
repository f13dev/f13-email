jQuery(document).ready(function($) {

    $("#f13-email-form-fields").sortable({
        opacity: 0.7,
        items: '> .f13-email-form-field',
        axis: 'y',
    });

    $(document).on('click', '#f13_email_smtp_enable', function() {
        if ($(this).is(':checked')) {
            $('.f13_email_smtp_setting').each(function() {
                $(this).show()
            });
        } else {
            $('.f13_email_smtp_setting').each(function() {
                $(this).hide()
            });
        }
    });

    $(document).on('click', '.f13-email-add-field', function() {
        $( ".f13-email-form-field" ).first().clone()
            .find("input:text").val("").end()
            .find(".f13-email-form-field-type").val("checkbox").end()
            .find(".f13-email-form-required").val("0").end()
            .find(".f13-email-form-field-options").hide().end()
        .appendTo( ".f13-email-form-fields" );

        $( "#f13-email-form-fields" ).sortable({
            items: '> .f13-email-form-field'
        });

    });

    $(document).on('click', '.f13-email-remove-field', function() {
        var i = $('.f13-email-form-field').length;
        if (i > 1) {
            $(this).closest('.f13-email-form-field').remove();
        }
    });

    $(document).on('change', '.f13-email-form-field-type', function() {
        var type = $(this).find(":selected").val();
        if (type == 'radio' || type == 'dropdown') {
            $(this).parent().parent().find('.f13-email-form-field-options').show();
        } else {
            $(this).parent().parent().find('.f13-email-form-field-options').hide();
        }
    });
});