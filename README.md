![](https://www.seven.io/wp-content/uploads/Logo.svg "seven Logo")

# SMS Plugin for [WISECP](https://www.wisecp.com/)

## Prerequisites
- A WISECP installation
- An [API Key](https://help.seven.io/en/api-key-access) from [seven](https://www.seven.io)

## Installation

1. Download the [latest release](https://github.com/seven-io/wisecp/releases/latest/download/seven-wisecp-latest.zip)
2. Extract the archive to `/path/to/wisecp/coremio/modules/SMS`
3. Login to the administration and go to `Settings -> SMS`
4. Set `seven` as `Notification Module` or `International Service Module` and click `Save Settings`
5. Click `seven` in the left tab pane, enter your `API Key` and click `Save Settings`

WISECP will now use seven as the SMS gateway.
See their [documentation](https://docs.wisecp.com/en/kb/sms-delivery-service) for more information on how SMS are being sent.

## Configuration Options

### API Key
An [API Key](https://help.seven.io/en/api-key-access) from seven required for sending.

### Sender ID
Set a custom value to be displayed as the sender.
Maximum 11 alphanumeric or 16 numeric characters.
Country specific restrictions may apply.

## Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/).

[![MIT](https://img.shields.io/badge/License-MIT-teal.svg)](LICENSE)
