<?php
namespace Multiple\Models;
class BaseModel extends \Phalcon\Mvc\Model
{

        public $tableName;
        public $where;
        public $set;
        public $db;
        public $order = '';
        public $page = '';
        public $group = '';

        public function setDb($tableName)
        {
                $this->setSource($tableName);
                $this->tableName = $tableName;
                $this->db = getDb();
        }

        public static function updateData($params, $where = '1=1', $bind = [])
        {
                return self::find([
                                'conditions' => $where,
                                'bind' => $bind
                        ])->update($params);
        }

        public static function updateById($id, $params)
        {
                return self::findFirst([
                                'conditions' => 'id=?1',
                                'bind' => [1 => $id]
                        ])->update($params);
        }

        public static function delByIds($ids)
        {
                $ids = is_array($ids) ? $ids : explode(',', $ids);
                return self::find([
                                'conditions' => 'id in(' . implode(',', $ids) . ')'
                        ])->delete();
        }

        public static function delByWhere($where, $bind)
        {
                return self::find([
                                'conditions' => $where,
                                'bind' => $bind
                        ])->delete();
        }

        public static function delById($id)
        {
                return self::findFirstById($id)->delete();
        }

        public static function getById($id, $cell = '*')
        {
                return self::findFirst([
                                'columns' => $cell,
                                'conditions' => 'id=?1',
                                'bind' => [1 => $id]
                        ])->toArray();
        }

        /**
         */
        public static function getByIds($ids, $cell = '*', $order = 'id asc')
        {
                $ids = is_array($ids) ? $ids : explode(',', $ids);
                return self::find([
                                'columns' => $cell,
                                'conditions' => 'id in(' . implode(',', $ids) . ')',
                                'order' => $order
                        ])->toArray();
        }

        /**
         */
        public static function getByWheres($cell = '*', $where = ' 1=1 ', $bind = [], $order = 'id asc')
        {
                return self::find([
                                'columns' => $cell,
                                'conditions' => $where,
                                'bind' => $bind,
                                'order' => $order
                        ])->toArray();
        }

}
