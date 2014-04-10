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
		$res->salutation = MSG_SALUTATION;
		$res->firstname = MSG_FNAME;
		$res->lastname = MSG_LNAME;
		$res->address1 = MSG_ADDRESS1;
		$res->address2 = MSG_ADDRESS2;
		$res->address3 = MSG_ADDRESS3;
		$res->address4 = MSG_ADDRESS4;
		$res->city = MSG_CITY;
		$res->state = MSG_STATE;
		$res->country = MSG_COUNTRY;
		$res->zipcode = MSG_ZIPCODE;
		$res->mobile = MSG_MOBILE;
		$res->telephone = MSG_TELEPHONE;
		$res->ext = MSG_EXT;
		$res->fax = MSG_FAX;
		$res->email = STR_EMAIL;

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

		$res->order_head = MSG_ORDER_HEAD;
		$res->order_date = MSG_ORDER_DATE;
		$res->order_ship = MSG_SHIPMENT;
		$res->order_pay = MSG_PAYMENT;
		$res->order_payment_tell = MSG_ORDER_PAYMENT_TELL;
		$res->order_end = MSG_ORDER_END;

		return $res;
	}

 function getOrder($id){
    global $mdb2;
    $SQL = "select ord.*, sddt.data_type_name as shiptype, pddt.description as paytype from kt_order as ord
                    left join kt_define_data_type as sddt on ord.shipment_id = sddt.id and sddt.ref_data_type = 'SHIPMENT_TYPE'
                    left join kt_define_data_type as pddt on ord.payment_id = pddt.id and pddt.ref_data_type = 'PAYMENT_TYPE'
                    WHERE ord.is_active = 'Y' and ord.is_delete = 'N' and ord.id=?";
    $rs = $mdb2->query($SQL, array(DBTYPE_INT), array($id) );
    return $rs->fetchRow();
}


// Create new PHPExcel object
global $mdb2;
$objPHPExcel = new PHPExcel();
$activeSheet = $objPHPExcel->getActiveSheet();
$FontColor = new PHPExcel_Style_Color();

$type = $_REQUEST['type'];
$orderid = $_REQUEST['order_id'];
// Set document properties
$label = getLabel();
$style = getArrayStyle();
$order = getOrder($orderid);
$activeSheet = $objPHPExcel->getActiveSheet();
//$FontColor = new PHPExcel_Style_Color();

$filename = 'Order_reciept_No'.$orderid;
// Set document properties

$objPHPExcel->getProperties()->setCreator('user_name')
                                                         ->setLastModifiedBy('user_name')
                                                         ->setTitle('KITTIVATE ORDER ID.'.$orderid)
                                                         ->setSubject("Office 2007 XLSX Test Document")
                                                         ->setDescription("Media Plan for Office 2007 XLSX, generated using PHP classes.")
                                                         ->setKeywords("office 2007 openxml php")
                                                         ->setCategory("Media Plan");
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('angsanaupc');
$activeSheet->getColumnDimension('A')->setWidth(3);

setStyle($activeSheet,array('B1','D1'), $label->order_head.$orderid, $style->aStyleHead);
setStyle($activeSheet,array('J1','K1'), $label->order_date, $style->aStyleDetailLabel);
setStyle($activeSheet,array('L1','M1'), $order->order_date, $style->aStyleDetail);

setStyle($activeSheet,array('B3','C3'), $label->firstname, $style->aStyleDetailLabel);
setStyle($activeSheet,array('D3','E3'), $order->firstname, $style->aStyleDetail);
setStyle($activeSheet,array('F3','G3'), $label->lastname, $style->aStyleDetailLabel);
setStyle($activeSheet,array('H3','I3'), $order->lastname, $style->aStyleDetail);

setStyle($activeSheet,array('I4','J4'), $label->address1, $style->aStyleDetailLabel);
setStyle($activeSheet,array('K4','M4'), $order->address1, $style->aStyleDetail);

setStyle($activeSheet,array('B5','C5'), $label->address2, $style->aStyleDetailLabel);
setStyle($activeSheet,array('D5','E5'), $order->address2, $style->aStyleDetail);
setStyle($activeSheet,array('F5','G5'), $label->address3, $style->aStyleDetailLabel);
setStyle($activeSheet,array('H5','I5'), $order->address3, $style->aStyleDetail);
setStyle($activeSheet,array('J5','K5'), $label->address4, $style->aStyleDetailLabel);
setStyle($activeSheet,array('L5','M5'), $order->address4, $style->aStyleDetail);

setStyle($activeSheet,array('B6','C6'), $label->city, $style->aStyleDetailLabel);
setStyle($activeSheet,array('D6','E6'), $order->city, $style->aStyleDetail);
setStyle($activeSheet,array('F6','G6'), $label->state, $style->aStyleDetailLabel);
setStyle($activeSheet,array('H6','I6'), $order->state, $style->aStyleDetail);
setStyle($activeSheet,array('J6','K6'), $label->zipcode, $style->aStyleDetailLabel);
setStyle($activeSheet,array('L6','M6'), $order->zipcode, $style->aStyleDetail);

