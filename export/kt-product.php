<?php
    require_once '../Includes/configs/init.php';
    include_once MAX_PATH."ajax/page/kt-order.php";
    require_once MAX_PATH.'Includes/libs/excel/Classes/PHPExcel.php';


	function setStyle($activeSheet, $col = array(), $text, $aStyle = array(), $type = ''){

		if(empty($type)){
			if(!empty($col) && is_array($col)){
				$activeSheet->mergeCells($col[0].':'.$col[1] );
				$activeSheet->setCellValue($col[0] , $text);
				$activeSheet->getStyle($col[0])->applyFromArray($aStyle);
			}else if(!empty($col) && !is_array($col)){
				$activeSheet->setCellValue($col , $text);
				$activeSheet->getStyle($col)->applyFromArray($aStyle);
			}
		}else if($type == 'number'){
			if(!empty($col) && is_array($col)){
				$activeSheet->mergeCells($col[0].':'.$col[1] );
				$activeSheet->setCellValue($col[0] , number_format($text, 0));
				$activeSheet->getStyle($col[0])->applyFromArray($aStyle);
			}else if(!empty($col) && !is_array($col)){
				$activeSheet->setCellValue($col , number_format($text, 0));
				$activeSheet->getStyle($col)->applyFromArray($aStyle);
			}
		}

	}

	function getArrayStyle(){
		//$this->load->library('ex');
		$res->aStyleHead = array(
                        'font'    => array(
								'bold'      => true,
                                'size'      => 20
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
            'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
            )
		);

		$res->aStyleDetailLabel = array(
                        'font'    => array(
								'bold'      => true,
                                'size'      => 14
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
            'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
            )
		);

		$res->aStyleDetailLabelLeft = array(
                        'font'    => array(
								'bold'      => true,
                                'size'      => 14
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
            'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
            )
		);

		$res->aStyleDetail = array(
                        'font'    => array(
								'bold'      => false,
                                'size'      => 14
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
            'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
            )
		);

                $res->aStyleDetailLeft = array(
                        'font'    => array(
								'bold'      => false,
                                'size'      => 14
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
            'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
            )
		);

		$res->aOrderDetail = array(
                        'font'    => array(
								'bold'      => false,
                                'size'      => 14
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
            'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
            )
		);

		$res->aOrderDetailCenter = array(
                        'font'    => array(
								'bold'      => false,
                                'size'      => 14
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
            'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
            )
		);

		$res->aOrderDetailRight = array(
                        'font'    => array(
								'bold'      => false,
                                'size'      => 14
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
            'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
            )
		);

		$res->aOrderDetailLabelCenter = array(
                        'font'    => array(
								'bold'      => true,
                                'size'      => 14
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
            'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
            )
		);

		$res->aOrderDetailLabelRight = array(
                        'font'    => array(
								'bold'      => true,
                                'size'      => 14
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
            'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
            )
		);

		$res->aOrderDetailCenterBottom = array(
                        'font'    => array(
								'bold'      => true,
                                'size'      => 14
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
            'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_NONE,),
            )
		);
		return $res;
	}

    function getLabel(){
        $res->barcode = MSG_BARCODE;
		$res->product = MSG_PRODUCT;
        $res->price = MSG_PRICE;
        $res->baht = MSG_BAHT;
        $res->qty = MSG_QTY;
		$res->total = MSG_TOTAL;
		$res->subtotal = MSG_SUMTOTAL;
		$res->shipprice = MSG_SHIP_PRICE;
		$res->grandtotal =STR_GRANTOTAL;
		$res->image = MSG_IMAGE;
		$res->doc_product = MSG_DOC_PRODUCT;
		$res->page = MSG_DOC_PAGE;
		$res->doc_date = MSG_DATE;
		$res->expire =MSG_PRODUCT_EXPIRE;
		$res->msg_select =MSG_SELECT_ITEM;
		return $res;
	}

