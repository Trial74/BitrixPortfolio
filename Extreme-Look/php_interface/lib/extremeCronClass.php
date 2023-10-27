<?
namespace VladClasses;

class extremeCronClass{

    private $DIR_LOG = '/var/www/u0907786/data/www/extreme-look.ru/bitrix/modules/extremelook/log/log_cron.txt';

    public function addLog($text = '...', $start = false, $end = false, $cronName = ''){
        if($start) {
            $firstLine = date('d-m-Y_H:i:s') . ' Старт крона '.$cronName.' ------------------------------------------';
            file_put_contents($this->DIR_LOG, $firstLine . PHP_EOL, FILE_APPEND);
        }elseif($end){
            $endLine = date('d-m-Y_H:i:s') . ' Конец крона '.$cronName.' ------------------------------------------';
            file_put_contents($this->DIR_LOG, $endLine . PHP_EOL, FILE_APPEND);
        }else{
            file_put_contents($this->DIR_LOG, $text . PHP_EOL, FILE_APPEND);
        }
    }
}