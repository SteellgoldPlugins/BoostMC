<?php

namespace Steellg0ld\Museum\forms\api;

use pocketmine\form\Form as IForm;
use pocketmine\Player;

abstract class Form implements IForm
{
    const FACTION_TITLE = "Faction";
    const FACTION_UPGRADE_TITLE = "Amélioration: ";
    const CODE_TITLE = "Code";
    const ECONOMY_TITLE = "Economie";

    /** @var array */
    protected $data = [];
    /** @var callable|null */
    private $callable;

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param Player $player
     * @see Player::sendForm()
     *
     * @deprecated
     */
    public function sendToPlayer(Player $player): void
    {
        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        $this->processData($data);
        $callable = $this->getCallable();
        if ($callable !== null) {
            $callable($player, $data);
        }
    }

    public function processData(&$data): void
    {
    }

    public function getCallable(): ?callable
    {
        return $this->callable;
    }

    public function setCallable(?callable $callable)
    {
        $this->callable = $callable;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}
