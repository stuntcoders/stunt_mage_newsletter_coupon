<?php

class StuntCoders_NewsletterCoupon_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function newsletterSubscriberSaveBefore($observer)
    {
        /** @var Mage_Newsletter_Model_Subscriber $subscriber */
        $subscriber = $observer->getEvent()->getData('subscriber');

        if (!$this->_getHelper()->isEnabled()) {
            return;
        }

        $subscriber->setData('generate_newsletter_coupon', $subscriber->isObjectNew());
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function newsletterSubscriberSaveAfter($observer)
    {
        /** @var Mage_Newsletter_Model_Subscriber $subscriber */
        $subscriber = $observer->getEvent()->getData('subscriber');

        if (!$subscriber->getData('generate_newsletter_coupon') || !$subscriber->isSubscribed()) {
            return;
        }

        try {
            $coupon = $this->_acquireCoupon();

            $subscriber->setData('discount_coupon', $coupon->getCode());
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
        }
    }

    /**
     * @throws Mage_Core_Model_Store_Exception
     * @return Mage_SalesRule_Model_Coupon
     */
    protected function _acquireCoupon()
    {
        /** @var Mage_SalesRule_Model_Rule $rule */
        $rule = $this->_getHelper()->getSalesRule();

        $coupon = Mage::getModel('salesrule/coupon');
        $coupon->setRule($rule)
            ->setIsPrimary(false)
            ->setUsageLimit($rule->getUsesPerCoupon())
            ->setUsagePerCustomer($rule->getUsesPerCustomer())
            ->setExpirationDate($rule->getToDate())
            ->setType(Mage_SalesRule_Helper_Coupon::COUPON_TYPE_SPECIFIC_AUTOGENERATED);

        $attempts = 0;
        do {
            try {
                $coupon->setCode($rule->getCouponCodeGenerator()->generateCode());
                $coupon->save();
            } catch (Exception $e) {
                $attempts++;
            }
        } while (!$coupon->getId() || $attempts >= 10);

        if (!$coupon->getId()) {
            Mage::throwException('Can\'t acquire coupon');
        }

        return $coupon;
    }

    /**
     * @return Stuntcoders_NewsletterCoupon_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('stuntcoders_newslettercoupon');
    }
}
