<?php
/**
 * @author  David Egan <david@carawebs.com>
 * @license https://opensource.org/licenses/gpl-2.0.php
 */
namespace Carawebs\BetterTaxonomy;

class TaxonomyDescription {

  /**
   * Callback function builds a wysiwyg field on the custom taxonomy/category edit page
   *
   * This is hooked to {$taxonomy}_edit_form_fields
   *
   * @see http://aerin.co.uk/how-to-stop-the-wp_editor-from-removing-formatting/
   * @see http://wordpress.stackexchange.com/questions/190510/replace-taxomony-description-field-with-visual-wysiwyg-editor
   * @see http://wordpress.stackexchange.com/questions/689/adding-fields-to-the-category-tag-and-custom-taxonomy-edit-screen-in-the-wordpr
   * @see https://paulund.co.uk/add-tinymce-editor-category-description
   * @param  object $term The term object
   * @return string       HTML markup of TinyMCE editor
   *
   */
   public function description( $term ) {

     ?>
     <tr class="form-field">
       <th scope="row" valign="top"><label for="description"><?php _ex( 'Description', 'Taxonomy Description', 'plugin_boilerplate' ); ?></label></th>
       <td>
         <?php

         $settings = [
           'wpautop'       => true,
           'media_buttons' => false,
           'quicktags'     => true,
           'textarea_rows' => '15',
           'textarea_name' => 'description'
         ];

         // wp_editor( wpautop( stripslashes($content) ), $editor_id, $args );

         wp_editor(
           htmlspecialchars_decode( $term->description ),
           'cat_description',
           $settings
         );

       ?>
       <br />
       <span class="description"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></span>
     </td>
   </tr>
   <?php

  }

}
