<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014-2015 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 * @author Elcodi Team <tech@elcodi.com>
 */

namespace Elcodi\Component\Cart\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;

use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\Cart\Entity\Interfaces\OrderInterface;
use Elcodi\Component\Cart\Entity\Interfaces\OrderLineInterface;
use Elcodi\Component\Cart\EventDispatcher\OrderLineEventDispatcher;
use Elcodi\Component\Cart\Factory\OrderLineFactory;
use Elcodi\Component\CartLineCoupon\Services\CartLineCouponManager;

/**
 * Class CartLineOrderLineTransformer
 *
 * Api Methods:
 *
 * * createOrderLinesByCartLines(OrderInterface, Collection) : Collection
 * * createOrderLineFromCartLine(OrderInterface, CartLineInterface) : OrderLineInterface
 */
class CartLineOrderLineTransformer
{
    /**
     * @var OrderLineEventDispatcher
     *
     * OrderLineEventDispatcher
     */
    protected $orderLineEventDispatcher;

    /**
     * @var CartLineCouponOrderLineCouponTransformer
     *
     * CartLineCoupon to OrderLineCoupon transformer
     */
    protected $cartLineCouponOrderLineCouponTransformer;

    /**
     * @var OrderLineFactory
     *
     * OrderLine factory
     */
    protected $orderLineFactory;

    /**
     * @var CartLineCouponManager
     *
     * CartLineCoupon manager
     */
    protected $cartLineCouponManager;

    /**
     * @var ObjectManager
     *
     * ObjectManager for Order Line entity
     */
    protected $orderLineObjectManager;

    /**
     * Construct method
     *
     * @param OrderLineEventDispatcher $orderLineEventDispatcher Event dispatcher
     * @param CartLineCouponOrderLineCouponTransformer $cartLineCouponOrderLineCouponTransformer CartLineCoupon to OrderLineCoupon transformer
     * @param OrderLineFactory         $orderLineFactory         OrderLineFactory
     * @param CartLineCouponManager    $cartLineCouponManager    CartLineCoupon manager
     * @param ObjectManager            $orderLineObjectManager   ObjectManager for Order Line entity
     */
    public function __construct(
        OrderLineEventDispatcher $orderLineEventDispatcher,
        CartLineCouponOrderLineCouponTransformer $cartLineCouponOrderLineCouponTransformer,
        OrderLineFactory $orderLineFactory,
        CartLineCouponManager $cartLineCouponManager,
        ObjectManager $orderLineObjectManager
    ) {
        $this->orderLineEventDispatcher = $orderLineEventDispatcher;
        $this->cartLineCouponOrderLineCouponTransformer = $cartLineCouponOrderLineCouponTransformer;
        $this->orderLineFactory = $orderLineFactory;
        $this->cartLineCouponManager = $cartLineCouponManager;
        $this->orderLineObjectManager = $orderLineObjectManager;
    }

    /**
     * Given a set of CartLines, return a set of OrderLines
     *
     * @param OrderInterface $order     Order
     * @param Collection     $cartLines Set of CartLines
     *
     * @return Collection Set of OrderLines
     */
    public function createOrderLinesByCartLines(
        OrderInterface $order,
        Collection $cartLines
    ) {
        $orderLines = new ArrayCollection();

        /**
         * @var CartLineInterface $cartLine
         */
        foreach ($cartLines as $cartLine) {
            $orderLine = $this
                ->createOrderLineByCartLine(
                    $order,
                    $cartLine
                );

            $cartLine->setOrderLine($orderLine);

            $orderLineCoupons = $this
                ->cartLineCouponOrderLineCouponTransformer
                ->createOrderLineCouponsByCartLineCoupons(
                    $orderLine,
                    $this->cartLineCouponManager->getCartLineCoupons($cartLine)
                );

            #$orderLine->setOrderLineCoupons($orderLineCoupons);

            $orderLines->add($orderLine);
        }

        return $orderLines;
    }

    /**
     * Given a cart line, creates a new order line
     *
     * @param OrderInterface    $order    Order
     * @param CartLineInterface $cartLine Cart Line
     *
     * @return OrderLineInterface OrderLine created
     */
    public function createOrderLineByCartLine(
        OrderInterface $order,
        CartLineInterface $cartLine
    ) {
        $orderLine = ($cartLine->getOrderLine() instanceof OrderLineInterface)
            ? $cartLine->getOrderLine()
            : $this->orderLineFactory->create();

        /**
         * @var OrderLineInterface $orderLine
         */
        $orderLine
            ->setOrder($order)
            ->setPurchasable($cartLine->getPurchasable())
            ->setQuantity($cartLine->getQuantity())
            ->setProductAmount($cartLine->getProductAmount())
            ->setAmount($cartLine->getAmount())
            ->setHeight($cartLine->getHeight())
            ->setWidth($cartLine->getWidth())
            ->setDepth($cartLine->getDepth())
            ->setWeight($cartLine->getWeight());

        $this->orderLineObjectManager
            ->persist($orderLine);

        $this
            ->orderLineEventDispatcher
            ->dispatchOrderLineOnCreatedEvent(
                $order,
                $cartLine,
                $orderLine
            );



        return $orderLine;
    }
}
