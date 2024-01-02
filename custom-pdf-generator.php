<?php
/*
Plugin Name: WP to PDF Generator
Description: Generate PDFs for posts with custom headers and footers.
Version: 2.0.0
Author: Chris Hurst
Author URI: https://iamchrishurst.com
*/

require_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php');

add_filter('manage_posts_columns', 'wp2pdf_add_pdf_column');
add_action('manage_posts_custom_column', 'wp2pdf_pdf_column_content', 10, 2);
add_action('admin_enqueue_scripts', 'wp2pdf_enqueue_pdf_generator_scripts');
add_action('wp_ajax_generate_pdf', 'wp2pdf_handle_pdf_generation');

function wp2pdf_add_pdf_column($columns) {
    $columns['pdf_generate'] = 'PDF';
    return $columns;
}

function wp2pdf_pdf_column_content($column_name, $post_id) {
    if ('pdf_generate' === $column_name && current_user_can('administrator')) {
        echo '<button class="button generate-pdf" data-postid="' . $post_id . '">Create PDF</button>';
    }
}

function wp2pdf_enqueue_pdf_generator_scripts() {
    wp_enqueue_script('pdf-generator-script', plugin_dir_url(__FILE__) . 'pdf-generator.js', array('jquery'), '2.0.3', true);
    wp_localize_script('pdf-generator-script', 'pdfGeneratorAjax', array(
        'ajaxurl'  => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('pdf-generate-nonce')
    ));
}

function wp2pdf_handle_pdf_generation() {
    check_ajax_referer('pdf-generate-nonce', 'security');

    $post_id = $_POST['post_id'];
    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error('Invalid post ID');
        return;
    }

    $pdf_filename = wp2pdf_generate_pdf($post);
    $pdf_url = plugin_dir_url(__FILE__) . 'pdfs/' . $pdf_filename;
    wp_send_json_success(['message' => 'PDF generated', 'pdf_url' => $pdf_url]);
}

function wp2pdf_generate_pdf($post) {
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->SetTitle($post->post_title);

    // Load and apply external CSS
    $stylesheet = file_get_contents(plugin_dir_url(__FILE__) . 'pdf-style.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    // Existing code to generate PDF content
    wp2pdf_add_pdf_content($mpdf, $post);

    $pdf_filename = sanitize_title($post->post_title) . '.pdf';
    $pdf_path = plugin_dir_path(__FILE__) . 'pdfs/' . $pdf_filename;

    $mpdf->Output($pdf_path, \Mpdf\Output\Destination::FILE);
    return $pdf_filename;
}

function wp2pdf_add_pdf_content($mpdf, $post) {
    $mpdf->AddPage();
    wp2pdf_add_pdf_header_footer($mpdf, $post);

    // Fetch ACF fields
    $objective  = get_field('objective_field', $post->ID);
    $method     = get_field('method_field', $post->ID);
    $result     = get_field('result_field', $post->ID);
    $conclusion = get_field('conclusion_field', $post->ID);

    // ACF Fields HTML
    $acfHTML = '<div style="margin-bottom: 20px;">';
    $acfHTML .= '<div class="abstract-title-heading"><h3>ABSTRACT</h3></div>';
    $acfHTML .= '<div style="background: #f2f3f1; padding: 10pt;"><h3>Objective</h3><hr><p>' . $objective . '</p>';
    $acfHTML .= '<h3>Method</h3><hr><p>' . $method . '</p>';
    $acfHTML .= '<h3>Result</h3><hr><p>' . $result . '</p>';
    $acfHTML .= '<h3>Conclusion</h3><hr><p>' . $conclusion . '</p>';
    $acfHTML .= '</div></div>';

    // Write ACF Fields to PDF
    $mpdf->WriteHTML($acfHTML);

    // Main Post Content
    $content = apply_filters('the_content', $post->post_content);
    $mpdf->WriteHTML($content);
}

function wp2pdf_add_pdf_header_footer($mpdf, $post) {
    $logo_url = plugin_dir_url(__FILE__) . 'images/ecm-logo.png';
    $headerHTML = '<div style="text-align: center; margin-bottom: 20px;">';
    $headerHTML .= '<img src="' . $logo_url . '" width="240" height="50" />';
    $headerHTML .= '<div><h1>' . $post->post_title . '</h1></div>';
    $headerHTML .= '<hr style="border: 4px solid ##cd5441;"></div>';

    $mpdf->WriteHTML($headerHTML);
    $mpdf->SetHTMLFooter('<div style="text-align: center; font-size: 10pt; margin-top: 15pt !important;">AJEM. Some Legal Jargon. 2023.</div>');
}
