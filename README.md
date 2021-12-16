![](https://www.sms77.io/wp-content/uploads/2019/07/sms77-Logo-400x79.png)

# SMS Plugin for [WISECP](https://www.wisecp.com/)

## Prerequisites
- A WISECP installation
- An API Key from [sms77](https://www.sms77.io)

## Installation

1. Download the [latest release](https://github.com/sms77io/wisecp/releases/latest/download/sms77-wisecp-latest.zip)
2. Extract the archive to `/path/to/wisecp/coremio/modules/SMS`
3. Login to the administration and go to `Settings -> SMS`
4. Set `sms77` as `Notification Module` or `International Service Module` and click `Save Settings`
5. Click `sms77` in the left tab pane, enter your `API Key` and click `Save Settings`

WISECP will now use sms77 as the SMS gateway.
See their [documentation](https://docs.wisecp.com/en/kb/sms-delivery-service) for more information on how SMS are being sent.

## Configuration Options

### API Key
An API key from sms77 required for sending - create one in your [developer dashboard](https://app.sms77.io/developer)

### Sender ID
Set a custom value to be displayed as the sender.
Maximum 11 alphanumeric or 16 numeric characters.
Country specific restrictions may apply.

## Support

Need help? Feel free to [contact us](https://www.sms77.io/en/company/contact/).

[![MIT](https://img.shields.io/badge/License-MIT-teal.svg)](LICENSE)
