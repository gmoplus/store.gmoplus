<?php

/******************************************************************************
 *  
 *  PROJECT: Flynax Classifieds Software
 *  VERSION: 4.9.3
 *  LICENSE: FL99604NUYY8 - https://www.flynax.com/flynax-software-eula.html
 *  PRODUCT: General Classifieds
 *  DOMAIN: store.gmoplus.com
 *  FILE: AUCTION_PAYMENT.INC.PHP
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

use \ShoppingCart\AuctionPayment;

require_once RL_PLUGINS . 'shoppingCart/static.inc.php';

if (false === IS_BOT) {
    if (isset($_REQUEST['xjxfun'])) {
        die('xajax restricted in "auction_payment" controller');
    }

    $errors = array();
    $no_access = false;
    $lang = array_merge($lang, $rlShoppingCart->getPhrases(['my_shopping_cart']));

    // remove unavailable steps
    foreach ($shc_steps as $k => $v) {
        if (!$v['auction']) {
            unset($shc_steps[$k]);
        }
    }

    $rlHook->load('shoppingCartAuctionProcessTop', $shc_steps, $errors, $no_access);

    $rlSmarty->assign('shc_steps', $shc_steps);

    if (!$errors && !$no_access) {
        // Remove instance
        if (!$_POST['from_post']
            && !array_key_exists($_GET['nvar_1'], $shc_steps)
            && $_GET['nvar_1'] != 'done'
            && !$_GET['step']
            && !isset($_GET['edit'])
        ) {
            AuctionPayment::removeInstance();
        }

        // Get/create AuctionPayment instance
        $auctionProcessing = AuctionPayment::getInstance();

        $rlHook->load('shoppingCartAuctionProcessInit', $auctionProcessing);

        // Set default config
        $shopping_cart_config = [
            'controller' => 'shc_auction_payment',
            'pageKey' => $page_info['Key'],
            'steps' => &$shc_steps,
        ];

        $auctionProcessing->setConfig($shopping_cart_config);

        // Initialize
        $auctionProcessing->init();

        // Process step
        $auctionProcessing->processStep();

        // Save instance
        AuctionPayment::saveInstance($auctionProcessing);
    }

    $rlSmarty->assign('no_access', $no_access);

    $rlHook->load('shoppingCartAuctionProcessBottom');
}
