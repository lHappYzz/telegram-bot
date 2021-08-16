# Telegram-bot quick start code
This start code allows you to make bots using official [Telegram Bot API](https://core.telegram.org/bots/api) a little easier. 
With this start-up code you can easily work with incoming updates e.g. manipulate with incoming messages, get sender instance,
send messages, easily create bot commands or integrate functionality of other services by using API.
## Usage examples
```php
$bot->sendMessage('Hello, ' . $bot->user->getFirstName() . '! How are you doing? :-)');

$userMessage = $bot->message->getMessageText();
$bot->sendMessage('I can see what you sending to me:' . $userMessage);

$apiresponse = \App\Api\privatApi::GET('someapiurl.com/someapirurl');

public function boot(bot $bot)
    {
        $message = 'List of available Commands:' . PHP_EOL;
        $classedInCommandsDir = $this->getCommandsInTheCommandDir();
        foreach ($classedInCommandsDir as $commandClass) {
            $command = $this->getCommandClassInstance(substr($commandClass, 0, -4));
            if ($command instanceof baseCommand) {
                $message .= '<'.$command->getSignature().'> - '.$command->getDescription().PHP_EOL;
            }
        }
        $bot->sendMessage($message);
    }
```
## Commands
You can very quickly make the bot respond to custom commands
by creating a class in the specified dir with special name syntax and then 
all you need is just define it's boot method as in example above.