<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="panel panel-default">
   <div class="panel-heading">
      <div class="row">
         <div class="col-md-12 text-left">
            <h3 class="panel-title"></h3>
            <a class="btn btn-primary" href="<?php echo base_url('admin/users/add'); ?>" title="<?php echo lang('add_user') ?>" data-toggle="tooltip"><?php echo lang('add_user'); ?></a>
         </div>
      </div>
   </div>
   <table class="table table-striped table-hover-warning">
      <thead>
         <?php // sortable headers ?>
         <tr>
            <td>
               <a href="<?php echo current_url(); ?>?sort=id&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo xss_clean($limit); ?>&offset=<?php echo xss_clean($offset); ?><?php echo xss_clean($filter); ?>"><?php echo lang('message_id'); ?></a>
               <?php if ($sort == 'id') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
            </td>
            <td>
               <a href="<?php echo current_url(); ?>?sort=username&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo xss_clean($limit); ?>&offset=<?php echo xss_clean($offset); ?><?php echo xss_clean($filter); ?>"><?php echo lang('admin_username'); ?></a>
               <?php if ($sort == 'username') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
            </td>
            <td>
               <a href="<?php echo current_url(); ?>?sort=first_name&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo xss_clean($limit); ?>&offset=<?php echo xss_clean($offset); ?><?php echo xss_clean($filter); ?>"><?php echo lang('admin_first_name'); ?></a>
               <?php if ($sort == 'first_name') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
            </td>
            <td>
               <a href="<?php echo current_url(); ?>?sort=last_name&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo xss_clean($limit); ?>&offset=<?php echo xss_clean($offset); ?><?php echo xss_clean($filter); ?>"><?php echo lang('admin_last_name'); ?></a>
               <?php if ($sort == 'last_name') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
            </td>
            <td>
               <a href="<?php echo current_url(); ?>?sort=status&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo xss_clean($limit); ?>&offset=<?php echo xss_clean($offset); ?><?php echo xss_clean($filter); ?>"><?php echo lang('admin_status'); ?></a>
               <?php if ($sort == 'status') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
            </td>
            <td>
               <a href="<?php echo current_url(); ?>?sort=is_admin&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo xss_clean($limit); ?>&offset=<?php echo xss_clean($offset); ?><?php echo xss_clean($filter); ?>"><?php echo lang('user_admin'); ?></a>
               <?php if ($sort == 'is_admin') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
            </td>
            <td class="pull-right">
               <?php echo lang('admin_table_action'); ?>
            </td>
         </tr>
         <?php // search filters ?>
         <tr>
            <?php echo form_open("{$this_url}?sort={$sort}&dir={$dir}&limit={$limit}&offset=0{$filter}", array('role'=>'form', 'id'=>"filters")); ?>
            <th>
            </th>
            <th <?php echo ((isset($filters['username'])) ? ' class="has-success"' : ''); ?>>
               <?php echo form_input(array('name'=>'username', 'id'=>'username', 'class'=>'form-control input-sm', 'placeholder'=>lang('admin_username'), 'value'=>set_value('username', ((isset($filters['username'])) ? $filters['username'] : '')))); ?>
            </th>
            <th <?php echo ((isset($filters['first_name'])) ? ' class="has-success"' : ''); ?>>
               <?php echo form_input(array('name'=>'first_name', 'id'=>'first_name', 'class'=>'form-control input-sm', 'placeholder'=>lang('admin_first_name'), 'value'=>set_value('first_name', ((isset($filters['first_name'])) ? $filters['first_name'] : '')))); ?>
            </th>
            <th <?php echo ((isset($filters['last_name'])) ? ' class="has-success"' : ''); ?>>
               <?php echo form_input(array('name'=>'last_name', 'id'=>'last_name', 'class'=>'form-control input-sm', 'placeholder'=>lang('admin_last_name'), 'value'=>set_value('last_name', ((isset($filters['last_name'])) ? $filters['last_name'] : '')))); ?>
            </th>
            <th colspan="3">
               <div class="text-right">
                  <a href="<?php echo xss_clean($this_url); ?>" class="btn btn-danger" data-toggle="tooltip" title="<?php echo lang('core_button_reset'); ?>"> <?php echo lang('core_button_reset'); ?></a>
                  <button type="submit" name="submit" value="<?php echo lang('core_button_filter'); ?>" class="btn btn-primary" data-toggle="tooltip" title="<?php echo lang('core_button_filter'); ?>"><?php echo lang('core_button_filter'); ?></button>
               </div>
            </th>
            <?php echo form_close(); ?>
         </tr>
      </thead>
      <tbody>
         <?php // data rows ?>
         <?php if ($total) : ?>
            <?php foreach ($users as $user) : ?>
               <tr>
                  <td <?php echo (($sort == 'id') ? ' class="sorted"' : ''); ?>>
                     <?php echo xss_clean($user['id']); ?>
                  </td>
                  <td <?php echo (($sort == 'username') ? ' class="sorted"' : ''); ?>>
                     <?php echo xss_clean($user['username']); ?>
                  </td>
                  <td <?php echo (($sort == 'first_name') ? ' class="sorted"' : ''); ?>>
                     <?php echo xss_clean($user['first_name']); ?>
                  </td>
                  <td <?php echo (($sort == 'last_name') ? ' class="sorted"' : ''); ?>>
                     <?php echo xss_clean($user['last_name']); ?>
                  </td>
                  <td <?php echo (($sort == 'status') ? ' class="sorted"' : ''); ?>>
                     <?php echo ($user['status']) ? '<span class="active">' . lang('admin_active') . '</span>' : '<span class="inactive">' . lang('admin_inactive') . '</span>'; ?>
                  </td>
                  <td <?php echo (($sort == 'is_admin') ? ' class="sorted"' : ''); ?>>
                     <?php echo ($user['is_admin']) ? lang('core_text_yes') : lang('core_text_no'); ?>
                  </td>
                  <td>
                     <div class="text-right">
                        <div class="btn-group">
                           <?php if ($user['id'] > 1) : ?>
                           <a href="#modal-<?php echo xss_clean($user['id']); ?>" data-toggle="modal" class="btn btn-danger btn-action" data-toggle="tooltip" title="<?php echo lang('admin_delete_record'); ?>"><i class="fas fa-trash"></i></a>
                           <?php endif; ?>
                           <a href="<?php echo xss_clean($this_url); ?>/edit/<?php echo xss_clean($user['id']); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="<?php echo lang('admin_edit_record'); ?>"><i class="fas fa-pencil-alt"></i></a>
                        </div>
                     </div>
                  </td>
               </tr>
            <?php endforeach; ?>
         <?php else : ?>
            <tr>
               <td colspan="7">
                  <?php echo lang('core_error_no_results'); ?>
               </td>
            </tr>
         <?php endif; ?>
      </tbody>
   </table>
   <?php // list tools ?>
   <div class="panel-footer">
      <div class="row">
         <div class="col-md-2 text-left">
            <label><?php echo sprintf(lang('admin label rows'), $total); ?></label>
         </div>
         <div class="col-md-2 text-left">
            <?php if ($total > 10) : ?>
            <select id="limit" class="form-control">
               <option value="10"<?php echo ($limit == 10 OR ($limit != 10 && $limit != 25 && $limit != 50 && $limit != 75 && $limit != 100)) ? ' selected' : ''; ?>>10 <?php echo lang('admin input items_per_page'); ?></option>
               <option value="25"<?php echo ($limit == 25) ? ' selected' : ''; ?>>25 <?php echo lang('admin_input_items_per_page'); ?></option>
               <option value="50"<?php echo ($limit == 50) ? ' selected' : ''; ?>>50 <?php echo lang('admin_input_items_per_page'); ?></option>
               <option value="75"<?php echo ($limit == 75) ? ' selected' : ''; ?>>75 <?php echo lang('admin_input_items_per_page'); ?></option>
               <option value="100"<?php echo ($limit == 100) ? ' selected' : ''; ?>>100 <?php echo lang('admin_input_items_per_page'); ?></option>
            </select>
            <?php endif; ?>
         </div>
         <div class="col-md-6">
            <?php echo xss_clean($pagination); ?>
         </div>
         <div class="col-md-2 text-right">
            <?php if ($total) : ?>
            <a href="<?php echo xss_clean($this_url); ?>/export?sort=<?php echo xss_clean($sort); ?>&dir=<?php echo xss_clean($dir); ?><?php echo xss_clean($filter); ?>" class="btn btn-primary" data-toggle="tooltip" title="<?php echo lang('admin_csv_export'); ?>"><span class="glyphicon glyphicon-export"></span> <?php echo lang('admin_csv_export'); ?></a>
            <?php endif; ?>
         </div>
      </div>
   </div>
</div>
</div>
<?php // delete modal ?>
<?php if ($total) : ?>
   <?php foreach ($users as $user) : ?>
      <div class="modal fade" id="modal-<?php echo xss_clean($user['id']); ?>" tabindex="-1" role="dialog" aria-labelledby="modal-label-<?php echo xss_clean($user['id']); ?>" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 id="modal-label-<?php echo xss_clean($user['id']); ?>"><?php echo lang('admin_user_delete');  ?></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               </div>
               <div class="modal-body">
                  <p><?php echo sprintf(lang('admin_user_delete_confirm'), $user['first_name'] . " " . $user['last_name']); ?></p>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('core_button_cancel'); ?></button>
                  <button type="button" class="btn btn-primary btn-delete-user" data-id="<?php echo xss_clean($user['id']); ?>"><?php echo lang('admin_button_delete'); ?></button>
               </div>
            </div>
         </div>
      </div>
   <?php endforeach; ?>
<?php endif; ?>