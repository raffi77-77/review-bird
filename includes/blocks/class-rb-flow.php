<?php

namespace Review_Bird\Includes\Blocks;

class Flow extends Block {
	
	public function register() {
		register_block_type( Review_Bird()->get_plugin_dir_path() . 'blocks/flow/build' );
	}
}
