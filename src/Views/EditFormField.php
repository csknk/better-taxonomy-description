<?php
namespace Carawebs\BetterTaxonomy\Views;

/**
 *
 */
class EditFormFields {

  /**
	* Output the form field when editing an existing term
	*
	* @since 0.1.0
	*
	* @param object $term
	*/
	public function edit_field( $term = false ) {
		?>

		<tr class="form-field term-<?php echo esc_attr( $this->meta_key ); ?>-wrap">
			<th scope="row" valign="top">
				<label for="term-<?php echo esc_attr( $this->meta_key ); ?>">
					<?php echo esc_html( $this->labels['singular'] ); ?>
				</label>
			</th>
			<td>
				<?php $this->form_field( $term ); ?>

				<?php if ( ! empty( $this->labels['description'] ) ) : ?>

					<p class="description">
						<?php echo esc_html( $this->labels['description'] ); ?>
					</p>

				<?php endif; ?>

			</td>
		</tr>

		<?php
	}


}
