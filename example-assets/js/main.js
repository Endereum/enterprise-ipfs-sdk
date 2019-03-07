
var file_vs = file_size = null;
var bar = $('.progress-bar');
var percent = $('.percent');
var file_type = 'unknown';
var doc_extensions = file_extensions.document.split(',');
var img_extensions = file_extensions.image.split(',');
var vid_extensions = file_extensions.video.split(',');
var aud_extensions = file_extensions.audio.split(',');

// Create File Name
function create_file_title(file_title){
  var new_file_title = file_title.replace(/[^A-Z0-9]+/ig, "-");
  new_file_title = new_file_title.replace(/(^-)/g, "");
  new_file_title = new_file_title.replace(/(-$)/g, "");
  return new_file_title;
}

// set file type
function set_file_type(extension){
  // Setting file type to unknown
  file_type = 'unknown';

  doc_extensions.forEach(function(doc_extension){
    if(extension == doc_extension){
      file_type = 'document';
    }
  });

  img_extensions.forEach(function(img_extension){
    if(extension == img_extension){
      file_type = 'image';
    }
  });

  vid_extensions.forEach(function(vid_extension){
    if(extension == vid_extension){
      file_type = 'video';
    }
  });

  aud_extensions.forEach(function(aud_extension){
    if(extension == aud_extension){
      file_type = 'audio';
    }
  });

  if(file_type == 'unknown') return false;
  else {
    $('#file_type').val(file_type);
    $('#file_extension').val(extension);
    return true;
  }
}

// Function to get file size
function set_file_size(file){
  var _size = file;
  var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'),
  i=0;while(_size>900){_size/=1024;i++;}
  file_size = (Math.round(_size*100)/100)+' '+fSExt[i];
  return true;
}

// On select file set file hint
function fs_set_hint(){
  if(file_type == 'document'){
    $('#file-hint').removeClass().addClass('la la-file-pdf-o');
    $('#file-hint-text').html('Document file selected. Size ' + file_size);
  }
  else if(file_type == 'image'){
    $('#file-hint').removeClass().addClass('la la-file-image-o');
    $('#file-hint-text').html('image file selected. Size ' + file_size);
  }
  else if(file_type == 'audio'){
    $('#file-hint').removeClass().addClass('la la-file-audio-o');
    $('#file-hint-text').html('Audio file selected. Size ' + file_size);
  }
  else if(file_type == 'video'){
    $('#file-hint').removeClass().addClass('la la-file-video-o');
    $('#file-hint-text').html('Video file selected. Size ' + file_size);
  }
  else if(file_type == 'unknown'){
    $('#file-hint').removeClass().addClass('la la-exclamation-circle');
    $('#file-hint-text').html('Please select file');
  }
}

// On select file auto fill form field
function fs_auto_fill(file_vs, file_title, file_name){
  if(file_type == 'unknown'){
    $('#file-title').val(null).removeAttr('value');
    $('#file_name').val('');

    console.log('I ran');

    // Error for unknown file
    fs_set_hint();
  }
  else{
    $('#file-title').val(file_title);
    $('#file_name').val(file_name.toLowerCase());

    // Set description for known file
    if(set_file_size(file_vs.size)) fs_set_hint();
  }
}

// Function Upload file
$("#upload-file-ipfs").change(function(){
  var file_name_original = $(this).val().replace(/.*(\/|\\)/, '')
  var file_name_array = file_name_original.split('.');

  // File Extension
  var extension = file_name_array[file_name_array.length-1];

  // Set extension and render file
  if(set_file_type(extension)){
    // Create file title and file name
    var new_file_title = create_file_title(file_name_original.replace('.' + extension, ''));
    var new_file_name = new_file_title + '.' + extension;

    // Final file name
    new_file_title = new_file_title.replace(/-/g, " ");

    $(this).parent(".file-upload-wrapper").attr("data-text", file_name_original).addClass('file-selected');
    file_vs = event.target.files[0];

    fs_auto_fill(file_vs, new_file_title, new_file_name);
  }else{
    $(this).parent(".file-upload-wrapper").attr("data-text", 'File type not allowed!').removeClass('file-selected');
    $(this).val(null);

    fs_auto_fill(null, null, null);
  }
});
   
$('#upload-file').ajaxForm({
  beforeSend: function() {
      $('#upload-file').css('display', 'none');
      $('#uploading_bar').css('display', '').animate({
        opacity: 1}, 300);
      var percentVal = '0%';
      bar.width(percentVal)
      percent.html(percentVal);
  },
  uploadProgress: function(event, position, total, percentComplete) {
      var percentVal = percentComplete + '%';
      bar.width(percentVal)
      percent.html(percentVal);
  },
  success: function() {
      var percentVal = '100%';
      bar.width(percentVal)
      percent.html(percentVal);
  },
	complete: function(xhr) {
    console.log('The response from the server: ' + xhr.responseText);
    if(xhr.responseText){
      $('#uploading_bar').animate({ opacity: 0}, 300, function(){
        $(this).css({
          'display': 'none',
          'opacity': 0
        });
        $('#done_info').css('display', '').animate({
          opacity: 1
        },300);
      });
    }else{
      bar.removeClass('bg-endereum').addClass('bg-danger');
      $('#uploading_bar').animate({ opacity: 0}, 300, function(){
        $(this).css({
          'display': 'none',
          'opacity': 0
        });
        $('#failed_info').css('display', '').animate({
          opacity: 1
        },300);
      });
    }
	}
});
