# :file_folder: Contents
- [:building_construction: Documentation](#Docs)
- [Overview](#book-overview)
- [Project Configuration](#gear-project-configuration)
- [Architecture Concepts](#toolbox-architecture-concepts)
- [Global functions](#telescope-global-functions)
- [Examples](#bulb-examples)
  - [Send Messages](#send-messages)
  - [Edit Messages](#edit-messages)
  - [Create Keyboards](#create-keyboards)
  - [Handle CallbackQuery Requests](#handle-callbackquery-requests)
  - [Create Custom Chat States](#create-custom-chat-states)
  - [Access Database](#access-database)

## :book: Overview
This project represents an object-oriented application that brings an opportunity to create telegram bots using official
[Telegram Bot API](https://core.telegram.org/bots/api) much easier and faster.

**Feel free to observe fields and public methods of the classes by yourself for better understanding how to work with them.**

> <picture>
>   <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/light-theme/info.svg">
>   <img alt="Info" src="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/dark-theme/info.svg">
> </picture><br>
> In general all necessary objects will be injected automatically depending on the module you work with. E.g. to handle a Command
> a `TelegramMessage` with `Bot` will be provided. To handle an `InlineRequest` an `InlineQuery` with `Bot` will be provided.

You should think of the `Bot` as some kind of gateway to the Telegram API. Here you can find the implemented API methods.
E.g. `sendMessage`.

> <picture>
>   <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/light-theme/danger.svg">
>   <img alt="Danger" src="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/dark-theme/danger.svg">
> </picture><br>
> :loudspeaker:	 Project is under developing. Critical errors and performance instability may occur. The codebase is unstable and changes very quickly. Code structure also is not fixed.

## :gear: Project Configuration
At the root of the application you can locate `app.example.ini`. Here you can observe pattern of your future `ini` file configuration. You should
create your own `app.ini` file and fill it with your values.

Currently there are 3 sections in `ini` file:
```ini
[database]
db_host = "db_host"
db_username = "db_username"
db_database = "db_database"
db_password = "db_password"

[bot]
;Token taken from https://t.me/BotFather
bot_token = "your_bot_token"
;Address to the index.php file. This is where telegram will send Updates
bot_url = "mybot.com/index.php"

[timezone]
;One of the supported timezones https://www.php.net/manual/en/timezones.php
timezone = "Europe/Kiev"
```

In general there are two ways of getting Updates from telegram - the polling via `getUpdates` method and `webhooks`. This application supports **ONLY**
`webhooks` so first you need to set up your environment, get url to your `index.php` and put it into your `app.ini` file.
You can use whether any host provider or local web server e.g. with configured `ngrok`. Also make sure you can use`https` protocol.

To set up a webhooks complete previous steps then go to your `index.php` file and uncomment the following row:
```php
$application->bot->setWebhook();
```
After that access your `index.php` using `GET` HTTP method. If no error occurred you can remove the row from `index.php` file, otherwise go to
`/storage/logs/error` directory and check logs for errors.

## :toolbox: Architecture Concepts
### 1. Commands
It is possible to easily create custom bot commands by defining a class with a `boot()` method to specify actions when given command is triggered.

```php
class ExampleCommand extends BaseCommand
{
    protected string $description = 'Example command description.';
    protected string $signature = '/example';

    public function boot(Bot $bot, TelegramMessage $telegramMessage, array $parameters = []): void
    {
        //Implementation
    }
}
```
In this example when user sends `/example` message to your bot then `ExampleCommand::boot()` method will be invoked.

Also it is possible to boot command from any place of your application by using `Application::bootCommand()` method
```php
Application::bootCommand($commandSignature, $telegramMessage, $parameters);
```

### 2. Chat States

> <picture>
>   <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/light-theme/warning.svg">
>   <img alt="Warning" src="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/dark-theme/warning.svg">
> </picture><br>
> For this feature to work properly, you must first set up a database connection.

The application offers a feature for managing chat states. You can create and manage different states of chats.
Each state is represented by a dedicated class where you can define the logic to process incoming requests from chats
in that specific state. This also may be useful for building multi-level dialogues.

```php
\Boot\Src\Entities\TelegramChat::class has two methods to work with states: 
\Boot\Src\Entities\TelegramChat::setStatus() && 
\Boot\Src\Entities\TelegramChat::getStatusId()

$state = StatusRecord::query()->select(['id'])->where('id', StatusRecord::STATUS_DEFAULT)->get()[0];
$callbackQuery->getChat()->setStatus($state);
```

```php
class DefaultState extends State
{
    public function handle(Bot $bot, TelegramMessage $telegramMessage): void
    {
        //handle chats with default status
    }
}
```

### 3. InlineQuery Handler
There is special class for handling incoming inline requests called `InlineQueryHandler`.
This handler will be invoked when incoming Update will have an inline query instance.
`Work dir: /app/InlineQuery`

### 4. CallbackQuery Handlers
The application includes a mechanism that allows you to create custom CallbackQuery handlers.
These handlers allow developers to define handling logic when users interact with inline buttons. Each inline button can be bind to different
callback handler.
`Work dir: app/CallbackQueryHandlers`

```php
$inlineKeyboard = new InlineKeyboardMarkup();
$inlineKeyboard
    ->addKeyboardRow()
    ->addButton('Button text')
    ->addCallbackHandler(TestHandler::class, $string);
```
The `$string` variable contains any string data you want to pass to the given handler when the inline button is pressed.

## :telescope: Global Functions
You can access any of these functions from any place of the project.
These functions make certain trivial tasks a little easier or provide access to important components such as container.

**The list is not structured yet and will be extended as new functionality is added.**

- `container(string $abstract = null, array $parameters = [])` - Access the available container instance.
- `array_first(array $array)` - Get the first element in array.
- `array_last(array $array)` - Get the last element in array.
- `camel_case_to_snake_case(string $input)` - Convert string camel case style to snake case.
- `snake_case_to_camel_case(string $input)` - Convert string snake case style to camel case.

## :bulb: Examples

### Send Messages
```php
$bot->sendMessage($message, 999999999, parseMode: 'html');
```

### Edit Messages
```php
$bot->editMessageText($text, $chat, 999999999);
```

### Create Keyboards
```php
//Inline keyboard
$inlineKeyboard = new InlineKeyboardMarkup();

$inlineKeyboard
    ->addKeyboardRow()
    ->addButton('Button text')
    ->addCallbackHandler(TestHandler::class, $callbackData);
    
$bot->sendMessage($message, 999999999, replyMarkup: $inlineKeyboard);
```
```php
//Regular keyboard
$keyboard = new ReplyKeyboardMarkup();

$keyboard->addKeyboardRow()->addButton('Button text')

$bot->sendMessage($message, 999999999, replyMarkup: $keyboard);
```
### Handle CallbackQuery Requests
_using builtin callbackQuery handle mechanism. Create you own handlers at the `/app/CallbackQueryHandlers` dir like below_
```php
class TestHandler extends CallbackQueryHandler
{
    public function handle(Bot $bot, CallbackQuery $callbackQuery): void
    {
        $bot->sendMessage($callbackQuery->getData(), $callbackQuery->getMessage()->getChat());
    }
}
```

### Create Custom Chat States
_by declaring classes at the `/app/States` dir like shown_
```php
class DefaultState extends State
{
    //Each state class implements handle function
    public function handle(Bot $bot, TelegramMessage $telegramMessage): void
    {
        //handle chats with default status
    }
}
```

### Access Database
_by using `QueryBuilder`. To access `QueryBuilder` firstly you need to create a Record class at `/app/Records` dir that will extend
`Record` class. Then you can call for `query()` method and build your sql query like shown:_

```php
class ChatRecord extends Record
{
    //Name of the table in you database
    protected string $table = 'chat';

    protected array $fillable = ['id', 'status_id', 'user_id', 'type'];
    protected array $customFields = ['status_id', 'user_id'];

    //This field is marker of the type to which the data obtained from the table should be converted to
    //Given type must implement the Recordable interface
    protected string $boundedTelegramEntity = TelegramChat::class;
}
```

Telegram chat implements Recordable interface so it can be used by the `Record` class
```php
class TelegramChat extends Entity implements Recordable
```

```php
$privateChats = ChatRecord::query()->select()->where('type', 'private')->get()
//$privateChats is an array of TelegramChat objects. Because ChatRecord::$boundTelegramEntity equals TelegramChat::class
foreach ($privateChats as $chat) {
    //Work with $chat
}
```
