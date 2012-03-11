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
 */
$success = true;
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        /* ensure setting is correct on install and upgrade */
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('locator.core_path',null,$modx->getOption('core_path').'components/locator/').'model/';
			$modx->addPackage('locator',$modelPath, 'lo_');
			 
            $setting = $modx->getObject('modSystemSetting',array(
                'key' => 'extension_packages',
            ));
            if (empty($setting)) {
                $setting = $modx->newObject('modSystemSetting');
                $setting->set('key','extension_packages');
                $setting->set('namespace','core');
                $setting->set('xtype','textfield');
                $setting->set('area','system');
            }
            $value = $setting->get('value');
            $value = $modx->fromJSON($value);
            if (empty($value)) {
                $value = array();
                $value['locator'] = array(
                    'path' => '[[++core_path]]components/locator/model/',
                );
                $value = '['.$modx->toJSON($value).']';
            } else {
                $found = false;
                foreach ($value as $k => $v) {
                    foreach ($v as $kk => $vv) {
                        if ($kk == 'locator') {
                            $found = true;
                        }
                    }
                }
                if (!$found) {
                    $value[]['locator'] = array(
                        'path' => '[[++core_path]]components/locator/model/',
                    );
                }
                $value = $modx->toJSON($value);
            }
            $value = str_replace('\\','',$value);
            $setting->set('value',$value);
            $setting->save();

            break;
        /* remove on uninstall */
        case xPDOTransport::ACTION_UNINSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('locator.core_path',null,$modx->getOption('core_path').'components/locator/').'model/';

            $setting = $modx->getObject('modSystemSetting',array(
                'key' => 'extension_packages',
            ));
            $value = $setting->get('value');
            $value = $modx->fromJSON($value);
            unset($value['locator']);
            $value = $modx->toJSON($value);
            $setting->set('value',$value);
            $setting->save();
            break;
    }
}

return $success;
