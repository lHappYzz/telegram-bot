<?php

namespace Boot\Factories;

use Boot\Src\Abstracts\UpdateUnit;
use Boot\Src\Entities\InlineQuery;
use Boot\Src\Entities\Location;
use Boot\Src\Entities\TelegramUser;
use Boot\Src\Update;

class InlineQueryUpdateFactory extends UpdateFactory
{
    /**
     * @inheritDoc
     */
    function createUpdate(): Update
    {
        return new Update(
            $this->updateId,
            $this->createInlineQuery(),
        );
    }

    /**
     * @return UpdateUnit
     */
    protected function createInlineQuery(): UpdateUnit
    {
        return new InlineQuery(
            $this->components['id'],
            new TelegramUser($this->components['from']),
            $this->components['query'],
            $this->components['offset'],
            $this->components['chat_type'],
            isset($this->components['location']) ? new Location(
                $this->components['location']['longitude'],
                $this->components['location']['latitude'],
                $this->components['location']['horizontal_accuracy'],
                $this->components['location']['live_period'],
                $this->components['location']['heading'],
                $this->components['location']['proximity_alert_radius'],
            ) : null,
        );
    }
}