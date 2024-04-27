<?php

/**
 * Load internalization supports
 */
function taxonomy_filter_load_textdomain() {
    load_plugin_textdomain( TFP_PREFIX, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'taxonomy_filter_load_textdomain' );

/**
 * Add taxonomy filter settings before the admin page is rendered
 */
function taxonomy_filter_admin_init() {
	register_setting( 'taxonomy_filter_options', TFP_OPTIONS );
    add_settings_section( 'taxonomy_filter_main', '', 'taxonomy_filter_option_main_show', 'taxonomy_filter_main_section' );

    // Check request data before save main settings
    if ( ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == TFP_PREFIX ) && isset( $_POST[ 'taxonomy_filter_main_action' ] ) ) {
        
        $nonce_name = 'taxonomy_filter_main_action_nonce'; // Use a consistent nonce name

        // Add nonce check
        if ( isset( $_POST[ $nonce_name ] ) && wp_verify_nonce( $_POST[ $nonce_name ], 'taxonomy_filter_main_action_nonce' ) ) {
            taxonomy_filter_save_main_settings();
        }
    }
}
add_action( 'admin_init', 'taxonomy_filter_admin_init' );

/**
 * Render admin settings page
 */
function taxonomy_filter_settings() {
	settings_fields( 'taxonomy_filter_options' );
    settings_fields( 'taxonomy_filter_user_options' );
	?>
	<div class="wrap">
        <div class="icon32"><img src="<?php echo plugins_url( 'taxonomy-filter/images/icon32.png' ) ?>" /></div>
        <h2><?php _e( 'Taxonomy Filter settings', TFP_PREFIX ) ?></h2>

        <script type="text/javascript">
        jQuery(document).ready(function($) {
            jQuery('body').on('click', '.button-primary.reset:not(.confirm, .disabled)', function(e) {
                e.preventDefault();
                jQuery(this).addClass('confirm');
                jQuery(this).val();
                jQuery(this).val('<?php _e( 'Confirm reset', TFP_PREFIX ) ?>');
            }).on('click', '.button-primary.taxonomy_filter_main.reset.confirm:not(.disabled)', function() {
                jQuery('#form_main_action').val('taxonomy_filter_reset');
                jQuery(this).val('<?php _e( 'Resetting..', TFP_PREFIX ) ?>').addClass('disabled');
            });

            <?php if (isset($_GET['updated'])) { ?>
            jQuery('#setting-error-settings_updated').delay(3000).slideUp(400);
            <?php }	?>
        });
        </script>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
                    <form method="post" action="">
                        <p>
                            <?php _e( 'Choose in which taxonomy admin page box show filter field. Change "hide filter" checkbox if you want to hide filter field if taxonomy has no values. You can enable a taxonomy filter by checking his checkbox input beside taxonomy names.', TFP_PREFIX ) ?>
                            <?php _e( 'When you enable a taxonomy filter, a section for choosing hidden taxonomy terms is displayed in every WordPress user profile\'s settings page. In that page you can select a list of taxonomy terms that are automatically removed from hierarchical term taxonomies inside admin pages.', TFP_PREFIX ) ?><br />
                            <span class="description"><?php _e( 'Note: The plugin does not support non-hierarchical tags.', TFP_PREFIX ) ?>
                        </p>
                        <?php
                        // Prints out all settings of taxonomy filter main settings page
                        do_settings_sections( 'taxonomy_filter_main_section' );
                        $nonce_name = 'taxonomy_filter_main_action_nonce'; // Use a consistent nonce name
                        wp_nonce_field( $nonce_name, $nonce_name );
                        ?>
                        <input id="form_main_action" name="taxonomy_filter_main_action" type="hidden" value="taxonomy_filter_main_update" /><br />
                        <p>
                            <input name="submit" type="submit" class="button-primary taxonomy_filter_main" value="<?php _e( 'Save', TFP_PREFIX ) ?>" />
                            <input name="reset" type="submit" class="button-primary taxonomy_filter_main reset" value="<?php _e( 'Reset', TFP_PREFIX ) ?>" />
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render admin main settings page options and fields
 */
function taxonomy_filter_main_fields() {
	?>
	<table class="wp-list-table widefat fixed posts taxonomy-filter-table" cellspacing="0">
		<thead>
			<tr>
                <th scope="col" id="cb" class="manage-column column-cb check-column"></th>
                <th scope="col" id="name" class="manage-column label-column"><?php _e( 'Name', TFP_PREFIX ) ?></th>
                <th scope="col" id="slug" class="manage-column slug-column"><?php _e( 'Slug', TFP_PREFIX ) ?></th>
                <th scope="col" id="rewrite" class="manage-column slug-column"><?php _e( 'Rewrite slug', TFP_PREFIX ) ?></th>
                <th scope="col" id="options" class="manage-column options-column"><?php _e( 'Options', TFP_PREFIX ) ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
                <th scope="col" id="cb" class="manage-column column-cb check-column"></th>
                <th scope="col" id="name" class="manage-column label-column"><?php _e( 'Name', TFP_PREFIX ) ?></th>
                <th scope="col" id="slug" class="manage-column slug-column"><?php _e( 'Slug', TFP_PREFIX ) ?></th>
                <th scope="col" id="rewrite" class="manage-column slug-column"><?php _e( 'Rewrite slug', TFP_PREFIX ) ?></th>
                <th scope="col" id="options" class="manage-column options-column"><?php _e( 'Options', TFP_PREFIX ) ?></th>
			</tr>
		</tfoot>
		<tbody id="the-list">
            <?php
            $options = get_option( TFP_OPTIONS );
            $tax_list = '';
            $i = 1;

            // Retrieve hierarchical taxonomies
            $args = array( 'hierarchical' => true );
            $taxonomies = get_taxonomies( $args, 'objects' );

            if ( $taxonomies ) {
                // Loop taxonomies
                foreach ( $taxonomies as $taxonomy ) {
                    // Retrieve current taxonomy data
                    $slug = is_bool( $taxonomy->rewrite ) || empty( $taxonomy->rewrite[ 'slug' ] ) ? '': $taxonomy->rewrite[ 'slug' ];
                    $name = $taxonomy->name;

                    // Append taxonomy name to a variable containing taxonomy list
                    $tax_list .= $name . ',';

                    // Check if current taxonomy is checked
                    $checked = '';
                    if ( $options->$name->replace == 1 ) $checked = 'checked="checked"';
                    ?>
                    <tr id="post-<?php echo $i ?>" <?php echo ($i % 2 == 0) ? 'class="alternate"' : '' ?> valign="top">
                        <th scope="row" class="check-column">
                            <?php echo '<input type="checkbox" id="' . $name . '" name="taxonomies[' . $name . ']" value="1" ' . $checked . '>' ?>
                        </th>
                        <td class="label-column">
                            <label for="<?php echo $name ?>">
                                <?php echo $taxonomy->labels->name; if ($taxonomy->_builtin == 1) echo ' <span class="description" style="color:#ababab">(builtin)</span>' ?>
                            </label>
                        </td>
                        <td class="slug-column"><?php echo $name ?></td>
                        <td class="slug-column"><?php echo $slug ?></td>
                        <td class="options-column">
                            <label><input type="checkbox" id="hide_blank_opt[<?php echo $name ?>]" name="hide_blank_opt[<?php echo $name ?>]" value="1" <?php if ( ! empty( $options ) && $options->$name->hide_blank == 1 ) echo 'checked="checked"';?> /> <?php _e( 'Hide filter if no element of the taxonomy is associated', TFP_PREFIX ) ?></label>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>
		</tbody>
	</table>
	<input type="hidden" name="tax" value="<?php echo $tax_list ?>"/>
    <?php
}

/**
 * Show admin main settings page
 */
function taxonomy_filter_option_main_show() {
    taxonomy_filter_main_fields();
}

/**
 * Save admin page main settings
 */
function taxonomy_filter_save_main_settings() {
	// Read main options and backup hidden taxonomies value
	$options = get_option( TFP_OPTIONS );
	$hidden_taxonomies_backup = $options->hidden;

    // Explode taxonomy list
	$taxonomies = explode( ',', $_POST[ 'tax' ] );
    $options = new stdClass();

    // Manage save actions
	if ( $_POST[ 'taxonomy_filter_main_action' ] != 'taxonomy_filter_reset') {
        // Read post data
		if ( isset( $_POST[ 'taxonomies' ] ) ) $taxs = $_POST[ 'taxonomies' ];
		else $taxs = array();
		if ( isset( $_POST[ 'hide_blank_opt' ] ) ) $hide_blank_opt = $_POST[ 'hide_blank_opt' ];
		else $hide_blank_opt = array();

        // Loop taxonomies
		foreach ( $taxonomies as $taxonomy ) {
			if ( isset( $taxonomy ) && ! empty( $taxonomy ) ) {
                // Set data (from post request)
				if ( $taxs[ $taxonomy ] == 1 ) $replace = 1;
				else $replace = 0;
				if ( ! empty( $hide_blank_opt[ $taxonomy ] ) ) $hide_blank = 1;
				else $hide_blank = 0;
				$option = new stdClass();

                // Save taxonomy slug
                $option->slug = $taxonomy;
                // Save replace value (1 = replace, 0 = WordPress default)
                $option->replace = $replace;
                // Save hide blank value (1 = hide, 0 = show)
                $option->hide_blank = $hide_blank;

                // Add current taxonomy to options class
				$options->$taxonomy = $option;
			}
		}
	}
	else {
        // Loop taxonomies
		foreach ( $taxonomies as $taxonomy ) {
			if ( ! empty($taxonomy ) ) {
                // Set data (from defaults)
				$replace = TFP_DEFAULT_REPLACE;
                $hide_blank = TFP_DEFAULT_HIDE_BLANK;
				$option = new stdClass();
				$hidden_taxonomies_backup = array();

                // Save taxonomy slug
                $option->slug = $taxonomy;
                // Save replace value (1 = replace, 0 = WordPress default)
                $option->replace = $replace;
                // Save hide blank value (1 = hide, 0 = show)
                $option->hide_blank = $hide_blank;

                // Add current taxonomy to options class
				$options->$taxonomy = $option;
			}
		}
	}

	// Prepare array for hidden terms
	$options->hidden = ( ! empty( $hidden_taxonomies_backup) ) ? $hidden_taxonomies_backup : array();

    // Save main options
	update_option( TFP_OPTIONS, $options );

    // Reload admin settings page
	header("Location: options-general.php?page=" . TFP_PREFIX . "&updated=true");
}
