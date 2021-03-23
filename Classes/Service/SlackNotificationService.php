<?php

/**
 * Send notifications to a slack channel
 * php version 7.1
 *
 * Copyright © 2021 Micha Grandel
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the
 *
 * Free Software Foundation, Inc.
 * 59 Temple Place, Suite 330
 * Boston, MA 02111-1307 USA
 *
 * @author Micha Grandel <micha@plywoodpirate.de>
 * @package Plywoodpirate\SlackNotifications\Log\Writer
 *
 * @category Service
 * @copyright 2021 Micha Grandel, all rights reserved
 * @license https://opensource.org/licenses/GPL-2.0 GNU General Public License
 *     2.0
 *
 * @link https://git.plywoopirate.de/
 * @link https://github.com/Plywoodpirate/slack_notifications
 */

declare(strict_types=1);

namespace Plywoodpirate\SlackNotifications\Service;

use GuzzleHttp\Exception\RequestException;
use Plywoodpirate\SlackNotifications\Utility\ExtensionUtility;
use Psr\Http\Message\RequestFactoryInterface;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Service\AbstractService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Send notifications to slack
 *
 * This service use Slack's webHooks to send notifications to a slack channel.
 * You may configure the web hook in the extension settings. See
 * ext_conf_template.txt for more details.
 *
 * @see https://github.com/Plywoodpirate/slack_notifications/blob/main/ext_conf_template.txt
 * @author Micha Grandel <micha@plywoodpirate.de>
 * @copyright 2021 Micha Grandel, all rights reserved
 * @link https://github.com/Plywoodpirate/slack_notifications/blob/main/Classes/Service/SlackNotificationService.php
 * @package Plywoodpirate\SlackNotifications\Service
 */
class SlackNotificationService extends AbstractService
{

    /** @var RequestFactoryInterface */
    protected $requestFactory;

    /** @var string webHook */
    protected $webHook;

    public function __construct(RequestFactoryInterface $requestFactory)
    {
        $this->requestFactory = $requestFactory;
        $this->setWebHook();
    }

    /**
     * Set web hook for Slack messages
     *
     * If the parameter $webHook is empty, the method will try to load
     * the webhook URL from extension configuration. In this case, the method
     * may throw two exceptions - a Not-Configured Exception or
     * Path-Does-Not-Exists Exception. These exceptions indicate that the
     * developer did something wrong and should not be caught, as documented in
     * the TYPO3 Core.
     *
     * Read the documentation of ExceptionConfiguration class for more details.
     *
     * @param string $webHook
     *
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     *
     * @see TYPO3\CMS\Core\Configuration\ExtensionConfiguration::get()
     */
    public function setWebHook(string $webHook = null): void
    {
        if (empty($webHook)) {
            // read webhook url from extension configuration
            /** @var ExtensionConfiguration $configuration */
            $configuration = GeneralUtility::makeInstance(
                ExtensionConfiguration::class
            );
            $webHook = $configuration->get(
                ExtensionUtility::EXTENSION_KEY,
                'slack/webHookUrl'
            );
        }

        $this->webHook = $webHook;
    }

    /**
     * Send a request to a slack webhook.
     *
     * The method needs a slack WebHook, i.e., an url to send a request with
     * the message. See self::setWebHook() for more details.
     *
     * @param string $requestBody request body
     *
     * @return void
     *
     * @see setWebHook()
     * @see RequestFactory
     *
     */
    public function sendRequest(string $requestBody)
    {
        if (empty($requestBody)) {
            $requestBody = json_encode(['text' => '']);
        }
        // send HTTP request
        try {
            $options = [
                'headers' => ['Content-type' => 'application/json'],
                'body' => $requestBody,
            ];
            $response = $this->requestFactory->request(
                $this->webHook,
                'POST',
                $options
            );
        } catch (RequestException $exception) {
            /** @var Logger $logger */
            $logger = GeneralUtility::makeInstance(ObjectManager::class)->get(
                LogManager::class
            )->getLogger();
            $logger->error(
                sprintf(
                    "Cannot load webhook \"%s\": %s",
                    $this->webHook,
                    $exception->getMessage()
                )
            );
        }
    }
}
