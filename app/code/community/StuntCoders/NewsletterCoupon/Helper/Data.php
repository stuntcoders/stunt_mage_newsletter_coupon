<?php

class StuntCoders_NewsletterCoupon_Helper_Data extends Mage_Core_Helper_Abstract
{
    const ENABLED_CONFIG_PATH = 'stuntcoders_newslettercoupon/general/enabled';
    const DISCOUNT_RULE_CONFIG_PATH = 'stuntcoders_newslettercoupon/general/discount_rule';

    /**
     * @param mixed $storeId
     * @return boolean
     */
    public function isEnabled($storeId = null)
    {
        return Mage::getStoreConfigFlag(self::ENABLED_CONFIG_PATH, $storeId);
    }

    /**
     * @param mixed $storeId
     * @return string
     */
    public function getRuleId($storeId = null)
    {
        return Mage::getStoreConfig(self::DISCOUNT_RULE_CONFIG_PATH, $storeId);
    }

    /**
     * @param mixed $storeId
     * @throws Mage_Core_Model_Store_Exception
     * @return Mage_SalesRule_Model_Rule
     */
    public function getSalesRule($storeId = null)
    {
        $rule = Mage::getModel('salesrule/rule')->load($this->getRuleId($storeId));

        if (!$rule->getId()) {
            Mage::throwException('Newsletter discount rule does not exist');
        }

        return $rule;
    }
}
