<?php

namespace Multiple\Backend\Models;

class User extends BaseModel
{

        public function initialize()
        {
                $this->setDb('laowang_user');
        }

        public function getuserbyid($id)
        {
                $db = getDb();
                $sql = "select id from " . $this->tableName . " where id=:id";
                $res = $db->execute($sql, Phalcon\Db::FETCH_ASSOC, array('id' => $id));
                if ($res)
                {
                        return $res;
                }
                return false;
        }

        /**
         * 编辑单条数据
         * @param type $uid
         * @param type $params
         * @return type 
         */
        public static function updateByUid($uid, $params)
        {
                $fields = array_keys($params);
                $values = array_values($params);
                $db = getDb();
                $r = $db->update(
                        "user", $fields, $values, 'ur_id=' . $uid
                );
                return $r ? $r : false;
        }

        /**
         * 编辑多条数据
         * @param type $uid
         * @param type $params
         * @return type
         */
        public function updateCellByUid($uid, $cell, $type = 1)
        {
                $db = getDb();
                if ($type == 1)
                {
                        $sql = "update yh_user SET " . $cell . " = CASE WHEN " . $cell . " is NULL THEN 1 ELSE " . $cell . " +1 END WHERE ur_id in(" . $uid . ")";
                } else
                {
                        $sql = "update yh_user SET " . $cell . " = CASE WHEN " . $cell . " <1 THEN 0 ELSE " . $cell . " -1 END WHERE ur_id in(" . $uid . ")";
                }
                return $db->execute($sql);
        }

        public function getUserNameByUid($uids)
        {
                $db = getDb();
                $uids = array_unique($uids);
                $uidStr = implode(',', $uids);
                $sqlU = "SELECT ur_id,ur_nickname FROM user_info where 1=1";
                $arrU = array();
                if (count($uids) > 1)
                {

                        $sqlU .= " AND ur_id in(" . $uidStr . ")";
                } else
                {
                        $sqlU .= " AND ur_id = :id";
                        $arrU['id'] = $uidStr;
                }
                $dataUser = $db->fetchAll($sqlU, 1, $arrU);
                $userArr = array();
                foreach ($dataUser as $dataUserV)
                {
                        $userArr[$dataUserV['ur_id']] = $dataUserV['ur_nickname'];
                }
                return $userArr;
        }

        public static function getinfo($id)
        {
                return self::findFirstById($id)->toArray();
        }

}
