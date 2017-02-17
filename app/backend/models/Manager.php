<?php
namespace Multiple\Backend\Models;
class Manager extends BaseModel
{

        public function initialize()
        {
                parent::setDb('laowang_manager');
        }

        public static function getinfo($id)
        {
                return self::findFirstById($id)->toArray();
        }
}