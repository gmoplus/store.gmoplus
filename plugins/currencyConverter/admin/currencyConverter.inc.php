<?php

/******************************************************************************
 *  
 *  PROJECT: Flynax Classifieds Software
 *  VERSION: 4.9.3
 *  LICENSE: FL99604NUYY8 - https://www.flynax.com/flynax-software-eula.html
 *  PRODUCT: General Classifieds
 *  DOMAIN: store.gmoplus.com
 *  FILE: CURRENCYCONVERTER.INC.PHP
 *  
 *  The software is a commercial product delivered under single, non-exclusive,
 *  non-transferable license for one domain or IP address. Therefore distribution,
 *  sale or transfer of the file in whole or in part without permission of Flynax
 *  respective owners is considered to be illegal and breach of Flynax License End
 *  User Agreement.
 *  
 *  You are not allowed to remove this information from the file without permission
 *  of Flynax respective owners.
 *  
 *  Flynax Classifieds Software 2025 | All copyrights reserved.
 *  
 *  https://www.flynax.com
 ******************************************************************************/

// ext js action
if ($_GET['q'] == 'ext') {
	// include system config
	require_once('../../../includes/config.inc.php');
	require_once(RL_ADMIN_CONTROL . 'ext_header.inc.php');
	require_once(RL_LIBS . 'system.lib.php');
	
	// date update
	if ($_GET['action'] == 'update') {
		$reefless->loadClass('Actions');
		
		$type = $rlValid->xSql($_GET['type']);
		$field = $rlValid->xSql($_GET['field']);
		$value = $rlValid->xSql(nl2br($_GET['value']));
		$id = $rlValid->xSql($_GET['id']);
		$key = $rlValid->xSql($_GET['key']);

		$updateData = array(
			'fields' => array(
				$field => $value
			),
			'where' => array(
				'ID' => $id
			)
		);

        // clear position if sticky disabled
        if ($field == 'Sticky' && $value == '0') {
            $updateData['fields']['Position'] = 0;
        }
        // Move currency to top if symbol has been set/changed
        elseif ($field == 'Symbol' && $value != '') {
            $position = $rlDb->getRow("SELECT MAX(`Position`) AS `Max` FROM `{db_prefix}currency_rate` WHERE `Sticky` = '1'", 'Max');

            $updateData['fields']['Position'] = $position + 1;
            $updateData['fields']['Sticky'] = '1';
        }
		
		$rlActions->updateOne($updateData, 'currency_rate');
		
		// update hook
		$reefless->loadClass('CurrencyConverter', null, 'currencyConverter');
		$rlCurrencyConverter->updateHook();
		
		exit;
	}
	
	// fetch data
	$limit = (int) $_GET['limit'];
	$start = (int) $_GET['start'];
    $sort_field = $rlValid->xSql($_GET['sort']);
    $sort_type = $_GET['dir'];

	$rlDb->setTable('currency_rate');
	$data = $rlDb->fetch('*', null, "ORDER BY `{$sort_field}` {$sort_type}", array($start, $limit));
	$rlDb->resetTable();
	
	foreach ($data as &$item) {
        $item['Status'] = $lang[$item['Status']];
		$item['Sticky_original'] = $item['Sticky'];
        $item['Sticky'] = $item['Sticky'] ? $lang['yes'] : $lang['no'];
	}

	$count = $rlDb->getRow("SELECT COUNT(`ID`) AS `Count` FROM `" . RL_DBPREFIX . "currency_rate`", 'Count');

	$output['total'] = $count;
	$output['data'] = $data;

	echo json_encode($output);
}
