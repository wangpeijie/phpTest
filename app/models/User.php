<?php
namespace Multiple\Models;
class User extends ModelBase
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