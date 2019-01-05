<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

class Database {
  protected $db;

  public function __construct( $db_config ) {
    try {
			$this->db = new mysqli( $db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name'] );
      $this->db->set_charset( "utf8" );
		} catch( PDOException $e ) {
			die( 'Error to connection database!' );
		}
  }

  public function query( $query ) {
		return $this->db->query( "$query" );
	}

  public function next_id( $table ) {
    $last = $this->get_row( "SELECT id FROM $table ORDER BY id DESC LIMIT 1" );
    return ( $last ) ? ( $last['id'] + 1 ) : 1;
  }

	public function get_results( $query ) {
		$output = [];
		$query = $this->query( $query );

		if ( $query ) {
      while ( $select = $query->fetch_assoc() )
  			$output[] = $select;

      return $output;
    } else {
      die( $this->db->error );
    }
	}

	public function get_row( $query ) {
		$query = $this->query( $query );
		if ( $query ) {
      return $query->fetch_assoc();
    } else {
      die( $this->db->error );
    }
	}

	public function get_count( $query = '' ) {
		$query = $this->query( $query );
		if ( $query ) {
      return $query->num_rows;
    } else {
      die( $this->db->error );
    }
	}

	public function insert( $table, $data = array() ) {
		if ( is_array( $data ) ) {
			$fields = '(' . implode( array_keys( $data ),  ',' ) . ')';
			$values = '(' . implode( array_map( array( $this, 'add_quotes' ), array_values( $data ) ), ',' ) . ')';

      if ( ! $this->query( "INSERT INTO $table $fields VALUES $values" ) )
        die( $this->db->error );
		}
	}

	public function update( $table, $data = array(), $where = array() ) {
		if ( is_array( $data ) && is_array( $where ) ) {
			$set 	= implode( array_map( array( $this, 'set_key_value' ), array_keys( $data ), array_values( $data ) ), ',' );
			$where 	= implode( array_map( array( $this, 'set_key_value' ), array_keys( $where ), array_values( $where ) ), ' AND ' );

      if ( ! $this->query( "UPDATE $table SET $set WHERE $where" ) )
        die( $this->db->error );
		}
	}

	public function delete( $table, $where ) {
		if ( is_array( $where ) ) {
			$where = implode( array_map( array( $this, 'set_key_value' ), array_keys( $where ), array_values( $where ) ), ' AND ' );
			if ( ! $this->query( "DELETE FROM $table WHERE $where" ) )
        die( $this->db->error );
		}
	}

	public function truncate( $table ) {
		$query = $this->db->query( "TRUNCATE TABLE $table" );
    if ( $query )
      die( $this->db->error );
	}

	private function add_quotes( $value ) {
		return "'" . $this->db->escape_string( $value ) . "'";
	}

	private function set_key_value( $key, $value ) {
		return sprintf( "%s='%s'", $key, $value );
	}
}
