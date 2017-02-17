<?php

namespace Multiple\Backend\Models;

class BackNav extends BaseModel
{

        public function initialize()
        {
                $this->setDb('laowang_back_nav');
        }

        public static function getMaxTaix()
        {
                $data = self::findFirst([
                                'columns' => 'taix',
                                'order' => 'taix desc'
                ]);
                return $data ? $data->taix : 1;
        }

        public static function getTopNavHtml($on)
        {
                $data = self::find(['conditions' => 'parent_id=0', 'order' => 'taix desc'])->toArray();
                $html = '';
                foreach ($data as $key => $value)
                {
                        $html .= '<li><a href="' . $value['url'] . '" class="' . ($on == $value['point'] ? 'active' : '') . '">' . $value['title'] . '</a></li>';
                }
                return $html;
        }

}
