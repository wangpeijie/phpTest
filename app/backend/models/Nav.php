<?php

namespace Multiple\Backend\Models;

class Nav extends BaseModel
{

        public function initialize()
        {
                $this->setDb('laowang_nav');
        }

        public static function getMaxTaix()
        {
                $data = self::findFirst([
                                'columns' => 'taix',
                                'order' => 'taix desc'
                ]);
                return $data ? $data->taix : 1;
        }

}
