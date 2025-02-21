# debt-manager
A custom written Debt Manager, not really ready for sharing but may aswell share it anyway, it uses the reference of the transaction to match payments using the Starling Bank API

### Requirements
- Mysql/Maria Database
- Starling (UK Bank) Developer Account
- Discord App (OAuth)

### Configuration
|        Parameter        | Required |  Type  | Default |                                            Description                                             |
| :---------------------: | :------: | :----: | :-----: | :------------------------------------------------------------------------------------------------: |
|        APP_HOST         |   yes    | string |    -    |                                      The URL to this service                                       |
|                         |          |        |         |                                                                                                    |
|         DB_HOST         |   yes    | string |    -    |                                         Mysql/MariaDB Host                                         |
|         DB_NAME         |   yes    | string |    -    |                                         Mysql/MariaDB Name                                         |
|         DB_USER         |   yes    | string |    -    |                                       Mysql/MariaDB Username                                       |
|         DB_PASS         |   yes    | string |    -    |                                       Mysql/MariaDB Password                                       |
|                         |          |        |         |                                                                                                    |
| STARLING_WEBHOOK_SECRET |   yes    | string |    -    | [Starling Bank - Personal Access V2 Webhook Key](https://developer.starlingbank.com/personal/list) |
|                         |          |        |         |                                                                                                    |
|    DISCORD_CLIENT_ID    |   yes    | string |    -    |                                       Discord App Client ID                                        |
|  DISCORD_CLIENT_SECRET  |   yes    | string |    -    |                                     Discord App Client Secret                                      |
|      DISCORD_ADMIN      |   yes    | string |    -    |                                  Discord User ID to set as Admin                                   |
|                         |          |        |         |                                                                                                    |
|          DEBUG          |    no    |  bool  |    -    |                                     Enables PHP debug logging                                      |
### Notes
- The Starling Webhook endpoint runs on port 81 not port 80 at /webhook.php, expose this to a unprotected API that Starling IP's are able to access, for example api.domain.tld
- This code is terrible, this was one of my first ever from scratch PHP projects which i then adapted to use Twig. No warranty etc etc, I did start rewriting this with Laravel but i never got to completing it and to be honest this works for what i wanted so i had no motivation to continue it.