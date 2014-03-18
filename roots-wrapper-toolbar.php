<?php
/*
Plugin Name:  Roots Wrapper Toolbar
Plugin URI:   http://github.com/roots/roots-wrapper-toolbar
Description:  A WordPress plugin (or mu-plugin) that displays the base and main templates selected by the Roots wrapper in the WordPress toolbar. Requires the <a href="http://roots.io/">Roots</a> theme and wrapper.
Version:      1.0.0
Author:       Nick Fox
Author URI:   http://roots.io/
License:      MIT
*/

defined('ABSPATH') or die('A common mistake that people make when trying to design something completely foolproof is to underestimate the ingenuity of complete fools. - Douglas Adams');

if (!class_exists('Roots_Wrapper_Toolbar')) {

  class Roots_Wrapper_Toolbar {
    private static $_single; // A singleton will prevent multiple toolbar dropdowns.
    public static $parent; // Use as the node parent if you want to add more dropdowns to the group.

    function __construct() {
      if (!isset(self::$_single)) {
        self::$_single = $this; // Singleton set.
        add_action('admin_bar_menu', array('Roots_Wrapper_Toolbar', 'init'), 999); // Wait until init to do our checks.
      }
    }

    public static function init($toolbar) {
    if (is_admin() || !apply_filters('rwt_user_level', is_super_admin()) || !is_admin_bar_showing() || !class_exists('Roots_Wrapping')) return; // Use is_super_admin for network support.

      global $template;

      $main_template  = Roots_Wrapping::$main_template; // Chosen by the WordPress template hierarchy.
      $base_template  = $template; // Base file selected by the Roots Wrapper.
      self::$parent   = 'rwtb-templates'; // The id for the dropdown group.

      $top = array(
        'id'    => 'rwtb',
        'title' => __('Roots Wrapper', 'roots'),
        'meta'  => array('class' => 'roots-toolbar')
      );

      $group = array(
        'parent' => 'rwtb',
        'id'     => 'rwtb-templates',
        'meta'   => array('class' => 'roots-templates')
      );

      $main = array(
        'parent' => self::$parent,
        'id'     => 'rwtb-main',
        'title'  => __('Main: ', 'roots') . basename($main_template),
        'meta'   => array('class' => 'roots-template-main', 'title' => esc_url($main_template))
      );

      $base = array(
        'parent' => self::$parent,
        'id'     => 'rwtb-base',
        'title'  => __('Base: ', 'roots') . basename($base_template),
        'meta'   => array('class' => 'roots-template-base', 'title' => esc_url($base_template))
      );

      ?>
      <style>
        #wp-admin-bar-rwtb > div:first-child:before { content: "\f115"; top: 2px; }
      </style>
      <?php

      $toolbar->add_node($top); // Add the top level, group and template nodes to the toolbar.
      $toolbar->add_group($group);
      $toolbar->add_node($main);
      $toolbar->add_node($base);
    }
  }

  new Roots_Wrapper_Toolbar();
}

?>
