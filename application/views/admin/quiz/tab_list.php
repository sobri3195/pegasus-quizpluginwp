<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav"> 
    <ul class="navbar-nav">
    	<?php 

      		if(uri_string() == "admin/quiz/update/".$tab_quiz_id)
      		{
      			$quiz_active = "active";
      		}
      		elseif(uri_string() == "admin/quiz/import/".$tab_quiz_id)
      		{
      			$import_active = "active";	
      		}
      		elseif(uri_string() == "admin/quiz/questions/".$tab_quiz_id || uri_string() == "admin/questions/add/".$tab_quiz_id || $this->uri->segment(3) == "update")
      		{
      			$question_active = "active";	
      		}
      		elseif(uri_string() == "admin/report/".$tab_quiz_id || $this->uri->segment(3) == 'summary')
      		{
      			$report_active = "active";	
      		}
      	?>
      <li class="nav-item <?php echo $quiz_active;?>">
        <a class="nav-link " href="<?php echo base_url('admin/quiz/update/').$tab_quiz_id;?>"><?php echo lang('update_quiz'); ?> </a>
        
      </li>
      <li class="nav-item <?php echo $question_active;?>">
        <a class="nav-link" href="<?php echo base_url('admin/quiz/questions/').$tab_quiz_id;?>"><?php echo lang('questions'); ?></a>
      </li>
      <li class="nav-item <?php echo $import_active;?>">
        <a class="nav-link " href="<?php echo base_url('admin/quiz/import/').$tab_quiz_id;?>"><?php echo lang('admin_import'); ?></a>
      </li>
      <li class="nav-item <?php echo $report_active;?>">
        <a class="nav-link" href="<?php echo base_url('admin/report/').$tab_quiz_id;?>"><?php echo lang('admin_report'); ?></a>
      </li>
    </ul>
  </div>
</nav>