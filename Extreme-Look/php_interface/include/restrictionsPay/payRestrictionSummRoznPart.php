<?use Bitrix\Sale\Internals\Entity,
Bitrix\Sale\Delivery\Restrictions;

//16 - оплата онлайн сайт
//25 - оплата онлайн сайт двухстадийка
//26 - оплата онлайн приложение
//30 - оплата онлайн приложение двухстадийка
//31 - оплата онлайн lashmaker
//32 - оплата онлайн lashmaker двухстадийка
//18 - Сбер рассрочка
//29 - сбер рассрочка приложение
//14 - тинькофф рассрочка 4 месяца сайт
//27 - тинькофф рассрочка 4 месяца приложение
//15 - тинькофф рассрочка 6 месяцев сайт
//28 - тинькофф рассрочка 6 месяцев приложение
//1 - наличный рассчёт
//22 - оплата по реквизитам
//23 - безналичный рассчёт
//24 - оплата по счёту розница
//8 - оплата по счёту партнёры
//33 - MC-Credit

class RestrictionSummRoznPart extends Restrictions\Base
{
    public static function getClassTitle()
    {
        return 'Розница/партнёр по сумме';
    }
    public static function getClassDescription()
    {
        return 'Для розничных клиентов или партнёров с ограничением по сумме заказа';
    }
    public static function check($orderPrice, array $restrictionParams, $payId = 0)
    {
        if((int)$restrictionParams['USER'] == 1 && getAllPartner()) return true;
        if((int)$restrictionParams['USER'] == 2 && !getAllPartner()) return true;
        switch ($restrictionParams['CONDITION']) {
            case 1:
                if ((int)$orderPrice == (int)$restrictionParams['SUMM']) return true;
                else return false;
                break;
            case 2:
                if ((int)$orderPrice <= (int)$restrictionParams['SUMM']) return true;
                else return false;
                break;
            case 3:
                if ((int)$orderPrice >= (int)$restrictionParams['SUMM']) return true;
                else return false;
                break;
            case 4:
                if ((int)$orderPrice != (int)$restrictionParams['SUMM']) return true;
                else return false;
                break;
            default:
                return false;
                break;
        }

    }
    protected static function extractParams(Entity $entity)
    {
        $collection = $entity->getCollection();
        $orderPrice = $collection->getOrder()->getPrice();
        return $orderPrice;
    }
    public static function getParamsStructure($entityId = 0)
    {
        return array(
            "USER" => array(
                'TYPE' => 'ENUM',
                'DEFAULT' => 1,
                'LABEL' => 'Условие',
                "OPTIONS" => array(
                    1 => "Розничный",
                    2 => "Партнёр"
                )
            ),
            "CONDITION" => array(
                'TYPE' => 'ENUM',
                'DEFAULT' => 1,
                'LABEL' => 'Условие',
                "OPTIONS" => array(
                    1 => "Равно",
                    2 => "Меньше или равно",
                    3 => "Больше или равно",
                    4 => "Не равно"
                )
            ),
            "SUMM" => array(
                'TYPE' => 'NUMBER',
                'DEFAULT' => 0,
                'LABEL' => 'Сумма заказа',
            )
        );
    }
}
