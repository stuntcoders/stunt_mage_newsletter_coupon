<?php

class StuntCoders_NewsletterCoupon_Model_System_Config_Source_Discount_Rule
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array(array(
            'value' => '',
            'label' => Mage::helper('stuntcoders_newslettercoupon')->__('-- Select Rule --'),
        ));

        $rulesCollection = Mage::getResourceModel('salesrule/rule_collection');
        foreach ($rulesCollection as $rule) {
            $options[] = array(
                'value' => $rule->getData('rule_id'),
                'label' => $rule->getName()
            );
        }

        return $options;
    }
}
