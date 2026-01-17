<?php

/******************************************************************************
 *  
 *  PROJECT: Flynax Classifieds Software
 *  VERSION: 4.9.3
 *  LICENSE: FL99604NUYY8 - https://www.flynax.com/flynax-software-eula.html
 *  PRODUCT: General Classifieds
 *  DOMAIN: store.gmoplus.com
 *  FILE: ESCROWTEST.PHP
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

use ShoppingCart\Payment;

/**
 * @since 3.1.0
 */
class EscrowTest
{
    public function initSafeDeal($orderID)
    {
        $DealID = $GLOBALS['reefless']->generateHash();
        $update = array(
            'fields' => array(
                'Escrow' => '1',
                'Escrow_date' => date('Y-m-d H:i:s', strtotime("+3 months", time())),
                'Deal_ID' => $DealID,
            ),
            'where' => array('ID' => $orderID),

        );
        $GLOBALS['rlDb']->updateOne($update, 'shc_orders');
    }

    /**
     * Confirm order by buyer and make payout to seller
     *
     * @since 3.1.0
     *
     * @param array $txnInfo
     * @return bool
     */
    public function confirmEscrow(array $txnInfo) : bool
    {
        $PayoutID = $GLOBALS['reefless']->generateHash();
        $update = array(
            'fields' => array(
                'Payout_ID' => $PayoutID,
                'Escrow_status' => 'confirmed',
            ),
            'where' => array('ID' => $txnInfo['Item_ID']),

        );

        return $GLOBALS['rlDb']->updateOne($update, 'shc_orders');
    }

    /**
     * Cancel order by buyer and refund payment
     *
     * @since 3.1.0
     *
     * @param array $txnInfo
     * @return bool
     */
    public function cancelEscrow(array $txnInfo) : bool
    {
        $RefundID = $GLOBALS['reefless']->generateHash();
        $update = array(
            'fields' => array(
                'Refund_ID' => $RefundID,
                'Escrow_status' => 'canceled',
                'Status' => 'canceled',
            ),
            'where' => array('ID' => $txnInfo['Item_ID']),
        );

        return $GLOBALS['rlDb']->updateOne($update, 'shc_orders');
    }
}
