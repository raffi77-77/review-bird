<?php

namespace Review_Bird\Includes\Database_Strategies;

use Exception;
use Review_Bird\Includes\Data_Objects\Data_Object;
use Review_Bird\Includes\Database_Strategy_Interface;
use Review_Bird\Includes\Traits\SingletonTrait;
use Review_Bird\Includes\Services\Helper;

class WPDB extends Database_Strategy implements Database_Strategy_Interface {

	use SingletonTrait;

	const UUID_COLUMN_NAME = 'uuid';
	protected $wpdb;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	public function start_transaction() {
		$this->wpdb->query( 'START TRANSACTION;' );
	}

	public function commit_transaction() {
		$this->wpdb->query( 'COMMIT;' );
	}

	public function rollback_transaction() {
		$this->wpdb->query( 'ROLLBACK;' );
	}

	public function create( $data, ...$args ) {
		// Check date related columns
		if ( ! empty( $args[1] ) && is_array( $args[1] ) && in_array( 'created_at', $args[1] ) ) {
			$date               = current_time( 'mysql', true );
			$data['created_at'] = $date;
			$data['updated_at'] = $date;
		}
		// Check uuid column existence
		if ( in_array( static::UUID_COLUMN_NAME, $args[1] ) ) {
			$data['uuid'] = Helper::get_uuid();
		}
		// Make data ready
		$data   = wp_array_slice_assoc( $data, $args[1] );
		$result = $this->wpdb->insert( $this->wpdb->prefix . $args[0], $data );
		if ( empty( $result ) ) {
			$this->wpdb->show_errors = true;
			throw new Exception( __( 'Error on wpdb save.' . $this->wpdb->last_error ) );
		}
		$data['id'] = absint( $this->wpdb->insert_id );

		return $data;
	}

	public function find( $id, $table_name = false, ...$args ) {
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM `" . $this->wpdb->prefix . $table_name . "` WHERE id=%d", $id ), ARRAY_A );
	}

	public function where( $conditions, ...$args ) {
		$where_clause = '1';
		$values       = array();
		// Build the WHERE clause
		$this->prepare_where_clause( $conditions, $where_clause, $values );
		// Order by
		$order_clause = '';
		if ( ! empty( $args[3] ) && is_string( $args[3] ) ) {
			if ( ! empty( $args[4] ) && is_string( $args[4] ) ) {
				$ordering = esc_sql( $args[4] );
			} else {
				$ordering = 'ASC';
			}
			$order_by     = esc_sql( $args[3] );
			$order_clause = "ORDER BY `$order_by` $ordering";
		}
		// Limit, offset
		$limit_clause = '';
		if ( ! empty( $args[1] ) && ! empty( $args[2] ) ) {
			$offset       = is_numeric( $args[2] ) ? ( $args[2] > 0 ? $args[2] - 1 : 0 ) : 0;
			$limit        = is_numeric( $args[1] ) ? $args[1] : 10;
			$offset       *= $limit;
			$values[]     = $offset;
			$values[]     = $limit;
			$limit_clause = 'LIMIT %d,%d';
		}
		$lock_clause = '';
		if (!empty($args[5]) && $args[5] === true) {
			$lock_clause = 'FOR UPDATE';
		}
		// Prepare and execute the query
		// TODO allow columns selecting, through the builder (future)
		$query = "SELECT * FROM `{$this->wpdb->prefix}{$args[0]}` WHERE {$where_clause} {$order_clause} {$limit_clause} {$lock_clause}";
		$query = ! empty( $values ) ? $this->wpdb->prepare( $query, ...$values ) : $query;

		return $this->wpdb->get_results( $query, ARRAY_A );
	}

	public function count( $conditions, ...$args ) {
		$where_clause = '1';
		$values       = array();
		// Build the WHERE clause
		$this->prepare_where_clause( $conditions, $where_clause, $values );
		// Prepare and execute the query
		$query = "SELECT COUNT(*) FROM `{$this->wpdb->prefix}{$args[0]}` WHERE {$where_clause}";
		$query = ! empty( $values ) ? $this->wpdb->prepare( $query, ...$values ) : $query;

		return $this->wpdb->get_var( $query );
	}

	protected function prepare_where_clause( $conditions, &$where_clause, &$values ) {
		foreach ( $conditions as $key => $value ) {
			// Check for special comparison operators
			if ( preg_match( '/(>=|<=|>|<|!=|=|LIKE)$/', $key, $matches ) ) {
				$operator = trim( $matches[1] );
				$key      = str_replace( $operator, '', $key );
			} else {
				$operator = '=';
			}
			if ( is_array( $value ) ) {
				if ( empty( $value ) ) {
					continue;
				}
				$placeholders = array();
				$null_where   = '';
				foreach ( $value as $val ) {
					if ( is_int( $val ) ) {
						$placeholders[] = '%d';
						$values[]       = $val;
					} elseif ( strtolower( $val ) === 'null' || is_null( $val ) ) {
						$null_where = "`$key` IS NULL";
					} else {
						$placeholders[] = '%s';
						$values[]       = $val;
					}
				}
				if ( count( $value ) > 1 && ! empty( $null_where ) ) {
					$where_clause .= " AND ( `$key` IN (" . implode( ', ', $placeholders ) . ") OR $null_where)";
				} elseif ( count( $value ) == 1 && ! empty( $null_where ) ) {
					$where_clause .= " AND $null_where";
				} else {
					$where_clause .= " AND `$key` IN (" . implode( ', ', $placeholders ) . ")";
				}
			} elseif ( is_int( $value ) ) {
				$where_clause .= " AND `$key` $operator %d";
				$values[]     = $value;
			} elseif ( is_null( $value ) || strtolower( $value ) === 'null' ) {
				$where_clause .= " AND `$key` IS NULL";
			} elseif ( is_string( $value ) || is_float( $value ) ) {
				$where_clause .= " AND `$key` $operator %s";
				$values[]     = $value;
			} else {
				$where_clause .= " AND `$key` $operator %d";
				$values[]     = $value;
			}
		}
	}

	public function delete( $where, ...$args ) {
		return $this->wpdb->delete( $this->wpdb->prefix . $args[0], $where );
	}

	public function update( $where, $data, ...$args ) {
		$data = is_object( $data ) ? (array) $data : $data;
		// Check updated_at related column
		if ( ! empty( $args[1] ) && is_array( $args[1] ) && in_array( 'updated_at', $args[1] ) ) {
			$data['updated_at'] = current_time( 'mysql', true );
		}
		if ( ! empty( $args[1] ) ) {
			$data = wp_array_slice_assoc( $data, $args[1] );
		}
		$where = is_object( $where ) ? (array) $where : $where;
		$this->wpdb->update( $this->wpdb->prefix . $args[0], $data, $where );

		return array_merge( $where, $data );
	}

	public function lock_row( Data_Object $data_object ) {
		$table_name = $data_object::TABLE_NAME;
		$query = "SELECT * FROM {$this->wpdb->prefix}$table_name WHERE id = %s LIMIT 1 FOR UPDATE";
		$this->wpdb->get_row( $this->wpdb->prepare( $query, $data_object->get_id() ) );
	}
}