# 🤖 Sipgate Slack Bot

The Sipgate Slack Bot is a tool that posts incoming and outgoing phone calls to Sipgate as an automated message in any Slack channel you want. The message contains the phone number and the contact name of the caller and offers the possibility to listen to a message on the answering machine directly. There is also a callback function in the Slack message.

## Note
The tool is still under development. Feel free to contribute. 🤓

## Requirements
PHP 7.2.4 or above

## Authors
- Robin Wieschendorf | <mail@robinwieschendorf.de> | [robinwieschendorf.de](https://robinwieschendorf.de)

## Contributing
We would be happy if you would like to take part in the development of this module. If you wish more features or you want to make improvements or to fix errors feel free to contribute. In order to contribute, you just have to fork this repository and make pull requests.

### Coding Style
We are using:
- [PSR-1: Basic Coding Standard](https://www.php-fig.org/psr/psr-1/)
- [PSR-12: Extended Coding Style](https://www.php-fig.org/psr/psr-12/)

### Version and Commit-Messages
We are using:
- [Semantic Versioning 2.0.0](https://semver.org)
- [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/)


## 💻 Development
Slack sends a request to your server for the callback function. If you develop locally, your computer is inaccessible to Slack. In order to be able to test this function locally, you can e.g. use the `ngrok` tool. `ngrok` forwards requests from a public address to your local computer.

To do this, download `ngrok` from https://ngrok.com. You can run `ngrok` on your computer as follows. After the call, `ngrok` will return a public web address as output.

```bash
./ngrok http -host-header=rewrite myapp.dev:80 
```