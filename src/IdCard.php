<?php
namespace Lijingping\SelfUse;
use DateTime;

/**
 * @author 云升网络
 * 2025/1/3 13:40
 **/
class IdCard
{
    protected static $province=[
        '11'=>'北京市',
        '12'=>'天津市',
        '13'=>'河北省',
        '14'=>'山西省',
        '15'=>'内蒙古自治区',
        '21'=>'辽宁省',
        '22'=>'吉林省',
        '23'=>'黑龙江省',
        '31'=>'上海市',
        '32'=>'江苏省',
        '33'=>'浙江省',
        '34'=>'安徽省',
        '35'=>'福建省',
        '36'=>'江西省',
        '37'=>'山东省',
        '41'=>'河南省',
        '42'=>'湖北省',
        '43'=>'湖南省',
        '44'=>'广东省',
        '45'=>'‌广西壮族自治区',
        '46'=>'‌海南省‌',
        '50'=>'‌重庆市',
        '51'=>'‌四川省‌',
        '52'=>'‌贵州省',
        '53'=>'‌云南省‌',
        '54'=>'‌西藏自治区',
        '61'=>'‌陕西省‌',
        '62'=>'‌甘肃省‌',
        '63'=>'‌青海省',
        '64'=>'宁夏回族自治区‌',
        '65'=>'‌新疆维吾尔自治区',
    ];

    /**
     * 根据身份证号获取出生日期、年龄、性别
     * sex('01':男，'02'：女)
     */
    public static function getInfo($id_card)
    {
        $is_true=self::RegexpMatchIdCard($id_card);
        if(!$is_true){
            return '身份证号不合法';
        }
        //获取所在省
        $data['province']=self::$province[substr($id_card,0,2)];
        // 获取出生日期
        $data['birthday']=date('Y-m-d',strtotime(substr($id_card,6,8)));
        //获取性别
        $data['sex'] = substr($id_card, (strlen($id_card)==18 ? -2 : -1), 1) % 2 ? '01' : '02';
        //获取年龄
        $data['age']=self::calculateAge($data['birthday']);
        //获取星座
        $data['zodiacSign']=self::getZodiacSign($data['birthday']);
        return $data;
    }

    /**
     * 身份证号是否合法
     * @author 云升网络
     * 2025/1/3 13:45
     * @param $id_card
     * @return bool
     */
    private static function RegexpMatchIdCard($id_card)
    {
        //校验身份证位数和出生日期部分
        $pattern = "/^\d{6}(18|19|20)?\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|[xX])$/";
        preg_match($pattern, $id_card, $match);
        $result = (bool)$match;
        if (!$result) {
            return false;
        }
        //校验前两位是否是所有省份代码
        $province_code = ['11', '12', '13', '14', '15', '21', '22', '23', '31', '32', '33', '34', '35', '36', '37', '41', '42', '43', '44', '45', '46', '50', '51', '52', '53', '54', '61', '62', '63', '64', '65', '71', '81', '82', '91'];
        if (!in_array(substr($id_card, 0, 2), $province_code)) {
            return false;
        }
        //校验身份证最后一位
        $last_char = substr($id_card, -1);
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); // 前17位的权重
        $c = array(1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2); //模11后的对应校验码
        $t_res = 0;
        foreach ($factor as $k=>$v){
            $t_res=$t_res+$v*$id_card[$k];
        }
        $calc_last_char = $c [$t_res % 11];
        if ($last_char != $calc_last_char&&strtoupper($last_char)!=$calc_last_char) {
            return false;
        }
        return true;
    }

    /**
     * 根据出生日期获取年龄
     */
    private static function calculateAge($birthDate) {
        // 确保日期格式正确
        $dateTime = DateTime::createFromFormat('Y-m-d', $birthDate);
        // 检查日期格式是否有效
        if (!$dateTime) {
            return "无效的日期格式，请使用 YYYY-MM-DD 格式。";
        }
        // 获取当前日期
        $today = new DateTime();
        // 计算年龄
        return $today->diff($dateTime)->y;
    }

    /**
     * 获取星座
     * @author 云升网络
     * 2025/1/3 14:01
     * @return string|null
     */
    private static function getZodiacSign($birthDate) {
        $birthDate = DateTime::createFromFormat('Y-m-d', $birthDate);
        // 检查日期格式是否有效
        if (!$birthDate) {
            return "无效的日期格式，请使用 YYYY-MM-DD 格式。";
        }
        $month = (int)$birthDate->format('n');
        $day = (int)$birthDate->format('j');

        switch ($month) {
            case 1:
                return ($day <= 19) ? '摩羯' : '水瓶';
            case 2:
                return ($day <= 18) ? '水瓶' : '双鱼';
            case 3:
                return ($day <= 20) ? '双鱼' : '白羊';
            case 4:
                return ($day <= 19) ? '白羊' : '金牛';
            case 5:
                return ($day <= 20) ? '金牛' : '双子';
            case 6:
                return ($day <= 21) ? '双子' : '巨蟹';
            case 7:
                return ($day <= 22) ? '巨蟹' : '狮子';
            case 8:
                return ($day <= 22) ? '狮子' : '处女';
            case 9:
                return ($day <= 22) ? '处女' : '天秤';
            case 10:
                return ($day <= 22) ? '天秤' : '天蝎';
            case 11:
                return ($day <= 21) ? '天蝎' : '射手';
            case 12:
                return ($day <= 21) ? '射手' : '摩羯';
            default:
                return null; // 不应该到达这里
        }
    }

}