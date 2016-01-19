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
            $query = "SELECT discount.id,discount.title,discount.description, discount.end_date FROM discount INNER JOIN discount_saved ON discount_saved.discount_id = discount.id WHERE discount_saved.user_id = :id";
            $stmt = $this->connection->prepare($query);
//            $statement = $this->connection->prepare($query);
            $stmt->execute(array(":id" => $this->_user_id));
            $result = $stmt->fetchAll();
        }catch (PDOException $e){
            return $e->getMessage();
        }
        return $result;
    }

    public function checkDiscountIsSavedByUserId($id){
        try {
            $query = "SELECT id FROM discount_saved WHERE discount_id = :id AND user_id = :user_id";
            $stmt = $this->connection->prepare($query);
            $stmt->execute(array(":id" => $id, ":user_id" => $this->_user_id));
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

    public function removeSavedDiscount($id,$userId){
        try{
            $query = "DELETE FROM discount_saved WHERE user_id = :uId AND discount_id = :dId";
            $stmt = $this->connection->prepare($query);

            if($stmt->execute(array(":uId"=>$userId, ":dId"=>$id))){
                return true;
            }else{
                return false;
            }

        }catch (PDOException $e){
            return "Error";
        }
    }

    public function addSavedDiscount($dId,$userId){
        $query = "INSERT INTO discount_saved (discount_id, user_id) VALUES (:dId, :user_id)";
        $stmt = $this->connection->prepare($query);

        if($stmt->execute(array(":dId" => $dId, ":user_id"=>$userId))){
            return true;
        }else{
            return false;
        }
    }

    public function load4SavedDiscounts(){
        $query = "SELECT discount.id,discount.title,discount.description, discount.end_date FROM discount INNER JOIN discount_saved ON discount_saved.discount_id = discount.id WHERE discount_saved.user_id = :uId LIMIT 4";
        try{
            $stmt = $this->connection->prepare($query);
            $stmt->execute(array(":uId" => $this->_user_id));
            $result = $stmt->fetchAll();
            if($stmt->rowCount() == 0){
                return false;
            }else {
                return $result;
            }
        }catch (PDOException $e){
            return $e->getMessage();
        }

    }


}