<?php

class endereum_ipfs{
  // API Key Private Variable
  private $api_key;

  /**
   * Constructor Function
   * Sets API key */
  function __construct($api_key){
    if(empty($this->api_key)) $this->api_key = $api_key;
  }

  /**
   * Private Function
   * CURL Request on our remote server */
  private function contact_server($action){
    // Initiate CURL Request
    $curl = curl_init();

    // DATA boundary
    $data_boundary = "--EN--";

    // CURL Form Data
    $raw_form_data = "--$data_boundary\r\n"."Content-Disposition: form-data; name=\"api_key\"\r\n\r\n$this->api_key\r\n";
    $raw_form_data = $raw_form_data."--$data_boundary\r\n"."Content-Disposition: form-data; name=\"action\"\r\n\r\n$action\r\n"."--$data_boundary--";
  
    curl_setopt($curl, CURLOPT_URL,"https://endereum.io/connect-key/");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $raw_form_data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      "cache-control: no-cache",
      "content-type: multipart/form-data; boundary=$data_boundary"
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
  
    if($err) return false;
    else return $response;
  }

  // Generate Token
  function generate_credentials(){
    if(empty($this->api_key)) return false;

    $response = (array)json_decode($this->contact_server('generate'));

    if('success' == $response['status']) return array(
      'user_id'     => $response['user_id'],
      'connect_key' => $response['connect_key']
    );

    return false;
  }

  // Get Token
  function get_credentials(){
    if(empty($this->api_key)) return false;

    $response = (array)json_decode($this->contact_server('get'));

    if('success' == $response['status']) return array(
      'user_id'     => $response['user_id'],
      'connect_key' => $response['connect_key']
    );

    return false;
  }
}
