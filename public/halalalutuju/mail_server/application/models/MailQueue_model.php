<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MailQueue_model extends CI_Model {

    public $title;
    public $content;
    public $date;

    public function getById($id)
    {
        $this->db->where('id', $id);
        $this->db->where("deleted_at is null");
        $query = $this->db->get('mail_queues');
        return $query->row();
    }

    public function update($id, $new_mail_queue)
    {        
        $this->db->where('id', $id);
        return $this->db->update('mail_queues', $new_mail_queue);
    }
}