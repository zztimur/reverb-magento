<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 4/18/16
 */

class Reverb_ReverbSync_Model_Field_Mapping extends Mage_Core_Model_Abstract
{
    const MAGENTO_ATTRIBUTE_FIELD = 'magento_attribute_code';
    const REVERB_API_FIELD_FIELD = 'reverb_api_field';

    public function getMagentoAttributeCode()
    {
        return $this->getData(self::MAGENTO_ATTRIBUTE_FIELD);
    }

    public function getReverbApiField()
    {
        return $this->getData(self::REVERB_API_FIELD_FIELD);
    }

    protected function _construct()
    {
        $this->_init('reverbSync/field_mapping');
    }
}
