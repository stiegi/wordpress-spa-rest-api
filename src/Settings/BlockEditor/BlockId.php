<?php
namespace Spa\Settings\BlockEditor;

class BlockId {
	public static function add_block_id_field() {
		wp_enqueue_script(
			'extend-block-example-js',
			esc_url( plugins_url( '/dist/enhance-block-editor.js', __FILE__ ) ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			'1.0.0',
			true // Enqueue the script in the footer.
		);
	}
}





