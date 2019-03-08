<?php
  // Current Directory
  $dir = __DIR__;

  // Require Main Integration SDK file
  require_once $dir.'/dist/integration.sdk.php';

  // Enterprise API Key
  $api_key = 'en.ipfs.xxxxxxxxxxxxxxxxxxxxxxxxx';

  // Creating new endereum IPFS object
  $endereum_ipfs = new endereum_ipfs($api_key);

  // Get upload credentials
  $app_credential = $endereum_ipfs->get_upload_credentials();

  // Generate new upload credentials, incase upload credentials are not returned by the server
  if(false == $app_credential){

    // Generating new upload credentials
    $app_credential = $endereum_ipfs->generate_upload_credentials();
  }
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Endereum IPFS Enterprise Solution Example</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
    crossorigin="anonymous">

  <!-- CSS -->
  <link rel="stylesheet" href="http://endereum.net/php/example-assets/css/style.min.css">
</head>

<body>
  <!--[if lt IE 7]>
      <p>You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

  <!-- Nav Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        Enterprise Example
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-12 mt-5">

        <!-- Upload form -->
        <form class="form" id="upload-file" action="https://api.endereum.io/file_upload" method="post" enctype="multipart/form-data">
          <!-- Invisible Fields -->
          <div style="display: none; position absolute;">
            <!-- [[ user_id ]] -->
            <input name="user_id" class="hidden" value="<?php echo $app_credential['enterprise_id']; ?>" hidden />

            <!-- [[ connect_key ]] -->
            <input name="connect_key" class="hidden" value="<?php echo $app_credential['connect_key']; ?>" hidden />

            <!-- [[ file_type ]] -->
            <input id="file_type" name="file_type" class="hidden" hidden />

            <!-- [[ file_title ]] -->
            <input id="file_extension" name="file_extension" class="hidden" hidden />
          </div>

          <!-- Actual Form -->
          <div class="form-body">
            <!-- File Select -->
            <div class="form-group mb-4">
              <h4 for="upload-file-ipfs">Select file</h4>
              <p>Make sure that you have selected the correct file before uploading. Do not manipulate the file type
                with fake extensions. If you found uploading malicious file on IPFS public cloud and violating our
                terms and condition, then we may permanently delete your account.</p>
              <div class="file-upload-wrapper" data-text="Please select file">
                <input id="upload-file-ipfs" name="file" type="file" class="file-upload-field" required data-toggle="tooltip"
                  data-trigger="hover" data-placement="top" data-title="Upload File">
              </div>
              <small class="form-text text-muted">
                <span id="file-hint-text">Please select file</span>
              </small>
            </div>

            <!-- [[ file_title ]] -->
            <div class="form-group mb-4">
              <h4 for="file-title">File Title</h4>
              <p>Create interesting file title. You can include spaces. No special characters are allowed. Remember
                special characters will be filtered out!</p>
              <input name="file_title" type="text" id="file-title" class="form-control" placeholder="Please select file"
                name="file-title" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="file Title">
              <small class="form-text text-muted">
                <span id="file-hint-text">Set your file title.</span>
              </small>
            </div>

            <!-- Accept terms and condition -->
            <div class="form-group skin skin-flat">
              <fieldset>
                <input type="checkbox" id="input-15" required>
                <label for="input-15">I Accept The Terms And Conditions</label>
              </fieldset>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-endereum"><i class="ft ft-upload"></i> Upload</button>
          </div>
        </form>

        <!-- Upload Success -->
        <div id="done_info" style="display: none; opacity: 0;">
          <div class="bs-callout-success callout-border-left p-1">
            <strong>File uploaded successfully!</strong>
            <p>We have received your file. We are now uploading your file to IPFS cloud. You can now return to your
              dashboard.</p>
              <button type="button" class="btn btn-secondary btn-min-width mr-1" onclick="location.reload();">Return
              to dashboard</button>
          </div>
        </div>

        <!-- Upload failed -->
        <div id="failed_info" style="display: none; opacity: 0;">
          <div class="bs-callout-danger callout-border-left p-1">
            <strong>File upload failed!</strong>
            <p>There is some technical problem. Please try after some time.</p>
            <button type="button" class="btn btn-secondary btn-min-width mr-1" onclick="location.reload();">Return
              to dashboard</button>
          </div>
        </div>

        <!-- uploading bar -->
        <div id="uploading_bar" style="display: none; opacity: 0;">
          <div class="text-center" id="file-uploading=progress">File Uploading - <span class="percent">0%</span></div>
          <div class="progress">
            <div class="progress-bar bg-endereum" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
              style="width: 0%" aria-describedby="file-uploading=progress"></div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="http://endereum.net/php/example-assets/js/jquery.form.js"></script>
  <script src="http://endereum.net/php/example-assets/js/file.extensions.js"></script>
  <script src="http://endereum.net/php/example-assets/js/main.js"></script>

</body>
</html>
