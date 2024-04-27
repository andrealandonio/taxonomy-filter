<?php
// Requires
require_once( 'taxonomy-filter-constants.php' );

/**
 * Add term form fields
 */
function taxonomy_filter_terms_add_form_fields() {
	wp_nonce_field( basename( __FILE__ ), TFP_TERMS_NONCE );
	?>
    <div class="form-field terms-tfp-wrap term-row-head"><?php _e( 'Taxonomy Filter options', TFP_PREFIX ) ?></div>
    <div class="form-field terms-tfp-wrap">
        <label for="<?php echo TFP_TERMS_FIELD_HIDDEN ?>"><?php _e( 'Hidden in filters', TFP_PREFIX ) ?></label>&nbsp;
        <input type="checkbox" name="<?php echo TFP_TERMS_FIELD_HIDDEN ?>" id="<?php echo TFP_TERMS_FIELD_HIDDEN ?>" value="1" class="<?php echo TFP_TERMS_FIELD_HIDDEN ?>" />
        <p><?php _e( 'Set if you want to hide term in taxonomy filters.', TFP_PREFIX ) ?></p>
    </div>
    <br />
	<?php
}

/**
 * Edit term form fields
 *
 * @param $term
 */
function taxonomy_filter_terms_edit_form_fields( $term ) {
	$tfp_field_hidden = taxonomy_filter_terms_meta_get_hidden( $term->term_id );
    ?>
	<?php wp_nonce_field( basename( __FILE__ ), TFP_TERMS_NONCE ) ?>

    <tr class="form-field terms-tfp-wrap">
        <th scope="row" colspan="2" class="term-row-head"><?php _e( 'Taxonomy Filter options', TFP_PREFIX ) ?></th>
    </tr>
    <tr class="form-field terms-tfp-wrap">
        <th scope="row"><label for="<?php echo TFP_TERMS_FIELD_HIDDEN ?>"><?php _e( 'Hidden in filters', TFP_PREFIX ) ?></label></th>
        <td>
            <input type="checkbox" name="<?php echo TFP_TERMS_FIELD_HIDDEN ?>" id="<?php echo TFP_TERMS_FIELD_HIDDEN ?>" value="1" <?php echo ( $tfp_field_hidden == 1 ) ? 'checked="checked"' : '' ?> class="<?php echo TFP_TERMS_FIELD_HIDDEN ?>" />
            <p class="description"><?php _e( 'Set if you want to hide term in taxonomy filters.', TFP_PREFIX ) ?></p>
        </td>
    </tr>
	<?php
}

/**
 * Save term form fields
 *
 * @param int $term_id
 */
function taxonomy_filter_terms_save_form_fields( $term_id ) {
	// Check nonce
	if ( ! isset( $_POST[ TFP_TERMS_NONCE ] ) || ! wp_verify_nonce( $_POST[ TFP_TERMS_NONCE ], basename( __FILE__ ) ) ) return;

	// Read main options
	$options = get_option( TFP_OPTIONS );

	// Read data
	$old_tfp_field_hidden = taxonomy_filter_terms_meta_get_hidden( $term_id );
	$new_tfp_field_hidden = isset( $_POST[ TFP_TERMS_FIELD_HIDDEN ] ) ? taxonomy_filter_terms_meta_sanitize_number( $_POST[ TFP_TERMS_FIELD_HIDDEN ] ) : 0;

	// Update option meta
	if ( $old_tfp_field_hidden && 0 === $new_tfp_field_hidden ) $options->hidden[ $term_id ] = 0;
	else if ( $old_tfp_field_hidden !== $new_tfp_field_hidden ) $options->hidden[ $term_id ] = 1;

	// Update main options
	update_option( TFP_OPTIONS, $options );
}

/**
 * Edit term columns
 *
 * @param array $columns
 *
 * @return mixed
 */
function taxonomy_filter_terms_edit_columns( $columns ) {
	$columns[ TFP_TERMS_FIELD_HIDDEN ] = __( 'Hidden in filters', TFP_PREFIX );

	return $columns;
}

/**
 * Customize term columns output
 *
 * @param string $out
 * @param string $column
 * @param int $term_id
 *
 * @return string
 */
function taxonomy_filter_terms_manage_columns( $out, $column, $term_id ) {
	if ( TFP_TERMS_FIELD_HIDDEN === $column ) {
		// Read value
		$value = taxonomy_filter_terms_meta_get_hidden( $term_id );

		// Output data
		$out = taxonomy_filter_terms_meta_manage_columns_check_active( $value );
	}

	return $out;
}

/**
 * Term meta sanitize for number
 *
 * @param $value
 *
 * @return string
 */
function taxonomy_filter_terms_meta_sanitize_number( $value ) {
	return is_numeric( $value ) ? $value : '';
}

/**
 * Get term hidden meta value
 *
 * @param int $term_id
 *
 * @return int
 */
function taxonomy_filter_terms_meta_get_hidden( $term_id ) {
	// Read main options
	$options = get_option( TFP_OPTIONS );
	return ( ! empty( $options->hidden[ $term_id ] ) && $options->hidden[ $term_id ] != '' ) ? 1 : 0;
}

/**
 * Return check active results
 *
 * @param int $value
 *
 * @return string
 */
function taxonomy_filter_terms_meta_manage_columns_check_active( $value ) {
	if ( empty( $value ) || $value == 0 ) return '-';
	else return '<span class="dashicons dashicons-yes"></span>';
}
