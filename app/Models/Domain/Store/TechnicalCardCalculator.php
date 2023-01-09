<?php


namespace App\Models\Domain\Store;


use App\Enums\MenuItemType;
use App\Enums\ModificationAction;
use App\Models\Store\MenuItem;
use App\Models\Store\Modification;
use App\Models\Store\ModificationMenuItem;
use App\Models\Store\TechnicalCard;
use Illuminate\Database\Eloquent\Model;

class TechnicalCardCalculator
{
    protected MenuItem $menuItem;
    protected ?ModificationMenuItem $modification;

    protected array $modificateParams = [
        'weight',
        'proteins',
        'fats',
        'carbohydrates',
        'calories'
    ];

    /**
     * TechnicalCard constructor.
     * @param MenuItem $menuItemTechCard
     * @param ModificationMenuItem $modification
     */
    public function __construct(MenuItem $menuItem, ?ModificationMenuItem $modification)
    {
        if ($menuItem->type->isNot(MenuItemType::Single)) {
            throw new \InvalidArgumentException("Расчет техкарты для составных товаров не предусмотрена");
        }
        $this->menuItem = $menuItem;
        $this->modification = $modification;
    }

    public function getActualTechCard(): TechnicalCard
    {
        if (!$this->menuItem->technicalCard) {
            return new TechnicalCard();
        }

        if ($this->modification) {
            return $this->applyModificationToTechCard();
        }

        return $this->menuItem->technicalCard;
    }

    private function applyModificationToTechCard()
    {
        $modificationAction = $this->modification->modification->action;
        $menuItemTechCard = $this->menuItem->technicalCard;


        switch ($modificationAction) {
            case ModificationAction::Add:
                $this->checkTechCards();
                $modificationTechCard = $this->modification->modification->technicalCard;
                return $this->sumTechCards($menuItemTechCard, $modificationTechCard);

            case ModificationAction::Subtract:
                return $this->subtractTechCards($menuItemTechCard);

            case ModificationAction::Replace:
                $this->checkTechCards();
                $modificationTechCard = $this->modification->modification->technicalCard;
                return clone $modificationTechCard;


            default:
                throw new \LogicException("Расчет для действия '{$modificationAction}' техкарты модификации не реализован");

        }
    }

    /**
     * Сложение техкарт
     * Например, Сырный бортик
     */
    private function sumTechCards(TechnicalCard $menuItemTechCard, TechnicalCard $modificationTechCard): TechnicalCard
    {
        $techCard = clone $menuItemTechCard;

        foreach ($this->modificateParams as $param) {
            $techCard->$param += $modificationTechCard->$param;
        }

        return $techCard;
    }

    /**
     * Разделение техкарты на 2
     * Например, Половинка ролла
     */
    private function subtractTechCards(TechnicalCard $menuItemTechCard): TechnicalCard
    {
        $techCard = clone $menuItemTechCard;

        foreach ($this->modificateParams as $param) {
            $techCard->$param /= 2;
        }

        return $techCard;
    }

    private function checkTechCards()
    {
        if ($this->modification && empty($this->modification->modification->technicalCard)) {
            throw new \InvalidArgumentException("Не удается расчитать состав блюда. У модификатора не задана техкарта.");
        }
    }


}
