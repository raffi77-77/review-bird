<?php

namespace Review_Bird\Includes\Admin;

use Review_Bird\Includes\Admin\Pages\Review;
use Review_Bird\Includes\Admin\Pages\Setting;
use Review_Bird\Includes\Admin\Pages\Skin;
use Review_Bird\Includes\Admin\Pages\Feedback;
use Review_Bird\Includes\Services\Freemius;
use Review_Bird\Includes\Traits\SingletonTrait;

class Admin {

	use SingletonTrait;

	public $fs;
	protected Setting\Page $setting_page;
	protected Review\Page $review_page;
	protected Skin\Page $skin_page;
	protected Feedback\Page $feedback_page;

	public function __construct() {
		$this->setting_page  = new Setting\Page();
		$this->review_page   = new Review\Page();
		$this->skin_page     = new Skin\Page();
		$this->feedback_page = new Feedback\Page();
	}

	public function boot() {
		$this->fs = Freemius::instance()->fs;
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'admin_init', array( Asset::instance(), 'admin_init' ) );
	}

	public function add_options_page(): void {
		add_menu_page( __( 'Review Bird', 'review-bird' ), __( 'Review Bird', 'review-bird' ), 'manage_options', 'review-bird', null, '', 13 );
		$this->setting_page->add_submenu_page();
		$this->review_page->add_submenu_page();
		$this->skin_page->add_submenu_page();
		$this->feedback_page->add_submenu_page();
	}
}