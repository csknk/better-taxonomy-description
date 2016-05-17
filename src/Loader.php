<?php
namespace BetterTaxonomyDescription;

/**
 * Class that loads actions and filters
 */
class Loader {

  protected $actions;

  protected $filters;

  public function __construct() {

    $this->actions = [];
    $this->filters = [];

  }

  public function add_action( $hook, $component, $callback ) {

    // add to the array of action hooks
    $this->actions = $this->add( $this->actions, $hook, $component, $callback );

  }

  private function add( $hooks, $hook, $component, $callback ) {

    $hooks[] = [ 'hook' => $hook, 'component' => $component, 'callback' => $callback ];

  }

  public function run() {

    foreach ( $this->filters as $hook ) {

      add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ) );

    }

    foreach ( $this->actions as $hook ) {

      add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ) );

    }

  }

}
