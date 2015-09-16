<?php

namespace Elcodi\Component\Cart\EventListener;

use Doctrine\Common\Persistence\ObjectManager;

use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\CartLineCouponInterface;
use Elcodi\Component\Cart\Event\CartLineOnEditEvent;
use Elcodi\Component\CartLineCoupon\Services\CartLineCouponManager;
use Elcodi\Component\CartLineCoupon\Services\CartLineCouponRuleManager;
use Elcodi\Component\Currency\Entity\Interfaces\MoneyInterface;
use Elcodi\Component\Currency\Entity\Money;
use Elcodi\Component\Currency\Wrapper\CurrencyWrapper;


class CartLineEditEventListener
{
    
    /**
     * @var ObjectManager
     *
     * ObjectManager for Cart entity
     */
    protected $cartLineObjectManager;

    /**
     * @var CartLineCouponObjectManager
     *
     * CartLineCouponObjectManager
     */
    protected $cartLineCouponObjectManager;

    /**
     * @var CartLineCouponManager
     *
     * CartLineCoupon manager
     */
    protected $cartLineCouponManager;

    /**
     * @var CartLineCouponRuleManager
     *
     * CartLineCoupon Rule manager
     */
    protected $cartLineCouponRuleManager;

    /**
     * @var CurrencyWrapper
     *
     * Currency Wrapper
     */
    protected $currencyWrapper;


    /**
     * Construct method
     *
     * @param ObjectManager             $cartLineObjectManager       ObjectManager for CartLine entity
     * @param ObjectManager             $cartLineCouponObjectManager ObjectManager fir CartLineCoupon entity
     * @param CartLineCouponManager     $cartLineCouponManager       Manager for cartLine coupons 
     * @param CartLineCouponRuleManager $cartLineCouponRuleManager   Manager for cartLine coupon rules
     * @param CurrencyWrapper           $currencyWrapper             Currency wrapper
     */
    public function __construct(
        ObjectManager             $cartLineObjectManager,
        ObjectManager             $cartLineCouponObjectManager,
        CartLineCouponManager $cartLineCouponManager,
        CartLineCouponRuleManager $cartLineCouponRuleManager,
        CurrencyWrapper $currencyWrapper
    ) {
        $this->cartLineObjectManager       = $cartLineObjectManager;
        $this->cartLineCouponObjectManager = $cartLineCouponObjectManager;
        $this->cartLineCouponManager       = $cartLineCouponManager;
        $this->cartLineCouponRuleManager   = $cartLineCouponRuleManager;
        $this->currencyWrapper             = $currencyWrapper;
    }

    /**
     * Method subscribed to CartLineEdit event
     *
     * Refresh cart line coupon amount depending on coupon rule and product qty
     * in cart
     */
    function updateCouponAmount(CartLineOnEditEvent $event)
    {
        $cartLine = $event->getCartLine();
        $total    = Money::create(
            0,
            $this
                ->currencyWrapper
                ->get()
        );

        $cartLineCoupons = $this
            ->cartLineCouponManager
            ->getCartLineCoupons($cartLine);

        foreach ($cartLineCoupons as $cartLineCoupon)
        {
            $total = $total->add($this->refreshCouponAmount($cartLineCoupon, $cartLine));
        }

        $this->refreshCartLineCouponAmount($cartLine, $total);
    }

    protected function refreshCouponAmount(CartLineCouponInterface $cartLineCoupon, CartLineInterface $cartLine)
    {

        $couponAmount = $this
            ->cartLineCouponRuleManager
            ->getCouponAmount(
                $cartLine,
                $cartLineCoupon->getCoupon()
            );
        $cartLineCoupon->setAmount($couponAmount); 

        $this->cartLineCouponObjectManager->persist($cartLineCoupon);
        $this->cartLineCouponObjectManager->flush();

        return $couponAmount;
    }


    protected function refreshCartLineCouponAmount(CartLineInterface $cartLine, $total)
    {
        $cartLine->setCouponAmount($total);
        $this->cartLineObjectManager->persist($cartLine);
        $this->cartLineObjectManager->flush();
    }
}
?>
