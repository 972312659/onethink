<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/5/2
 * Time: 19:25
 */

namespace Admin\Model;


use Think\Model;

class SaleAndRentalModel extends Model
{
    /**
     * 自动验证规则
     */
    protected $_validate=[
        ['name', 'require', '发布人不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['title', 'require', '标题不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['price', 'require', '价格不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['status', 'require', '租售不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['tel', 'require', '电话不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH],
        ['tel', '11', '请输入11位电话号码', self::EXISTS_VALIDATE, 'length'],
    ];
    /**
     * 自动完成验证
     */
    protected $_auto=[
        ['create_time', 'time', self::MODEL_BOTH, 'function'],
        ['publish', 0, self::MODEL_INSERT, 'string'],
    ];
}