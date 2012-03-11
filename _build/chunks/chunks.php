<?php
/**
 * locator Chunks
 *
 * @package locator
 * @author S. Hamblett steve.hamblett@linux.com
 */ 

$chunks = array();
$c= $modx->newObject('modChunk');
$c->set('name', 'locatorTpl');
$c->set('description', 'Locator output chunk');
$c->set('snippet', file_get_contents($sources['chunks'] . 'locatorTpl.html'));
$chunks[] = $c;


