<?php

class Reverb_ReverbSync_Adminhtml_RevOfflineSync_SyncController extends Mage_Core_Controller_Front_Action {

    //offline syn function
    public function productSyncAction() {
        try {
            $isEnabled = Mage::getStoreConfig('ReverbSync/extensionOption_group/module_select');
            if (!$isEnabled)
                return;
            $revConnection = Mage::helper('ReverbSync/connection') -> revConnection();
            $productId = $this -> getRequest() -> getParam('product_id');
            $product = Mage::getModel('catalog/product') -> load($productId);
            $stock = Mage::getModel('cataloginventory/stock_item') -> loadByProduct($product);
            //load the product
            $revSync = $product -> getRevSync();
            $productType = $product -> getTypeID();
            if ($productType != 'simple') {
                die("Only simple products can be synced.");
            }
            if (!$revSync) {

                die("Please enable Sync to Reverb and click on Save.");
            }
            $mapperModel = Mage::getModel('reverbSync/Mapper_Product');
            //map the product
            $fieldsArray = $mapperModel -> productMapping($product);
            //pass the data to create the product in Reverb

            try {
                $responseData = Mage::helper('ReverbSync/data') -> createObject($revConnection, $fieldsArray, 'listings');
                $revPurl = parse_url($responseData, PHP_URL_PATH);
                $revPid = explode("/", $revPurl);
                $revPid = explode('-', $revPid[2]);
                $revPid = $revPid[0];
                $product -> setRevProductId($revPid);
                $product -> setRevProductUrl($responseData);
                //$product -> save();
                $product -> getResource() -> saveAttribute($product, 'rev_product_id');
                $product -> getResource() -> saveAttribute($product, 'rev_product_url');
                Mage::helper('ReverbSync/data') -> reverbReports($productId, $product -> getName(), $product -> getSku(), $stock -> getQty(), $responseData, 1, null);
                $sucessBlock = $this -> getLayout() -> createBlock('Mage_Core_Block_Template', 'ReverbSync', array('template' => 'ReverbSync/product/productsync.phtml'));
                $sucessBlock -> setRevProductSync($revPid);
                $sucessBlock -> setRevProductUrlSync($responseData);
                echo $sucessBlock -> toHTML();
            } catch(Exception $e) {
                $errorMessage = $this -> __($e -> getMessage());
                Mage::helper('ReverbSync/data') -> reverbReports($productId, $product -> getName(), $product -> getSku(), $stock -> getQty(), null, 0, $e -> getMessage());
                $errorBlock = $this -> getLayout() -> createBlock('Mage_Core_Block_Template', 'Reverb_ReverbSync', array('template' => 'ReverbSync/product/productfailsync.phtml'));
                $errorBlock -> setErrorMessage($errorMessage);
                echo $errorBlock -> toHTML();
            }
        } catch(Exception $e) {
            $excp = 'Message: ' . $e -> getMessage();
            Mage::log($excp);
        }
    }

}
?>
