<?php

$plugin->block_visibility_conditions = [];
$conditions = &$plugin->block_visibility_conditions;

/**
 * Keep track of control visibility conditions while we map templates
 *
 * @see index.php
 */

return new class($conditions) {

  public $current_block_id  = false;
  public $current_condition = false;
  public $parent_conditions = [];
  public $condition_number  = 0;

  function __construct( &$conditions ) {
    $this->stored_conditions = &$conditions;
  }

  /**
   * Init/Reset data before being used on a new block
   */
  function init( $block_id ) {

    $this->current_block_id  = $block_id;
    $this->stored_conditions[ $this->current_block_id ] = [];

    $this->current_condition = false;
    $this->parent_conditions = [];
    $this->condition_number  = 0;
  }

  function has_conditions() {
    return !empty($this->current_condition);
  }

  function start_condition( $attributes ) {

    // Previous conditions must applied (and stored) to controls until the conditons end
    if( $this->current_condition ) {
      $this->parent_conditions []= $this->current_condition;
    }

    $this->current_condition = [
      'number' => $this->condition_number ++,
      'level'  => 0
    ];

    $this->store_condition( $attributes );
  }

  /**
   * We need to store conditions attributes to be able to evaluate them later
   */
  function store_condition( $attributes ) {

    $condition = &$this->stored_conditions[ $this->current_block_id ];

    $number = $this->current_condition['number'];
    $level  = $this->current_condition['level'];

    if( !isset($condition[ $number ]) ) $condition[ $number ] = [];

    $condition[ $number ][ $level ] = $attributes;
  }

  function end_condition() {

    // Last condition in $parent_conditions is the direct parent, it's the one we want to restore
    $this->current_condition = !empty($this->parent_conditions)
      ? array_pop($this->parent_conditions)
      : false
    ;
  }

  function add_condition_level( $attributes ) {

    if( !$this->current_condition ) return;

    $this->current_condition['level'] ++;

    $this->store_condition( $attributes );
  }

  function active_conditions() {

    $conditions = [];

    $conditions []= $this->current_condition['number'] . '-' . $this->current_condition['level'];

    foreach( $this->parent_conditions as $condition ) {
      $conditions []= $condition['number'] . '-' . $condition['level'];
    }

    return $conditions;
  }

};
