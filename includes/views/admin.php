<div class="wrap genesis-form">
<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

<p><span class="description">
<?php _e( 'Use the form below to choose which platform you wish to convert FROM, and which platform you wish to convert TO.', 'seo-data-transporter' ); ?>
</span></p>

<p><span class="description">
<?php _e( 'Click "Analyze" for a list of elements you are able to convert, along with the number of records that will be converted. Some platforms do not share similar elements, or store data in a non-standard way. These records will remain unchanged. Any compatible elements will be displayed for your review. Also, some records will be ignored if the post/page in question already contains a record for that particular SEO element in the new platform.', 'seo-data-transporter' ); ?></span></p>

<p><span class="description">
<?php _e( 'Click "Convert" to perform the conversion. After the conversion is complete, you will be alerted to how many records were converted, and how many records had to be ignored, based on the criteria above.', 'seo-data-transporter' ); ?></span></p>

<form method="post" action="<?php echo admin_url( 'tools.php?page=seodt' ); ?>">
<?php
wp_nonce_field( 'seo-data-transporter' );

_e( 'Convert inpost SEO data from: ', 'seo-data-transporter' );
$this->generate_select( 'platform_old', SEO_Data_Transporter()->get_supported_themes(), SEO_Data_Transporter()->get_supported_plugins() );

_e( ' to: ', 'seo-data-transporter' );
$this->generate_select( 'platform_new', SEO_Data_Transporter()->get_supported_themes(), SEO_Data_Transporter()->get_supported_plugins() );
?>

<div class="bottom-buttons">
	<?php submit_button( __( 'Analyze', 'seo-data-transporter' ), 'secondary', 'analyze', false ); ?>
	<?php submit_button( __( 'Convert', 'seo-data-transporter' ), 'primary', 'submit', false ); ?>
</div>
</form>
</div>
