<?php

/**
 * Created by PhpStorm.
 * User: ivokroon
 * Date: 08/01/16
 * Time: 16:11
 */
class DiscountSaved extends Model
{
//    private $_userId;
//
//    public function __construct()
//    {
//        $session = new SessionController();
//        $this->_userId = $session->get("user_session")['user_id'];
//    }

    public function get(){
        try {
            $session = new SessionController();
            $user_id = $session->get('user_session')['user_id'];

            $query = "SELECT discount.title, discount.end_date FROM discount INNER JOIN discount_saved ON discount_saved.discount_id = discount.id WHERE discount_saved.user_id = :id";
            $stmt = $this->connection->prepare($query);
//            $statement = $this->connection->prepare($query);
            $stmt->execute(array(":id" => $user_id));
            $result = $stmt->fetchAll();
        }catch (PDOException $e){
            return $e->getMessage();
        }
        return $result;
    }

    public function checkDiscountIsSavedByUserId($id){
        try {
            $query = "SELECT id FROM discount_saved WHERE discount_id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->execute(array(":id" => $id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$row){
                return false;
            }else{
                return true;
            }

        }catch (PDOException $e){
            return $e->getMessage();
        }


    }

}