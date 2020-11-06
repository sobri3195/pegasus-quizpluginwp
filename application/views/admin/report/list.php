<?php defined('BASEPATH') OR exit('No direct script access allowed');
   if($this->session->flashdata('success')) {
      echo '<div class="alert alert-success alert-dismissible">
      <button type="button" class="close" aria-label="close" data-dismiss="alert">&times;</button>
      <strong>Success!</strong> '.$this->session->flashdata("success").'
      </div>';
   }
?>
<div class="panel panel-default">
   <div class="card">
      <?php
         $data['tab_quiz_id'] = $quiz_id;
         $this->load->view('admin/quiz/tab_list',$data);
      ?>
   </div>
   <input type="hidden" class="quiz_id" name="quiz_id" value="<?php echo xss_clean($quiz_id); ?>">
   <div class="clearfix"></div>
   <hr>
   <table id="table" class="display table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
         <tr>
            <th><?php echo lang('admin_table_no'); ?></th>
            <th><?php echo lang('name'); ?></th>
            <th><?php echo lang('dashboard_question'); ?></th>
            <th><?php echo lang('attended'); ?></th>
            <th><?php echo lang('correct'); ?></th>
            <th><?php echo lang('wrong'); ?></th>
            <th><?php echo lang('quiz_date'); ?></th>
            <th><?php echo lang('admin_table_action'); ?></th>
         </tr>
      </thead>
      <tbody>
      </tbody>
   </table>
</div>