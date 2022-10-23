# Telegram Notifier

This is a simple script to send notifications to your Telegram group. It uses the Telegram Bot API to send messages to
your account. You can use this script to send notifications to your Telegram group when there is a event on your
github repository.

## How to use

1. Add https://t.me/SmirlTechNotifierDevTeamBot to your Telegram group.
2. Get your group id by sending `/getgroupid` to the  https://t.me/myidbot
3. Add `https://telegram-notifier.smirltech.com/api/github-notify/{groupId}` to your github repository webhook.

## How it works

Get notified when there is a event on your github repository. This script will send a message to your Telegram group
when there is a event on your github repository. The message will contain the following information:

![alt text](img.png "Telegram Notifier")
