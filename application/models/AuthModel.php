<?php 
defined('BASEPATH') or exit('No direct script access allowed');


class AuthModel extends CI_Model
{

    function getAccount($email)
    {
        return $this->db->get_where('tbl_account', ['email' => $email]);
    }

    private function encryptPassword($password)
    {
        return md5('$npi_' . $password . '_applicant!');
    }

    function register($email, $password)
    {
        return $this->db->insert('tbl_account', ['email' => $email, 'password' => $this->encryptPassword($password)]);
    }

    function login($email, $password)
    {
        return $this->db->get_where('tbl_account', ['email' => $email, 'password' => $this->encryptPassword($password)]);
    }
    
    function updateToken($token, $email)
    {
        return $this->db->update('tbl_account', ['token' => $token], ['email' => $email]);
    }

}

?>