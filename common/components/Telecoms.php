<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/14
 * Time: 23:07
 */
namespace common\components;

use common\exceptions\TelecomsMessageFailException;
use common\exceptions\TelecomsNoNumberException;
use common\models\location\LocationCurrent;
use common\models\profile\Profile;
use frontend\modules\find\forms\SendMessageForm;
use Yii;
use yii\base\Component;
use yii\base\Exception;

class Telecoms extends Component
{
    public function locateWithNumber($number)
    {
        return [
            'latitude' => 110.1234567,
            'longitude' => 45.66666,
        ];
    }

    public function getNumbers($latitude, $longitude, $radius)
    {
        $numbers = Yii::$app->params['initNumbers'];
        if (!$numbers) {
            throw new TelecomsNoNumberException("No numbers got in this area");
        }
        return $numbers;
    }

    /**
     * @param $numbers
     * @param $sendMessageForm SendMessageForm
     * @return bool
     * @throws Exception
     * @throws TelecomsMessageFailException
     */
    public function sendMessage($numbers, $sendMessageForm)
    {


        $profile = $sendMessageForm->getProfile();
        $current = $sendMessageForm->getLocationCurrent();

        $number = implode(',', $numbers);


        $receivePhone = Yii::$app->params['receivePhone'];
        $gender = $profile->gender == 'male' ? '男' : '女';
        $content = "【失踪儿童搜寻平台】现走失一名$profile->age 岁$gender 孩," .
            "最近于$current->occur_at 出现在 $sendMessageForm->city $sendMessageForm->location 附近," .
            "姓名:$profile->name,身高约$profile->height 厘米,$profile->dress $profile->appearance," .
            "有目击者清将目击时间地点发送到$receivePhone ";


        /*$ch = curl_init();
        $url = 'http://apis.baidu.com/kingtto_media/106sms/106sms?mobile=' . $number . '&content=' . $content;
        $header = array(
            'apikey: ' . Yii::$app->params['apiKey'],
        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch, CURLOPT_URL, $url);
        $res = json_decode(curl_exec($ch), true);


        if ($res ['returnstatus'] != 'Success') {
            throw new TelecomsMessageFailException($res['message']);
        }*/
        $sendMessage = file_put_contents("/alidata/www/default/maps/sendMessage.txt", "接收号码:" . $number . "\n短信内容:\n" . $content);

        if (!$sendMessage) {
            return false;
        }
        return true;
    }
}

