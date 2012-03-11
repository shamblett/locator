<?php
/**
 * Locator Snippet
 *
 * @package locator
 * @author S. Hamblett <steve.hamblett@linux.com> 2012
 */ 

$snippets = array();
$s = $modx->newObject('modSnippet');
$s->set('name', 'locator');
$s->set('description', 'A Simple IP address to country location snippet');
$s->set('snippet', file_get_contents($sources['snippets'] . 'locator.snippet.php'));
$snippets[] = $s;

