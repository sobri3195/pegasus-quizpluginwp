<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-12 col-md-12 col-lg-12">
   <div class="card">
      <div class="card-body">
         <?php echo form_open_multipart('', array('role'=>'form')); ?>
         <?php foreach ($settings as $setting) : ?>  
         <?php // prepare field settings
            $field_data = array();
            
            if ($setting['is_numeric'])
            {
                $field_data['type'] = "number";
                $field_data['step'] = "any";
            }
            
            if ($setting['options'])
            {
                $field_options = array();
                if ($setting['input_type'] == "dropdown")
                {
                    $field_options[''] = lang('admin_input_select');
                }
                $field_options = json_decode($setting['options']);
                $field_options = json_decode(json_encode($field_options), true);
            }
            
            switch ($setting['input_size'])
            {
                case "small":
                    $col_size = "col-sm-12";
                    break;
                case "medium":
                    $col_size = "col-sm-12";
                    break;
                case "large":
                    $col_size = "col-sm-12";
                    break;
                default:
                    $col_size = "col-sm-12";
            }
            
            if ($setting['input_type'] == 'textarea')
            {
                $col_size = "col-sm-12";
            }
            ?>
         <?php  if ($setting['translate'] && $this->session->languages && $this->session->language) : ?>
         <?php // has translations ?>
         <?php
            $setting['value'] = (@json_decode($setting['value']) !== FALSE) ? json_decode($setting['value']) : $setting['value'];

            if ( ! is_array($setting['value']))
            {
                $old_value = $setting['value'];
                $setting['value'] = array();
                foreach ($this->session->languages as $language_key=>$language_name)
                {
                    $setting['value'][$language_key] = ($language_key == $this->session->language) ? $old_value : "";
                }
            }
            ?>
         <div class="row">
            <div class=" <?php echo xss_clean($col_size); ?>" >
               <div class="form-group <?php echo form_error($setting['name']) ? ' has-error' : ''; ?>">
                  <?php echo form_label(lang($setting['label']), $setting['name'], array('class'=>'control-label')); ?>
                  <?php if (strpos($setting['validation'], 'required') !== FALSE) : ?>
                  <span class="required">*</span>
                  <?php endif; ?>
                  <div role="tabpanel">
                     <ul class="nav nav-tabs" role="tablist">
                        <?php foreach ($this->session->languages as $language_key=>$language_name) : ?>
                        <li role="presentation" class="<?php echo ($language_key == $this->session->language) ? 'active' : ''; ?>"><a href="#<?php echo xss_clean($language_key); ?>" aria-controls="<?php echo xss_clean($language_key); ?>" role="tab" data-toggle="tab"><?php echo xss_clean($language_name); ?></a></li>
                        <?php endforeach; ?>
                     </ul>
                     <div class="tab-content">
                        <?php foreach ($this->session->languages as $language_key=>$language_name) : ?>
                        <div role="tabpanel" class="tab-pane<?php echo ($language_key == $this->session->language) ? ' active' : ''; ?>" id="<?php echo xss_clean($language_key); ?>">
                           <br />
                           <?php
                              $field_data['name']  = $setting['name'] . "[" . $language_key . "]";
                              $field_data['id']    = $setting['name'] . "-" . $language_key;
                              $field_data['class'] = "form-control" . (($setting['show_editor']) ? " editor" : "");
                              $field_data['value'] = (@$setting['value'][$language_key]) ? $setting['value'][$language_key] : "";
                              
                              // render the correct input method
                              if ($setting['input_type'] == 'input')
                              {
                                  echo form_input($field_data);
                              }
                              elseif ($setting['input_type'] == 'file')
                              {
                                  echo form_input($field_data);
                              }
                              elseif ($setting['input_type'] == 'textarea')
                              {
                                  echo form_textarea($field_data);
                              }
                              elseif ($setting['input_type'] == 'radio')
                              {
                                  echo "<br />";
                                  foreach ($field_options as $value=>$label)
                                  {
                                      echo form_radio(array('name'=>$field_data['name'], 'id'=>$field_data['id'] . "-" . $value, 'value'=>$value, 'checked'=>(($value == $field_data['value']) ? 'checked' : FALSE)));
                                      echo xss_clean($label);
                                  }
                              }
                              elseif ($setting['input_type'] == 'dropdown')
                              {
                                  echo form_dropdown($setting['name'], $field_options, $field_data['value'], 'id="' . $field_data['id'] . '" class="' . $field_data['class'] . '"');
                              }
                              elseif ($setting['input_type'] == 'timezones')
                              {
                                  echo "<br />";
                                  echo timezone_menu($field_data['value']);
                              }
                              ?>
                        </div>
                        <?php endforeach; ?>
                     </div>
                  </div>
                  <?php if ($setting['help_text']) : ?>
                  <span class="help-block"><?php echo lang($setting['help_text']); ?></span>
                  <?php endif; ?>
               </div>
            </div>
         </div>
         <?php else : ?>
         <?php // no translations
            $field_data['name']  = $setting['name'];
            $field_data['id']    = $setting['name'];
            $field_data['class'] = "form-control" . (($setting['show_editor']) ? " editor" : "");
            $field_data['value'] = $setting['value'];
            ?>
         <div class="row">
            <div class="<?php echo xss_clean($col_size); ?>">
               <div class="form-group <?php echo form_error($setting['name']) ? ' has-error' : ''; ?>">
                  <?php echo form_label(lang($setting['label']), $setting['name'], array('class'=>'control-label')); ?>
                  <?php if (strpos($setting['validation'], 'required') !== FALSE) : ?>
                  <span class="required">*</span>
                  <?php endif; ?>
                  <?php // render the correct input method quiz, test, online, exams
                     if ($setting['input_type'] == 'input')
                     {
                         echo form_input($field_data);
                     }
                     elseif ($setting['input_type'] == 'file')
                     {
                         echo form_upload($field_data);
                         ?>
                  <img src="<?php echo base_url('/assets/images/logo/').$setting['value']; ?>" class="img-thumbnail popup d-block setting_img_prev" alt="...">
                  <?php
                     }
                     elseif ($setting['input_type'] == 'textarea')
                     {   
                         echo form_textarea($field_data);
                     }
                     elseif ($setting['input_type'] == 'radio')
                     {
                         echo "<br />";
                         foreach ($field_options as $value=>$label)
                         {
                             echo form_radio(array('name'=>$field_data['name'], 'id'=>$field_data['id'] . "-" . $value, 'value'=>$value, 'checked'=>(($value == $field_data['value']) ? 'checked' : FALSE)));
                             echo xss_clean($label);
                         }
                     }
                     elseif ($setting['input_type'] == 'dropdown')
                     {
                         echo form_dropdown($setting['name'], $field_options, $field_data['value'], 'id="' . $field_data['id'] . '" class="' . $field_data['class'] . '"');
                     }
                     elseif ($setting['input_type'] == 'timezones')
                     {
                         echo "<br />";
                         echo timezone_menu($field_data['value']);
                     }
                     ?>
                  <?php if ($setting['help_text']) : ?>
                  <span class="help-block"><?php echo lang($setting['help_text']); ?></span>
                  <?php endif; ?>
               </div>
            </div>
         </div>
         <?php endif; ?>
         <?php endforeach; ?>
         <div class="row text-right">
            <button type="submit" name="submit" class="btn btn-primary  mr-3"><?php echo lang('core_button_save'); ?></button>
            <a class="btn btn-dark" href="<?php echo xss_clean($cancel_url); ?>"><?php echo lang('core_button_cancel'); ?></a>
         </div>
         <div class="row"><br /></div>
         <?php echo form_close(); ?>
      </div>
   </div>
</div>