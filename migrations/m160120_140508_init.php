<?php

use yii\db\Schema;
use yii\db\Migration;

class m160120_140508_init extends Migration
{


    public function safeUp()
    {
       
        $tableOptions = '';
        if ($this->db->driverName === 'mysql') 
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        //授权表
        $this->createTable('rbac_auth_assignment', [
            'user' => Schema::TYPE_STRING . "(64)  NOT NULL  COMMENT '用户'",
            'role' => Schema::TYPE_STRING . "(64) NOT NULL COMMENT '角色' ",
        ], $tableOptions );

        $this->addPrimaryKey('pk_assignment', 'rbac_auth_assignment', ['user', 'role']);

        $this->insert('rbac_auth_assignment', [

            'user' => 'admin',
            'role' => 'administrator'
        ]);


      //角色分类表
       $this->createTable('rbac_auth_category', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . "(64) NOT NULL COMMENT '分类名称'",
            'description' => Schema::TYPE_STRING . "(128) COMMENT '描述'",
            'type' => Schema::TYPE_INTEGER . " NOT NULL COMMENT '类型'",
            'sort_num' => Schema::TYPE_INTEGER . " NOT NULL COMMENT '排序号'"

        ], $tableOptions );

        $this->batchInsert('rbac_auth_category', 
            ['id', 'name', 'description', 'type', 'sort_num'],
            [
                [1, '系统角色', NULL, 1, 1],
                [2, '会员角色', NULL, 1, 1],
                [5, '基本操作规则', NULL, 3, 1],
                [6, '基本权限', '', 2, 1],
                [7, '系统权限', '系统权限', 2, 100],
                [8, '管理角色', '', 1, 2],
                [9, '控制器权限', '', 2, 1436084643],
            ]
        );

      //权限表
       $this->createTable('rbac_auth_permission', [
            'id' => Schema::TYPE_STRING . "(64) NOT NULL",
            'category' => Schema::TYPE_STRING . "(64) NOT NULL COMMENT '分类名称'",
            'name' =>  Schema::TYPE_STRING . "(64) NOT NULL COMMENT '权限名称'",
            'description' => Schema::TYPE_STRING . "(128)  COMMENT '描述'",
            'form' => Schema::TYPE_INTEGER . " NOT NULL COMMENT '表单'",
            'options' => Schema::TYPE_TEXT . " COMMENT '选项'",
            'default_value' => Schema::TYPE_TEXT . " COMMENT '默认值'",
            'rule' => Schema::TYPE_STRING . "(64) COMMENT '规则'",
            'sort_num' => Schema::TYPE_INTEGER . " NOT NULL COMMENT '排序号'"

        ], $tableOptions );

        $this->addPrimaryKey('pk_permission', 'rbac_auth_permission', 'id');

        $this->batchInsert('rbac_auth_permission', 
            ['id', 'category', 'name', 'description', 'form', 'options', 'default_value', 'rule', 'sort_num'],
            [
                ['allow_access', 'system', '允许访问', '', 3, NULL, '*', 'BooleanRule', 10],
                ['deny_access', 'system', '禁止访问', '', 3, NULL, '', NULL, 1000],
                ['manager_admin', 'system', '管理后台', '', 1, NULL, '', 'BooleanRule', 0],
                ['menu/menu', 'controller', '菜单子项', '', 5, NULL, 'index|首页\r\ncreate|录入\r\nupdate:get|编辑(GET)\r\nupdate:post|编辑(POST)\r\ndelete|删除', 'ControllerRule', 15],
                ['menu/menu-category', 'controller', '菜单管理', '', 5, NULL, 'index|首页\r\ncreate|录入\r\nupdate:get|编辑(GET)\r\nupdate:post|编辑(POST)\r\ndelete|删除', 'ControllerRule', 10],
                ['rbac/permission', 'controller', '权限管理', '', 5, NULL, 'index|首页\r\ncreate|录入\r\nupdate:get|编辑(GET)\r\nupdate:post|编辑(POST)\r\ndelete|删除', 'ControllerRule', 1437841705],
                ['rbac/permission', 'controller', '权限管理', '', 5, NULL, 'index|首页\r\ncreate|录入\r\nupdate:get|编辑(GET)\r\nupdate:post|编辑(POST)\r\ndelete|删除', 'ControllerRule', 1437841705],
                ['rbac/role', 'controller', '角色管理', '', 5, NULL, 'index|首页\r\ncreate|录入\r\nrelation:get|设置权限(GET)\r\nrelation:post|设置权限(POST)\r\nupdate:get|编辑(GET)\r\nupdate:post|编辑(POST)\r\ndelete|删除', 'ControllerRule', 1437841695],
                ['system/setting', 'controller', '系统设置', '', 5, NULL, 'basic:get|站点信息(GET)\r\nbasic:post|站点信息(POST)\r\naccess:get|注册与访问控制(GET)\r\naccess:post|注册与访问控制(POST)\r\nseo:get|SEO设置(GET)\r\nseo:post|SEO设置(POST)\r\ndatetime:get|时间设置(GET)\r\ndatetime:post|时间设置(POST)\r\nemail:get|邮件设置(GET)\r\nemail:post|邮件设置(POST)', 'ControllerRule', 0],
                ['user/user', 'controller', '用户管理', '', 5, NULL, 'index|首页\r\ncreate|录入\r\nupdate:get|编辑(GET)\r\nupdate:post|编辑(POST)\r\ndelete|删除', 'ControllerRule', 1437841685]
            ]
        );

