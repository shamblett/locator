<?php

/**
 * Locator - A Simple IP address to country location snippet
 *
 * @author S. Hamblett <steve.hamblett@linux.com> 2012
 *
 * @package  locator
 * 
 * Parameters :-
 * 
 * ipAddress            - IP address in standard xxx.xxx.xxx.xxx format, if the string
 *                               'remote' is specified then the incoming IP address as returned by
 *                               $_SERVER["REMOTE_ADDR"] is used.
 * tpl                        - Output template to use, if not set only placeholders are set
 * toJSON               - Return the dataset as a JSON string defaults to 0

 * 
 * Placeholders, all prefixed with locator :-
 * 
 * ipAddress                     - IP address supplied
 * countryCode2               - 2 letter country code based on ISO 3166
 * countryCode3               - 3 letter country code based on ISO 3166
 * countryName                - Country name based on ISO 3166
 * modxCountryCode        - Country code from the MODX countries file(en)
 * error                              - Set to 'None' if no error or an error code string.
 * 
 * Examples : 
 * 
 *           Place holders only - [[!locator? &ipAddress=`82.28.123.128` ]] 
 *           Plus template and incoming IP address  - [[!locator? &ipAddress=`remote` &tpl=`locatorChunk` ]]
 *           To JSON - [[!locator? &ipAddress=`82.28.123.128` &toJSON=`1` ]]
 */

/* Add the locator package, we need to do this because of our table prefix */
$modelPath = $modx->getOption('locator.core_path', null, $modx->getOption('core_path') . 'components/locator/') . 'model/';
$modx->addPackage('locator', $modelPath, 'lo_');

/* Initialise the parameter set */
$ipAddress = $ipAddress == 'remote' ? $_SERVER["REMOTE_ADDR"] : $ipAddress;
$tpl = !empty($tpl) ? $tpl : '';
$toJSON = $toJSON == 1 ? true : false;

/* Convert the IP address */
$ipNumber = ip2long($ipAddress);
if ($ipNumber === false) {

    $modx->toPlaceholder('error', 'IP Address Conversion Error', 'locator');
    if ($tpl != '') {
        $output = $modx->getChunk($tpl, array('error' => 'IP Address Conversion Error'));
        return $output;
    }
    return;
}

/* Do the look up */
$c = $modx->newQuery('locatorCountry');
$c->where(array('ipFrom:<=' => $ipNumber));
$c->andCondition(array('ipTo:>=' => $ipNumber));
$countryObject = $modx->getObject('locatorCountry', $c);
if (!$countryObject) {

    $modx->toPlaceholder('error', 'No Country Found', 'locator');
    if ($tpl != '') {
        $output = $modx->getChunk($tpl, array('locator.error' => 'No Country Found'));
        return $output;
    }
    return;
}

$countryInfo = $countryObject->toArray();
$countryInfo['ipAddress'] = $ipAddress;

/* Get the MODX  country number, use 'en' for ISO 3166 */
include $modx->getOption('core_path') . "lexicon/country/en.inc.php";
if (!isset($_country_lang)) {

    $modx->toPlaceholder('error', 'No MODX Country File', 'locator');
    if ($tpl != '') {
        $output = $modx->getChunk($tpl, array('locator.error' => 'No MODX Country File'));
        return $output;
    }
    return;
}
foreach ($_country_lang as $countryKey => $countryName) {

    if (trim(strtolower($countryName)) == trim(strtolower($countryInfo['countryName'])))
        $countryInfo['modxCountryCode'] = $countryKey;
}

if ( !isset( $countryInfo['modxCountryCode'] )) {
    
     $modx->toPlaceholder('error', 'No MODX Country Code', 'locator');
    if ($tpl != '') {
        $output = $modx->getChunk($tpl, array('locator.error' => 'No MODX Country Code'));
        return $output;
    }
    return;
    
}
/* Return the country information */
$countryInfo['error'] = 'None';
$modx->toPlaceholders($countryInfo, 'locator');

if ($toJSON) {

    $countryString = json_encode($countryInfo);
    return $countryString;
}

if ($tpl != '') {
    $output = $modx->getChunk($tpl, $countryInfo);
    return $output;
}




