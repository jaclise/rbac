<?php
namespace jaclise\rbac\rules;

use Yii;

class ControllerRule extends Rule
{

    public function execute($permission, $params = [], $role = null)
    {
        $actionId = isset($params['actionId']) ? $params['actionId'] : LuLu::getApp()->requestedAction->id;
        
        $actions = $permission['value'];
        if (in_array($actionId, $actions))
        {
            return true;
        }
        
        $method = Yii::getApp()->request->method;
        $method = strtolower($method);
        if (in_array($actionId . ':' . $method, $actions))
        {
            return true;
        }
        
        return false;
    }
}
