jQuery(document).ready(function($) {
    $(document).on('click', '.generate-pdf', function(event) {
        event.preventDefault(); // Prevent default action

        var button = $(this);
        var postId = button.data('postid');
        var data = {
            action: 'generate_pdf',
            security: pdfGeneratorAjax.security,
            post_id: postId
        };

        $.post(pdfGeneratorAjax.ajaxurl, data, function(response) {
            if (response.success) {
                button.after('<a href="' + response.data.pdf_url + '" class="button download-pdf" target="_blank">Download PDF</a>');
                button.remove();
            } else {
                alert('Error: ' + (response.data.message || 'Unknown error'));
            }
        });
    });
});
