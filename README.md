# WP to PDF Generator

## Description
The WP to PDF Generator plugin allows administrators to generate PDFs for WordPress posts. This plugin includes custom headers and footers and supports Advanced Custom Fields (ACF) Pro fields for enhanced PDF content customization.

## Features
- **PDF Column in Admin Posts List**: Adds a 'PDF' column to the posts management screen, enabling administrators to generate PDFs for individual posts.
- **Custom Headers and Footers**: Includes a custom header with a logo and post title, along with a footer containing legal text.
- **ACF Pro Fields Support**: Integrates ACF Pro fields in the PDF, including Objective, Method, Result, and Conclusion fields.
- **External CSS Styling**: Allows for the customization of PDF appearance using an external CSS file.
- **AJAX-based PDF Generation**: Utilizes AJAX for seamless PDF generation without reloading the page.

## Installation
1. Upload the plugin files to the `/wp-content/plugins/wp-to-pdf-generator` directory, or install the plugin through the WordPress plugins screen.
2. Activate the plugin through the 'Plugins' screen in WordPress.

## Usage
- Navigate to the 'Posts' section in the WordPress admin area.
- Click the 'Create PDF' button in the PDF column corresponding to the desired post.
- The plugin generates a PDF of the post, including the specified ACF fields and custom styles.

## Development Setup
1. Ensure you have `composer` installed.
2. Run `composer install` to install the required packages.
3. Add the necessary ACF Pro fields (Objective, Method, Result, Conclusion) to your WordPress setup.

## Customization
- Edit `pdf-style.css` to modify the styles applied to the PDF content.
- Modify the `wp2pdf_add_pdf_header_footer` function in `custom-pdf-generator.php` to change header and footer content.

## Security
- Uses nonces for AJAX security.
- Implements permissions checks to ensure only administrators can generate PDFs.

## Dependencies
- [mPDF](https://mpdf.github.io/) library for PDF generation.
- [Advanced Custom Fields (ACF) Pro](https://www.advancedcustomfields.com/pro/) for additional field support.

## Contributing
Contributions to the WP to PDF Generator plugin are welcome. Please ensure to follow the WordPress coding standards and submit pull requests for any enhancements.

## License
Standard WordPress GPL license.

## Author
Chris Hurst
[https://iamchrishurst.com](https://iamchrishurst.com)

## Version
2.0.0
