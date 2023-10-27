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

class RestrictionOnlySiteOrApp extends Restrictions\Base
{
    public static function getClassTitle()
    {
        return 'Только на сайте или в приложении';
    }
    public static function getClassDescription()
    {
        return 'Выводить платёжку только на сайте или в приложении';
    }
    public static function check($template, array $restrictionParams, $payId = 0)
    {
        switch ($restrictionParams['CONDITION']){
            case 1:
                if($template == 'enext') return true;
                else return false;
            break;
            case 2:
                if($template == 'mobileapp') return true;
                else return false;
            break;
            default:
                return false;
            break;
        }
    }
    protected static function extractParams(Entity $entity)
    {
        return SITE_TEMPLATE_ID;
    }
    public static function getParamsStructure($entityId = 0)
    {
        return array(
            "CONDITION" => array(
                'TYPE' => 'ENUM',
                'DEFAULT' => 1,
                'LABEL' => 'Условие',
                "OPTIONS" => array(
                    1 => "На сайте",
                    2 => "В приложении"
                )
            )
        );
    }
}
