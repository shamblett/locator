<?php
/**
 * Locator - a location package
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 *
 *
 * @package locator
 */
/**
 * Build Schema script
 *
 * @package locator
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define package name and sources */
define('PKG_NAME','Locator');
define('PKG_NAME_LOWER','locator');

$root = dirname(dirname(__FILE__)).'/';
$sources = array(
    'root' => $root,
    'core' => $root.'core/components/'.PKG_NAME_LOWER.'/',
    'model' => $root.'core/components/'.PKG_NAME_LOWER.'/model/',
    'assets' => $root.'assets/components/'.PKG_NAME_LOWER.'/',
);

/* load modx and configs */
require_once dirname(__FILE__) . '/build.config.php';
include_once MODX_CORE_PATH . 'model/modx/modx.class.php';
require_once dirname(__FILE__) . '/build.properties.php';
$modx= new modX();
$modx->initialize('mgr');
$modx->loadClass('transport.modPackageBuilder','',false, true);
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$remdir = $sources['model'] . 'locator/';

if ($handle = opendir($remdir)) {
    echo "Removing old model classes\n";
    while (false !== ($file = readdir($handle))) {
    	if (substr($file, 0, 1) != '.' ) {
	    	$result = @unlink($remdir.$file);
    	}
    }
    
    closedir($handle);
    
}

$remdir = $sources['model'] . 'locator/mysql/';

if ($handle = opendir($remdir)) {
    echo "Removing old model MYSQL classes\n";
    while (false !== ($file = readdir($handle))) {
    	if (substr($file, 0, 1) != '.' ) {
	    	$result = @unlink($remdir.$file);
    	}
    }
    
    closedir($handle);
    
}

echo "Building new schema\n";
 
foreach (array('mysql') as $driver) {
    $xpdo= new xPDO(
        $properties["{$driver}_string_dsn_nodb"],
        $properties["{$driver}_string_username"],
        $properties["{$driver}_string_password"],
        $properties["{$driver}_array_options"],
        $properties["{$driver}_array_driverOptions"]
    );
    $xpdo->setPackage('modx', dirname(XPDO_CORE_PATH) . '/model/');
    $xpdo->setDebug(true);

    $manager= $xpdo->getManager();
    $generator= $manager->getGenerator();

    $generator->classTemplate= <<<EOD
<?php
/**
 * Locator - a location package
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 *
 *
 * @package locator
 */
/**
 * [+phpdoc-package+]
 */
class [+class+] extends [+extends+] {}
?>
EOD;
    $generator->platformTemplate= <<<EOD
<?php
/**
 * Locator - a location package
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 *
 *
 * @package locator
 */
/**
 * [+phpdoc-package+]
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\\\', '/') . '/[+class-lowercase+].class.php');
class [+class+]_[+platform+] extends [+class+] {}
?>
EOD;
    $generator->mapHeader= <<<EOD
<?php
 /**
 * Locator - a location package
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com> --
 *
 *
 * @package locator
 */
/**
 * [+phpdoc-package+]
 */
EOD;
    $generator->parseSchema($sources['model'] . 'schema/'.PKG_NAME_LOWER.'.'.$driver.'.schema.xml', $sources['model']);
}


$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();
