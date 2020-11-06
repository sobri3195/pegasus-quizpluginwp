<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
  <div class="col-12 col-md-12 col-lg-12">
    <div class="card">
      <?php
        $data['tab_quiz_id'] = $post_quiz_id;
        $this->load->view('admin/quiz/tab_list',$data);
      ?>
    </div>
    <hr>  

      <?php echo form_open_multipart('', array('role'=>'form','novalidate'=>'novalidate')); ?>
      <div class="row">

        <div class="col-5">
          <div class="form-group <?php echo form_error('quiz_id') ? ' has-error' : ''; ?>">
            <?php echo  form_label(lang('quiz_name'), 'quiz_id'); ?>

            <span class="required">*</span>

            <?php 
            $populateData = $this->input->post('quiz_id') ? $this->input->post('quiz_id') : (isset($question_data['quiz_id']) ? $question_data['quiz_id'] :  '' ); 
            $populateData = $post_quiz_id ? $post_quiz_id : $populateData;

            echo form_dropdown('quiz_id', $quiz_name_array, $populateData, 'id="quiz_id" class="form-control select_dropdown"'); 
            ?> 
            <span class="small form-error"> <?php echo strip_tags(form_error('quiz_id')); ?> </span>  

          </div>
        </div>

        <div class="col-4">
          <div class="form-group">
            <?php echo  form_label( lang('upload_excel_file'), 'excel_file'); ?> <span class="required text-danger">*</span>
            <input type="File" name="excel_file" class="form-control excel_file" id="excel_file">
            <span class="small text-danger form-error"> <?php echo strip_tags(form_error('excel_file')); ?> </span>  
          </div>
        </div>

        <?php 
        $populateData = $this->input->post('over_write') == 1 ? 'checked' : (isset($quiz_data['over_write']) && $quiz_data['over_write'] == 1 ? 'checked' :  '' );
        ?>

        <div class="col-3">
          <div class="form-group togle_button">
            <?php echo  form_label(lang('quiz_excel_question_over_write'), 'over_write'); ?>
            <label class="custom-switch form-control">
              <input type="checkbox" name="over_write" value="1" <?php echo xss_clean($populateData); ?> class="custom-switch-input over_write"  data-size="sm">
              <span class="custom-switch-indicator"></span>
            </label>
          </div>
        </div>

        <div class="col-12 my-2">
          <a target="_blank" href="<?php echo base_url('assets/import-demo/demo.xlsx'); ?>"><?php echo lang('downlod_sample_file');?>  </a>
        </div>

        <div class="clearfix"></div>

        <div class="col-12 mt-5 text-center">
          <input type="Submit" class="btn btn-success mr-5" value="<?php echo lang('admin_upload_image');?>">
          <a href="<?php echo base_url('admin') ?>" class="btn btn-dark"><?php echo lang('core_button_cancel'); ?></a>
        </div>
      </div>
      <?php echo form_close();?>
  </div>
</div>