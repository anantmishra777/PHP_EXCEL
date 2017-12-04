<?php
	//include DB 
	include 'config.php';

	/** Include PHPExcel */
	require_once dirname(__FILE__) . '/PHPExcel-1.8/Classes/PHPExcel.php';


	// Create new PHPExcel object
	//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
	$objPHPExcel = new PHPExcel();

	//find row and column size in DB
	try
	{		
		$query = $conn->query('SELECT * FROM info ');			
	}
	catch(PDOException $e)
	{
		$e->getMessage();
	}
	$rowSize = $query->rowCount();
	$columnSize = $query->columnCount();
	$result = $query->fetchAll();

	// Add column names to spreadsheet
	// column names must be updated here as per table
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A1', 'indexid')
	            ->setCellValue('B1', 'username')
	            ->setCellValue('C1', 'password')
	            ->setCellValue('D1', 'aaa');

	// column names must be updated here as per table
	$columns_array = array('indexid', 'username', 'password', 'aaa');	

	//adding data to spreadsheet
	for($i=1; $i<=$rowSize; $i++)
	{
		$c = 'A';  //to be used in cell name
		for ($j=1; $j<=$columnSize; $j++, $c++)
		{
			$cell_name = $c.($i+1);			
			$column_name = $columns_array[$j-1];

			//uncomment echo command to verify cell names and their respective values 
			//echo $cell_value.'---------'.$result[$i-1][$column_name].'<br>';			

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_name, $result[$i-1][$column_name]);
		}
	}

	// Uncomment commands to Rename worksheet
	//echo date('H:i:s') , " Rename worksheet" , EOL;
	//$objPHPExcel->getActiveSheet()->setTitle('Simple');

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	// Save Excel 2007 file
	$callStartTime = microtime(true);

	// Use PCLZip rather than ZipArchive to create the Excel2007 OfficeOpenXML file
	PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
	$callEndTime = microtime(true);
	$callTime = $callEndTime - $callStartTime;

	echo 'Files have been created in ' , getcwd() , EOL;
?>