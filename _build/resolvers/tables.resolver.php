<?php
/**
 * Locator - a location package 
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * @package locator
 */
/**
 * locator build script
 *
 * @package locator
 * @subpackage build
 *
 * Creates the tables on install
 *
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('locator.core_path',null,$modx->getOption('core_path').'components/locator/').'model/';
            $modx->addPackage('locator',$modelPath, 'lo_');

            $modx->setLogLevel(modX::LOG_LEVEL_ERROR);
            
            /*Create the table and  load with the ip/country data */
            $schemaPath = $modelPath . "schema/";
            $sqlFile = $schemaPath . "locator.sql";
            $sqlFileContents = file_get_contents($sqlFile);
            $sqlStatements = explode(';', $sqlFileContents);
            foreach ( $sqlStatements as $sql) {
                
                $modx->exec($sql);
            }
            $modx->setLogLevel(modX::LOG_LEVEL_INFO);
            break;
    }
}
return true;