// Create new PHPExcel object
global $mdb2;
global $session;
$objPHPExcel = new PHPExcel();
$activeSheet = $objPHPExcel->getActiveSheet();
$FontColor = new PHPExcel_Style_Color();

$type = $_REQUEST['type'];
// Set document properties
$label = getLabel();
$style = getArrayStyle();
$activeSheet = $objPHPExcel->getActiveSheet();
//$FontColor = new PHPExcel_Style_Color();

$filename = 'kittivate_product_detail';
// Set document properties

$objPHPExcel->getProperties()->setCreator('user_name')
                                 ->setLastModifiedBy('user_name')
                                 ->setTitle('KITTIVATE PRODUCT DETAIL')
                                 ->setSubject("Office 2007 XLSX Test Document")
                                 ->setDescription("Media Plan for Office 2007 XLSX, generated using PHP classes.")
                                 ->setKeywords("office 2007 openxml php")
                                 ->setCategory("Media Plan");
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('angsanaupc');
$activeSheet->getColumnDimension('A')->setWidth(3);


$product = $_SESSION["product_query"];
$startDetail = $startRow = 3;
if($type == "excel"){
 setStyle($activeSheet,array('D'.($startDetail-2), 'I'.($startDetail-2)), $label->doc_product, $style->aStyleHead);

 setStyle($activeSheet,array('A'.($startDetail-1), 'B'.($startDetail-1)), $label->msg_select, $style->aStyleDetailLabelLeft);
 setStyle($activeSheet,array('C'.($startDetail-1), 'G'.($startDetail-1)), $_SESSION["product_select"], $style->aStyleDetailLeft);
 setStyle($activeSheet,'L'.($startDetail-1), $label->doc_date, $style->aStyleDetail);
 setStyle($activeSheet,array('M'.($startDetail-1), 'N'.($startDetail-1)), date("d/m/Y"), $style->aStyleDetail);
 
 setStyle($activeSheet,'A'.$startDetail, 'No', $style->aOrderDetailLabelCenter);
 setStyle($activeSheet,array('B'.$startDetail, 'C'.$startDetail), $label->barcode, $style->aOrderDetailLabelCenter);
 setStyle($activeSheet,array('D'.$startDetail, 'I'.$startDetail), $label->product, $style->aOrderDetailLabelCenter);
 setStyle($activeSheet,array('J'.$startDetail, 'K'.$startDetail), $label->price, $style->aOrderDetailLabelCenter);
 setStyle($activeSheet,'L'.$startDetail, $label->qty, $style->aOrderDetailLabelCenter);
 setStyle($activeSheet,array('M'.$startDetail, 'N'.$startDetail), $label->expire, $style->aOrderDetailLabelCenter);
}
//$result =& $mdb2->query($SQL, $datatype, $dataoftype);
$aPage = array();
$i=1;
$page=1;
foreach($product as $row) {
    $startDetail++;
    if(($i-1) % 40 == 0 && $i !== 1 && $type == "pdf"){
            $startDetail++;
            $startDetail++;
            $startDetail++;
            $startDetail++;
            $startDetail++;
            $startDetail++;
            //$startDetail++;
    }
    if(($i-1) % 40 == 0 && $type == "pdf"){
        setStyle($activeSheet,array('D'.($startDetail-2), 'I'.($startDetail-2)), $label->doc_product, $style->aStyleHead);
		setStyle($activeSheet,'N'.($startDetail-2), $label->page." ".$page, $style->aStyleDetail);
                
		 setStyle($activeSheet,array('A'.($startDetail-1), 'B'.($startDetail-1)), $label->msg_select, $style->aStyleDetailLabelLeft);
                setStyle($activeSheet,array('C'.($startDetail-1), 'G'.($startDetail-1)), $_SESSION["product_select"], $style->aStyleDetailLeft);
		setStyle($activeSheet,'L'.($startDetail-1), $label->doc_date, $style->aStyleDetail);
		setStyle($activeSheet,array('M'.($startDetail-1), 'N'.($startDetail-1)), date("d/m/Y"), $style->aStyleDetail);
        
                setStyle($activeSheet,'A'.$startDetail, 'No', $style->aOrderDetailLabelCenter);
		setStyle($activeSheet,array('B'.$startDetail, 'C'.$startDetail), $label->barcode, $style->aOrderDetailLabelCenter);
		setStyle($activeSheet,array('D'.$startDetail, 'I'.$startDetail), $label->product, $style->aOrderDetailLabelCenter);
		setStyle($activeSheet,array('J'.$startDetail, 'K'.$startDetail), $label->price, $style->aOrderDetailLabelCenter);
		setStyle($activeSheet,'L'.$startDetail, $label->qty, $style->aOrderDetailLabelCenter);
		setStyle($activeSheet,array('M'.$startDetail, 'N'.$startDetail), $label->expire, $style->aOrderDetailLabelCenter);
		array_push($aPage,$startDetail);
		$page++;
    	$startDetail++;	
	}
	
	$expire = empty($row['expire']) ? '': date('d m Y', strtotime($row['expire']));
    setStyle($activeSheet,'A'.$startDetail, $i, $style->aOrderDetailCenter);
    setStyle($activeSheet,array('B'.$startDetail, 'C'.$startDetail), " ".$row['barcode'], $style->aOrderDetail);
    setStyle($activeSheet,array('D'.$startDetail, 'I'.$startDetail), " ".$row['name'], $style->aOrderDetail);
    setStyle($activeSheet,array('J'.$startDetail, 'K'.$startDetail), $row['price'], $style->aOrderDetailCenter, 'number');
    setStyle($activeSheet,'L'.$startDetail, $row['stock'], $style->aOrderDetailCenter, 'number');
    setStyle($activeSheet,array('M'.$startDetail, 'N'.$startDetail), $expire, $style->aOrderDetailCenter);
    //$startDetail++;
    

    $i++;
}
//var_dump($aPage);
$i =1;
if($type == "pdf"){
    foreach($aPage as $val){
            if(count($aPage) == $i)
                    $activeSheet->getStyle('A'.($val).':N'.$startDetail)->getBorders()->getOutline()->setBorderStyle( PHPExcel_Style_Border::BORDER_THIN);
            else
                    $activeSheet->getStyle('A'.($val).':N'.($val+40))->getBorders()->getOutline()->setBorderStyle( PHPExcel_Style_Border::BORDER_THIN);


            $i++;
    }
}else if($type == "excel"){
    $activeSheet->getStyle('A'.$startRow.':N'.$startDetail)->getBorders()->getOutline()->setBorderStyle( PHPExcel_Style_Border::BORDER_THIN);
}


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
if($type=='excel'){
    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
}elseif($type=='pdf'){
    $activeSheet->setShowGridLines(FALSE);
    $activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
    $activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
    $activeSheet->getPageMargins()->setTop(.1);
    $activeSheet->getPageMargins()->setBottom(.1);
    $activeSheet->getPageMargins()->setLeft(0.3);
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline;filename="'.$filename.'.pdf"');
    //header('Content-Disposition: attachment;filename="'.$client.'-'.$brand.'.pdf"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
    $objWriter->save('php://output', true);
}elseif($type=='save'){
//    $haveRow = $mdb2->isHaveRow("SELECT * FROM query_report where create_by_id = '$user_ids' and checksum = '$aCellMd5'");

//    if(!$haveRow){
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    $objWriter->save('../var/export/'.$filename.'.xls');
//    $mdb2->query("INSERT INTO query_report (query_type, query_data, path_file, checksum, create_date, create_by_id, create_ip)
//                VALUES ('WEBSITE_AD_STATUS', '$aCellSerialize', '{$filename}.xls', '$aCellMd5', now(), '$user_ids', '$user_ip')");
//    }
}


exit;

