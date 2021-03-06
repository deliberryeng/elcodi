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

namespace Elcodi\Component\Payment\EventDispatcher;

use Symfony\Component\EventDispatcher\Event;

use Elcodi\Component\Core\EventDispatcher\Abstracts\AbstractEventDispatcher;
use Elcodi\Component\Payment\ElcodiPaymentEvents;
use Elcodi\Component\Payment\Entity\PaymentMethod;
use Elcodi\Component\Payment\Event\PaymentCollectionEvent;

/**
 * Class PaymentEventDispatcher
 */
class PaymentEventDispatcher extends AbstractEventDispatcher
{
    /**
     * Dispatch payment methods collection
     *
     * @return PaymentMethod[] Payment methods
     */
    public function dispatchPaymentCollectionEvent()
    {
        $event = new PaymentCollectionEvent();

        $this
            ->eventDispatcher
            ->dispatch(
                ElcodiPaymentEvents::PAYMENT_COLLECT,
                $event
            );

        return $event->getPaymentMethods();
    }
}
