<?php


namespace KKMClient\Models;


use KKMClient\Exceptions\BadConfigException;
use KKMClient\Exceptions\CheckIsNotPrintable;
use KKMClient\Helpers\CheckStringsBuilder;
use KKMClient\Interfaces\CommandInterface;
use KKMClient\Models\Queries\Commands\RegisterCheck;
use KKMClient\Models\Queries\Enums\CheckTypes;

abstract class AbstractCheck
{

    /** Номер принтера в настройках KKMServer */
    protected $deviceNumber;

    protected $checkType = CheckTypes::REFUND;
    protected $command;

    public function createCommand() : CommandInterface
    {
        if (!$this->deviceNumber) {
            throw new BadConfigException('deviceNumber не может быть пустым');
        }

        if (!$this->isPrintable()) {
            throw new CheckIsNotPrintable("Печать чека не требуется");
        }

        $this->command = new RegisterCheck();
        $this->command->setCheckType($this->checkType);

        $strings = $this->getTemplate()->getStrings();
        $this->command->setStrings($strings);
        $this->command->setDeviceNumber($this->deviceNumber);

        return $this->command;
    }

    /**
     * Если печатать чек не нужно возвращаем false
     * @return bool
     */
    public function isPrintable(): bool
    {
        return true;
    }

    abstract public function getTemplate(): CheckStringsBuilder;
}
