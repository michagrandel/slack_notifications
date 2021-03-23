<?php

/**
 * Log messages as notifications to a slack channel
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
 * @category LogWriter
 * @copyright 2021 Micha Grandel, all rights reserved
 * @license https://opensource.org/licenses/GPL-2.0 GNU General Public License 2.0
 *
 * @link https://git.plywoopirate.de/
 * @link https://github.com/Plywoodpirate/slack_notifications
 */

declare(strict_types=1);

namespace Plywoodpirate\SlackNotifications\Log\Writer;

use Plywoodpirate\SlackNotifications\Service\SlackNotificationService;
use Plywoodpirate\SlackNotifications\Utility\SlackBlockUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Log\Exception\InvalidLogWriterConfigurationException;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\Writer\AbstractWriter;
use TYPO3\CMS\Core\Log\Writer\WriterInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Send log messages as notifications to a slack channel
 *
 * This LogWriter uses slack webhooks to send log messages to a Slack Channel.
 * You may configure the web hook in the extension settings. See
 * ext_conf_template.txt for more details.
 *
 * @author Micha Grandel <micha@plywoodpirate.de>
 * @package Plywoodpirate\SlackNotifications\Service
 * @copyright 2021 Micha Grandel, all rights reserved
 * @link https://github.com/Plywoodpirate/slack_notifications/blob/main/Classes/Log/Writer/SlackNotificationWriter.php
 * @see https://github.com/Plywoodpirate/slack_notifications/blob/main/ext_conf_template.txt
 */
class SlackNotificationWriter extends AbstractWriter implements WriterInterface
{
    /**
     * Constructor
     *
     * @param array $options Configuration options - depends on the actual log writer
     *
     * @throws InvalidLogWriterConfigurationException
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    /**
     * Send log record as notification to a Slack channel
     *
     * The methods uses a slack notification service to handle all slack
     * related methods.
     *
     * @param LogRecord $record
     *
     * @return WriterInterface $this
     *
     */
    public function writeLog(LogRecord $record): WriterInterface
    {
        /** @var SlackNotificationService $slackNotificationService */
        $slackNotificationService = GeneralUtility::makeInstance(
            SlackNotificationService::class,
            GeneralUtility::makeInstance(RequestFactory::class)
        );

        if (empty($record['data'])) {
            // send a simple slack message
            $blockData = sprintf("{'text': '%s'}", $message);
        } elseif ($record['data']['block'] === SlackBlockUtility::SECTION) {
            $blockData = SlackBlockUtility::composeSectionBlock(
                $record['message'],
                $record['data']['text']
            );
        } else {
            $blockData = null;
        }
        $slackNotificationService->sendRequest($blockData);

        return $this;
    }
}
