<?php defined('BASEPATH') OR exit('No direct script access allowed');
class QuestionModel extends CI_Model 
{
    var $table = 'questions';
    var $column_order = array(null, 'quizes.title', 'questions.title', NULL);
    var $column_search = array('questions.title', 'quizes.title');
    var $order = array('questions.id' => 'DESC');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query() {
        $this->db->from($this->table);
        $this->db->join('quizes', 'quizes.id = questions.quiz_id', 'inner');
        $i = 0;
        foreach ($this->column_search as $item) {
            // if datatable send POST for search
            if ($_POST['search']['value']) {

                // first loop
                if ($i === 0) {

                    // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_start();

                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                // last loop
                if (count($this->column_search) - 1 == $i) {
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
        // here order processing
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order) ]);
        }
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function get_question() {
        $this->_get_datatables_query();
        if ($_POST['length'] != - 1) 
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->select('questions.*,quizes.title as quiz_name')
        ->order_by('questions.id', 'asc')
        ->get();
        return $query->result();
    } 

    function get_all_category() {
        return $this->db->where('category_is_delete',0)->where('category_status',1)->order_by('category_title','asc')->get('category')->result();
    }

    function get_all_quiz() {
        return $this->db->where('deleted',0)->order_by('title','asc')->get('quizes')->result();
    }

    function get_all_users() {
        return $this->db->where('deleted','0')->where('status','1')->order_by('username','asc')->get('users')->result();
        
    }

    function insert_question($data) 
    {
        $this->db->insert('questions', $data);
        return $this->db->insert_id();
    }

    function batch_insert_quiz_fields($data) 
    {
     return $this->db->insert_batch('questions', $data);   
 }

 function question_name_like_this($id, $title) 
 {
    $this->db->like('title', $title);
    if ($id) 
    {
        $this->db->where('id !=', $id);
        $this->db->where('id <', $id);
    }
    return $this->db->count_all_results('questions');
}

function get_question_by_id($question_id)
{
    return $this->db->where('id',$question_id)->get('questions')->row();
}

function update_question($question_id, $data) 
{
    $this->db->set($data)->where('id', $question_id)->update('questions');
    return $this->db->affected_rows();
}

function delete_question($question_id) 
{
    $this->db->where('id', $question_id)->delete('questions');
    return $this->db->affected_rows();
}

function update_quiz_images_by_id($question_id, $updated_image_value) {
    $this->db->set('featured_image', $updated_image_value)->where('id', $question_id)->update('quizes');
    return $this->db->affected_rows();
}

}
