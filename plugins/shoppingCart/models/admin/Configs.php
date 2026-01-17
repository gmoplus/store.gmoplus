<?php

/******************************************************************************
 *  
 *  PROJECT: Flynax Classifieds Software
 *  VERSION: 4.9.3
 *  LICENSE: FL99604NUYY8 - https://www.flynax.com/flynax-software-eula.html
 *  PRODUCT: General Classifieds
 *  DOMAIN: store.gmoplus.com
 *  FILE: CONFIGS.PHP
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

namespace ShoppingCart\Admin;

use ShoppingCart\Payment;

/**
 * @since 3.0.0
 */
class Configs
{
    /**
     * Save settings
     */
    public function saveSettings()
    {
        global $reefless, $config;

        $configs = $_POST['config'];

        foreach ($configs as $cKey => $cVal) {
            $update[] = array(
                'fields' => array(
                    'Default' => is_array($cVal) ? implode(",", $cVal) : $cVal,
                    'Values' => $cKey == 'shc_shipper_address' ? serialize($_POST['f']) : '',
                ),
                'where' => array(
                    'Key' => $cKey,
                ),
            );
        }

        $action = $GLOBALS['rlDb']->update($update, 'config');

        if ($action) {
            $aUrl = array('controller' => $GLOBALS['controller'], 'module' => 'configs', 'form' => 'settings');

            if ($config['shc_method_currency_convert'] != 'single'
                && $configs['shc_method_currency_convert'] == 'single'
            ) {
                $aUrl['convertPrices'] = 1;
            }

            if ($config['shc_use_multifield'] != $configs['shc_use_multifield']) {
                $shippingFields = new \ShoppingCart\Admin\ShippingFields();
                $shippingFields->controlTypes($configs['shc_use_multifield']);
            }

            if ($configs['shc_method'] == 'multi') {
                $GLOBALS['rlShoppingCart']->checkFieldsPaymentGateways();
            }

            $reefless->loadClass('Notice');
            $GLOBALS['rlNotice']->saveNotice($GLOBALS['lang']['config_saved']);
            $reefless->redirect($aUrl);
        }
    }

    /**
     * Prepare data to settings
     */
    public function prepareData()
    {
        global $rlDb, $rlLang, $rlSmarty, $config;

        $shipperAddress = $rlDb->getOne('Values', "`Key` = 'shc_shipper_address'", 'config');

        if ($shipperAddress) {
            $_POST['f'] = unserialize($shipperAddress);
        }

        $groups = $rlDb->fetch(
            array('ID', 'Key'),
            array('Status' => 'active'),
            null,
            1,
            'listing_groups'
        );

        $groups = $rlLang->replaceLangKeys($groups, 'listing_groups', array('name'), RL_LANG_CODE, 'admin');

        $fields = $rlDb->fetch(
            array('Key', 'ID', 'Type'),
            array('Type' => 'price', 'Status' => 'active'),
            null,
            1,
            'listing_fields'
        );

        $fields = $rlLang->replaceLangKeys($fields, 'listing_fields', array('name'), RL_LANG_CODE, 'common');

        $account_types = $GLOBALS['rlAccount']->getAccountTypes();

        $payment_gateways = $GLOBALS['rlPayment']->getGatewaysAll();

        if ($payment_gateways) {
            foreach ($payment_gateways as $pgKey => $pgValue) {
                $payment_gateways[$pgKey]['name'] = $GLOBALS['lang']['payment_gateways+name+' . $pgValue['Key']];

                $payment_gateways[$pgKey]['escrow'] = 0;
                if (Payment::isEscrow($pgValue['Key'])) {
                    $payment_gateways[$pgKey]['escrow'] = 1;
                }
            }
        }

        $rlSmarty->assign_by_ref('groups', $groups);
        $rlSmarty->assign_by_ref('listing_fields', $fields);
        $rlSmarty->assign_by_ref('account_types', $account_types);
        $rlSmarty->assign_by_ref('payment_gateways', $payment_gateways);
    }
}
