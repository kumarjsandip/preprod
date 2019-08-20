<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }
 $self_dir=admin_url().'?'.$this->id.'_tab_action=get_code'; 
  ?><div class="crm_fields_table">
    <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_name"><?php _e("Account Name",'contact-form-salesforce-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
  <input type="text" name="crm[name]" value="<?php echo !empty($name) ? $name : 'Account #'.$id; ?>" id="vx_name" class="crm_text">

  </div>
  <div class="clear"></div>
  </div>
       <div class="crm_field">
  <div class="crm_field_cell1">
  <label for="vx_env"><?php _e('Environment','contact-form-salesforce-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
<select name="crm[env]" class="crm_text" id="vx_env" data-save="no" <?php if( $api!='web' && !empty($info['access_token'])){ echo 'disabled="disabled"'; } ?> >
  <?php $envs=array(''=>__('Production','contact-form-salesforce-crm'),'test'=>__('Sandbox','contact-form-salesforce-crm'));
foreach($envs as $k=>$v){
    $sel='';
if(!empty($info['env']) && $info['env'] == $k){ $sel='selected="selected"'; }
echo '<option value="'.$k.'" '.$sel.'>'.$v.'</option>';
}
 ?>
 </select>
  </div>
  <div class="clear"></div>
  </div>
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_api"><?php _e("Integration Method",'contact-form-salesforce-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
  <label for="vx_api"><input type="radio" name="crm[api]" value="api" id="vx_api" class="vx_tabs_radio" <?php if($this->post('api',$info) != "web"){echo 'checked="checked"';} ?>> <?php _e('API','contact-form-salesforce-crm'); $this->tooltip('vx_api'); ?></label>
  <label for="vx_web" style="margin-left: 15px;"><input type="radio" name="crm[api]" value="web" id="vx_web" class="vx_tabs_radio" <?php if($this->post('api',$info) == "web"){echo 'checked="checked"';} ?>> <?php _e('Web-to-Lead or Web-to-Case (use this if API is not enabled for your Org) ','contact-form-salesforce-crm'); $this->tooltip('vx_web'); ?></label> 
  </div>
  <div class="clear"></div>
  </div>
  <div class="vx_tabs" id="tab_vx_web" style="<?php if($this->post('api',$info) != "web"){echo 'display:none';} ?>">
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="org_id"><?php _e('Salesforce Org. ID','contact-form-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <div class="vx_tr" >
  <div class="vx_td">
  <input type="password" id="org_id" name="crm[org_id]" class="crm_text" placeholder="<?php _e('Salesforce Organization ID','contact-form-salesforce-crm'); ?>" value="<?php echo esc_html($this->post('org_id',$info)); ?>">
  </div><div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php _e('Toggle Key','contact-form-salesforce-crm'); ?>"><?php _e('Show Key','contact-form-salesforce-crm') ?></a>
  </div></div>
  <span class="howto"><?php _e("in salesforce Go to Setup -> Company information -> Organization ID",'contact-form-salesforce-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>  
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="debug_email"><?php _e('Salesforce Debugging Emails','contact-form-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <input type="text" name="crm[debug_email]" id="debug_email" placeholder="<?php _e('Debugging Email','contact-form-salesforce-crm'); ?>" class="crm_text" value="<?php echo $this->post('debug_email',$info) ?>" />
  
  <span class="howto"><?php _e('Recommended - Salesforce will send notification about success or failure of lead/case to debug email.','contact-form-salesforce-crm'); ?></span> 
  </div>
  <div class="clear"></div>
  </div>   
  </div>
  <div class="vx_tabs" id="tab_vx_api" style="<?php if($this->post('api',$info) == "web"){echo 'display:none';} ?>">
  
  <div class="crm_field">
  <div class="crm_field_cell1"><label><?php _e('Salesforce Access','contact-form-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <?php  if(isset($info['access_token'])  && $info['access_token']!="") {
  ?>
  <div style="padding-bottom: 8px;" class="vx_green"><i class="fa fa-check"></i> <?php
                            $instance_url=str_replace("https://","",$info["instance_url"]);
  echo sprintf(__("Authorized Connection to %s on %s",'contact-form-salesforce-crm'),'<code>'.$instance_url.'</code>',date('F d, Y h:i:s A',$info['sales_token_time']));
        ?></div>
  <?php
  }else{
  $test_link='https://test.salesforce.com/services/oauth2/authorize?response_type=code&state='.urlencode($link."&".$this->id."_tab_action=get_token&id=".$id."&vx_nonce=".$nonce.'&vx_env=test').'&client_id='.$client['client_id'].'&redirect_uri='.urlencode($client['call_back']); 
      
 $link_href=$link='https://login.salesforce.com/services/oauth2/authorize?response_type=code&state='.urlencode($link."&".$this->id."_tab_action=get_token&id=".$id."&vx_nonce=".$nonce.'&vx_env=').'&client_id='.$client['client_id'].'&redirect_uri='.urlencode($client['call_back']); 
 if(!empty($info['env'])){ $link_href=$test_link; }    
  ?>
  <a class="button button-default button-hero sf_login" id="vx_login_btn" data-id="<?php echo esc_html($client['client_id']) ?>" href="<?php echo $link_href ?>" data-login="<?php echo $link ?>" data-test="<?php echo $test_link ?>"> <i class="fa fa-lock"></i> <?php _e("Login with Salesforce",'contact-form-salesforce-crm'); ?></a>
  <?php
  }
  ?></div>
  <div class="clear"></div>
  </div>                  
    <?php if(isset($info['access_token'])  && $info['access_token']!="") {
  ?>
    <div class="crm_field">
  <div class="crm_field_cell1"><label><?php _e("Revoke Access",'contact-form-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">  <a class="button button-secondary" id="vx_revoke" href="<?php echo $link."&".$this->id."_tab_action=get_token&vx_nonce=".$nonce.'&id='.$id?>"><i class="fa fa-unlock"></i> <?php _e("Revoke Access",'contact-form-salesforce-crm'); ?></a>
  </div>
  <div class="clear"></div>
  </div> 
      <div class="crm_field">
  <div class="crm_field_cell1"><label><?php _e("Test Connection",'contact-form-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">      <button type="submit" class="button button-secondary" name="vx_test_connection"><i class="fa fa-refresh"></i> <?php _e("Test Connection",'contact-form-salesforce-crm'); ?></button>
  </div>
  <div class="clear"></div>
  </div> 
  <?php
    }
  ?>
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_error_email"><?php _e("Notify by Email on Errors",'contact-form-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2"><textarea name="crm[error_email]" id="vx_error_email" placeholder="<?php _e("Enter comma separated email addresses",'contact-form-salesforce-crm'); ?>" class="crm_text" style="height: 70px"><?php echo isset($info['error_email']) ? $info['error_email'] : ""; ?></textarea>
  <span class="howto"><?php _e("Enter comma separated email addresses. An email will be sent to these email addresses if an order is not properly added to Salesforce. Leave blank to disable.",'contact-form-salesforce-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>  
   <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_custom_app_check"><?php _e("Salesforce App",'contact-form-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2"><div><input type="checkbox" name="crm[custom_app]" id="vx_custom_app_check" value="yes" <?php if($this->post('custom_app',$info) == "yes"){echo 'checked="checked"';} ?> ><?php echo __("Use Own Salesforce App - If you want to connect one Salesforce account to 5+ sites then use a separate Salesforce App for each 5 sites",'contact-form-salesforce-crm'); $this->tooltip('vx_custom_app'); ?></div>
  </div>
  <div class="clear"></div>
  </div>
  <div id="vx_custom_app_div" style="<?php if($this->post('custom_app',$info) != "yes"){echo 'display:none';} ?>">
     <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_id"><?php _e("Consumer Key",'contact-form-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">
     <div class="vx_tr">
  <div class="vx_td">
  <input type="password" id="app_id" name="crm[app_id]" class="crm_text" placeholder="<?php _e("Salesforce Consumer Key",'contact-form-salesforce-crm'); ?>" value="<?php echo esc_html($this->post('app_id',$info)); ?>">
  </div><div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php _e('Toggle Consumer Key','contact-form-salesforce-crm'); ?>"><?php _e('Show Key','contact-form-salesforce-crm') ?></a>
  
  </div></div>
  
     <ol>
  <li><?php echo __('In Salesforce, go to Setup -> App Manager -> create new "Connected APP"','contact-form-salesforce-crm'); ?></li>
  <li><?php _e('Enter Application Name(eg. My App) then check "Enable OAuth Settings" checkbox','contact-form-salesforce-crm'); ?></li>
  <li><?php echo sprintf(__('Enter %s or %s in Callback URL','contact-form-salesforce-crm'),'<code>https://www.crmperks.com/sf_auth/</code>','<code>'.$self_dir.'</code>'); ?>
  </li>
<li><?php _e('Select OAuth Scopes(Full Access) then Save Application','contact-form-salesforce-crm'); ?></li>
<li><?php _e('Copy Consumer Key and Secret','contact-form-salesforce-crm'); ?></li>
   </ol>
   
</div>
  <div class="clear"></div>
  </div>
     <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_secret"><?php _e("Consumer Secret",'contact-form-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">
       <div class="vx_tr" >
  <div class="vx_td">
 <input type="password" id="app_secret" name="crm[app_secret]" class="crm_text"  placeholder="<?php _e("Salesforce Consumer Secret",'contact-form-salesforce-crm'); ?>" value="<?php echo esc_html($this->post('app_secret',$info)); ?>">
  </div><div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php _e('Toggle Consumer Secret','contact-form-salesforce-crm'); ?>"><?php _e('Show Key','contact-form-salesforce-crm') ?></a>
  
  </div></div>
  </div>
  <div class="clear"></div>
  </div>
       <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_url"><?php _e("Callback URL",'contact-form-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2"><input type="text" id="app_url" name="crm[app_url]" class="crm_text" placeholder="<?php _e("Salesforce Callback URL",'contact-form-salesforce-crm'); ?>" value="<?php echo esc_html($this->post('app_url',$info)); ?>"> 
 
  </div>
  <div class="clear"></div>
  </div>
  </div>
  </div> 
 
  <button type="submit" value="save" class="button-primary" title="<?php _e('Save Changes','contact-form-salesforce-crm'); ?>" name="save"><?php _e('Save Changes','contact-form-salesforce-crm'); ?></button>  
  </div>  