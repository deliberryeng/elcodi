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

namespace Elcodi\Component\Plugin\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Elcodi\Component\Plugin\Command\Abstracts\AbstractPluginEnableCommand;
use Elcodi\Component\Plugin\Entity\Plugin;

/**
 * Class PluginEnableCommand
 */
class PluginEnableCommand extends AbstractPluginEnableCommand
{
    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('elcodi:plugin:enable')
            ->setAliases([
                'plugin:enable',
            ])
            ->setDescription('Enable a plugin')
            ->addArgument(
                'hash',
                InputArgument::REQUIRED,
                'Plugin hash'
            );
    }

    /**
     * This command loads all the exchange rates from base_currency to all available
     * currencies
     *
     * @param InputInterface  $input  The input interface
     * @param OutputInterface $output The output interface
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->startCommand($output);
        $pluginHash = $input->getArgument('hash');
        $plugin = $this
            ->pluginRepository
            ->findOneBy([
                'hash' => $pluginHash,
            ]);

        if (!($plugin instanceof Plugin)) {
            $this->printMessageFail(
                $output,
                'Plugin',
                'Plugin with hash "' . $pluginHash . '" not found'
            );

            return null;
        }

        $plugin->enable();
        $this
            ->pluginObjectManager
            ->flush($plugin);

        $this->printMessage(
            $output,
            'Plugin',
            'Plugin with hash "' . $pluginHash . '" enabled'
        );

        $this->finishCommand($output);
    }
}
