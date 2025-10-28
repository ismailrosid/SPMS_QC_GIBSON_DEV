<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Preferences Option Value
|--------------------------------------------------------------------------
*/
$config['division'] = array(
	'AG'	=> 'Accoustic Guitar', 
	'EG'	=> 'Electric Guitar'
);

$config['production_process'] = array(
	'set'	=> "Set",
	'bolt'	=> "Bolt",
	'jazz'	=> "Jazz",
	'uk'	=> "UK"
);

$config['production_step'] = array(
	'd_process_1', 'd_process_2', 'd_process_3',
	'd_process_4', 'd_process_5', 'd_process_6',
	'd_process_7', 'd_process_8', 'd_process_9',
	'd_process_10', 'd_process_11', 'd_process_12',
	'd_process_13', 'd_process_14', 'd_process_15',
	'd_warehouse'
);

$config['serial_reset'] = array(
	'month'	=> 'Monthly', 
	'year'	=> 'Yearly'
);

$config['product_location'] = array(
	'AG_N' => 'AG_N',
	'AG_C' => 'AG_C',
	'EG' => 'EG',
	'EG_1' => 'EG_1',
	'EG_2' => 'EG_2'
);

$config['model_difficult'] = array(
	'A' => 'A',
	'B' => 'B',
	'C' => 'C',
	'D' => 'D'
);

$config['serial_digit'] = array(3, 4, 5)
;

$config['product_line'] = array(
	1 => 'Line 1', 
	2 => 'Line 2'
);

$config['status_export'] = array(
	'export'	=> 'Export', 
	'process' 	=> 'Process'
);

$config['list_month'] = array();
$aCalInfo = cal_info(0);
for ($nMonth=1; $nMonth<=12; $nMonth++) {
	$config['list_month'][$nMonth] = $aCalInfo['months'][$nMonth];
}
