<?php
/**
 * Show custom profile fields
 *
 * @param \WP_User $user
 */
function taxonomy_filter_show_custom_profile_fields( $user ) {
    if ( current_user_can( 'list_users' ) ) {
        ?>
        <h3 class="taxonomy_filter_profile_table"><?php _e( 'Taxonomy Filters Management', TFP_PREFIX ) ?></h3>
        <div class="taxonomy_filter_profile_table description"><?php _e( 'Choose hidden taxonomy terms for the user. By default, all taxonomy terms are visible in the hierarchical term taxonomies sections inside admin pages. You can choose only from max 2 nested levels but all the children of a hidden term are automatically removed from admin pages. Keep in mind that the hidden terms are not searchable and filterable. Only taxonomies with at least one term are shown below.', TFP_PREFIX ) ?></div>
        <table class="taxonomy_filter_profile_table form-table">
            <tr>
                <th><label for="<?php echo TFP_META_HIDDEN_TAXONOMIES ?>"><?php _e( 'Hidden terms', TFP_PREFIX ) ?></label></th>
                <?php
                $options = get_option( TFP_OPTIONS );
                $user_hidden_taxonomies = get_user_meta( $user->ID, TFP_META_HIDDEN_TAXONOMIES, true );
                if ( empty( $user_hidden_taxonomies ) ) $user_hidden_taxonomies = array();
        
                // Loop over taxonomy_filter option items
                $empty_taxonomy = true;

                // Convert to iterable object
                if ( 'object' == gettype( $options ) ) {
                    $options = get_object_vars( $options );
                }

                if ( count( $options ) > 0 ) {
                    foreach ( $options as $taxonomy ) {
                        // If current taxonomy is enabled for replace add filter box
                        if ( $taxonomy->replace == 1 ) {
                            ?>
                            <td class="taxonomy_filter_hidden_taxonomy">
                                <?php
                                // Get configured taxonomy terms
                                $terms = get_terms( $taxonomy->slug, array(
                                    'hide_empty' => false,
                                    'parent' => 0,
                                    'orderby' => 'name',
                                    'order' => 'ASC'
                                ) );

                                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                                    ?>
                                    <div class="taxonomy_filter_hidden_taxonomy"><?php _e( 'Taxonomy:', TFP_PREFIX ) ?><br /><span class="taxonomy_filter_hidden_taxonomy"><?php echo $taxonomy->slug ?></span></div>
                                    <select class="taxonomy_filter_hidden_taxonomy" name="<?php echo TFP_META_HIDDEN_TAXONOMIES . '[]' ?>" id="<?php echo TFP_META_HIDDEN_TAXONOMIES ?>" class="regular-text" multiple>
                                        <?php
                                        // Loop configured taxonomy terms
                                        foreach ( $terms as $term ) {
                                            ?>
                                            <option value="<?php echo $term->term_id ?>" <?php echo ( in_array( $term->term_id, $user_hidden_taxonomies ) ) ? "selected=\"selected\"" : "" ?>><?php echo $term->name ?></option>
                                            <?php
                                            // Get child taxonomy terms
                                            $child_terms = get_terms( $taxonomy->slug, array(
                                                'hide_empty' => false,
                                                'child_of' => $term->term_id,
                                                'orderby' => 'name',
                                                'order' => 'ASC'
                                            ) );

                                            if ( ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ) {
                                                // Loop child taxonomy terms
                                                foreach ( $child_terms as $child_term ) {
                                                    ?>
                                                    <option value="<?php echo $child_term->term_id ?>" <?php echo ( in_array( $child_term->term_id, $user_hidden_taxonomies ) ) ? "selected=\"selected\"" : "" ?>><?php echo '- ' . $child_term->name ?></option>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                }
                                ?>
                            </td>
                            <?php
                            $empty_taxonomy = false;
                        }
                    }
                }

                // No taxonomy to filter
                if ( $empty_taxonomy ) {
                    ?><td>-</td><?php
                }
                ?>
            </tr>
        </table>
        <?php
    }
}
add_action( 'show_user_profile', 'taxonomy_filter_show_custom_profile_fields' );
add_action( 'edit_user_profile', 'taxonomy_filter_show_custom_profile_fields' );

/**
 * Update custom profile fields
 *
 * @param int $user_id
 */
function taxonomy_filter_update_custom_profile_fields( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) return;

    // Read selected taxonomies array values
    $selected_taxonomies = $_POST[ TFP_META_HIDDEN_TAXONOMIES ];

    if ( current_user_can( 'list_users' ) ) {
        // Save for admins (update every time)
        update_user_meta( $user_id, TFP_META_HIDDEN_TAXONOMIES, $selected_taxonomies );
    }
    else {
        // Save for other users (update only if not empty)
        if ( ! empty( $_POST[ TFP_META_HIDDEN_TAXONOMIES ] ) ) update_user_meta( $user_id, TFP_META_HIDDEN_TAXONOMIES, $selected_taxonomies );
    }
}
add_action( 'personal_options_update', 'taxonomy_filter_update_custom_profile_fields' );
add_action( 'edit_user_profile_update', 'taxonomy_filter_update_custom_profile_fields' );
