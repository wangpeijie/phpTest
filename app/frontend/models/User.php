<?php
namespace Multiple\frontend\Models;
class User extends BaseModel
{

        public function initialize()
        {
                parent::setDb('laowang_user');
        }
        
        public static function getinfo($id)
        {
                return self::findFirstById($id)->toArray();
        }
}