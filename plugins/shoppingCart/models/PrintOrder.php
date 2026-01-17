<?php

/******************************************************************************
 *  
 *  PROJECT: Flynax Classifieds Software
 *  VERSION: 4.9.3
 *  LICENSE: FL99604NUYY8 - https://www.flynax.com/flynax-software-eula.html
 *  PRODUCT: General Classifieds
 *  DOMAIN: store.gmoplus.com
 *  FILE: PRINTORDER.PHP
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

namespace ShoppingCart;

/**
 * @since 3.0.0
 */
class PrintOrder
{
    /**
     * Print page
     */
    public function print()
    {
        global $rlSmarty;

        $rlSmarty->display('controllers/print.tpl');

        exit;
    }

    /**
     * Print order details
     *
     * @param array $data
     */
    public function printShopping($data)
    {
        global $rlSmarty;

        $rlSmarty->assign_by_ref('orderInfo', $data);
        $rlSmarty->display(RL_PLUGINS . 'shoppingCart/view/order_details_print.tpl');
    }

    /**
     * Print auction details
     *
     * @param array $data
     */
    public function printAuction($data)
    {
        global $rlSmarty;

        $rlSmarty->assign_by_ref('auction_info', $data);
        $rlSmarty->display(RL_PLUGINS . 'shoppingCart/view/auction_details.tpl');
    }
}
