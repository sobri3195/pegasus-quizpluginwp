<?php defined('BASEPATH') OR exit('No direct script access allowed');
class TestModel extends CI_Model 
{
    function get_all_category() {
        return $this->db->where('category_is_delete',0)->where('category_status',1)->order_by('category_title','asc')->get('category')->result();
    }

    function get_quiz_by_id($quiz_id) 
    {
        return $this->db->where('deleted','0')->where('id',$quiz_id)->order_by('id','asc')->get('quizes')->row();
    }

    function get_leader_board_quiz_by_id($quiz_id) 
    {
        return $this->db->where('deleted','0')->where('id',$quiz_id)->where('leader_board',1)->get('quizes')->row();
    }

    function my_quiz_history($user_id, $session_quiz_id, $pro_per_page, $page) 
    {
        return $this->db->select('participants.*,quizes.id as quiz_id, quizes.title as quiz_title, duration_min')
        ->join("quizes", "quizes.id = participants.quiz_id", "LEFT")
        ->where('participants.user_id',$user_id)
        ->where('quizes.id !=',$session_quiz_id)
        ->limit($pro_per_page, $page)
        ->order_by('participants.id','desc')
        ->get('participants')
        ->result();
    }

    function my_quiz_history_count($user_id, $session_quiz_id) 
    {
        $query = $this->db->select('participants.id,quizes.id as quiz_id, quizes.title as quiz_title')
        ->join("quizes", "quizes.id = participants.quiz_id", "LEFT")
        ->where('participants.user_id',$user_id)
        ->where('quizes.id !=',$session_quiz_id)
        ->order_by('participants.id','asc')
        ->get('participants');
        
        return $query->num_rows();    
    }

    function leader_board_quiz_history($quiz_id,$session_quiz_id) 
    {
        return $this->db->select('participants.*,users.id as user_id, first_name, last_name')
        ->join("users", "users.id = participants.user_id", "LEFT")
        
        ->where('participants.quiz_id !=',$session_quiz_id)
        ->where('participants.quiz_id',$quiz_id)
        ->order_by('participants.id','asc')
        ->get('participants')
        ->result();
    }

    function get_question_by_quiz_id($quiz_id,$number_questions) 
    {
        return $this->db->where('deleted','0')->where('quiz_id',$quiz_id)->order_by('id','asc')->limit($number_questions)->get('questions')->result();
    }


    function get_random_question_by_quiz_id($quiz_id,$number_questions)
    {
        return $this->db->where('deleted','0')->where('quiz_id',$quiz_id)->order_by('id','RANDOM')->limit($number_questions)->get('questions')->result();
    }

    function get_question_by_question_id($quiz_id, $question_id) 
    {
        return $this->db->where('deleted','0')->where('quiz_id',$quiz_id)->where('id',$question_id)->order_by('id','asc')->get('questions')->row();
    }

    function insert_participant($data) 
    {
        $this->db->insert('participants', $data);
        return $this->db->insert_id();
    }
    function insert_user_questions($data) 
    {
        $this->db->insert('user_questions', $data);
        return $this->db->insert_id();
    }
    
    function update_participant($quiz_id, $participants_content, $participant_id)
    {
        $user_id = $this->user['id'] ? $this->user['id'] : NULL; 

        $this->db->set($participants_content)
        ->where('user_id', $user_id)
        ->where('quiz_id',$quiz_id)
        ->where('id',$participant_id)
        ->update('participants');
        return $this->db->affected_rows();

    }

    function delete_last_test($quiz_id,$user_id)
    {
        $this->db->where('user_id', $user_id)->where('quiz_id',$quiz_id)->delete('participants');
        return $this->db->affected_rows();

    }

    function check_test_is_done($quiz_id,$user_id)
    {
        return $this->db->where('quiz_id',$quiz_id)->where('user_id',$user_id)->order_by('id','desc')->get('participants')->row();
    }

    function get_all_users() {
        return $this->db->where('deleted','0')->where('status','1')->order_by('username','asc')->get('users')->result();
    }

    function insert_article($data) 
    {
        $this->db->insert('articles', $data);
        return $this->db->insert_id();
    }

    function insert_article_fields($data) 
    {
     return $this->db->insert('article_fields', $data);
 }

 function batch_insert_article_fields($data) 
 {
     return $this->db->insert_batch('article_fields', $data);   
 }

 function update_article_fields($article_id, $field_id, $data)
 {
    $this->db->set($data)->where('id', $field_id)->where('article_id',$article_id)->update('article_fields');
    return $this->db->affected_rows();
}

function article_name_like_this($id, $title) 
{
    $this->db->like('article_title', $title);
    if ($id) 
    {
        $this->db->where('id !=', $id);
        $this->db->where('id <', $id);
    }
    return $this->db->count_all_results('articles');
}

function highlight_title_like_this($title) 
{
    $this->db->like('highlight_title', $title);
    $result = $this->db->count_all_results('articles');

    return  $result > 0 ? '-copy-'. $result: '-copy';
}

function compare_box_title_like_this($title) 
{
    $this->db->like('compare_box_title', $title);
    $result =  $this->db->count_all_results('articles');
    return  $result > 0 ? '-copy-'. $result: '-copy';
}

function compare_list_title_like_this($title) 
{
    $this->db->like('compare_list_title', $title);
    $result =  $this->db->count_all_results('articles');
    return  $result > 0 ? '-copy-'. $result: '-copy';
}

function get_articles() {
    $this->_get_datatables_query();
    if ($_POST['length'] != - 1) 
        $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->select('articles.id,article_title,category_title,first_name,last_name, (SELECT count(id) FROM article_fields WHERE article_id = articles.id) total_fields')
    ->order_by('articles.id', 'asc')
    ->get();
    return $query->result();
}

function get_article_by_id($article_id)
{
    return $this->db->where('id',$article_id)->get('articles')->row();
}

function get_article_field_by_id($article_id)
{
    return $this->db->where('article_id',$article_id)->get('article_fields')->result();
}

function update_article($article_id, $data) 
{
    $this->db->set($data)->where('id', $article_id)->update('articles');
    return $this->db->affected_rows();
}

function delete_fields_by_article_id($article_id) 
{
    $this->db->where('article_id', $article_id)->delete('article_fields');
    return $this->db->affected_rows();
}
function delete_article($article_id) 
{
    $this->db->where('id', $article_id)->delete('articles');
    return $this->db->affected_rows();
}

function update_article_images_by_id($article_id, $updated_image_value) {
    $this->db->set('article_image', $updated_image_value)->where('id', $article_id)->update('articles');
    return $this->db->affected_rows();
}

function check_atrticle_field_exist_or_not($article_id,$field_id)
{
    return $this->db->where('article_id',$article_id)->where('id',$field_id)->get('article_fields')->row();
}

function get_participant_by_id($participant_id) 
{
    return $this->db->where('id',$participant_id)->get('participants')->row_array();
}
function get_user_question_by_participant_id($participant_id)
{
    return $this->db->where('participant_id',$participant_id)->order_by('question_id','asc')->get('user_questions')->result_array();
}
function save_quiz_view_data($data)
{
    $this->db->insert('quiz_count',$data);
    return $this->db->insert_id();
}
}
