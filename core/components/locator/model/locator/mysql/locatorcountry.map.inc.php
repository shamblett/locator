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
 * @package locator
 */
$xpdo_meta_map['locatorCountry']= array (
  'package' => 'locator',
  'table' => 'locator_country',
  'fields' => 
  array (
    'ipFrom' => 0,
    'ipTo' => 0,
    'countryCode2' => '',
    'countryCode3' => '',
    'countryName' => '',
  ),
  'fieldMeta' => 
  array (
    'ipFrom' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'ipTo' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'countryCode2' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '2',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'countryCode3' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '3',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'countryName' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
);
