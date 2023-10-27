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

class RestrictionRoznPart extends Restrictions\Base
{
    public static function getClassTitle()
    {
        return 'Розница/партнёр';
    }
    public static function getClassDescription()
    {
        return 'Для розничных клиентов или партнёров';
    }
    public static function check($orderPrice, array $restrictionParams, $payId = 0)
    {
        if((int)$restrictionParams['USER'] == 1 && getAllPartner()) return false;
        if((int)$restrictionParams['USER'] == 2 && !getAllPartner()) return false;
        return true;
    }
    protected static function extractParams(Entity $entity)
    {
        return true;
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
            )
        );
    }
}
