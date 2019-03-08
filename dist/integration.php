<?php

class endereum_ipfs{
  /** Our validation API server END point */
  private $server_api_endpoint = "https://endereum.io/connect-key/";


  /** API Key Private Variable */
  private $api_key;


  /**
   * Constructor Function
   * Sets API key for internal use */
  function __construct($api_key){

    /** Check API key, it should not be empty */
    if(empty($this->api_key)) {

      /** Set API key with the key provided */
      $this->api_key = $api_key;
    }
  }


  /**
   * Private Function: Contact our server to validate API key
   * and to get required data
   * 
   * @function: CURL Request on our remote server
   * 
   * @param: action: type of action
   * @return: response: response got from our server */
  private function contact_server($action){
    /** Initiate CURL Request */
    $curl = curl_init();

    /** DATA boundary */
    $data_boundary = "--EN--";

    /** CURL Form Data */
    $raw_form_data = "--$data_boundary\r\n"."Content-Disposition: form-data; name=\"api_key\"\r\n\r\n$this->api_key\r\n";
    $raw_form_data = $raw_form_data."--$data_boundary\r\n"."Content-Disposition: form-data; name=\"action\"\r\n\r\n$action\r\n"."--$data_boundary--";

    /** CURL HTTP header options */
    $header_option = array(
      "cache-control: no-cache",
      "content-type: multipart/form-data; boundary=$data_boundary"
    );
  
    /** Set CURL Option */
    curl_setopt($curl, CURLOPT_URL,             $this->server_api_endpoint);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST,   "POST");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,  true);
    curl_setopt($curl, CURLOPT_MAXREDIRS,       10);
    curl_setopt($curl, CURLOPT_TIMEOUT,         30);
    curl_setopt($curl, CURLOPT_HTTP_VERSION,    CURL_HTTP_VERSION_1_1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,  0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  0);
    curl_setopt($curl, CURLOPT_POSTFIELDS,      $raw_form_data);
    curl_setopt($curl, CURLOPT_HTTPHEADER,      $header_option);

    /** Get CURL response */
    $response = curl_exec($curl);

    /** Check for errors */
    $err = curl_error($curl);

    /** Close CURL request */
    curl_close($curl);
  
    /**
     * ERROR: @return: false - Incase of any error
     * 
     * SUCCESS: @return: response */
    if($err) return false;
    else return $response;
  }


  /**
   * Public Function: Generate Upload Credentials 
   * 
   * Always generate new credentials and discards the OLD one */
  function generate_upload_credentials(){
    /**
     * Return false if API key is not set 
     * 
     * @return: false */
    if(empty($this->api_key)) return false;

    /**
     * Decode response from the server */
    $response = json_decode($this->contact_server('generate'));

    /**
     * Convert response to an array */
    $response = (array)$response;

    /**
     * Check response status is success */
    if('success' == $response['status']) {

      /**
     * Return user ID and Connect Key
     * 
     * @return: enterprise_id and connect_key */
      return array(
        'enterprise_id' => $response['user_id'],      // User ID as a Enterprise ID
        'connect_key'   => $response['connect_key']   // Connect key
      );
    }

    /**
     * In case any error, return false 
     * 
     * @return: false */
    return false;
  }


  /**
   * Public Function: Get Upload Credentials 
   * 
   * Always fetch latest generated upload credentials */
  function get_upload_credentials(){
    /**
     * Return false if API key is not set 
     * 
     * @return: false */
    if(empty($this->api_key)) return false;

    /**
     * Decode response from the server */
    $response = json_decode($this->contact_server('get'));

    /**
     * Convert response to an array */
    $response = (array)$response;

    /**
     * Check response status is success */
    if('success' == $response['status']) {

      /**
     * Return user ID and Connect Key
     * 
     * @return: enterprise_id and connect_key */
      return array(
        'enterprise_id' => $response['user_id'],      // User ID as a Enterprise ID
        'connect_key'   => $response['connect_key']   // Connect key
      );
    }

    /**
     * In case any error, return false 
     * 
     * @return: false */
    return false;
  }
}
