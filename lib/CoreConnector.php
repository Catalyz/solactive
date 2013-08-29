<?php

class CoreConnector {
    static function login($login, $password)
    {
        $dbh = self::connect();

        $sth = $dbh->prepare('SELECT katao_user.id
        		, katao_user.phone as phone
        		, katao_user.email as email
        		, katao_user.is_admin as is_admin
        		, katao_member.is_referer as is_referer
        		, katao_member.is_delegate as is_delegate
        		, katao_member.is_member as is_member
        		, CONCAT(katao_member.first_name, " ", katao_member.last_name) as person_name
        		, katao_supplier.name as company_name
        	FROM katao_user
        		LEFT OUTER JOIN katao_member ON katao_user.katao_member_id = katao_member.id
        		LEFT OUTER JOIN katao_supplier ON katao_user.katao_supplier_id = katao_supplier.id
			WHERE (REPLACE(katao_user.phone, " ", "") = :login OR katao_member.card_number_sol = :login) AND katao_user.password = :password');

        $sth->execute(array(':login' => $login, ':password' => $password));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    static function getRoleFromFlags($flags)
    {
        $result = null;
        foreach(array('is_member', 'is_delegate', 'is_referer', 'is_admin') as $permission) {
            if ($flags[$permission]) {
                $result = substr($permission, 3);
            }
        }
        return $result;
    }
    static function getUserInfo($login)
    {
        $dbh = self::connect();

        $sth = $dbh->prepare('SELECT katao_user.id,
	IF(katao_supplier.name IS NULL,
		CONCAT(katao_member.first_name, " ", katao_member.last_name),
		IF(katao_member.first_name IS NULL,
			katao_supplier.name,
			CONCAT(katao_supplier.name, " (", katao_member.first_name, " ", katao_member.last_name, ")")
		)
	) as name
       		, katao_user.is_admin as is_admin
       		, katao_member.is_referer as is_referer
       		, katao_member.is_delegate as is_delegate
       		, katao_member.is_member as is_member
        	FROM katao_user
        		LEFT OUTER JOIN katao_member ON katao_user.katao_member_id = katao_member.id
        		LEFT OUTER JOIN katao_supplier ON katao_supplier.member_id = katao_member.id
			WHERE (REPLACE(katao_user.phone, " ", "") = :login OR katao_member.card_number_sol = :login)');

        $sth->execute(array(':login' => $login));
        if($result = $sth->fetch(PDO::FETCH_ASSOC)){
  		$result['role'] = CoreConnector::getRoleFromFlags($result);
        }
        return $result;
    }
    static function getCoordsForAllActors()
    {
        $dbh = self::connect();
        $result = array();
        foreach($dbh->query ('SELECT id, gmap_lng as lng, gmap_lat as lat FROM katao_user', PDO::FETCH_ASSOC) as $row) {
            $result[$row['id']] = ($row['lat'] && $row['lng'])?$row['lat'] . ',' . $row['lng']:null;
        }
        return $result;
    }

    static function getAllUsersList($includeDefault)
    {
        $dbh = self::connect();
        $result = array();
        if ($includeDefault) {
            $result[0] = 'Tous';
        }
        foreach($dbh->query ('SELECT DISTINCT katao_user.id as id,
	IF(katao_supplier.name IS NULL,
		CONCAT(katao_member.first_name, " ", katao_member.last_name),
		IF(katao_member.first_name IS NULL,
			katao_supplier.name,
			CONCAT(katao_supplier.name, " (", katao_member.first_name, " ", katao_member.last_name, ")")
		)
	) as name

        FROM katao_user
        		LEFT OUTER JOIN katao_member ON katao_user.katao_member_id = katao_member.id
        		LEFT OUTER JOIN katao_supplier ON katao_supplier.member_id = katao_member.id
        WHERE katao_user.status = 2 ORDER BY name ASC', PDO::FETCH_ASSOC) as $row) {
            $result[$row['id']] = $row['name'];
        }
        return $result;
    }

    static function getAllUsersInfos()
    {
        static $result = null;
        if (null == $result) {
            $dbh = self::connect();

            foreach($dbh->query ('SELECT DISTINCT katao_user.id as id,
	IF(katao_supplier.name IS NULL,
		CONCAT(katao_member.first_name, " ", katao_member.last_name),
		IF(katao_member.first_name IS NULL,
			katao_supplier.name,
			CONCAT(katao_supplier.name, " (", katao_member.first_name, " ", katao_member.last_name, ")")
		)
	) as name,
		katao_member.card_number_sol as card_no,
		REPLACE(katao_user.phone, " ", "") as phone
		, katao_user.is_admin as is_admin
   		, katao_member.is_referer as is_referer
   		, katao_member.is_delegate as is_delegate
   		, katao_member.is_member as is_member
        FROM katao_user
        		LEFT OUTER JOIN katao_member ON katao_user.katao_member_id = katao_member.id
        		LEFT OUTER JOIN katao_supplier ON katao_supplier.member_id = katao_member.id
         ORDER BY name ASC', PDO::FETCH_ASSOC) as $row) { // WHERE katao_user.status = 2
                $result[$row['id']] = array(
                    'name' => $row['name'],
                    'phone' => $row['phone'],
                    'card_no' => $row['card_no'],
                    );
                $result[$row['id']]['role'] = CoreConnector::getRoleFromFlags($row);
            }
        }

        return $result;
    }

    static function addDeposit($amount, $caption)
    {
    }

    static protected function connect()
    {
        static $dbHandler = null;

        if (null == $dbHandler) {
            $dbHandler = new PDO('mysql:dbname=catalyz_sol_violette;host=localhost', 'czKatao', '4,X3n,3U9ytsHujE');
        }

        return $dbHandler;
    }
}

?>
