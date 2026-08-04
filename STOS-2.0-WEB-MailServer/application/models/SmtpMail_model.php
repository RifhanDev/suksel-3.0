<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SmtpMail_model extends CI_Model {

    public $title;
    public $content;
    public $date;

    public function getById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('smtp_mails');
        return $query->row();
    }
}