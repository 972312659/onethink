<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/5/2
 * Time: 17:17
 */

namespace Home\Model;


use Think\Model;

class ProprietorModel extends Model
{
    /**
     * @var array
     * 自动验证规则
     */
    protected $_validate=[
        ['name', 'require', '姓名不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['room_number', 'require', '房号不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['relation', 'require', '关系不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['tel', 'require', '电话不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['userID', 'require', '身份证不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['tel', '11', '请输入11位电话号码', self::EXISTS_VALIDATE, 'length'],
    ];

}