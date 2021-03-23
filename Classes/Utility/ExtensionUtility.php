<?php

/**
 * Utility for extension configuration
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
 * @category Utility
 * @copyright 2021 Micha Grandel, all rights reserved
 * @license https://opensource.org/licenses/GPL-2.0 GNU General Public License
 *     2.0
 *
 * @link https://git.plywoopirate.de/
 * @link https://github.com/Plywoodpirate/slack_notifications
 */

declare(strict_types=1);

namespace Plywoodpirate\SlackNotifications\Utility;

/**
 * Set global constants for this extensions
 *
 * @author Micha Grandel <micha@plywoodpirate.de>
 * @package Plywoodpirate\SlackNotifications\Utility
 * @copyright 2021 Micha Grandel, all rights reserved
 * @link $Project_Link${blob}${masterbranch}/web/typo3conf/ext/...
 */
class ExtensionUtility
{
    // Extension Key
    public const EXTENSION_KEY = 'slack_notifications';
}
