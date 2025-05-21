<?php

namespace Review_Bird\Includes\Admin;

use Review_Bird\Includes\Traits\SingletonTrait;

class Asset {

	use SingletonTrait;

	protected $l10n;

	public function get_l_10_n() {
		return $this->l10n;
	}

	public function set_l_10_n( $l10n ): void {
		$this->l10n = $l10n;
	}

	public function admin_init() {
		$this->define_l10n();
		add_action( 'admin_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		// add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_scripts' ) );
		// add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
	}

	public function define_l10n() {
		$l10n = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'prefix'   => Review_Bird()->get_plugin_prefix(),
		);
		$this->set_l_10_n( $l10n );
	}

	public function styles() {
	}

	public function scripts() {
	}
}