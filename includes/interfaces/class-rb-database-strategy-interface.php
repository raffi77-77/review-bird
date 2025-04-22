<?php

namespace Review_Bird\Includes;

interface Database_Strategy_Interface {
	public function create( $data );

	public function update( $where, $data );

	public function find( $id );

	public function where( $conditions );
	
	public function count( $conditions );

	public function delete( $where );
}