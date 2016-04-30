<?php
/**
 * Author: Sean Dunagan
 * Created: 8/22/15
 */

class Reverb_ReverbSync_Model_Log
{
    const LOG_FILE_PREFIX = 'reverb_sync';

    public function setSessionErrorIfAdminIsLoggedIn($error_message)
    {
        if (Mage::helper('reverb_base')->isAdminLoggedIn())
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ReverbSync')->__($error_message));
        }
    }

    public function logCategoryMappingError($error_message)
    {
        $this->logSyncError($error_message, 'category_mapping');
    }

    public function logOrderSyncError($error_message)
    {
        $this->logSyncError($error_message, 'orders');
    }

    public function logShipmentTrackingSyncError($error_message)
    {
        $this->logSyncError($error_message, 'shipment_tracking');
    }

    public function logListingsFetchError($error_message)
    {
        $this->logSyncError($error_message, 'listings_fetch');
    }

    public function logListingSyncError($error_message)
    {
        $this->logSyncError($error_message, 'listings');
    }

    public function logListingImageSyncError($error_message)
    {
        $this->logSyncError($error_message, 'listing_images');
    }

    public function logSyncError($error_message, $sync_process = null)
    {
        if (is_null($sync_process))
        {
            $sync_process = 'orders';
        }

        $log_file = self::LOG_FILE_PREFIX . '_' . $sync_process . '.log';
        Mage::log($error_message, null, $log_file);
    }
}
