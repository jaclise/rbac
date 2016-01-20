<?php
namespace jaclise\rbac\rules;

class Rule extends \yii\base\Object
{

    public static function getRules()
    {
        $items = [
            'BooleanRule' => '布尔值判断', 
            'ControllerRule' => '对控制器中Action判断'
        ];
        return $items;
    }

    public function execute($permission, $params = [], $role = null)
    {
        return true;
    }
}
