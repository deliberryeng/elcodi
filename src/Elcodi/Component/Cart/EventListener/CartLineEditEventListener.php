<?php

namespace Elcodi\Component\Cart\EventListener;

use Doctrine\Common\Persistence\ObjectManager;

use Elcodi\Component\Cart\Entity\Interfaces\CartInterface;
use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\Cart\Event\CartLineOnEditEvent;
use Elcodi\Component\CartCoupon\Services\CartCouponManager;
use Elcodi\Component\CartCoupon\Services\CartCouponRuleManager;

class CartLineEditEventListener
{
    
    /**
     * @var ObjectManager
     *
     * ObjectManager for Cart entity
     */
    protected $cartLineObjectManager;

    /**
     * @var CartCouponRuleManager
     *
     * CartCoupon Rule managers
     */
    protected $cartCouponRuleManager;

    /**
     * @var CartCouponManager
     *
     * CartCouponManager
     */
    protected $cartCouponManager;

    /**
     * Construct method
     *
     * @param ObjectManager         $cartLineObjectManager ObjectManager for CartLine
     *                                                     entity
     * @param CartCouponManager     $cartCouponManager     Cart coupon manager
     * @param CartCouponRuleManager $cartCouponRuleManager Manager for cart coupon rules
     */
    public function __construct(
        ObjectManager         $cartLineObjectManager,
        CartCouponRuleManager $cartCouponRuleManager,
        CartCouponManager     $cartCouponManager
    ) {
        $this->cartLineObjectManager = $cartLineObjectManager;
        $this->cartCouponManager     = $cartCouponManager;
        $this->cartCouponRuleManager = $cartCouponRuleManager;
    }

    /**
     * Method subscribed to CartLineEdit event
     *
     * Refresh cart line coupon amount depending on coupon rule and product qty
     * in cart
     */
    function updateCouponAmount(CartLineOnEditEvent $event)
    {
        $cart     = $event->getCart();
        $cartLine = $event->getCartLine();

        $coupons = $this
            ->cartCouponManager
            ->getCoupons($cart);
        foreach ($coupons as $coupon)
        {
            if ($coupon->getProduct()->getId() == $cartLine->getProduct()->getId()){
                $this->refreshCouponAmount($cartLine, $coupon);
            }
        }
    }

    protected function refreshCouponAmount($cartLine, $coupon)
    {
        $couponAmount = $this
            ->cartCouponRuleManager
            ->getCouponAmount(
                $cartLine,
                $coupon
            );
        $cartLine->setCouponAmount($couponAmount); 

        $this->cartLineObjectManager->persist($cartLine);
        $this->cartLineObjectManager->flush();
    }
}
?>
