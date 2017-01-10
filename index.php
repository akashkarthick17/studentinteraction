<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
 <?php  
if(isset($_FILES['file'])){
    
   
     $file_name = $_FILES['file']['name'];
      $file_size = $_FILES['file']['size'];
      $file_tmp = $_FILES['file']['tmp_name'];
      $file_type = $_FILES['file']['type'];
       
          $value = explode('.',$file_name);

       
      $file_ext=strtolower(end($value));
       $temp = explode(".", $file_name);
$newfilename = "filesuccess" . '.' . end($temp);
      
      $extensions= array("xls","xlsx");
     
      
      if(in_array($file_ext,$extensions)=== false){
         $errors="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152) {
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true) {
         move_uploaded_file($file_tmp,"files/".$newfilename);
         echo "Success";
      }else{
         echo ($errors);
      }
    
    
    
    
    
    
    
     $connect = mysqli_connect("localhost", "root", "", "rmd_database");  
 include ("PHPExcel/IOFactory.php");  
 $html="<table border='1'>";  
 $objPHPExcel = PHPExcel_IOFactory::load("files/$newfilename");  
 foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
 {  
      $highestRow = $worksheet->getHighestRow();  
      for ($row=2; $row<=$highestRow; $row++)  
      {  
           $html.="<tr>";  
            $id = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
           $name = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(1, $row)->getValue());  
           $position = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(2, $row)->getValue());  
           $sql = "INSERT INTO sample VALUES (".$id.",'".$name."', '".$position."')";  
           mysqli_query($connect, $sql); 
             $html.= '<td>'.$id.'</td>'; 
           $html.= '<td>'.$name.'</td>';  
           $html .= '<td>'.$position.'</td>';  
           $html .= "</tr>";  
      }  
 }  
 $html .= '</table>';  
 echo $html;  
 echo '<br />Data Inserted';  
     unlink("files/$newfilename");
    
}

 ?>
 
 
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <title>Document</title>
  </head>
  <body>
     <form action="index.php" method = "POST" enctype = "multipart/form-data">
        <div>
            
            <input type="file" name="file" >
            
            
        </div>
        <div>
            <input type="submit" name="submit" value="submit">
            
        </div>
         
         
         
         
     </form>
      
  </body>
  </html>  