setStyle($activeSheet,array('B7','C7'), $label->country, $style->aStyleDetailLabel);
setStyle($activeSheet,array('D7','E7'), $order->country, $style->aStyleDetail);
setStyle($activeSheet,array('J7','K7'), $label->order_ship, $style->aStyleDetailLabel);
setStyle($activeSheet,array('L7','M7'), $order->shiptype, $style->aStyleDetail);

setStyle($activeSheet,array('H8','I8'), $label->order_pay, $style->aStyleDetailLabel);
setStyle($activeSheet,array('J8','M8'), $order->paytype, $style->aStyleDetail);

$activeSheet->getStyle('A2:N8')->getBorders()->getOutline()->setBorderStyle( PHPExcel_Style_Border::BORDER_THIN);

$startDetail = $startRow = 10;
setStyle($activeSheet,'A'.$startDetail, 'No', $style->aOrderDetailLabelCenter);
setStyle($activeSheet,array('B'.$startDetail, 'C'.$startDetail), $label->barcode, $style->aOrderDetailLabelCenter);
setStyle($activeSheet,array('D'.$startDetail, 'I'.$startDetail), $label->product, $style->aOrderDetailLabelCenter);
setStyle($activeSheet,array('J'.$startDetail, 'K'.$startDetail), $label->price, $style->aOrderDetailLabelCenter);
setStyle($activeSheet,'L'.$startDetail, $label->qty, $style->aOrderDetailLabelCenter);
setStyle($activeSheet,array('M'.$startDetail, 'N'.$startDetail), $label->total."(".$label->baht.")", $style->aOrderDetailLabelCenter);

$SQL = "select * from kt_orderdetail WHERE order_id = ?";
$result =& $mdb2->query($SQL, array(DBTYPE_INT), array($orderid));

$i=1;
while($row = $result->fetchRow()) {
    $startDetail++;
    $rs = $mdb2->query("select kp.barcode, kdt.data_type_name as unit_en, kdt.description as unit_th
                    from kt_product as kp
                    left join kt_define_data_type as kdt on (kp.unit = kdt.id)
                    WHERE kp.id = {$row->pid}");
    $product = $rs->fetchRow();

            //if($this->session['language'] == 'thailand'){
                    $productName = $row->name_th." ".$row->volumn." ".$product->unit_th;
            //}else{
                    //$productName = $row->name_en." ".$row->volumn." ".$product->unit_en;
            //}
            setStyle($activeSheet,'A'.$startDetail, $i, $style->aOrderDetailCenter);
            setStyle($activeSheet,array('B'.$startDetail, 'C'.$startDetail), " ".$product->barcode, $style->aOrderDetail);
            setStyle($activeSheet,array('D'.$startDetail, 'I'.$startDetail), " ".$productName, $style->aOrderDetail);
            setStyle($activeSheet,array('J'.$startDetail, 'K'.$startDetail), $row->price, $style->aOrderDetailCenter, 'number');
            setStyle($activeSheet,'L'.$startDetail, $row->qty, $style->aOrderDetailCenter, 'number');
            setStyle($activeSheet,array('M'.$startDetail, 'N'.$startDetail), $row->sumtotal, $style->aOrderDetailCenter, 'number');
            //$startDetail++;
            $i++;
}

$activeSheet->getStyle('A'.$startRow.':N'.$startDetail)->getBorders()->getOutline()->setBorderStyle( PHPExcel_Style_Border::BORDER_THIN);
$endDetail = $startDetail+1;
setStyle($activeSheet,array('A'.$endDetail, 'L'.$endDetail), $label->subtotal, $style->aOrderDetailLabelRight);
setStyle($activeSheet,array('M'.$endDetail, 'N'.$endDetail), $order->subtotal, $style->aOrderDetailCenterBottom, 'number');
$endDetail++;

setStyle($activeSheet,array('A'.$endDetail, 'L'.$endDetail), $label->shipprice, $style->aOrderDetailLabelRight);
setStyle($activeSheet,array('M'.$endDetail, 'N'.$endDetail), $order->shipprice, $style->aOrderDetailCenterBottom, 'number');
$endDetail++;

setStyle($activeSheet,array('A'.$endDetail, 'L'.$endDetail), $label->grandtotal, $style->aOrderDetailLabelRight);
setStyle($activeSheet,array('M'.$endDetail, 'N'.$endDetail), $order->grandtotal, $style->aOrderDetailCenterBottom, 'number');
$activeSheet->getStyle('A'.$startRow.':N'.$endDetail)->getBorders()->getOutline()->setBorderStyle( PHPExcel_Style_Border::BORDER_THIN);

$endCredit = $endDetail + 3;

setStyle($activeSheet,array('A'.$endCredit, 'F'.$endCredit), $label->order_end, $style->aStyleDetailLabelLeft);
setStyle($activeSheet,array('I'.$endCredit, 'N'.$endCredit), $label->order_payment_tell, $style->aStyleDetailLabel);

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
    $objWriter->save('php://output');
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

