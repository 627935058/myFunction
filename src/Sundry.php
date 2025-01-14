<?php
namespace Lijingping\SelfUse;
use Exception;

/**
 * @author 云升网络
 * 2025/1/3 17:00
 **/
class Sundry
{
    /**
     * 二维数组根据某个字段排序
     * @param array $array
     * @param string $keys
     * @param int $sort 排序方式 SORT_ASC正序 SORT_DESC 倒序
     * @return array
     *@author 云升网络
     * 2025/1/6 10:37
     */
    public static function array_sort(array $array, string $keys, int $sort = SORT_ASC): array
    {
        if(empty($array)) return [];
        $keyValues = [];
        foreach ($array as $k => $v) {
            $keyValues[$k] = $v[$keys];
        }
        array_multisort($keyValues, $sort, $array);
        return $array;
    }

    /**
     * 数组根据某个字段进行分组
     * @author 云升网络
     * 2025/1/6 10:39
     * @param array $array
     * @param string $keys
     * @return array
     */
    public static function array_group(array $array, string $keys): array
    {
        $arr = [];
        foreach ($array as $k => $v) {
            $arr[$v[$keys]][] = $v;
        }
        return $arr;
    }

    /**
     * 二维数组根据指定的字段指定排序方式排序
     * @param array $array
     * @param string $keys
     * @param array $order 制定的排序方式 如  $array中的id字段按照[3,4,1,2,5,6,7]的顺序进行排序
     * @return array
     *@author 云升网络
     * 2025/1/6 10:40
     */

    public static function array_sort_by_keys_rule(array $array, string $keys, array $order): array
    {
        usort($array, function($a, $b) use ($order,$keys) {
            $posA = array_search($a[$keys], $order);
            $posB = array_search($b[$keys], $order);
            if ($posA === false) {
                $posA = PHP_INT_MAX; // 如果 $a[$keys] 不在 $order 中，则给它一个很大的值
            }
            if ($posB === false) {
                $posB = PHP_INT_MAX; // 如果 $b[$keys] 不在 $order 中，则给它一个很大的值
            }
            return $posA - $posB;
        });
        return $array;
    }

    /**
     * 获取数组的全排列
     * @author 云升网络
     * 2025/1/6 11:20
     * @param $arr
     * @return array
     */
    public static function array_dfs($arr): array
    {
        $len = count($arr);
        $stack = [];
        $used = array_fill(0, $len, false);
        $list = [];
        self::dfs($arr, $len, 0, $stack, $used, $list);
        return $list;
    }

    /**
     * 获取数组的全排列
     * @param array $arr //待处理的数组
     * @param int $len //数组长度
     * @param  $depth //递归深度
     * @param  $stack //一个引用参数，保存当前排列的栈。它在递归过程中被不断更新，记录当前选择的元素。
     * @param  $used //一个布尔数组（长度与$arr相同），用于标记当前数组元素是否被使用，避免重复使用相同的元素。
     * @param  $list //一个引用参数，保存生成的排列组合，最终作为结果返回。
     *@author 云升网络
     * 2025/1/6 11:09
     */

    private static function dfs(array $arr, int $len, $depth, &$stack, &$used, &$list)
    {
        if ($depth == $len) {
            $list[] = implode(',', $stack);
            $list = array_unique($list);
            return;
        }
        for ($i = 0; $i < $len; $i++) {
            if ($used[$i]) {
                continue;
            }
            $stack[] = $arr[$i];
            $used[$i] = true;
            self::dfs($arr, $len, $depth + 1, $stack, $used, $list);
            array_pop($stack);
            $used[$i] = false;
        }
    }
    /**
     * 将数值金额转换为中文大写金额
     * @param $amount
     * @param string $prefix //返回值的前缀
     * @return string
     * @throws Exception
     * @author 云升网络
     * 2025/1/6 10:44
     */
    public static function AmountToCn($amount,string $prefix=''): string
    {
        // 检查输入是否合法
        if (!is_numeric($amount)) {
            throw new Exception('要转换的金额只能为数字!');
        }
        // 处理负数情况
        $isNegative = $amount < 0;
        $amount = abs($amount); // 转换为正数进行处理
        // 特殊情况：处理零
        if ($amount == 0) {
            $result= "零元整";
        }else{
            // 定义中文数字和单位
            $digital = ['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'];
            $position = ['仟', '佰', '拾', '亿', '仟', '佰', '拾', '万', '仟', '佰', '拾', '元'];

            // 拆分整数和小数部分
            $amountArr = explode('.', number_format($amount, 2, '.', ''));
            $integer = $amountArr[0];
            $decimal = $amountArr[1] ?? '00';

            // 检查金额长度
            if (strlen($integer) > 12) {
                throw new Exception('要转换的金额不能为万亿及更高金额!');
            }

            // 处理整数部分
            $result = '';
            $integerArr = str_split($integer);
            $integerLength = count($integerArr);

            for ($i = 0; $i < $integerLength; $i++) {
                $digit = (int)$integerArr[$i];
                // 只处理非零的数字
                if ($digit > 0) {
                    // 加入数字和单位
                    $result .= $digital[$digit] . $position[12 - $integerLength + $i];
                } else {
                    // 处理连续的零
                    if ($i > 0 && (int)$integerArr[$i - 1] > 0) {
                        $result .= $digital[0]; // 加入“零”
                    }
                }
            }

            // 处理小数部分
            if ($decimal > 0) {
                $decimalArr = str_split($decimal);
                if ($decimalArr[0] > 0) {
                    $result .= $digital[$decimalArr[0]] . '角';
                }
                if (isset($decimalArr[1]) && $decimalArr[1] > 0) {
                    $result .= $digital[$decimalArr[1]] . '分';
                }
            } else {
                $result .= '整';
            }

            // 添加负号
            if ($isNegative) {
                $result = '负' . $result;
            }
        }
        return $prefix . $result;
    }

    /**
     * 构建树形结构
     * @author 云升网络
     * 2025/1/6 15:47
     * @param array $comments
     * @param int $parentId
     * @param string $field
     * @param string $p_field
     * @return array
     */
    public static function buildCommentTree(array $comments, int $parentId = 0,string $field='id', string $p_field='pid'): array
    {
        $branch = [];
        foreach ($comments as $comment) {
            if ($comment[$p_field] == $parentId) {
                // 递归获取子评论
                $children = self::buildCommentTree($comments, $comment[$field],$field,$p_field);
                if ($children) {
                    $comment['children'] = $children; // 将子评论添加到当前评论中
                }else{
                    $comment['children'] = [];
                }
                $branch[] = $comment;
            }
        }
        return $branch;
    }
}