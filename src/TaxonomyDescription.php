<?php
namespace PluginBoilerplate;

class TaxonomyDescription {

  /**
   * Callback function builds a wysiwyg field on the custom taxonomy/category edit page
   *
   * This is hooked to $taxonomy_edit_form_fields
   *
   * @param  [type] $tag [description]
   * @return [type]      [description]
   * @see http://aerin.co.uk/how-to-stop-the-wp_editor-from-removing-formatting/
   * @see http://wordpress.stackexchange.com/questions/190510/replace-taxomony-description-field-with-visual-wysiwyg-editor
   */
  public function description( $tag ) {

    ?>
    <table class="form-table">
      <tr class="form-field">
        <th scope="row" valign="top"><label for="description"><?php _ex( 'Description', 'Taxonomy Description', 'plugin_boilerplate' ); ?></label></th>
        <td>
          <?php

          $settings = [
            'wpautop'       => FALSE,
            'media_buttons' => true,
            'quicktags'     => true,
            'textarea_rows' => '15',
            'textarea_name' => 'description'
          ];

          // wp_editor( wpautop( stripslashes($content) ), $editor_id, $args );

          wp_editor(
            html_entity_decode( $tag->description ),
            //$tag->description,
            'cat_description',
            $settings
          );

          ?>
          <br />
          <span class="description"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></span>
        </td>
      </tr>
    </table>
    <?php

  }

  public static function remove_html_filtering() {

    // remove the html filtering
    remove_filter( 'pre_term_description', 'wp_filter_kses' );
    remove_filter( 'term_description', 'wp_kses_data' );

  }

}
