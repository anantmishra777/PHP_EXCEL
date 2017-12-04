<?php
	//include DB
	include 'config.php';   

	$flag=0;
	if( isset($_POST["submit"]) )
	{		
		//  Include PHPExcel_IOFactory
		include 'PHPExcel-1.8\Classes\PHPExcel\IOFactory.php';

		//saving xlsx file
		$inputFileName = $_FILES['file']['tmp_name'];

		//  Read your Excel workbook
		try
		{
		    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
		    $objPHPExcel = $objReader->load($inputFileName);
		}
		catch(Exception $e)
		{
		    die('Error loading file "'.pathinfo($inputFileName, PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		//  Loop through each row of the worksheet in turn
		for ($row = 1; $row <= $highestRow; $row++)
		{ 
		    //  Read a row of data into an array
		    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

		    //  Insert row data array into your database of choice here
		    try
		    {
		    	$query = $conn->prepare('INSERT INTO info(indexid, username, password) VALUES(?, ?, ?)');
		    	$query->execute( array($rowData[0][0], $rowData[0][1], $rowData[0][2]) );
		    }
		    catch(PDOException $e)
		    {
		    	$e->getMessage();
		    }

		    //if insert query fails then update flag
		    if( !$query )
		    	$flag = 1;
		}

		if($flag==1)
			echo "Upload failed!<br><br>";
		else
			echo 'File uploaded!<br><br>';
	}
?>

<!DOCTYPE html>
<html>
	<body>
		<form enctype="multipart/form-data" method="post" role="form">
			<div class="form-group">
				<label for="exampleInputFile">File Upload</label>
				<input type="file" name="file" id="file" size="150" />
				<p class="help-block">Only Excel/CSV File Import.</p>
			</div>
			<button type="submit" class="btn btn-default" name="submit" value="submit">Upload</button>
		</form>
	</body>
</html>