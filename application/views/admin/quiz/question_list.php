<?php defined('BASEPATH') OR exit('No direct script access allowed');
   if($this->session->flashdata('success')) {
      echo '<div class="alert alert-success alert-dismissible">
      <button type="button" class="close" aria-label="close" data-dismiss="alert">&times;</button>
      <strong>Success!</strong> '.$this->session->flashdata("success").'
      </div>';
   }
?>

<div class="panel panel-default list_quiz_question">
   <div class="card">
      <?php
         $data['tab_quiz_id'] = $quiz_id;
         $this->load->view('admin/quiz/tab_list',$data);
      ?>

   </div>
   
   <input type="hidden" class="quiz_id" name="quiz_id" value="<?php echo xss_clean($quiz_id); ?>">
   
    
    
   <div class="clearfix"></div>

   <hr>
   <table id="table_question" class="display table table-striped table-bordered" cellspacing="0" width="100%">
      <a href="<?php echo base_url("admin/questions/add/$quiz_id");?>" class="btn btn-primary cat add-question float-right"><?php echo lang('admin_add_question');?></a>
      <thead>
         <tr>
            <th><?php echo lang('admin_table_no'); ?></th>
            <th style="width: 40%;"><?php echo lang('dashboard_question');?></th> 
            <th style="width: 40%;"><?php echo lang('question_answer');?></th> 
            <th style="width: 20%;"><?php echo lang('admin_table_action'); ?></th>
         </tr>
      </thead>
      <tbody>
      </tbody>
   </table>
</div>