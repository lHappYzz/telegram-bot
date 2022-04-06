<?php

namespace Boot\Src;

use BadMethodCallException;
use Boot\Traits\helpers;
use RuntimeException;

/**
 * Class Update
 * @link https://core.telegram.org/bots/api#getting-updates
 */
class Update
{
    use helpers;

    protected int $updateId;
    protected string $updateType;

    //At most one of the optional parameters can be present in any given update
    protected ?telegramMessage $message;
    protected ?telegramMessage $editedMessage;
    protected ?CallbackQuery $callbackQuery;

    protected const UPDATE_TYPE_MESSAGE = 'message';
    protected const UPDATE_TYPE_EDITED_MESSAGE = 'edited_message';
    protected const UPDATE_TYPE_CALLBACK_QUERY = 'callback_query';

    protected array $bindings = [
        self::UPDATE_TYPE_MESSAGE => telegramMessage::class,
        self::UPDATE_TYPE_EDITED_MESSAGE => telegramMessage::class,
        self::UPDATE_TYPE_CALLBACK_QUERY => CallbackQuery::class,
    ];

    /** @var telegramMessage|CallbackQuery */
    protected $initiatedUpdateParameter;

    /**
     * Update constructor.
     * @param array $requestData
     * @see telegramRequest::parseTelegramRequest
     */
    public function __construct(array $requestData)
    {
        $this->updateId = $requestData['update_id'];

        $this->setUpdateType($requestData);

        $this->initUpdateField($this->updateType, $requestData);
    }

    /**
     * @param string $methodName
     * @return Entity
     */
    public function getInstance(string $methodName): Entity
    {
        if (get_class($this->initiatedUpdateParameter) === $this->bindings[
            $this->camelCaseToSnakeCase($this->resolveInstanceName($methodName))]
        ) {
            return $this->initiatedUpdateParameter;
        }

        if (method_exists($this->initiatedUpdateParameter, $methodName)) {
            return $this->initiatedUpdateParameter->$methodName();
        }

        throw new BadMethodCallException('Bad method call: ' .
            $methodName .
            '. At ' . __CLASS__ .
            '::' . __METHOD__ .
            '. Line: ' . __LINE__);
    }

    private function resolveInstanceName(string $methodName): string
    {
        return substr_replace($methodName, '', 0, 3);
    }

    private function initUpdateField(string $updateType, array $requestData): void
    {
        $this->{$this->snakeCaseToCamelCase($updateType)} = new $this->bindings[$updateType]($requestData[$this->updateType]);

        $this->initiatedUpdateParameter = &$this->{$this->snakeCaseToCamelCase($updateType)};
    }

    private function setUpdateType(array $requestData): void
    {
        foreach ($this->bindings as $type => $binding) {
            if (array_key_exists($type, $requestData)) {
                $this->updateType = $type;
                return;
            }
        }
        throw new RuntimeException('Can not recognize telegram update type.');
    }
}