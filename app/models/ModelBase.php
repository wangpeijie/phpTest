<?php
namespace Multiple\Models;
class ModelBase extends \Phalcon\Mvc\Model
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

        /**
         * 添加数据
         * @param type $table
         * @param type $para
         * @return type
         */
        public function insertData($params)
        {
                $fields = array_keys($params);
                $values = array_values($params);
                $r = $this->db->insert(
                        $this->tableName, $values, $fields
                );
                return $r ? $r : false;
        }

        public function batchInsert($params)
        {
                $keys = array_keys($params[0]);
                $sql = 'insert into ' . $this->tableName . '(' . implode(',', $keys) . ') values';
                foreach ($params as $value)
                {
                        $values = array_values($value);
                        foreach ($values as $key => $value)
                        {
                                if (is_string($value))
                                {
                                        $values[$key] = "'" . addcslashes(str_replace("'", "''", $value), "\000\n\r\\\032") . "'";
                                }
                        }
                        $sql2[] = '(' . implode(',', $values) . ')';
                }
                $sql .= implode(',', $sql2);
                return $this->db->execute($sql);
        }

        /**
         * 编辑数据
         * @param type $table
         * @param type $where
         * @param type $params
         * @return type
         */
        public function updateData($where, $params)
        {
                $fields = array_keys($params);
                $values = array_values($params);
                $r = $this->db->update(
                        $this->tableName, $fields, $values, $where
                );
                return $r ? $r : false;
        }

        public function delByIds($ids)
        {
                $ids = is_array($ids) ? $ids : explode(',', $ids);
                $sql = "delete from " . $this->tableName . " where id in(" . implode(',', $ids) . ")";
                return $this->db->execute($sql);
        }

        public function delById($id)
        {
                $sql = "delete from " . $this->tableName . " where id=:id";
                return $this->db->execute($sql, ['id' => (int) $id]);
        }

        /**
         */
        public function getById($id, $cell = '*')
        {
                $sql = 'SELECT ' . $cell . ' FROM ' . $this->tableName . ' where id=:id';
                return $this->db->fetchOne($sql, Phalcon\Db::FETCH_ASSOC, ['id' => $id]);
        }

        /**
         */
        public function getByIds($ids, $cell = '*')
        {
                $ids = is_array($ids) ? $ids : explode(',', $ids);
                $sql = 'SELECT ' . $cell . ' FROM ' . $this->tableName . ' where id in(' . implode(',', $ids) . ')';
                return $this->db->fetchAll($sql, Phalcon\Db::FETCH_ASSOC);
        }

        /**
         */
        public function getByWhere($cell = '*', $where = [])
        {
                $this->setWhere($where);
                $sql = 'SELECT ' . $cell . ' FROM ' . $this->tableName . ' where ' . $this->where . $this->group . $this->order . $this->page;
                return $this->db->fetchAll($sql, Phalcon\Db::FETCH_ASSOC, $where);
        }

        /**
         */
        public function getByWheres($cell = '*', $where = ' 1=1 ', $param = [])
        {
                $sql = 'SELECT ' . $cell . ' FROM ' . $this->tableName . ' where ' . $where . $this->group . $this->order . $this->page;
                return $this->db->fetchAll($sql, Phalcon\Db::FETCH_ASSOC, $param);
        }

        public function order($order = ' id desc')
        {
                if (is_array($order))
                {
                        $this->order = ' order by ' . implode(',', $order);
                } else
                {
                        $this->order = ' order by ' . $order;
                }
                return $this;
        }

        public function page($page, $size)
        {
                $this->page = ' limit ' . ($page - 1) * $size . ',' . $size;
                return $this;
        }

        public function group($id)
        {
                $this->group = ' group by ' . (is_array($id) ? implode(',', $id) : $id);
                return $this;
        }

        /**
         * 单个修改
         */
        public function updateById($id, $params)
        {
                $this->setSet($params);
                $sql = 'update ' . $this->tableName . ' set ' . $this->set . ' where id=:id';
                $params['id'] = $id;
                return $this->db->execute($sql, $params);
        }

        private function setSet($param)
        {
                $sql = '';
                $keys = array_keys($param);
                foreach ($keys as $value)
                {
                        $sql2[] = $value . '=:' . $value;
                }
                $this->set = $sql . implode(',', $sql2);
        }

        private function setWhere($param)
        {
                $keys = $param ? array_keys($param) : [];
                foreach ($keys as $value)
                {
                        $sql2[] = $value . '=:' . $value;
                }
                $this->where = $param ? implode(' and ', $sql2) : ' 1=1 ';
        }

        public function updateByWhere($set, $where)
        {
                $this->setSet($set);
                $this->setWhere($where);
                $sql = 'update ' . $this->tableName . ' set ' . $this->set . ' where ' . $this->where;
                $params = array_merge($set, $where);
                return $this->db->execute($sql, $params);
        }

        public function updateByWheres($set, $where = ' 1=1 ', $param = [])
        {
                $this->setSet($set);
                $this->where = $where;
                $sql = 'update ' . $this->tableName . ' set ' . $this->set . ' where ' . $this->where;
                $params = array_filter(array_merge($set, $param));
                return $this->db->execute($sql, $params);
        }

}
