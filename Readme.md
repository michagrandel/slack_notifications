# Slack Notifications

![License](https://img.shields.io/github/license/plywoodpirate/slack_notifications)
![Version](https://img.shields.io/github/v/release/plywoodpirate/slack_notifications)
![Language](https://img.shields.io/github/languages/top/plywoodpirate/slack_notifications)
![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)


Send notifications to a slack channel

## Features

You can use this extension to send messages to a slack channel. This will 
use a web hook to transfer the messages.

* Send message directly to a slack channel via a web hook
* Configure your Logging to send log messages to a slack channel
* Use markdown to enrich your messages

## Getting Started

### Requirements

You will need a slack account and you need to have administration privileges
on your slack server (or someone who allows you to use webhooks for that slack
server).

### Setup Slack WebHook

Please follow the [official documentation](slack-webhook) to setup your webhook.

Copy the webhook URL, you will need it later.

### Install and configure the extension

Install the extension by adding it to your composer.json:

```bash
composer require "plywoodpirate/slack_notifications"
composer update
```

Open the Extension list in your TYPO3 CMS Backend and activate the extension 
"Slack notifications for TYPO3" (slack_notifications).

Put the webhook URL in the extension settings.

Now you can send or log messages in your PHP code.

For example, to log error messages in slack, you may want to add these lines
in your AdditionalConfiguration.php:

```php
$GLOBALS['TYPO3_CONF_VARS']['LOG']['writerConfiguration'] = [
    \TYPO3\CMS\Core\Log\LogLevel::ERROR => [
        \Plywoodpirate\SlackNotifications\Log\Writer\SlackNotificationWriter::class => [],
    ],
];

$GLOBALS['TYPO3_CONF_VARS']['LOG']['Ion2s']['processorConfiguration'] = [
    \TYPO3\CMS\Core\Log\LogLevel::ERROR => [
        \Plywoodpirate\SlackNotifications\Log\Processor\SimpleBlockProcessor::class => [
            'block' => 'section',
            'text' => 'mrkdwn'
        ]
    ],
];
```

## Built With

* [composer](http://lxml.de/) \
  *is a tool for dependency management in PHP.*
* [PHP 7.x](https://www.php.net/) \
  *is a popular general-purpose scripting language.*
* [TYPO3 CMS](https://typo3.org/) \
  *is an Open Source Enterprise Content Management System*

## Contributing

First of all: Thank you very kindly for your interest in contributing to our code!

Please take a moment and read [CONTRIBUTING.md](CONTRIBUTING.md) to get you started!

## Code of Conduct

Everyone interacting in this project's codebases, issue trackers, chat rooms,
and mailing lists is expected to follow
the [Code of Conduct][CODE_OF_CONDUCT.md].

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available,
see the [releases on this repository][github-releases].

## Authors

* **Micha Grandel** - *Author and maintainer* - [Github][github]

We thank all of our [contributors][github-contributors], who participated in
this project.

## License

This project is licensed under the [Apache 2.0 License](LICENSE.md).


[github]: https://github.com/michagrandel

[github-releases]: https://github.com/michagrandel/slack_notifications/releases

[github-contributors]: https://github.com/michagrandel/slack_notifications/graphs/contributors

[gitflow]: https://danielkummer.github.io/git-flow-cheatsheet/

[gitflow-model]: http://nvie.com/posts/a-successful-git-branching-model/
[slack-webhook]: https://api.slack.com/messaging/webhooks
