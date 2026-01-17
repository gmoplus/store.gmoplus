<?php

/******************************************************************************
 *  
 *  PROJECT: Flynax Classifieds Software
 *  VERSION: 4.9.3
 *  LICENSE: FL99604NUYY8 - https://www.flynax.com/flynax-software-eula.html
 *  PRODUCT: General Classifieds
 *  DOMAIN: store.gmoplus.com
 *  FILE: UPDATELISTINGS.PHP
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

/**
 * @since 3.0.0
 */
class UpdateListings
{
    /**
     * Update listing options
     *
     * @param int $limit
     */
    public function update($limit = 100)
    {
        global $rlDb, $config;

        $settings = $_SESSION['updateListings']['data'];

        if (!$settings) {
            return;
        }

        $listingTypes = $settings['types'] ? implode(',', $settings['types']) : '';
        $accountTypes = $settings['atypes'] ? implode(',', $settings['atypes']) : '';
        $f_price = $config['price_tag_field'];

        $sql = "SELECT `T1`.`ID`, `T1`.`{$f_price}`, `T3`.`Type` ";
        $sql .= "FROM `{db_prefix}listings` AS `T1` ";
        $sql .= "LEFT JOIN `{db_prefix}accounts` AS `T2` ON `T1`.`Account_ID` = `T2`.`ID` ";
        $sql .= "LEFT JOIN `{db_prefix}categories` AS `T3` ON `T1`.`Category_ID` = `T3`.`ID` ";
        $sql .= "WHERE `T1`.`Status` = 'active' AND FIND_IN_SET(`T3`.`Type`, '{$listingTypes}') > 0 ";
        $sql .= "AND `T1`.`shc_mode` = 'listing' AND `T1`.`ID` <= {$config['shc_update_listings']} ";
        if (!empty($accountTypes)) {
            $sql .= "AND FIND_IN_SET(`T2`.`Type`, '{$accountTypes}') > 0 ";
        }
        $sql .= "LIMIT {$limit}";

        $data = $rlDb->getAll($sql);

        if ($data) {
            $priceFormat = new \ShoppingCart\PriceFormat();
            foreach ($data as $key => $value) {
                $typeInfo = $settings[$value['Type']];

                $options = [
                    'shc_mode' => $typeInfo['shc_mode'],
                    'shc_quantity' => (int) $typeInfo['quantity'],
                    'shc_available' => 1,
                    'shc_shipping_price_type' => 'free',
                ];

                if ($typeInfo['shc_mode'] == 'auction') {
                    $value[$f_price] = explode('|', $value[$f_price]);
                    $price = $value[$f_price][0];
                    $startPrice = round(($typeInfo['start_price'] * $price) / 100, 2);
                    $options['shc_start_price'] = $startPrice;
                    $options['shc_reserved_price'] = $price;
                    $options['shc_days'] = (int) $typeInfo['days'];
                    $options['shc_bid_step'] = (float) $typeInfo['bid_step'];
                }

                $priceFormat::saveOptions($value['ID'], $options);
            }
        }
    }

    /**
     * Get total listings
     *
     * @param array $settings
     */
    public function getTotal($settings = [])
    {
        if (!$settings) {
            return 0;
        }

        $listingTypes = implode(',', (array) $settings['types']);
        $accountTypes = implode(',', (array) $settings['atypes']);

        $sql = "SELECT COUNT(`T1`.`ID`) AS `total` ";
        $sql .= "FROM `{db_prefix}listings` AS `T1` ";
        $sql .= "LEFT JOIN `{db_prefix}accounts` AS `T2` ON `T1`.`Account_ID` = `T2`.`ID` ";
        $sql .= "LEFT JOIN `{db_prefix}categories` AS `T3` ON `T1`.`Category_ID` = `T3`.`ID` ";
        $sql .= "WHERE `T1`.`Status` = 'active' AND FIND_IN_SET(`T3`.`Type`, '{$listingTypes}') > 0 AND `T1`.`shc_mode` = 'listing' ";
        if (!empty($accountTypes)) {
            $sql .= "AND FIND_IN_SET(`T2`.`Type`, '{$accountTypes}') > 0";
        }

        $row = $GLOBALS['rlDb']->getRow($sql);

        return (int) $row['total'];
    }
}
