# WP Telegram Bot API

Telegram Bot API library for WordPress.

## Usage

```bash
composer require wptelegram/bot-api
```

```php
require_once __DIR__ . '/autoload-wp.php';

$bot_token  = 'YOUR BOT TOKEN HERE';

$bot_api = new \WPTelegram\BotAPI\API( $bot_token );

$bot_api->sendMessage( [
    'chat_id' => 123456789,
    'text'    => 'Hello World',
] );

$bot_api->sendPhoto( [
    'chat_id' => 123456789,
    'photo'   => 'https://domain.com/path/to/photo.jpg',
] );
```

**NOTES:**

- Do not require conditionally, like `if ( class_exists() )`.
- It's safe to include it directly.
- Library takes care of its multiple versions being loaded.
- It always loads the latest version.

## Requirements

- `PHP >= 7.2`
- `WP >= 5.8`
