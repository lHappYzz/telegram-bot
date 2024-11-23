# :file_folder: Contents
- [:building_construction: Documentation](#Docs)
- [Overview](#book-overview)
  - [Client Side Section Structure](#client-side-contains-bots-business-logic-located-at-app-directory-and-has-this-structure)
  - [Core Section Structure](#framework-core-located-at-boot-directory-and-has-this-structure)
- [Project Configuration](#gear-project-configuration)
- [Architecture Concepts](#toolbox-architecture-concepts)
  - [Commands](#1-commands)
  - [Chat states](#2-chat-states)
  - [InlineQueryHandler](#3-inlinequery-handler)
  - [CallbackQueryHandler](#4-callbackquery-handlers)
  - [Models](#5-records)
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

The project structure can be divided into two main components: the client and the core.

- Client Component: This is the workspace where the bot's business logic is defined.
It includes the specific behavior and rules that govern how the bot interacts with users and processes information.
- Core Component: This defines the framework's underlying functionality. 
It handles component loading logic, specifying how and what dependencies are provided to various parts of the framework.
Additionally, it manages sending requests to Telegram, processing webhooks, and ensuring smooth communication 
between the bot and Telegram's API.

This separation ensures a clean, modular architecture, allowing the client to focus solely on bot-specific logic while
the core provides robust infrastructure for its operation.

### Client side contains bot's business logic located at `app` directory and has this structure:
```
/
├── CallbackQueryHandlers/    # CallbackQuery handlers
├── Commands/                 # Bot commands
├── Config/                   # Container configuraiton
│   ├── Config.php
│   └── ContainerConfig.php
├── InlineQuery/              # InlineQuery handler
├── Records/                  # Models to access database
├── States/                   # Chat states. Can be used for remembering of the last state of telegram chat
├── app.example.ini           # Example of the application .ini file
├── app.ini                   # Application .ini file
└── Bot.php                   # Gateway to Telegram API functions
```
### Framework core located at `boot` directory and has this structure:
```
/
├── Cache/          
│   └── Cache.php                   # Used to cache any information or SQL results
├── Classes/                        # Directory for additional classes
├── Database/
│   ├── Relations/                  # Object-oriented representation of SQL relationships
│       ├── BelongsToRelation.php   # Inverted one-to-many relation
│       ├── HasManyRelation.php     # One-to-many relation
│       └── Relation.php            # Abstraction under relations
│   ├── DB.php                      # Connection to database
│   ├── QueryBuilder.php            # QueryBuilder to build SQL requests
│   └── Record.php                  # Base model class
├── Facades/
│   └── TelegramFacade.php          # Gateway to the core functions that responsible for work with Telegram API
├── Factories/                      # A layer of the app that defines how to create different type of tg updates
    ├── CallbackableFactory.php
    ├── InlineQueryUpdate.php
    ├── MessagebleUpdateFactory.php
    └── UpdateFactory.php
├── Interfaces/                     # Interfaces used throughout the core of the framework
├── Log/
│   └── Logger.php                  # Logger
├── Src/
│   ├── Abstracts/                  # Directory of abstraction used throughout the core of the framework
│   ├── APIMethods/                 # Contains the rules that must be checked for each API method before sendind a request
│   ├── Entities/                   # Entities described in TG API documentation
│   ├── Exceptions/                 # Exceptions
│   ├── TelegramRequest.php         # Logic to send request to the API
│   ├── TelegramResponse.php        # Response created by TelegramRequest
│   ├── TelegramWebhook.php         # Telegram Update parser
│   └── Update.php                  # Represents Telegram Update
├── Trais/                          # Directory for traits
├── Application.php                 # An application instance
├── Container.php                   # Container for dependency injection
├── Gate.php
├── helpers.php                     # Global help functions
├── ParameterResolveBinder.php      # Allows to set entity creation rules for the container
├── Responsibilities.php
└── TelegramUpdateParser.php
```

**Feel free to observe fields and public methods of the classes by yourself for better understanding how to work with them.**

> <picture>
>   <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/light-theme/info.svg">
>   <img alt="Info" src="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/dark-theme/info.svg">
> </picture><br>
> In general all necessary objects will be injected automatically depending on the module you work with. E.g. to handle a Command
> a `TelegramMessage` with `Bot` will be provided. To handle an `InlineRequest` an `InlineQuery` with `Bot` will be provided.

You should think of the `app/Bot` as some kind of gateway to the Telegram API. Here you can find the implemented API methods.
E.g. `sendMessage`. You can find list of currently implemented commands in the `DocBlock` of the `app/Bot` class.

> <picture>
>   <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/light-theme/danger.svg">
>   <img alt="Danger" src="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/dark-theme/danger.svg">
> </picture><br>
> :loudspeaker:	 Project is under developing. Critical errors and performance instability may occur. The codebase is unstable and changes very quickly. Code structure also is not fixed.

## :gear: Project Configuration
At the root of the application you can locate `app.example.ini`. Here you can observe pattern of your future `ini` file configuration. You should
create your own `app.ini` file and fill it with your values.

Currently, there are 3 sections in `ini` file:
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

`Work dir: app/Commands`

Each command is represented by a class that extends the base class `boot/Src/Abstracts/BaseCommand` and implements its own `boot()` method:
```php
abstract public function boot(
  Bot $bot,
  TelegramMessage $telegramMessage,
  array $parameters = []
): void;
```
Inherited methods:
```php
public function getDescription(): string;
public function getSignature(): string;
public function getAllowedUsers(): array; //Returns ids of users authorized for command. If empty array is returned command is public.
```
The `$signature` field is essential for mapping user inputs to the appropriate command class.
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

Also, it is possible to boot command from any place of your application by using `Application::bootCommand()` method:
```php
Application::bootCommand($commandSignature, $telegramMessage, $parameters);
```

### 2. Chat States

> <picture>
>   <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/light-theme/warning.svg">
>   <img alt="Warning" src="https://raw.githubusercontent.com/Mqxx/GitHub-Markdown/main/blockquotes/badge/dark-theme/warning.svg">
> </picture><br>
> For this feature to work properly, you must first set up a database connection.

`Work dir: app/States`

There is implemented mechanism for managing chat states, that allows the app to "remember" a chat's current state
and respond accordingly. Each chat state is represented by a dedicated class that extends the base `boot/Src/Abstracts/State` class.
These state classes can be mapped to database records using the `app/Records/StatusRecord` class by filling the bindings array,
where the key is the status identifier and the value is the corresponding state class:
```php
public static array $statesBindings = [
  self::STATUS_NO_STATUS => NoState::class,
  self::STATUS_DEFAULT => DefaultState::class,
  self::STATUS_POST_SUGGESTION => PostSuggestionState::class,
];
```
Each state class defines a `boot/Src/Abstracts/State::handle()` method where the logic for processing `boot/Src/Update` for a chat in that specific state is implemented.
```php
class DefaultState extends State
{
    /**
     * @inheritDoc
     */
    public function handle(Bot $bot, TelegramMessage $telegramMessage): void
    {
        //handle chats with default status
    }
}
```
To transition a chat from one state to another, use the `setStatus(int $id)` method provided by the `boot/Src/Entities/TelegramChat` entity:
```php
$telegramMessage->getChat()->setStatus(StatusRecord::STATUS_DEFAULT);
```
In this example `->getChat()` returns an instance of `boot/Src/Entities/TelegramChat` class.
So the next `boot/Src/Update` from the chat will then be processed in the appropriate state class.

This feature can be useful fore create a multi-level dialogues with users. Or whenever you want to wait for an answer
for the given question.

### 3. InlineQuery Handler
`Work dir: /app/InlineQuery`

The framework provides a mechanism for processing incoming 'InlineQuery' updates. Within the designated directory,
you can find the `app/InlineQuery/InlineQueryHandler`, which is an integral part of this system.
The framework automatically invokes this handler whenever an InlineQuery-type Update is received on the registered webhook.

To define the processing logic for the `InlineQuery`, implement it in the `app/InlineQuery/InlineQueryHandler::handle()` method of the handler.
During the handler's construction, the framework will inject a Bot entity for accessing framework methods, 
along with the `boot/Src/Entities/InlineQuery` entity. This object contains all the information related to the incoming `Update`.

By leveraging this mechanism, you can easily process `InlineQuery` updates and implement custom logic for
interactions initiated via inline queries.
```php
class InlineQueryHandler
{
    public function __construct(protected InlineQuery $inlineQuery, protected Bot $bot) {}

    public function handle(): void
    {
        //
    }
}
```


### 4. CallbackQuery Handlers
`Work dir: app/CallbackQueryHandlers`

The framework includes a mechanism for processing `CallbackQuery` requests, implemented as dedicated classes.
Each handler extends the base `boot/Src/Abstracts/CallbackQueryHandler` class, allowing developers to define custom logic for handling
callback queries received from Telegram.

```php
class TestHandler extends CallbackQueryHandler
{
    public function handle(Bot $bot, CallbackQuery $callbackQuery): void
    {
        $bot->sendMessage(
            $callbackQuery->getData(),
            $callbackQuery->getMessage()->getChat()->getId()
        );
    }
}
```
In this example, the `$bot` object provides access to the bot's core functionality, such as sending messages.
The `$callbackQuery` object contains all relevant information about the received callback query, 
including its data payload and the associated message and chat details.

Callback queries are particularly useful for creating interactive features in bots, such as `inline keyboards`.
By leveraging this mechanism, developers can dynamically respond to user interactions in a structured and efficient way.
For instance:
1. Inline Keyboards: `boot/Src/Entities/CallbackQuery` is triggered when a user interacts with buttons in an inline keyboard attached to a bot message.
2. Custom Logic: Use the CallbackQuery payload (`$callbackQuery->getData()`) to perform specific actions.

So to create an `InlineKeyboard` you should use `boot/Src/Entities/ReplyMarkup/InlineKeyboardMarkup` object as shown:
```php
$inlineKeyboard = new InlineKeyboardMarkup();
$inlineKeyboard
    ->addKeyboardRow() //Creates new row for the keyboard. Can contain multiple buttons
    ->addButton('Button text')
    ->addCallbackHandler(TestHandler::class, callbackData: 'any callback data');
```
After the inline button pressed, Telegram will send an `Update` and framework will invoke given handler where you 
can retrieve the _**"any callback data"**_.

### 5. Records
`Work dir: app/Records`

The framework includes a models module that defines object representations for database interactions called `Records`.
Each model must extend the base `boot/Database/Record` class and implement specific fields to define its behavior and mapping to database tables:

**Key Properties**:
- `$table` Represents the name of the database table that the model maps to. This is essential for identifying where the data is stored.
- `$fillable` A list of fields that can be set when creating or updating a record using methods like
`boot/Database/Record::create()` or `boot/Database/Record::update()`.
This ensures controlled data manipulation and prevents mass-assignment vulnerabilities.
- `$customFields` Defines additional fields stored in the database that are not present in the Telegram entity associated with this model.
This is useful for extending functionality beyond what the Telegram entity provides.

Interactions with Core Framework Entities:
Models can integrate with framework entities for seamless data handling.
For instance, the `app/Records/ChatRecord` model has a direct relationship with the `boot/Src/Entities/TelegramChat` entity.
The `boot/Src/Entities/TelegramChat` class encapsulates all essential information about a chat within the messenger, making it easier
to initialize and persist data.

```php
class ChatRecord extends Record
{
    protected string $table = 'chat';
    protected array $fillable = ['id', 'status_id', 'user_id', 'type'];
    protected array $customFields = ['status_id', 'user_id'];
    
    protected string $boundedTelegramEntity = TelegramChat::class;
    
    /**
    * @return BelongsToRelation
    */
    public function status(): BelongsToRelation
    {
     return $this->belongsTo(StatusRecord::class, 'status_id', 'id');
    }
}
```
Example Workflow:
When a new chat needs to be registered in the bot's database, the framework allows this through the `boot/Database/Record::createFrom()` method:
```php
ChatRecord::createFrom($telegramChat)
    ->with([
        'status_id' => StatusRecord::STATUS_DEFAULT,
        'user_id' => $telegramUser->getId()
    ])->create();
```
Here:
- The model checks the `$fillable` property to identify which fields to extract from the `boot/Src/Entities/TelegramChat` entity.
The `boot/Database/Record::create()` method saves this information in the corresponding database table.
#### Telegram Entity Binding:
The `$boundedTelegramEntity` property specifies the Telegram entity associated with the model.
Binding is only possible if the entity implements the `Recordable` interface, ensuring compatibility with the model's design.

```php
public function getTableName(): string;
public function fetchAll(): array; //Returns an array of database record objects
public function update(): bool; //Updates a record in the database
public function create(): bool; //Creates a record to the database
public function delete(): bool; //Removes a record from the database
public function fetch(int $id): ?static; //Returns an object that represents table record identified by the $tableName field
public function find(array $ids): array; //Find multiple records by their ids
public function static query(): QueryBuilder; //Creates new instance of QueryBuilder on the given Record
public function newQuery(): QueryBuilder; //Creates new instance of QueryBuilder on the given Record
public static function createFrom(Recordable $recordableEntity): static; //Creates new DB record using telegramEntity
public function with(array $columnValues): static; //Used to initialize record's fields that listed in customFields array
public function belongsTo(string $relatedAbstract, string $foreignKey, string $relatedLocalKey): BelongsToRelation; //Creates new inverted one-to-many relation
public function hasMany(string $relatedAbstract, string $relatedForeignKey, string $localKey): HasManyRelation; //Creates new one-to-many relation
```

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
_by using `boot/Database/QueryBuilder`. To access `boot/Database/QueryBuilder` firstly you need to create a Record class at `/app/Records` dir that will extend
`boot/Database/Record` class. Then you can call for `boot/Database/Record::query()` method and build your sql query like shown:_

```php
class ChatRecord extends Record
{
    //Name of the table in you database
    protected string $table = 'chat';

    protected array $fillable = ['id', 'status_id', 'user_id', 'type'];
    protected array $customFields = ['status_id', 'user_id'];
    protected string $boundedTelegramEntity = TelegramChat::class;
}

$queryBuilder = ChatRecord::query();
$chats = $queryBuilder->select(['id, type'])->get();
```