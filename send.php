<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


$setting = array('brandUrl'=>'https://convertical-api.com','key'=>'2444A8E5-2714-1707-72DE-8BB623B97DF8');
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}
$result_data = sendLeads1($_POST);
$response = array();
$integrationResult = explode("@@", $result_data);


if ($integrationResult['0']=='ok'){
  $loginurl = loginUrl1($integrationResult['1']);
  $response['status'] ="success";
  $response['error'] ="0";
  $response['url'] = $integrationResult['1'];
  $response['data'] = $result_data;
}else{
  $response['status'] ="failed";
  $response['error'] ="1";
  $response['data'] = $result_data;
}
echo json_encode($response);


function sendLeads1($leads){
  global $setting;

if($leads['offerName'])
    $offerName = $string = $leads['offerName'];


  $apiData = array(
        'email' => trim($leads['email']),
        'firstName' => trim($leads['firstName']),
        'lastName' => trim($leads['lastName']),
        'phone' => trim($leads['phone']),
        //'areaCode' => trim($leads['phone_country_code']),
        'ip' => trim($_SERVER['REMOTE_ADDR']),
        'password' => 'asd123ASD',
        'offerWebsite' => trim($leads['source']),
        'offerName' =>  trim($leads['sub']),
        'custom1' => trim($leads['custom1']),
        'custom2' =>  trim($leads['custom2']),
        'custom3' =>  trim($leads['custom3']),
        'custom4' =>  trim($leads['custom4']),
    );


    $curl_url = trim($setting['brandUrl'])."/api/v2/leads";
        
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $curl_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 300,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query($apiData),
      CURLOPT_HTTPHEADER => array(
        "Cache-Control: no-cache",
        "Content-Type: application/x-www-form-urlencoded",
        "Postman-Token: 55da6cb9-8df6-4b1d-b860-04fbf1d2b6de",
        "Api-Key: ".$setting['key']
      ),
    ));

    $output1 = curl_exec($curl);
    $data1= json_decode($output1, TRUE);
    $returnResult = false;
    
    if(isset($data1['details']['redirect']['url'])){
        $login_url = $data1['details']['redirect']['url'];
       $returnResult = "ok@@".$login_url;           
    }else{
      $error = $data1['errors'][0]['message'];
      $returnResult = $error;
    }
    return $returnResult;
}

function loginUrl1($url){
  return $url;
}
?>