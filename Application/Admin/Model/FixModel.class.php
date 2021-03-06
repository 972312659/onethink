<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/4/26
 * Time: 14:21
 */

namespace Admin\Model;


use Think\Model;

class FixModel extends Model
{
    /**
     * 自动验证规则
     */
    protected $_validate=[
        ['question', 'require', '问题不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['name', 'require', '姓名不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['relation', 'require', '关系不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['tel', 'require', '电话不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['tel', '11', '请输入11位电话号码', self::EXISTS_VALIDATE, 'length'],
    ];
    /**
     * 自动完成验证
     */
    protected $_auto=[
        ['inputtime', 'time', self::MODEL_BOTH, 'function'],
        ['status', 0, self::MODEL_INSERT, 'string'],
        ['sn','getSn',1,'callback'],//调用本身模型的函数
    ];
    /**
     * 生成订单号
     */
    public function getSn()
    {
        $word='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $sn='';
        for($i=0;$i<=10;$i++){
            $sn.=substr($word,rand(0,61),1);
        }
        return $sn;
    }
}