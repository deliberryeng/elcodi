<?php

namespace Elcodi\Component\CartCoupon\EventListener;

use Elcodi\Component\CartCoupon\Event\CartCouponOnCheckEvent;
use Elcodi\Component\Coupon\Exception\CouponProductNotInCartException;
    

/**
 * Class ProductInCartCouponEventListener
 *
 */
class ProductInCartCouponEventListener
{
    
    /**
     * Construct
     *
     */
    public function __construct()
    {
    }

    /**
     * Check if product is in cart
     *
     * @param CartCouponOnCheckEvent $event Event
     *
     */
    public function checkProductInCart(CartCouponOnCheckEvent $event)
    {
        $productId = $event
            ->getCoupon()
            ->getProduct()
            ->getId();

        $cartLines = $event
            ->getCart()
            ->getCartLines();

        $found = false;
        foreach ($cartLines as $cartLine){
            if ($productId == $cartLine->getProduct()->getId())
            {
                $found = true;
            }
        }

        if (!$found){
            throw new CouponProductNotInCartException();
        }
    }
}
?>
