<?php

/**
 * Prepare log message to send them as markdown blocks to a slack channel
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
 * @package Plywoodpirate\SlackNotifications\Log\Processor
 *
 * @category LogProcessor
 * @copyright 2021 Micha Grandel, all rights reserved
 * @license https://opensource.org/licenses/GPL-2.0 GNU General Public License 2.0
 *
 * @link https://git.plywoopirate.de/
 * @link https://github.com/Plywoodpirate/slack_notifications
 */

namespace Plywoodpirate\SlackNotifications\Log\Processor;

use Plywoodpirate\SlackNotifications\Utility\SlackBlockUtility;
use TYPO3\CMS\Core\Log\Exception\InvalidLogProcessorConfigurationException;
use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\Processor\AbstractProcessor;

/**
 * Modifies log messages so that they are processed as markdown blocks in slack
 *
 * This processor add additional meta data to log records, so the Slack
 * Notification Writer can identify these and process them using BlockKit for
 * slack instead of normal messages.
 *
 * About Blocks
 * ------------
 *
 * Blocks using the BlockKit in Slack are enriched messages with additional
 * features. For example, you may use markdown for rich text or add interactive
 * elements to your messages.
 *
 * See Slack's BlockKit documentation for more details.
 *
 * @author Micha Grandel <micha@plywoodpirate.de>
 * @package Plywoodpirate\SlackNotifications\Service
 * @copyright 2021 Micha Grandel, all rights reserved
 * @link https://github.com/Plywoodpirate/slack_notifications/blob/main/Classes/Log/Processor/SimpleBlockProcessor.php
 * @see https://api.slack.com/block-kit
 */
class SimpleBlockProcessor extends AbstractProcessor
{
    /**
     * Format of Slack Blocks.
     *
     * For example:
     * - section
     * - header
     *
     * @var string $blockFormat
     * @see https://api.slack.com/reference/block-kit/blocks
     */
    protected $block;

    /**
     * Format for text inside the Slack Blocks
     *
     * Valid values are:
     * - plain_text
     * - mrkdwn
     *
     * @var string $textFormat
     *
     * @see https://api.slack.com/reference/block-kit/composition-objects#text
     */
    protected $text;

    /**
     * Constructor
     *
     * @param array $options Configuration options - depends on the actual log
     *     writer
     *
     * @throws InvalidLogProcessorConfigurationException
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        if (key_exists('block', $options)) {
            $this->block = $options['block'];
        } else {
            $this->block = SlackBlockUtility::SECTION;
        }
        if (key_exists('text', $options)) {
            $this->text = $options['text'];
        } else {
            $this->text = SlackBlockUtility::MARKDOWN;
        }
    }

    /**
     * @return string
     */
    public function getBlock(): string
    {
        return $this->block;
    }

    /**
     * @param string $block
     */
    public function setBlock(string $block): void
    {
        $this->block = $block;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Process log records as Markdown Blocks in slack
     *
     * @param \TYPO3\CMS\Core\Log\LogRecord $logRecord The log record to process
     * @return \TYPO3\CMS\Core\Log\LogRecord The processed log record with additional data
     *
     * @see https://api.slack.com/reference/block-kit/blocks#section
     */
    public function processLogRecord(LogRecord $logRecord): LogRecord
    {
        $logRecord->addData([
            'block' => $this->block,
            'text' => $this->text
        ]);
        return $logRecord;
    }
}
