<?php
namespace app\api\middleware;

class IDCard{
    public static function getAgeFromIdNo($idno=''){
        $btime = strtotime(substr($idno, 6, 8));//idno是身份证号 截取日期并转为时间戳
        $byear =date('Y',$btime );
        $bmonth =date('m',$btime );
        $bday =date('d',$btime );
        $curYear=date('Y');
        $curMoth = date('m');
        $curDay = date('d');
        $age = $curYear - $byear;
        if( $curMoth < $bmonth || ($curMoth ==$bmonth && $curDay < $bday)){
            $age--;
        }
        return $age;
    }
}