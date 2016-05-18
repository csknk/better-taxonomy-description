<?php
namespace Carawebs\BetterTaxonomy\Views;

/**
 * Form views
 */
class NewFormField {

  /**
	* Output the form field for this metadata when adding a new term
	*
	* @since 0.1.0
	*/
	public function add_field() {
		?>

		<div class="form-field term-<?php echo esc_attr( $this->meta_key ); ?>-wrap">
			<label for="term-<?php echo esc_attr( $this->meta_key ); ?>">
				<?php echo esc_html( $this->labels['singular'] ); ?>
			</label>

			<?php $this->form_field(); ?>

			<?php if ( ! empty( $this->labels['description'] ) ) : ?>

				<p class="description">
					<?php echo esc_html( $this->labels['description'] ); ?>
				</p>

			<?php endif; ?>

		</div>

		<?php
	}

}
