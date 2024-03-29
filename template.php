<?php
/**
 * @file
 * Contains theme override functions and preprocess functions for the theme.
 *
 * ABOUT THE TEMPLATE.PHP FILE
 *
 *   The template.php file is one of the most useful files when creating or
 *   modifying Drupal themes. You can modify or override Drupal's theme
 *   functions, intercept or make additional variables available to your theme,
 *   and create custom PHP logic. For more information, please visit the Theme
 *   Developer's Guide on Drupal.org: http://drupal.org/theme-guide
 *
 * OVERRIDING THEME FUNCTIONS
 *
 *   The Drupal theme system uses special theme functions to generate HTML
 *   output automatically. Often we wish to customize this HTML output. To do
 *   this, we have to override the theme function. You have to first find the
 *   theme function that generates the output, and then "catch" it and modify it
 *   here. The easiest way to do it is to copy the original function in its
 *   entirety and paste it here, changing the prefix from theme_ to tmemes_.
 *   For example:
 *
 *     original: theme_breadcrumb()
 *     theme override: tmemes_breadcrumb()
 *
 *   where tmemes is the name of your sub-theme. For example, the
 *   zen_classic theme would define a zen_classic_breadcrumb() function.
 *
 *   If you would like to override either of the two theme functions used in Zen
 *   core, you should first look at how Zen core implements those functions:
 *     theme_breadcrumbs()      in zen/template.php
 *     theme_menu_local_tasks() in zen/template.php
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called template suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node-forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and template suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440
 *   and http://drupal.org/node/190815#template-suggestions
 */

/* Set message, if less module doesn't exist. */
if (!module_exists('less')){
  drupal_set_message(t('The module <a href="http://drupal.org/project/less">less</a> doesn\'t exist. Download, and enable it"'), 'warning');
}

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
/* -- Delete this line if you want to use this function
function tmemes_preprocess_html(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // The body tag's classes are controlled by the $classes_array variable. To
  // remove a class from $classes_array, use array_diff().
  //$variables['classes_array'] = array_diff($variables['classes_array'], array('class-to-remove'));
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */

function tmemes_preprocess_page(&$vars, $hook) {
    //Add only content tpl.php if we are on colorbox page

  if (isset($_GET['colorbox'])) {
    $vars['theme_hook_suggestions'][] = 'page__null' ;
  }
  //dsm(get_defined_vars());
}
// */

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */

function tmemes_preprocess_node(&$variables, $hook) {
  $node = $variables['node'];
  $variables['date'] = format_date($node->created, 'short');
  $variables['printed_date'] = tmemes_printed_date($variables['date']);
  // Optionally, run node-type-specific preprocess functions, like
  // tmemes_preprocess_node_page() or tmemes_preprocess_node_story().
  $function = __FUNCTION__ . '_' . $variables['node']->type;
  if (function_exists($function)) {
    $function($variables, $hook);
  }
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */

function tmemes_preprocess_comment(&$variables, $hook) {
  $comment = $variables['elements']['#comment'];
  $node = $variables['elements']['#node'];
  $variables['created'] = format_date($comment->created, 'short');
  $variables['submitted'] = t('Submitted by !username on !datetime', array('!username' => $variables['author'], '!datetime' => $variables['created']));
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */

function tmemes_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  $variables['classes_array'][] = 'count-' . $variables['block_id'];
  if ($variables['block']->subject) {
    $variables['classes_array'][] = 'with-title';
  }

  if ($variables['block']->region == 'sidebar_first' || $variables['block']->region == 'sidebar_second') {
    $variables['classes_array'][] = 'in-sidebar';
  }
}


function tmemes_printed_date($date) {
  $output = '';
  $date = explode(' ', $date);

  $output .= '<span class="date-first">' . $date[0] . '</span>';
  $output .= '<span class="date-last">' . $date[1] . '</span>';
  return $output;
}