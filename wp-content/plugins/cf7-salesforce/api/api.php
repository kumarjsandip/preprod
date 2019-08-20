<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if(!class_exists('vxcf_sales_api')){
    
class vxcf_sales_api extends vxcf_sales{
  
  public $info='' ; // info
  public $error= "";
  public $timeout=30;
  public $api_version='v37.0';
  public $api_res='';
  
  function __construct($info) { 
        if(isset($info['data'])){
  $this->info= $info['data'];
      }
if(!empty(self::$api_timeout)){
    $this->timeout=self::$api_timeout;
}

  }
  
  /**
  * Get New Access Token from salesforce
  * @param  array $form_id Form Id
  * @param  array $info (optional) Salesforce Credentials of a form
  * @param  array $posted_form (optional) Form submitted by the user,In case of API error this form will be sent to email
  * @return array  Salesforce API Access Informations
  */
public function get_token($info=""){
  if(!is_array($info)){
  $info=$this->info;
  }
  if(!isset($info['refresh_token']) || empty($info['refresh_token'])){
   return $info;   
  }
  $client=$this->client_info(); 
  ////////it is oauth    
  $body=array("client_id"=>$client['client_id'],"client_secret"=>$client['client_secret'],"redirect_uri"=>$client['call_back'],"grant_type"=>"refresh_token","refresh_token"=>$info['refresh_token']);
     $env='login';
      if( !empty($info['env'])){
       $env='test';  
      }
  $res=$this->post_sales('token',"https://$env.salesforce.com/services/oauth2/token","post",$body);

  $re=json_decode($res,true); 
  if(isset($re['access_token']) && $re['access_token'] !=""){ 
  $info["access_token"]=$re['access_token'];
  $info["instance_url"]=$re['instance_url'];
  $info["issued_at"]=$re['issued_at'];
//  $info["org_id"]=$re['id'];
  $info["class"]='updated';
  $token=$info;
  }else{
  $info['error']=$re['error_description'];
  $info['access_token']="";
   $info["class"]='error';
  $token=array(array('errorCode'=>'406','message'=>$re['error_description']));

  }
  $info["valid_api"]=current_time('timestamp')+86400; //api validity check
  //update salesforce info 
  //got new token , so update it in db
  $this->update_info( array("data"=> $info),$info['id']); 
  return $info; 
  }
public function handle_code(){
      $info=$this->info;
      $id=$info['id'];

        $client=$this->client_info();
  $log_str=$res=""; $token=array();
  if(isset($_REQUEST['code'])){
  $code=$this->post('code');   
  if(!empty($code)){
      $env='login';
      if(!empty($_REQUEST['vx_env']) || !empty($info['env'])){
       $env='test'; $info['env']='test';  
      }
  $body=array("client_id"=>$client['client_id'],"client_secret"=>$client['client_secret'],"redirect_uri"=>$client['call_back'],"grant_type"=>"authorization_code","code"=>$code);
  $res=$this->post_sales("token","https://$env.salesforce.com/services/oauth2/token","post",$body);
  
  $log_str="Getting access token from code";
   $token=json_decode($res,true); 
   if(!isset($token['access_token'])){
      $log_str.=" =".$res; 
   }
  }
  if(isset($_REQUEST['error'])){
   $token['error_description']=$this->post('error_description');   
  }
  }else{  
  //revoke token on user request

  if(isset($info['instance_url']) && $info['instance_url']!="")
  $res=$this->request($info['instance_url']."/services/oauth2/revoke?token=".$info['refresh_token'],"get","");  
  $log_str="Access token Revoked on Request";
  }
 
  $info['instance_url']=$this->post('instance_url',$token);
  $info['access_token']=$this->post('access_token',$token);
  $info['client_id']=$client['client_id'];
  $info['_id']=$this->post('id',$token);
  $info['refresh_token']=$this->post('refresh_token',$token);
  $info['issued_at']=time();
  $info['signature']=$this->post('signature',$token);
  $info['sales_token_time']=current_time('timestamp');
  $info['error']=$this->post('error_description',$token);
  $info['api']="api";
  $info["class"]='error';
  if(!empty($info['access_token'])){
  $info["class"]='updated';
  }
  $this->info=$info;
 // $info=$this->validate_api($info);
  $this->update_info( array('data'=> $info) , $id); 
  return $info;
}
  /**
  * Posts data to salesforce, Get New access token on expiration message from salesforce
  * @param  string $path salesforce path 
  * @param  string $method CURL method 
  * @param  array $body (optional) if you want to post data
  * @return array Salesforce Response array
  */
  public  function post_sales_arr($path,$method,$body=""){
  $info=$this->info;    
  $get_token=false; 
if(!isset($info['instance_url']) || empty($info['instance_url'])){
    return array(array( 'errorCode'=>'2005' , 'message'=>__('No Access to Salesforce API - 2005','gravity-forms-salesforce-crm')));
}
  $url=$info['instance_url'];
  $dev_key=$info['access_token'];
  $head=array(); 
  if(!empty($body) && is_array($body)){ 
  if(isset($body['OwnerId'])){
  $head['Sforce-Auto-Assign']='false';    
  }
  $body=json_encode($body);

  }
  $sales_res=$this->post_sales($dev_key,$url.$path,$method,$body,$head); 

  $sales_response=json_decode($sales_res,true); 
  if(isset($sales_response[0]['errorCode']) && $sales_response[0]['errorCode'] == "INVALID_SESSION_ID"){ 
  $get_token=true;         
  }

  if($get_token){ 
  ////////////try to get new token
  $token=$this->get_token();     
  if(isset($token['access_token'])&& $token['access_token']!=""){
  $dev_key=$token['access_token'];     
  $url=$token['instance_url'];
  $sales_res=$this->post_sales($dev_key,$url.$path,$method,$body,$head);

  $sales_response=json_decode($sales_res,true); 
  }
  }
  
  $this->api_res=$sales_res; 
  return $sales_response;   
  }
  /**
  * Posts data to salesforce
  * @param  string $dev_key Slesforce Access Token 
  * @param  string $path Salesforce Path 
  * @param  string $method CURL method 
  * @param  string $body (optional) if you want to post data 
  * @return string Salesforce Response JSON
  */
  public function post_sales($dev_key,$path,$method,$body="",$head=''){
  
  if($dev_key == 'token'){
  $header=array('content-type'=>'application/x-www-form-urlencoded');   
  }else{
  $header=array("Authorization"=>' Bearer ' . $dev_key,'content-type'=>'application/json');     
  if(!empty($head) && is_array($head)){ $header=array_merge($header,$head);  }
  }
  if(is_array($body)&& count($body)>0)
  { $body=http_build_query($body);
  }
  if($method != "get"){
  $header['content-length']= strlen($body);
  }   
  $response = wp_remote_post( $path, array(
  'method' => strtoupper($method),
  'timeout' => $this->timeout,
  'headers' => $header,
  'body' => $body
  )
  );

  return !is_wp_error($response) && isset($response['body']) ? $response['body'] : "";
  }
  /**
  * Get Salesforce Client Information
  * @param  array $info (optional) Salesforce Client Information Saved in Database
  * @return array Salesforce Client Information
  */
  public function client_info(){
      $info=$this->info;
  $client_id= "3MVG9A2kN3Bn17hv8jZKWJ31Px1IqJczU2PfHT4_qS9Fr61h7m5R4PhRELnDAWu.aa_rbBirpGMRR56AFa4kg";
  $client_secret="7441227697513084813";
  $call_back="https://www.crmperks.com/sf_auth/";
  //custom app
  if(is_array($info)){
      if($this->post('custom_app',$info) == "yes" && $this->post('app_id',$info) !="" && $this->post('app_secret',$info) !="" && $this->post('app_url',$info) !=""){
     $client_id=$this->post('app_id',$info);     
     $client_secret=$this->post('app_secret',$info);     
     $call_back=$this->post('app_url',$info);     
      }
  }
  return array("client_id"=>$client_id,"client_secret"=>$client_secret,"call_back"=>$call_back);
  }
  
  /**
  * Get fields from salesforce
  * @param  string $form_id Form Id
  * @param  array $form (optional) Form Settings 
  * @param  array $request (optional) custom array or $_REQUEST 
  * @return array Salesforce fields
  */
  public function get_crm_fields($object,$is_options=false){ 

$sales_response=$this->post_sales_arr('/services/data/'.$this->api_version.'/sobjects/'.ucfirst($object)."/describe","get",""); 

  ///seprating fields
  if(isset($sales_response['fields']) && is_array($sales_response['fields'])){
  $field_info=array();
  foreach($sales_response['fields'] as $k=>$field){ 
  
        if( (isset($field['createable']) && $field['createable'] ==true) || $field['name'] == 'Id' ){
        
          $required=""; 
  if( !empty($field['nameField']) || (!empty($field['createable']) && empty($field['nillable']) && empty($field['defaultedOnCreate']))  ){
  $required="true";   
  } 
  $type=$field['type'];
  if($type == 'reference' && !empty($field['referenceTo']) && is_array($field['referenceTo'])){
   $type=reset($field['referenceTo']);   
  }
  $field_arr=array('name'=>$field['name'],"type"=>$type);
  $field_arr['label']=$field['label']; 
  $field_arr['req']=$required;
  $field_arr["maxlength"]=$field['length'];
  $field_arr["custom"]=$field['custom'];    
            
         if(isset($field['picklistValues']) && is_array($field['picklistValues']) && count($field['picklistValues'])>0){
         $field_arr['options']=$field['picklistValues'];
          }
      if($is_options ){
          if(!empty($field_arr['options'])){
       $field_info[$field['name']]=$field_arr;
          } 
      }else{
  
  $field_info[$field['name']]=$field_arr;  
  } }
      
  } 
  if(isset($field_info['Id'])){
     $id=$field_info['Id'];
     unset($field_info['Id']);
   $field_info['Id']=$id;   
  }
  $field_info['vx_list_files']=array('name'=>'vx_list_files',"type"=>'files','label'=>'Files - Related List','custom'=>true);
  return $field_info;
  }
  $msg=__("No Fields Found",'gravity-forms-salesforce-crm');
  if(isset($sales_response[0]['errorCode'])){
  $msg=$sales_response[0]['message'];    
  }
  if(isset($sales_response['error'])){
  $msg=$sales_response['error'];    
  }

  return $msg;
  }
    
  /**
  * Get campaigns from salesforce
  * @return array Salesforce campaigns
  */
  public function get_campaigns(){ 

$q="SELECT Name,Id FROM Campaign";
  $query='/services/data/'.$this->api_version.'/query?q='.urlencode($q);
  $sales_response=$this->post_sales_arr($query,"get");
  $field_info=__('No Campaigns Found','gravity-forms-salesforce-crm');
  if(!empty($sales_response['records'])){
  $field_info=array();
  foreach($sales_response['records'] as $k=>$field){
  $field_info[$field['Id']]=$field['Name'];     
  }
  }
    if(isset($sales_response[0]['errorCode'])){
   $field_info=$sales_response[0]['message'];   
  }
  return $field_info;
}
  /**
  * Get users from salesforce
  * @return array Salesforce users
  */
  public function get_users(){ 
       $q='SELECT email , name , id from User';
  $sales_response=$this->post_sales_arr("/services/data/v27.0/query?q=".urlencode($q) ,"get","");
  ///seprating fields
  $field_info=__('No Users Found','gravity-forms-salesforce-crm');
  if(isset($sales_response['records']) && is_array($sales_response['records'])){
  $field_info=array();
  foreach($sales_response['records'] as $k=>$field){
  $field_info[$field['Id']]=$field['Name'].' ( '.$field['Email'].' )';     
  }
  $q="SELECT Id,Name FROM GROUP WHERE TYPE='Queue'";
$query='/services/data/'.$this->api_version.'/query?q='.urlencode($q);
$sales_response=$this->post_sales_arr($query,"get");
  if(isset($sales_response['records']) && is_array($sales_response['records'])){
  foreach($sales_response['records'] as $k=>$field){
  $field_info[$field['Id']]=$field['Name'].' (Queue)';     
  }
  }
  }
    if(isset($sales_response[0]['errorCode'])){
   $field_info=$sales_response[0]['message'];   
  } 
  return $field_info;
}
  /**
  * Get users from salesforce
  * @return array Salesforce users
  */
  public function get_price_books(){ 
$q= "SELECT Id,Name,Description from Pricebook2 Limit 100";
  $sales_response=$this->post_sales_arr('/services/data/'.$this->api_version.'/query?q='.urlencode($q) ,"get","");
  ///seprating fields
  $field_info=__('No Price Book Found','gravity-forms-salesforce-crm');
  if(isset($sales_response['records']) && is_array($sales_response['records'])){
  $field_info=array();
  foreach($sales_response['records'] as $k=>$field){
  $field_info[$field['Id']]=$field['Name'];     
  }
  }
    if(isset($sales_response[0]['errorCode'])){
   $field_info=$sales_response[0]['message'];   
  } 
  return $field_info;
}
/**
* campaign member status list
* 
*/
  public function get_member_status(){ 
  $sales_response=$this->post_sales_arr("/services/data/v27.0/sobjects/CampaignMember/describe","get","");
  $field_info=__('Status List Not Found','gravity-forms-salesforce-crm');
  if(isset($sales_response['fields']) && is_array($sales_response['fields'])){
  $field_info=array();
  foreach($sales_response['fields'] as $field){
      if(isset($field['name']) && $field['name'] == "Status" && isset($field['picklistValues']) && is_array($field['picklistValues'])){
       foreach($field['picklistValues'] as $k=>$v){
       if(isset($v['value'])){ 
         $field_info[$v['value']]=$v['label'];  
       }     
       }
       break;  
      }  
  }
  }
    if(isset($sales_response['errorCode'])){
   $field_info=$sales_response['message'];   
  }
  return $field_info;
}
  /**
  * Get Objects from salesforce
  * @return array
  */
  public function get_crm_objects(){

  $sales_res=$this->post_sales_arr('/services/data/'.$this->api_version.'/sobjects/',"get","");

  $fields=array();
  if(isset($sales_res['sobjects'])){
  foreach($sales_res['sobjects'] as $object){
  if($object['createable'] == true && $object['layoutable'] == true){
  $fields[$object['name']]=$object['label'];  
  }    
  }
  return $fields;
  }
  $msg="No Objects Found";
  if(isset($sales_res[0]['errorCode'])){
  $msg=$sales_res[0]['message'];    
  }
  return $msg;
  }
  /**
  * Send data to Salesforce using wp_remote_post()
  *
  * @filter gf_salesforce_salesforce_debug_email Disable debug emails (even if you have debugging enabled) by returning false.
  * @filter gf_salesforce_salesforce_debug_email_address Modify the email address Salesforce sends debug information to
  * @param  array  $post  Data to send to Salesforce
  * @param  boolean $test Is this just testing the OID configuration and not actually sendinghelpful data?
  * @return array|false         If the Salesforce server returns a non-standard code, an empty array is returned. If there is an error, `false` is returned. Otherwise, the `wp_remote_request` results array is returned.
  */
public function post_web($post,$info,$object='Lead', $test = false) {
  global $wp_version;
  // Web-to-Lead uses `oid` and Web to Case uses `orgid`
  switch($object) {
  case 'Case':
  $post['orgid'] = $this->post('org_id',$info);
  break;
  case 'Lead':
  $post['oid'] =$this->post('org_id',$info);
  break;
  }

  // We need an Org ID to post to Salesforce successfully.
  if(empty($post['oid']) && empty($post['orgid'])) {
  
  return NULL;
  }
$header=array(
  'user-agent' => 'Woocommerce Salesforce Plugin plugin - WordPress/'.$wp_version.'; '.get_bloginfo('url')
  );
//in web2lead first name and lasty name and email should be unique  
// $header['Content-Type']='application/x-www-form-urlencoded'; 
//var_dump($post); die('--------');

if(!empty($post) && is_array($post)){
$files=$body=array();
foreach($post as $k=>$v){
    if(is_array($v)){
        foreach($v as $vv){
     $body[]=urlencode($k).'='.urlencode($vv);       
        }
    }else{
  $body[]=urlencode($k).'='.urlencode($v);       
    }
}

$post=implode('&',$body);
}

//$post=http_build_query($post);
  // Set SSL verify to false because of server issues.
  $args = array(
  'body'      => $post,
  'headers'   => $header,
  'timeout' => $this->timeout,
 // 'sslverify' => false
  );
  
  // Use test/www subdomain based on whether this is a test or live
  $sub =$test ? 'test' : 'webto' ;
  
  // Use (test|www) subdomain and WebTo(Lead|Case) based on setting
  $url =  sprintf('https://%s.salesforce.com/servlet/servlet.WebTo%s?encoding=UTF-8', $sub, $object);
 
  // POST the data to Salesforce
  $result = wp_remote_post($url, $args);

///var_dump($result,$url,$post); die();
  // There was an error
  if(is_wp_error( $result )) {
 // return NULL;
  }
  $done=array('entry created'=>'TRUE');
  // Find out what the response code is
  $code = wp_remote_retrieve_response_code( $result );
  // Salesforce should ALWAYS return 200, even if there's an error.
  // Otherwise, their server may be down.
  if( intval( $code ) !== 200) {
  return NULL;
  }
  // If `is-processed` isn't set, then there's no error.
  elseif(!isset($result['headers']['is-processed'])) {
  return $done;
  }
  // If `is-processed` is "true", then there's no error.
  else if ($result['headers']['is-processed'] === "true") {
  return $done;
  }
  // But if there's the word "Exception", there's an error.
  /*  else if(strpos($result['headers']['is-processed'], 'Exception')) {
  return NULL;
  }*/
  return NULL;
  }
     /**
  * Posts object to salesforce, Creates/Updates Object or add to object feed
  * @param  array $entry_id Needed to update salesforce response
  * @return array Salesforce Response and Object URL
  */
public function push_object($object,$temp_fields,$meta){  
    
  $fields_info=array(); $fields=array(); $extra=array();
  $id=""; $error=""; $action=""; $link=""; $search=$search_response=$status=""; 
  $files=array();
  $debug = isset($_REQUEST['vx_debug']) && current_user_can('manage_options');
  if(is_array($temp_fields)){
  foreach($temp_fields as $k=>$v){
  if($k == 'Id'){
      $id=$v['value']; unset($meta['primary_key']);
  }else{
      $fields[$k]=$v['value'];
  }   
  } } 

    $event=$this->post('event',$meta);
  if(isset($this->info['api']) && $this->info['api'] == "web"){ 
 
  if($this->post('debug_email',$this->info) !=""){
   $fields['debug']="1";   
   $fields['debugEmail']=$this->post('debug_email',$this->info);   
  } 
    //associate lead and campaign
  if($this->post('add_to_camp',$meta) == "1" && in_array($object,array("Lead"))){ 
    $fields['Campaign_ID']=$this->post('web_camp_id',$meta); 
    $fields['member_status']=$this->post('web_mem_status',$meta); 
  } 

  $is_sandbox= !empty($this->info['env']) ? true : false;
  $sales_response=$this->post_web($fields,$this->info,$object,$is_sandbox); 
    
  $status="3"; $action="Added";
  if(empty($sales_response)){ $status=""; $error=sprintf(__('Error While Posting to Salesforce %s'),' (Web2Lead)'); }

  }
  else{
  $fields_info=isset($meta['fields']) && is_array($meta['fields']) ? $meta['fields'] : array();
  if($event!='add_note'){
  $fields=$this->clean_sf_fields($fields,$fields_info);  
  //remove related list fields
  if(isset($fields['vx_list_files'])){
    $files=$fields['vx_list_files'];
    if(!is_array($files)){
        $files_temp=json_decode($files,true);
     if(is_array($files_temp)){
    $files=$files_temp;     
     }else if (!empty($files) && filter_var($files,FILTER_VALIDATE_URL)){
      $files=array($files);   
     }   
    }
    unset($fields['vx_list_files']);  
  }
}
  if($debug){ ob_start();}
  //check primary key
  $search=array(); $search2=array();
  if( !empty($meta['primary_key']) ){    
  $search=$this->get_search_val($meta['primary_key'],$fields,$fields_info);
  
  if( !empty($meta['primary_key2']) ){
  $search2=$this->get_search_val($meta['primary_key2'],$fields,$fields_info);
  }
  if(!empty($search) || !empty($search2)){
    //  $search=array('FirstName'=>esc_sql("+~'john@"));
    // $search=array('Phone'=>esc_sql("(810) 476-3056"));
    
  //if primary key option is not empty and primary key field value is not empty , then check search object
  $search_response=$sales_response=$this->search_in_sf($object,$search,$search2); 
 
  if($debug){
  ?>
  <pre>
  <h3>Search field</h3>
  <p><?php print_r($search) ?></p>
  <h3>Search term</h3>
  <p><?php print_r($search2) ?></p>
  <h3>Search response</h3>
  <p><?php print_r($sales_response) ?></p>
  </pre>    
  <?php
  }
      if(is_array($search_response) && count($search_response)>10){
       $search_response=array_slice($search_response,count($search_response)-10,10);   
      }
      $extra["Search"]=$search;
      if(!empty($search2)){
      $extra["Search2"]=$search2;
      }
      $extra["response"]=!empty($search_response) ? $search_response : $this->api_res;
  
  if(isset($sales_response[0]['Id'])&& $sales_response[0]['Id']!=""){
  //object found, update old object or add to feed
  $id=$sales_response[count($sales_response)-1]['Id'];
  }
  
  if(isset($sales_response[0]['errorCode'])){
  $error=$sales_response[0]['message'];
  }
  }
  $sales_response='';
  }

  if(!empty($meta['crm_id'])){
   $id=$meta['crm_id'];   
  } 
     if(in_array($event,array('delete_note','add_note'))){    
  if(isset($meta['related_object'])){
    $extra['Note Object']= $meta['related_object'];
  }
  if(isset($meta['note_object_link'])){
    $extra['note_object_link']=$meta['note_object_link'];
  }
}

 $entry_exists=$sent=false;

  $post_data=json_encode($fields);
  //if($error ==""){
  if($id == ""){
  $action="Added";
if(empty($meta['new_entry'])){
    $sent=true;
if( isset($this->id) && $this->id == 'vxc_sales' &&  $object == "Order"){
   $order_res=$this->add_order($fields,$meta);   
  $sales_response=$order_res['res'];
  if(is_array($order_res['extra'])){
  $extra=array_merge($extra, $order_res['extra']);
  }   
}else{  
  //create new lead
$sales_response=$this->post_sales_arr('/services/data/'.$this->api_version.'/sobjects/'.$object,"post",$fields);
  }
  if(isset($sales_response['id'])){
  $id=$sales_response['id'];
  $status="1";
  } }else{
      $error='Entry does not exist';
  }
}else{ 
$entry_exists=true;
  if($event == 'add_note'){     
  $sales_response=$this->post_note($fields,$meta);

    if(isset($sales_response['id'])){
  $id=$sales_response['id'];
  $status="1";
    }  
  }
  else if(in_array($event,array('delete','delete_note'))){
     
  $action="Deleted";
  $sales_response=$this->post_sales_arr('/services/data/'.$this->api_version.'/sobjects/'.$object."/".$id,"DELETE");
    if(empty($sales_response)){ $status="5"; } 
  }
  else{    
      
  $action="Updated";
  //update old object
   if(empty($meta['update'])){
  $sales_response=$this->post_sales_arr('/services/data/'.$this->api_version.'/sobjects/'.$object."/".$id,"PATCH",$fields);
   if(empty($sales_response)){ $status="2";  $sent=true; } 
 }else{
   $status="2";  
 }
  }
  }
if($sent && !empty($id)){
    if(is_array($files) ){
        foreach($files as $file){
         $file_name=substr($file,strrpos($file,'/')+1);   
    $post=array('Title'=>$file_name); 
  $c=file_get_contents($file);
  $post['VersionData']=base64_encode($c);
  $post['PathOnClient']=$file_name;
  $extra['Uploading File']=$file;
  $post=json_encode($post);
  $file_res=$this->post_sales_arr('/services/data/'.$this->api_version.'/sobjects/ContentVersion','post',$post);
  $extra['Uploaded File']=$file_res;
  if(!empty($file_res['id'])){ 
    $file_res=$this->post_sales_arr('/services/data/'.$this->api_version.'/sobjects/ContentVersion/'.$file_res['id'],'get','');
    if(!empty($file_res['ContentDocumentId'])){
       $post=array('ContentDocumentId'=>$file_res['ContentDocumentId'],'LinkedEntityId'=>$id,'ShareType'=>'V','Visibility'=>'AllUsers');  
       $post=json_encode($post);
    $link_res=$this->post_sales_arr('/services/data/'.$this->api_version.'/sobjects/ContentDocumentLink','post',$post);
    $extra['Linked File']=$link_res;
    }  
  }
        }
    }
}
  //associate lead and campaign
  if($this->post('add_to_camp',$meta) == "1" && $id !="" && in_array($object,array("Lead","Contact"))){
   $camp_id=$this->post('campaign',$meta);   
    if($this->post('camp_type',$meta) != ""){
    $camp_id=$this->post('campaign_id',$meta);    
    }
  $camp_post=array($object."Id"=>$id,"CampaignId"=>$camp_id,"Status"=>$this->post('member_status',$meta));  
  $extra['camp_post']=$camp_post;
  $camp_post=json_encode($camp_post);
$camp_res=$this->post_sales_arr('/services/data/'.$this->api_version.'/sobjects/CampaignMember',"post",$camp_post); 
  if($debug){
  ?>
  <pre>
  <h3>Post to Campaign</h3>
  <p><?php print_r($camp_post) ?></p>
  <h3>Campaign response</h3>
  <p><?php print_r($camp_res) ?></p>
  </pre>    
  <?php
  }
$extra['camp_post']=$camp_post; 
$extra['camp_res']=$camp_res; 
  }
  }
  if($id !="")
  {
  $link=$this->info['instance_url']."/".$id;
  }
  if(isset($sales_response[0]['errorCode'])){
  $error=$sales_response[0]['message'];
  $sales_response=$sales_response[0]; $id='';
  }
  if($debug){
  ?>
  <pre>
  <h3>Salesforce Information</h3>
  <p><?php print_r($this->info) ?></p>
  <h3>Data Sent</h3>
  <p><?php echo json_encode($fields) ?></p>
  <h3>Salesforce response</h3>
  <p><?php print_r($sales_response) ?></p>
  <h3>Object</h3>
  <p><?php print_r($object."--------".$action) ?></p>
  </pre>    
  <?php
  $contents=trim(ob_get_clean());
  if($contents!=""){
  update_option($this->id."_debug",$contents);   
  }
  }
  
         //add entry note
 if(!empty($status) && !empty($meta['__vx_entry_note']) && !empty($id)){
 $disable_note=$this->post('disable_entry_note',$meta); 
   if(!($entry_exists && !empty($disable_note))){  
       $entry_note=$meta['__vx_entry_note'];
 $note_temp=array('Title'=>$entry_note['Title'],'Body'=>$entry_note['Body'],'ParentId'=>$id); 
  $note_response=$this->post_note($note_temp,$meta);

  $extra['Note Title']=$entry_note['Title'];
  $extra['Note Body']=$entry_note['Body'];
  $extra['Note Response']=$note_response;
 
   }  
 }


  return array("error"=>$error,"id"=>$id,"link"=>$link,"action"=>$action,"status"=>$status,"data"=>$fields,"response"=>$sales_response,"extra"=>$extra);
  }

public function get_search_val($field,$fields,$fields_info){
   $search=array();
  if(strpos($field,'FirstName+') !== false ){
      if(!empty($fields['FirstName'])){
          $search['FirstName']=  $fields['FirstName'];
      }
      if(!empty($fields['LastName'])){
          $search['LastName']= $fields['LastName'];
      }   
  }else if(isset($fields[$field]) && $fields[$field] !=''){
      $val=$fields[$field];
      if(isset($fields_info[$field]['type']) && $fields_info[$field]['type'] == 'phone'){
         $val=preg_replace( '/[^0-9]/', '', $val );
      }
     $search=array( $field=>$val ); 
  } 
return $search;   
}  
public function post_note($post,$meta,$id=''){
 $note_object=!empty($meta['note_list']) ? 'ContentNote' : 'Note'; 

     if(!empty($meta['note_list'])){
     $note_body=!empty($post['Body']) ? $post['Body'] : '';
      $note_body_arr=explode("\n",$note_body);
      $note_body='<p>'.implode('</p><p>',$note_body_arr).'</p>';   
     $post['Content']=base64_encode($note_body);
     unset($post['Body']);
      if(!empty($post['ParentId'])){
  $id=$post['ParentId'];
  unset($post['ParentId']);   
 }
     }
   

$post_data=json_encode($post);  
 $sales_response=$this->post_sales_arr('/services/data/'.$this->api_version.'/sobjects/'.$note_object,"POST",$post_data);
    if(isset($sales_response['id']) && !empty($meta['note_list']) && !empty($id)){
$arr=array('ContentDocumentId'=>$sales_response['id'],'LinkedEntityId'=>$id,'ShareType'=>'V','Visibility'=>'AllUsers');        
$post_data=json_encode($arr);
$sales_response['linkedTo']=$this->post_sales_arr('/services/data/'.$this->api_version.'/sobjects/ContentDocumentLink',"POST",$post_data);      
    }
return $sales_response;
}

  /**
  * Create Order and Order items
  * 
  * @param mixed $post
  * @param mixed $meta
  */
public function add_order($post, $meta){ 

      $_order=self::$_order;
/*$path='/services/data/v30.0/sobjects/Order/8016A000000eFRMQA2';
$path='/services/data/v37.0/sobjects/Order/describe';
$sales_response=$this->post_sales_arr($path,'get');
   var_dump($sales_response); die('-----------------');
   */
     $items=$_order->get_items();
     $products=array();  $order_items=array(); $sales_response=array();  $extra=array();
     if(is_array($items) && count($items)>0 && !empty($meta['price_book'])){
      foreach($items as $item_id=>$item){
       $p_id= !empty($item['variation_id']) ? $item['variation_id'] : $item['product_id'];
        $line_desc=array();
        if(!isset($products[$p_id])){
        $product=wc_get_product($p_id);
        }else{
         $product=$products[$p_id];   
        }
        $products[$p_id]=$product;
        $sku=$product->get_sku(); 
        $unit_price=$product->get_price();
        
        if(!empty($item['variation_id'])){
          //get attributes
         $attrs=$product->get_attributes();
          if(is_array($attrs) && count($attrs)>0){
           //   $sku.='-'.$item['variation_id'];
      foreach($attrs as $term_key=>$term){
          if(isset($item[$term_key])){
    $line_desc[$item_id][]=$item[$term_key];          
          }
      }     
        } 
      }
      //
      $price_book_id="";
      $price_book=$meta['price_book'];
      $path='/services/data/'.$this->api_version.'/query';
    $q= "SELECT Id,UnitPrice,ProductCode,Pricebook2Id from PricebookEntry where ProductCode='".$sku."'  order by Id DESC Limit 1"; //and Pricebook2Id='".$price_book."'
  $path.='?q='.urlencode($q);
    $sales_response=$this->post_sales_arr($path,'GET');   
    
        $extra=array('Search Product'=>array('ProductCode'=>$sku,'Pricebook2Id'=>$price_book),'Search Result'=>$sales_response);
     if(isset($sales_response['records']) && is_array($sales_response['records']) && isset($sales_response['records'][0])){
      $price_book_id=$sales_response['records'][0]['Id'];   
      $price_book=$sales_response['records'][0]['Pricebook2Id'];   
     }else{
     $res=$this->search_in_sf('Product2',array('ProductCode'=>$sku) );
       $product_id='';
       if(!empty($res[0]['Id'])){
       $product_id=$res[0]['Id'];    
       }else{
       //create product in sf
             $path='/services/data/'.$this->api_version.'/sobjects/Product2';
         $sf_pro=array('IsActive'=>true,'ProductCode'=>$sku,'Name'=>$product->get_title());
         if(!empty($meta['pro_desc'])){
             $sf_pro['Description']=$meta['pro_desc'];
         }
       $sf_pro_json=json_encode($sf_pro);
       $sales_response=$this->post_sales_arr($path,'POST',$sf_pro_json);
       $product_id=$sales_response['id'];
       $extra['Create Product']=$sf_pro;
       $extra['Create Result']=$sales_response; 
       } 
       if(!empty($product_id) ){
        //add to price book
        $path='/services/data/'.$this->api_version.'/sobjects/PricebookEntry'; 
       $sf_entry=array('IsActive'=>true,'Product2Id'=>$product_id,'Pricebook2Id'=>$price_book,'UnitPrice'=>$unit_price);
         $sf_entry_json=json_encode($sf_entry);
       $sales_response=$this->post_sales_arr($path,'POST',$sf_entry_json);
       $extra['Add PriceBook']=$sf_entry;
       $extra['PriceBook Redult']=$sales_response;  
       if(is_array($sales_response) && isset($sales_response['id'])){
           $price_book_id=$sales_response['id'];
       }  
       }    
     }
      
        if(!empty($price_book_id)){
         //add as order item
       $order_item=array('attributes'=>array('type'=>'OrderItem'),'quantity'=>$item['qty'],'PricebookEntryId'=>$price_book_id,'UnitPrice'=>$unit_price);
       if(isset($line_desc[$item_id])){ 
       $order_item['Description']=implode(" | ",$line_desc[$item_id]);
       }
       $order_items[]=$order_item; 
        }   
     }

     if(count($order_items)>0 && !empty($price_book)){
     $post['Pricebook2Id']=$price_book;
     $post['OrderItems']=array('records'=>$order_items);
     }
     }
     
     if(!(is_array($sales_response) && isset($sales_response[0]['errorCode']) ) ){ 
      $path='/services/data/'.$this->api_version.'/commerce/sale/order/';
               //create order
     $att_order=array('attributes'=>array('type'=>'Order'));
     $post=is_array($post) ? $post : array();
     $post=array_merge($att_order,$post);
         if(empty($post['status'])){
       $post['Status']='Draft';  
     }
      $post_json=array("order"=>array($post));   

       $sales_response=$this->post_sales_arr($path,'POST',json_encode($post_json));
   //var_dump($sales_response,$post_json); die('-----------------');
       if(isset($sales_response['records'][0]) && is_array($sales_response['records'][0]) && isset($sales_response['records'][0]['Id'])){
           $sales_response=array("id"=>$sales_response['records'][0]['Id']);
       }
     }

       return array('res'=>$sales_response,'extra'=>$extra);
  }
  /**
  * Cleans salesrforce fields
  * formates date and checkboxes
  * @param  array $fixed fields to post
  * @param  array $fields_info fields info
  * @return array Salesforce fields
  */
public function clean_sf_fields($fixed,$fields_info){ 
  $sf_fields=array();
  if(is_array($fixed)){
     
foreach($fixed as $field_key=>$field_val){ 
  //convert date to salesforce compatible format
  if(isset($fields_info[$field_key])){
  if($fields_info[$field_key]['type'] == "date" && !empty($field_val) ){
  $field_val=date('Y-m-d',strtotime(str_replace(array("/"),"-",$field_val))); 
  }else if($fields_info[$field_key]['type'] == "datetime" && !empty($field_val)){
      $offset=get_option('gmt_offset');
     $offset=$offset*3600;
  $field_val=date('c',strtotime(str_replace(array("/"),"-",$field_val))-$offset); 
  }else if($fields_info[$field_key]['type'] == "boolean"){
  $field_val=empty($field_val) || in_array($field_val,array('no','No')) ? 0 : 1 ; 
  }else if($fields_info[$field_key]['type'] == "multipicklist"){
      if(is_array($field_val)){
       $field_val=implode(';',$field_val);   
      }
  }
  if(is_array($field_val)){ 
      $field_val=implode(', ',$field_val);
  }
  $sf_fields[$field_key]=$field_val;      
  }   
}
  }
    
  return $sf_fields;    
  }
  /**
  * Formates Salesforce success or error response into message string
  * @param  array $sales_res Slesforce response 
  * @return string formated string
  */
public function search_in_sf($sales_object,$search,$search2){
  $sales_response=array(); 
   /*
   $val='(810) 476-63056';
   $field_type='phone';
  if(in_array($field_type,array("email","phone"))){
    //reomve saleforce reserved characters from key value
  $clean_key=""; $key_val=str_split($val);
  foreach($key_val as $v){
  if(in_array($v,array("?","&","|","!","{","}","[","]","(",")","^","~","*",":",'\\','"',"'","+","-")))
  $v='\\'.$v;
  $clean_key.=$v;    
  }
  $q="FIND {".$clean_key."} IN ".strtoupper($field_type)." FIELDS RETURNING ".$sales_object."(Id)"; 
  $query='/services/data/'.$this->api_version.'/search?q='.urlencode($q);
  $sales_response=$this->post_sales_arr($query,"get");
    if(isset($sales_response['searchRecords'])){
  $sales_response=$sales_response['searchRecords'];
  } }
  */

  $where=array();
  if(!empty($search)){
      $where[]=$search; 
  }
    if(!empty($search2)){
        $where[]=$search2;
  }
  if(!empty($where)){
     $where2=array(); 
    foreach($where as $search){
          $temp=array();
      foreach($search as $k=>$v){
      $temp[]=$k." = '".esc_sql($v)."'";    
      }
    $where2[]=' ( '.implode(' AND ',$temp).' ) '; 
    }  
  $q="SELECT Id FROM $sales_object WHERE ".implode(' OR ',$where2);       
  $query='/services/data/'.$this->api_version.'/query?q='.urlencode($q);
  $sales_response=$this->post_sales_arr($query,"get");
///  var_dump($sales_response,$q); die('-------------');
  if(isset($sales_response['records'])){
  $sales_response=$sales_response['records'];
  }
  }
  
  return $sales_response;   
  } 
public function get_entry($object,$id){
  return $this->post_sales_arr("/services/data/v27.0/sobjects/".$object.'/'.$id,"get");     
  }
public function create_fields_section($fields){
$arr=array(); 
if(!isset($fields['object'])){
        $objects=array(''=>'Select Object');
    $objects_sf=$this->get_crm_objects(); //var_dump($objects,$this->info);
    if(is_array($objects_sf)){
    $objects=array_merge($objects,$objects_sf);
    }
 $arr['gen_sel']['object']=array('label'=>__('Select Object','gravity-forms-salesforce-crm'),'options'=>$objects,'is_ajax'=>true,'req'=>true);   
}else if(isset($fields['fields']) && !empty($fields['object'])){
    // filter fields
    $crm_fields=$this->get_crm_fields($fields['object']); 
    if(!is_array($crm_fields)){
        $crm_fields=array();
    }
    $add_fields=array();
    if(is_array($fields['fields']) && count($fields['fields'])>0){
        foreach($fields['fields'] as $k=>$v){
           $found=false;
                foreach($crm_fields as $crm_key=>$val){
                    if(strpos($crm_key,$k)!== false){
                        $found=true; break;
                }
            }
         //   echo $found.'---------'.$k.'============'.$crm_key.'<hr>';
         if(!$found){
       $add_fields[$k]=$v;      
         }   
        }
    }
 $arr['fields']=$add_fields;   
}

return $arr;  
} 
 
      
}
}
?>