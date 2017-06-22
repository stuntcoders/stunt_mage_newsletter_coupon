# Mageto Newsletter Coupon

Lightweight Magento module which automatically generates copon codes for first time newsletter subscribers. Module integrates with Magento core Sales Rules, inheriting all the flexibility sales rules provide.

## Configuration

1) Create a Sales Rule (`Promotions -> Shopping Cart Price Rules`) - Configure it as any other slaes rule. Set conditions, actions and discount amount.
2) Choose for which rule should the coupons be generated (`System -> Configuration -> StuntCoders -> Newsletter Coupon`)
3) Add coupon code snippet to the newsletter success email template

## Coupon code snippet
Add to your newsletter success email template to show coupon code:
```
{{depend subscriber.getDiscountCoupon()}}
    Your coupon code is: {{htmlescape var=$subscriber.getDiscountCoupon()}}
{{/depend}}
```

Copyright StuntCoders — [Start Your Online Store Now](https://stuntcoders.com/)
