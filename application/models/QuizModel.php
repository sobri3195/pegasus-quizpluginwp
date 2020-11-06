<?php defined('BASEPATH') OR exit('No direct script access allowed');
class QuizModel extends CI_Model 
{

    var $table = 'quizes';
    var $column_order = array(null, 'title', 'category_title', 'number_questions', 'duration_min' , NULL);
    var $column_search = array('title', 'category_title','number_questions','duration_min');
    var $order = array('quizes.id' => 'DESC');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query() {
        $this->db->from($this->table);
        $this->db->join('category', 'quizes.category_id = category.id', 'inner');
        $this->db->join('users', 'users.id = quizes.user_id', 'inner');
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

    function get_quiz() {
        $this->_get_datatables_query();
        if ($_POST['length'] != - 1) 
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->select('quizes.*,category_title')
        ->order_by('quizes.id', 'desc')
        ->get();
        return $query->result();
    }

    function get_all_category() {
        return $this->db->where('category_is_delete',0)->where('category_status',1)->order_by('category_title','asc')->get('category')->result();
    }

    function get_all_users() {
        return $this->db->where('deleted','0')->where('status','1')->order_by('first_name','asc')->get('users')->result();
    }

    function insert_quiz($data) 
    {
        $this->db->insert('quizes', $data);
        return $this->db->insert_id();
    }

    function batch_insert_quiz_fields($data) 
    {
     return $this->db->insert_batch('quizes', $data);
 }

 function quiz_name_like_this($id, $title) 
 {
    $this->db->like('title', $title);
    if ($id) 
    {
        $this->db->where('id !=', $id);
        $this->db->where('id <', $id);
    }
    return $this->db->count_all_results('quizes');
}

function get_quiz_by_id($quiz_id)
{
    return $this->db->where('id',$quiz_id)->get('quizes')->row();
}

function update_quiz($quiz_id, $data) 
{
    $this->db->set($data)->where('id', $quiz_id)->update('quizes');
    return $this->db->affected_rows();
}

function delete_quiz($quiz_id) 
{
    $this->db->where('id', $quiz_id)->delete('quizes');
    $status = $this->db->affected_rows();
    if($status)
    {
       $this->db->where('quiz_id', $quiz_id)->delete('questions');
   }
   return $status; 
}

function update_quiz_images_by_id($quiz_id, $updated_image_value) {
    $this->db->set('featured_image', $updated_image_value)->where('id', $quiz_id)->update('quizes');
    return $this->db->affected_rows();
}

var $table_question = 'questions';
var $column_order_question = array(null, 'questions.correct_choice', 'questions.title', NULL);
var $column_search_question = array('questions.title', 'questions.correct_choice');
var $order_question = array('questions.id' => 'DESC');

private function _get_question_datatables_query() {
    $this->db->from($this->table_question);
    $this->db->join('quizes', 'quizes.id = questions.quiz_id', 'inner');
    $i = 0;
    foreach ($this->column_search_question as $item) {
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
            if (count($this->column_search_question) - 1 == $i) {
                // close bracket
                $this->db->group_end();
            }
        }
        $i++;
    }
    // here order processing
    if (isset($_POST['order'])) {
        $this->db->order_by($this->column_order_question[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->column_order_question)) {
        $order = $this->column_order_question;
        $this->db->order_by(key($order), $order[key($order) ]);
    }
}

function count_filtered_question($quiz_id) {
    $this->_get_question_datatables_query();
    $query = $this->db->where('quiz_id',$quiz_id)->get();
    return $query->num_rows();
}

function count_all_question($quiz_id) {
    $this->db->from($this->table_question)->where('quiz_id', $quiz_id);
    return $this->db->count_all_results();
}

function get_question($quiz_id) {
    $this->_get_question_datatables_query();
    if ($_POST['length'] != - 1) 
        $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->select('questions.*,quizes.title as quiz_name')->where('quiz_id',$quiz_id)
    ->order_by('questions.id', 'asc')
    ->get();
    return $query->result();
}

}