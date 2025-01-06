<?php

namespace Lijingping\SelfUse;
/**
 * lon 经度
 * lat 维度
 */
class Distance
{
    /**
     * 两点之间的距离
     * @param $p1
     * @param $p2
     * @param bool $if_unit
     * @return float
     */
    public static function get_distance_text($p1, $p2, bool $if_unit = true): float
    {
        //近距离计算(百公里之内)
//        $distance = self::distance_haversine($p1, $p2);
//        if ($distance / 1000 > 1000) {
        //千公里以上使用远距离计算
        $distance = self::distance_vincenty($p1, $p2);
//        }
        if ($if_unit) {
            if ($distance < 1000) {
                $distance = round($distance, 2);
            } else {
                $distance = round($distance / 1000, 2);
            }
        } else {
            $distance = round($distance, 2);
        }
        return $distance;
    }

    /**
     * 点到线的距离 高德
     * $pos =  [116.377904, 39.915423],$path = [[116.368904, 39.913423],[116.382122, 39.901176],[116.387271, 39.912501],[116.398258, 39.904600]]
     */
    public static function point_to_line($pos = [], $path = [], bool $if_unit = true): float
    {
        $distance = [];
        //线段个数
        $line_num = count($path) - 1;
        if ($line_num > 0) {
            for ($i = 0; $i < $line_num; $i++) {
                //计算点到线段的最短距离
                $distance[] = self::spotToLine($pos, $path[$i], $path[$i + 1]);
            }
        } elseif ($line_num == 0) {
            $distance[] = self::get_distance_text($pos, $path[0], false);
        } else {
            $distance[] = 0;
        }
        $distance = min($distance);
        if ($if_unit) {
            if ($distance < 1000) {
                $distance = round($distance, 2);
            } else {
                $distance = round($distance / 1000, 2);
            }
        } else {
            $distance = round($distance, 2);
        }
        return $distance;
    }

    /**
     * 较长距离（数千公里以上）计算，更精确
     * @param $p1
     * @param $p2
     * @return float|int
     */
    private static function distance_vincenty($p1, $p2)
    {
        //经度
        $lon1 = $p1[0];
        $lon2 = $p2[0];
        //维度
        $lat1 = $p1[1];
        $lat2 = $p2[1];
        $earthRadius = 6371000; // in meters

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1) * cos($lat2) * sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * 较短距离（数百公里以内）计算，更精确
     * @param $p1
     * @param $p2
     * @return float|int
     */
    private static function distance_haversine($p1, $p2)
    {
        //经度
        $lon1 = $p1[0];
        $lon2 = $p2[0];
        //维度
        $lat1 = $p1[1];
        $lat2 = $p2[1];
        $d = PI() / 180;
        $f = $lon1 * $d;
        $h = $lon2 * $d;
        $k = 2 * 6378137;
        $d = $lat1 * $d - $lat2 * $d;
        $e = (1 - cos($h - $f) + (1 - cos($d)) * cos($f) * cos($h)) / 2;
        return $k * asin(sqrt($e));
    }

    /**
     * 计算点到线段的最小距离
     */
    private static function spotToLine($point, $lineStart, $lineEnd): float
    {
        //点到线段开始为止的距离
        $a = self::get_distance_text($point, $lineStart, false);
        //点到线段结束为止的距离
        $b = self::get_distance_text($point, $lineEnd, false);
        //线段的长度
        $c = self::get_distance_text($lineStart, $lineEnd, false);
        //判断点到线段的最短距离
        if ($a * $a + $c * $c <= $b * $b) {
            //如果点到线段起点的长度的平方+线段长度的平方<=点到线段结束的长度的平方，则线段起点的角为钝角或直角，点到线段的最短距离为点到线段起点的距离
            $result = $a;
        } else {
            //线段起点的角为锐角
            if ($b * $b + $c * $c <= $a * $a) {
                //如果点到线段重点的长度的平方+线段长度的平方<=点到线段起点的长度的平方，则线段终点的角为钝角或直角，点到线段的最短距离为点到线段终点的距离
                $result = $b;
            } else {
                //线段终点的角也为锐角
                //使用海伦公式计算出三角形的面积
                $p = ($a + $b + $c) / 2;
                $s = sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));
                //三角形的面积公式：面积=底*高/2 推导出：高=面积*2/底
                $result = ($s * 2) / $c;
            }
        }
        return $result;
    }
}