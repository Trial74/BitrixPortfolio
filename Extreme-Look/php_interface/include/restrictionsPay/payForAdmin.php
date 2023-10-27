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

class RestrictionOnlyAdmin extends Restrictions\Base
{
    public static function getClassTitle()
    {
        return 'Только для админа';
    }
    public static function getClassDescription()
    {
        return 'Выводить платёжку только для админа';
    }
    public static function check($template, array $restrictionParams, $payId = 0)
    {
        global $USER;
        if(!empty($restrictionParams['ADM']) && $USER->IsAdmin()) return true;
        else return false;
    }
    protected static function extractParams(Entity $entity)
    {
        return null;
    }
    public static function getParamsStructure($entityId = 0)
    {
        return array(
            "ADM" => array(
                'TYPE' => 'Y/N',
                'VALUE' => 'Y',
                'LABEL' => 'Только админу',
            ),
        );
    }
}
