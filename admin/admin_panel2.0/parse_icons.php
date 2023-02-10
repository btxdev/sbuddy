<?php

  $path = 'media/filesICO/svg/';
  $default_icon = 'DEV.svg';

  if(!file_exists($path)) {
    exit('FILE NOT EXISTS');
  }

  // FILETYPES
  $icon_by_type = Array(
    'video' => 'VIDEO.svg',
    'audio' => 'AUDIO.svg',
    'compressed' => 'ARCHIVE.svg',
    'executable' => 'BAT.svg',
    'document' => 'document.svg',
    'image' => 'IMG.svg',
    'other' => $default_icon,
    'file' => $default_icon
  );

  // EXTENSIONS
  $video_extensions = Array(
    '3g2',
    '3gp',
    'avi',
    'flv',
    'h264',
    'm4v',
    'mkv',
    'mov',
    'mp4',
    'mpg',
    'mpeg',
    'rm',
    'swf',
    'vob',
    'wmv'
  );
  $audio_extensions = Array(
    'aif',
    'cda',
    'mid',
    'midi',
    'mp3',
    'mpa',
    'ogg',
    'wav',
    'wma',
    'wpl'
  );
  $compressed_extensions = Array(
    '7z',
    'arj',
    'deb',
    'pkg',
    'rar',
    'rpm',
    'gz',
    'z',
    'zip'
  );
  $executable_extensions = Array(
    'apk',
    'bat',
    'bin',
    'cgi',
    'pl',
    'com',
    'exe',
    'gadget',
    'jar',
    'msi',
    'py',
    'wsf'
  );
  $document_extensions = Array(
    'key',
    'odp',
    'pps',
    'ppt',
    'pptx',
    'odt',
    'pdf',
    'rtf',
    'tex',
    'txt',
    'wpd',
    'pdpp'
  );
  $image_extensions = Array(
    'jpeg',
    'jpg',
    'gif',
    'png',
    'webp',
    'svg',
    'bmp'
  );

  // ===========================================================================

  // BY EXTENSION
  $files = scandir($path);
  foreach($files as $id => $file) {
    if($id < 2 || $file == 'desktop.ini') {
      continue;
    }
    $ext = substr($file, 0, strripos($file, '.'));
    if(strlen($ext) == 0) {
      continue;
    }
    if($id == 2) {
      echo('if(ext == \''.$ext.'\') { return \''.$path.$file.'\'; }<br>');
    }
    else {
      echo('else if(ext == \''.$ext.'\') { return \''.$path.$file.'\'; }<br>');
    }
  }

  // ===========================================================================

  // BY IMAGE_EXTENSION FORCED
  foreach($image_extensions as $ext) {
    echo('else if(ext == \''.strtoupper($ext).'\') { return \''.$path.$icon_by_type['image'].'\'; }<br>');
  }
  // BY DOCUMENT_EXTENSION FORCED
  foreach($document_extensions as $ext) {
    echo('else if(ext == \''.strtoupper($ext).'\') { return \''.$path.$icon_by_type['document'].'\'; }<br>');
  }
  // BY EXECUTABLE_EXTENSION FORCED
  foreach($executable_extensions as $ext) {
    echo('else if(ext == \''.strtoupper($ext).'\') { return \''.$path.$icon_by_type['executable'].'\'; }<br>');
  }
  // BY COMPRESSED_EXTENSION FORCED
  foreach($compressed_extensions as $ext) {
    echo('else if(ext == \''.strtoupper($ext).'\') { return \''.$path.$icon_by_type['compressed'].'\'; }<br>');
  }
  // BY AUDIO_EXTENSION FORCED
  foreach($audio_extensions as $ext) {
    echo('else if(ext == \''.strtoupper($ext).'\') { return \''.$path.$icon_by_type['audio'].'\'; }<br>');
  }
  // BY VIDEO_EXTENSION FORCED
  foreach($video_extensions as $ext) {
    echo('else if(ext == \''.strtoupper($ext).'\') { return \''.$path.$icon_by_type['video'].'\'; }<br>');
  }

  // ===========================================================================

  // BY FILETYPE
  foreach($icon_by_type as $type => $ext) {
    echo('else if(type == \''.$type.'\') { return \''.$path.$ext.'\'; }<br>');
  }

  // ===========================================================================

  // FINALLY
  echo('else { return \''.$path.'ERROR.svg'.'\'; }<br>');

?>
