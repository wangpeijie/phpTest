<?php

namespace Multiple\Backend\Models;

class Publics
{

        public static function leftNav($data)
        {
                $html .= '<ul style="margin-left:10px;">';
                foreach ($data as $value)
                {
                        $html .= '<li style="padding:10px 0 10px 20px;" data-url="' . $value['url'] . '"><a href="javascript:;">' . $value['title'] . '</a>';
                        if (isset($value['child']) && $value['child'])
                        {
                                $html .= self::leftNav($value['child']);
                        }
                        $html .= '</li>';
                }
                $html .= '</ul>';
                return $html;
        }

}
