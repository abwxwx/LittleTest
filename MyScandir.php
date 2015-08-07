<?php

//写一个函数，能够遍历一个文件夹下的所有文件和子文件夹

function my_scandir($dir)
{
     $files = array();
     if ( $handle = opendir($dir) ) {
         while ( ($file = readdir($handle)) !== false ) {
			// echo "filename: $file : filetype: " . filetype($dir .'/'.$file) . "<br/>";
			$full_file_name = $dir .'/'.$file;
            if ( $file != ".." && $file != "." ) {
                 if ( is_dir($full_file_name) ) {
                     $files[$file] = my_scandir($full_file_name);
                 }else {
                     $files[] = $file;
                 }
             }
         }
         closedir($handle);
         return $files;
     }
	 else
	 {
		 echo "open dir error";
	 }
}

$files = my_scandir($dir);
print_r($files);