      //权限关系表
       $this->createTable('rbac_auth_relation', [
            'role' => Schema::TYPE_STRING . "(64) NOT NULL",
            'permission' => Schema::TYPE_STRING . "(64) NOT NULL",
            'value' => Schema::TYPE_STRING . "(128) ",

        ], $tableOptions );

        $this->addPrimaryKey('pk_permission', 'rbac_auth_relation', ['role', 'permission']);

        $this->batchInsert('rbac_auth_relation', 
            [`role`, `permission`, `value`],
            [
                ['administrator', 'allow_access', '*'],
                ['administrator', 'deny_access', ''],
                ['administrator', 'manager_admin', '1'],
                ['administrator', 'menu/menu', 'index,create,update:get,update:post,delete'],
                ['administrator', 'menu/menu-category', 'index,create,update:get,update:post,delete'],
                ['administrator', 'rbac/permission', 'index,create,update:get,update:post,delete'],
                ['administrator', 'rbac/role', 'index,create,relation:get,relation:post,update:get,update:post,delete'],
                ['administrator', 'system/setting', 'basic:get,basic:post,access:get,access:post,seo:get,seo:post,datetime:get,datetime:post,email:get,email:post'],
                ['administrator', 'user/user', 'index,create,update:get,update:post,delete']
            ]
        );

      //角色表
       $this->createTable('rbac_auth_role', [
            'id' => Schema::TYPE_STRING . "(64) NOT NULL",
            'category' => Schema::TYPE_STRING . "(64) NOT NULL",
            'name' => Schema::TYPE_STRING . "(64) NOT NULL",
            'description' => Schema::TYPE_STRING . "(128)",
            'is_system' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT '0'",
        ], $tableOptions );

      $this->addPrimaryKey('pk_role', 'rbac_auth_role', 'id');

      $this->batchInsert('rbac_auth_role', 
            ['id', 'category', 'name', 'description', 'is_system'],
            [
                ['administrator', 'admin', '管理员', '', 1],
                ['demo', 'admin', '测试角色', '', 0],
                ['deny_access', 'system', '禁止访问', '', 1],
                ['deny_speak', 'system', '禁止发言', '', 1],
                ['deny_speak', 'system', '禁止发言', '', 1],
                ['editor', 'admin', '编辑', '', 0],
                ['guest', 'system', '游客', '', 1],
                ['member_1', 'member', '初级会员', '', 0],
                ['member_2', 'member', '中级会员', '', 0]
            ]
        );

    }

    public function safeDown()
    {
        $this->dropTable('rbac_auth_assignment');

        $this->dropTable('rbac_auth_category');

        $this->dropTable('rbac_auth_permission');

        $this->dropTable('rbac_auth_relation');

        $this->dropTable('rbac_auth_role');
    }
}
