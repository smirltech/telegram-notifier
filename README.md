
![Telegram Notifier](https://banners.beyondco.de/Telergam%20Notifier.png?theme=light&packageManager=&packageName=https%3A%2F%2Ftelegram.smirltech.com&pattern=architect&style=style_1&description=A+bot+to+send+notifications+into+your+Telegram+group.&md=1&showWatermark=1&fontSize=100px&images=bell)

# Telegram Notifier
This is a simple script to send notifications to your Telegram group. It uses the Telegram Bot API to send messages to
your account. You can use this script to send notifications to your Telegram group when there is a event on your
github repository.

## How to use

1. Add https://t.me/SmirlTechNotifierBot to your Telegram group.
2. Get your group id by sending `/getgroupid` to the  https://t.me/myidbot
3. Add `https://telegram.smirltech.com/api/github/{groupId}` to your github repository webhook.

## How it works

Get notified when there is a event on your github repository. This script will send a message to your Telegram group
when there is a event on your github repository. The message will contain the following information:

![alt text](img.png "Telegram Notifier")
