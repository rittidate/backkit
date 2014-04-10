<?php
function getNameFromNumber($num) {
    $numeric = ($num - 1) % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval(($num - 1) / 26);
    if ($num2 > 0) {
        return getNameFromNumber($num2) . $letter;
    } else {
        return $letter;
    }
}

    require_once '../Includes/configs/init.php';
    include_once MAX_PATH."ajax/page/media-plan-detail.php";
    include_once MAX_PATH."ajax/page/media-plan.php";
    require_once MAX_PATH.'Includes/libs/excel/Classes/PHPExcel.php';

    //error_reporting(E_ALL);
    $_REQUEST['del_data_no_save'] = 'Y';
    $_REQUEST['page'] = 1;
    $_REQUEST['sidx'] = 'website_name';
    $_REQUEST['sord'] = 'desc';
    $_REQUEST['q'] = 'media_plan_detail';
    $_REQUEST['fileclass'] = 'media-plan.php';
    $type = $_REQUEST['type'];

    $titleRowHeight = 10;
    $headRowHeight = 11;
    $contentRowHeight = 9;
    $summaryRowHeight = 11;

    if(empty($_REQUEST['media_plan_id'])) {
        $_SESSION['error_msg'] = 'data not found or data not saved';
        OX_Redirect::redirect('warning.php');
        exit;
    }

    $mp = new Media_plan();

    $user_ids = $mp->getViewUserID();

    if(!empty($user_ids)){
        $cond_roles .= " and create_by_id in ($user_ids)";
    }
    $rs = $mdb2->query("select * from media_plan where media_plan_id = ? $cond_roles ",
            array(DBTYPE_INT), array($_REQUEST['media_plan_id']) );
    if($row = $rs->fetchRow()){
        $brand = $row->brand;
        $client = $row->client;
        $campaign = $row->campaign;
        $media_price = $row->media_price;
        $discount = $row->discount;
        $campaign_period = $row->campaign_period;
        $traffic_conditions = $row->traffic_conditions;
        $campaign_length = $row->campaign_length;
        $createddate = new Date($row->create_date);
        $revision = sprintf('R-%03d', $row->revision_id);
        $media_plan_name = $row->media_plan_name;
        $booked_impressions_period = unserialize($row->booked_impressions_period);
        $rebate = $row->rebate;
        $production_value = $row->production_value;
        $tech_cost = $row->tech_cost;
        $est_impressions = $row->est_impressions;
        
        $_REQUEST['campaign_length'] = $campaign_length;
        $mdp = new Media_plan_detail();
        $res = $mdp->search();
        $user_name = $mdp->user_name;

    }else{
        $_SESSION['error_msg'] = 'data not found or data not saved';
        OX_Redirect::redirect('warning.php');
        exit;
    }
    $aStyleHead = array(
                        'font'    => array(
                                'name'     => 'Arial',
				'bold'      => true,
                                'size'      => 6
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
                        'borders' => array(
                                'top' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_NONE,
                                ),
                                'bottom' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_NONE,
                                ),
                                'right' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_NONE,
                                ),
                                'left' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_NONE,
                                ),
                        )
);


$aStyleBorder = array(
			'font'    => array(
                                'name'     => 'Arial',
                                'size'      => 6
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
			),
                        'borders' => array(
                                'top' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                        'color' => array('rgb' => '000000'),
                                ),
                                'bottom' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                        'color' => array('rgb' => '000000'),
                                ),
                                'right' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                        'color' => array('rgb' => '000000'),
                                ),
                                'left' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                        'color' => array('rgb' => '000000'),
                                ),
                        )
		);

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$activeSheet = $objPHPExcel->getActiveSheet();
$FontColor = new PHPExcel_Style_Color();
$RedColor = $FontColor->setRGB("FF0000");

// Set document properties
$objPHPExcel->getProperties()->setCreator($user_name)
							 ->setLastModifiedBy($user_name)
							 ->setTitle($media_plan_name)
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Media Plan for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Media Plan");


// Add some data
$objPHPExcel->setActiveSheetIndex(0);

$headStartRow = 2;
$dataStartRow = $headStartRow+12;
$aConstantValue = array(
                        'A'.$headStartRow       => '                      Synergy-E',
                        'A'.($headStartRow+1)   => '                      Interactive Media Booking Form !',
                        //'A'.($headStartRow+2)   => '',
                        'A'.($headStartRow+2)   => MSG_CLIENT.':',
                        'B'.($headStartRow+2)   => $client,
                        'A'.($headStartRow+3)   => MSG_BRAND.':',
                        'B'.($headStartRow+3)   => $brand,
                        'A'.($headStartRow+4)   => MSG_CAMPAIGN.':',
                        'B'.($headStartRow+4)   => $campaign,
                        'A'.($headStartRow+5)   => 'Contact Person:',
                        'A'.($headStartRow+6)   => 'Planner:',
                        'B'.($headStartRow+6)   => $user_name,
                        'A'.($headStartRow+7)   => MSG_CREATEDDATE,
                        'B'.($headStartRow+7)   => $createddate->format('%d/%m/%Y'),
                        'A'.($headStartRow+8)   => MSG_CAMPAIGN_PERIOD.':',
                        'B'.($headStartRow+8)   => $campaign_period,
                        'A'.($headStartRow+9)   => MSG_CAMPAIGN_LENGTH.':',
                        'B'.($headStartRow+9)   => $campaign_length,
                        'A'.($headStartRow+10)  => MSG_PLAN_REVISION.':',
                        'B'.($headStartRow+10)  => $revision,

                        'E'.($headStartRow+3)   => 'Campaign Summary',
                        'E'.($headStartRow+4)   => preg_replace("/<br\W*?\/>/", "", MSG_TOTAL_BOOKED_IMPRESSIONS_L),
                        'E'.($headStartRow+6)   => MSG_SOV_2,
                        'E'.($headStartRow+7)   => MSG_TRAFFIC_CONDITIONS,
                        'F'.($headStartRow+7)   => $traffic_conditions,
                        'E'.($headStartRow+5)   => MSG_EST_IMPRESSIONS,
                        'F'.($headStartRow+5)   => number_format ($est_impressions),

                        'G'.($headStartRow+4)    => 'Imps',
                        'G'.($headStartRow+5)    => 'Imps',
                        'G'.($headStartRow+6)    => '%'
);

foreach ($aConstantValue as $key => $value){
    $activeSheet->setCellValue($key, $value);
}
$activeSheet->mergeCells('A'.($headStartRow+1) .':'.'B'.($headStartRow+1) );
$activeSheet->mergeCells('E'.($headStartRow+3)  .':'.'F'.($headStartRow+3) );

$a_booked_impressions_period = array();
foreach ($booked_impressions_period as $column_name => $period) {
    $a_booked_impressions_period[$column_name] = array('c_name'=>$period,'width'=>10, 'is_booked_imps'=>true, 'is_number'=>true, 'is_sum'=>false);
}

$aColumnName1 = array(
                    'website_name'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_WEBSITE),'width'=>18.1, 'is_booked_imps'=>false, 'is_number'=>false, 'is_sum'=>false),
                    'section_name'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_SECTION_NAME),'width'=>10, 'is_booked_imps'=>false, 'is_number'=>false, 'is_sum'=>false),
                    'total_inventory'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_TOTAL_INVENTORY),'width'=>10, 'is_booked_imps'=>false, 'is_number'=>true, 'is_sum'=>true),
                    'website_stat'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_WEBSITE_STAT),'width'=>10, 'is_booked_imps'=>false, 'is_number'=>true, 'is_sum'=>true),
                    'ad_type_name'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_ADTYPE),'width'=>14, 'is_booked_imps'=>false, 'is_number'=>false, 'is_sum'=>false),
                    'slot_size_name'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_SECTION_SLOT_SIZE),'width'=>15, 'is_booked_imps'=>false, 'is_number'=>false, 'is_sum'=>false),
                    'period'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_PRICING_SCHEME),'width'=>8, 'is_booked_imps'=>false, 'is_number'=>false, 'is_sum'=>false),
                    'unit_cost'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_UNIT_COST),'width'=>8.1, 'is_booked_imps'=>false, 'is_number'=>true, 'is_sum'=>false),
                    );

$aColumnName2 = array(
                    'total_booked_impressions'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_TOTAL_BOOKED_IMPRESSIONS_L),'width'=>11.1, 'is_booked_imps'=>false, 'is_number'=>true, 'is_sum'=>true),
                    'sov'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_SOV),'width'=>8.1, 'is_booked_imps'=>false, 'is_number'=>true, 'is_sum'=>false),
                    'total_media_cost'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_TOTAL_MEDIA_COST),'width'=>9.1, 'is_booked_imps'=>false, 'is_number'=>true, 'is_sum'=>true),
                    'ecpm_media_cost'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_ECPM_MEDIA_COST),'width'=>9.1, 'is_booked_imps'=>false, 'is_number'=>true, 'is_sum'=>false),
                    'est_revenue'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_EST_REVENUE),'width'=>9.1, 'is_booked_imps'=>false, 'is_number'=>true, 'is_sum'=>true),
                    'remark'=>array('c_name'=>preg_replace("/<br\W*?\/>/", "\n", MSG_REMARK),'width'=>13.1, 'is_booked_imps'=>false, 'is_number'=>true, 'is_sum'=>false) );

$aColumnName = array_merge($aColumnName1, $a_booked_impressions_period, $aColumnName2);

$aNoneBorder = array('A1','A'.($headStartRow),'A'.($headStartRow+1),'A'.($headStartRow+2),'A'.($headStartRow+3),'A'.($headStartRow+4),'A'.($headStartRow+5),'A'.($headStartRow+6),'A'.($headStartRow+7),'A'.($headStartRow+8),'A'.($headStartRow+9),'A'.($headStartRow+10),'E'.($headStartRow+3),'E'.($headStartRow+4),'E'.($headStartRow+5),'E'.($headStartRow+6),'E'.($headStartRow+7),'E'.($headStartRow+8),'F'.($headStartRow+4),'F'.($headStartRow+5),'G'.($headStartRow+4),'G'.($headStartRow+5));

/*
 * set auto width
 */
$page_width = 0;
foreach ($aColumnName as $column_name => $columns){
    $page_width += (int)$columns['width'];
}
$ini_column_num = 15;
$ini_avg_1column_width = $page_width/$ini_column_num;
$ini_percent_1column = $ini_avg_1column_width*100/$page_width;

$cur_column_num = count($aColumnName);
$cur_avg_1column_width = $page_width/$cur_column_num;
$cur_percent_1column = $cur_avg_1column_width*100/$page_width;

foreach ($aColumnName as $column_name => $columns){
    $aColumnName[$column_name]['width'] = ceil($columns['width']*$cur_percent_1column/$ini_percent_1column);
}
/*
 * end set auto width
 */

//FREEZE Pane
$activeSheet->freezePane('E1');


for($j=1;$j<$dataStartRow;$j++){
    $activeSheet->getRowDimension($j)->setRowHeight($titleRowHeight);
    $c=1;
    foreach ($aColumnName as $column_name => $columns){
            $excel_column = getNameFromNumber($c);
            $activeSheet->getStyle($excel_column.$j)->applyFromArray($aStyleHead);
            $activeSheet->getColumnDimension($excel_column)->setWidth($columns['width']);
            $c++;
    }
}

// Set style for header row using alternative method
$activeSheet->getStyle('A'.($headStartRow).':A'.($headStartRow+1))->applyFromArray($aStyleHead);
$activeSheet->getStyle('A'.($headStartRow).':A'.($headStartRow+1))->getFont()->setSize(5);

$activeSheet->getStyle('A'.($headStartRow+2).':A'.($headStartRow+11))->applyFromArray($aStyleHead);
$activeSheet->getStyle('B'.($headStartRow+2).':B'.($headStartRow+11))->applyFromArray($aStyleHead);

$activeSheet->getStyle('E'.($headStartRow+3))->applyFromArray($aStyleHead);
$activeSheet->getStyle('E'.($headStartRow+3))->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE)->setSize(7);

$activeSheet->getStyle('E'.($headStartRow+4).':G'.($headStartRow+4))->applyFromArray($aStyleHead);
$activeSheet->getStyle('E'.($headStartRow+6))->applyFromArray($aStyleHead);
$activeSheet->getStyle('F'.($headStartRow+6))->applyFromArray($aStyleHead);

$activeSheet->getStyle('E'.($headStartRow+5).':G'.($headStartRow+5))->applyFromArray($aStyleHead);
//$activeSheet->getStyle('E'.($headStartRow+5).':G'.($headStartRow+5))->getFont()->setColor($RedColor);

$activeSheet->getStyle('E'.($headStartRow+7).':E'.($headStartRow+8))->applyFromArray($aStyleHead);
//$activeSheet->getStyle('E'.($headStartRow+7).':E'.($headStartRow+8))->getFont()->setBold(false)->setColor($RedColor);

//set Columns
$i = 1;
$a_booked_imps = array();
foreach ($aColumnName as $c_name=>$columns){
    $excel_column = getNameFromNumber($i);

    if(!$columns['is_booked_imps']){
        $activeSheet->setCellValue($excel_column.$dataStartRow, $columns['c_name']);
        $activeSheet->mergeCells($excel_column.$dataStartRow.':'.$excel_column. ($dataStartRow+1) );
    }else{
        $a_booked_imps[] = $excel_column.$dataStartRow;
            $activeSheet->setCellValue($excel_column.$dataStartRow, 'Booked Imps');
        $activeSheet->setCellValue($excel_column.($dataStartRow+1), $columns['c_name']);
        $activeSheet->getStyle($excel_column.($dataStartRow+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCFFCC');
    }


    $activeSheet->getStyle($excel_column.($dataStartRow))->applyFromArray($aStyleBorder);
    $activeSheet->getStyle($excel_column.($dataStartRow+1))->applyFromArray($aStyleBorder);

    $activeSheet->getStyle($excel_column.$dataStartRow)->getFont()->setBold(true);
    $activeSheet->getStyle($excel_column.($dataStartRow+1))->getFont()->setBold(true);
    $activeSheet->getStyle($excel_column.$dataStartRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $activeSheet->getStyle($excel_column.$dataStartRow)->getAlignment()->setWrapText(true);
    // Set fills
    $activeSheet->getStyle($excel_column.$dataStartRow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $activeSheet->getStyle($excel_column.$dataStartRow)->getFill()->getStartColor()->setRGB('C0C0C0');

//    $activeSheet->getRowDimension($excel_column.$dataStartRow)->setRowHeight($headRowHeight);
//    $activeSheet->getRowDimension($excel_column.($dataStartRow+1))->setRowHeight($headRowHeight);

    if($c_name == 'L' || $c_name == 'N'){
        $activeSheet->getStyle($excel_column.($dataStartRow))->getFont()->setColor($RedColor);
    }

    $activeSheet->getColumnDimension($excel_column)->setWidth($columns['width']);
    $i++;
}

if(count($a_booked_imps)>1){
    $activeSheet->mergeCells($a_booked_imps[0].':'.$a_booked_imps[count($a_booked_imps)-1]);
}

//$activeSheet->getStyle('A'.($dataStartRow).':O'.($dataStartRow+1))->getBorders()->getOutline()->setBorderStyle( PHPExcel_Style_Border::BORDER_THIN);
//
//$activeSheet->getStyle('I'.($dataStartRow))->getBorders()->getOutline()->setBorderStyle( PHPExcel_Style_Border::BORDER_THIN);
//$activeSheet->getStyle('I'.($dataStartRow+1))->getBorders()->getOutline()->setBorderStyle( PHPExcel_Style_Border::BORDER_THIN);

// Add a drawing to the worksheet
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('logo.png');
//$objDrawing->setHeight(50);
$objDrawing->setHeight(35);
$objDrawing->setWorksheet($activeSheet);

$startRowData = $dataStartRow+2;

$objPHPExcel->setActiveSheetIndex(0);
$r=1;
$c_row = count($res->rows);
$aSumColumn = array();
foreach ($res->rows as $row) {
    $cells = $row['cell'];

    $i = 1;
    $c_booked_imps = count($booked_impressions_period);
    $walk_booked_imps = 0;
    $jump = 0;
    $c_cname = count($aColumnName) + BOOKED_IMPS_NUM - $c_booked_imps;
    foreach ($aColumnName as $column_name => $columns){
        $excel_column = getNameFromNumber($i);

        $is_media_plan_group = $cells[$c_cname];
        $is_split_calulate = $cells[$c_cname+1];

        if($columns['is_sum']==true){
            if($column_name=='est_revenue' && $is_media_plan_group=='N'){
                $aSumColumn[$column_name][] = $excel_column.$startRowData;
            }elseif($is_media_plan_group=='N' && $is_split_calulate == 'Y' && $column_name!='est_revenue'){
                $aSumColumn[$column_name][] = $excel_column.$startRowData;
            }elseif($is_media_plan_group=='Y' && $is_split_calulate == 'N' && $column_name!='est_revenue'){
                $aSumColumn[$column_name][] = $excel_column.$startRowData;
            }
        }
        if($is_media_plan_group=='Y'){
            $activeSheet->getStyle($excel_column.$startRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
            if($column_name=='website_name'){
                $activeSheet->getStyle($excel_column.$startRowData)->getFont()->setBold(true)->setSize(8);
            }
        }

        $activeSheet->setCellValue($excel_column.$startRowData, $cells[$jump+$i-1]);

        if($columns['is_number']){
            if($type=='pdf' && is_numeric($cells[$jump+$i-1]) ){
                $activeSheet->setCellValue($excel_column.$startRowData, number_format($cells[$jump+$i-1]));
            }else
                $activeSheet->getStyle($excel_column.$startRowData)->getNumberFormat()->setFormatCode("#,##0");
        }

        if($columns['is_booked_imps']==true){
            ++$walk_booked_imps;
            if($walk_booked_imps==$c_booked_imps){
                $jump = BOOKED_IMPS_NUM - $walk_booked_imps;
            }
            if($is_media_plan_group=='N'){
                $activeSheet->getStyle($excel_column.$startRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCFFCC');
            }
        }

        //footer summary
        if($c_row==$r){
            if(isset($res->userdata[$column_name])){
                if($columns['is_sum']==true){
                    $total = ( empty($aSumColumn[$column_name])?'': implode(',', $aSumColumn[$column_name]) );
                    if($type=='excel'){
                        $activeSheet->setCellValue($excel_column.($startRowData+1), (empty($total)?'':"=SUM({$total})") );
                    }elseif($type=='pdf'){
                        $activeSheet->setCellValue($excel_column.($startRowData+1), number_format($res->userdata[$column_name]) );

                    }
                    if($column_name=='total_booked_impressions'){
                        $cell_total_booked_impressions = $excel_column.($startRowData+1);
                        $activeSheet->setCellValue('F'.($headStartRow+4), "=$cell_total_booked_impressions");
                        $activeSheet->getStyle('F'.($headStartRow+4))->getNumberFormat()->setFormatCode("#,##0");
                    }
                    elseif($column_name=='website_stat'){
                        $cell_website_stat = $excel_column.($startRowData+1);
                    }elseif($column_name=='total_media_cost'){
                        $cell_media_cost = $excel_column.($startRowData+1);
                        $sum_total_media_cost = $res->userdata['total_media_cost'];
                    }
                }elseif($column_name=='sov'){
                    if($type=='excel'){
                        $activeSheet->setCellValue($excel_column.($startRowData+1), "={$cell_total_booked_impressions}*100/{$cell_website_stat}");
                        $activeSheet->setCellValue('F'.($headStartRow+6), "=".$excel_column.($startRowData+1));
                        $activeSheet->getStyle('F'.($headStartRow+6))->getNumberFormat()->setFormatCode("#,##0");
                    }elseif($type=='pdf'){
                        $activeSheet->setCellValue($excel_column.($startRowData+1), round($res->userdata['total_booked_impressions']*100/$res->userdata['website_stat'] ));
                        $activeSheet->setCellValue('F'.($headStartRow+6), round($res->userdata['total_booked_impressions']*100/$res->userdata['website_stat']) );
                    }

                }elseif($column_name=='website_name'){
                        $activeSheet->setCellValue($excel_column.($startRowData+1), $res->userdata[$column_name]);
                }

               $activeSheet->getStyle($excel_column.($startRowData+1))->getNumberFormat()->setFormatCode("#,##0");
            }
            $activeSheet->getStyle($excel_column.($startRowData+1))->applyFromArray($aStyleBorder);
            $activeSheet->getStyle($excel_column.($startRowData+1))->getFont()->setBold(true);

            $activeSheet->getStyle($excel_column.($startRowData+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $activeSheet->getStyle($excel_column.($startRowData+1))->getFill()->getStartColor()->setRGB('C0C0C0');

        }

        $activeSheet->getStyle($excel_column.$startRowData)->applyFromArray($aStyleBorder);
        $i++;
    }
    $activeSheet->getRowDimension($startRowData)->setRowHeight($contentRowHeight);
    $r++;

    $activeSheet->getStyle('A'.$startRowData)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $activeSheet->getStyle('A'.($startRowData+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $startRowData++;

}


$endRowData = $startRowData;
$summaryRow =  $endRowData+2;
$mediaPriceRow = $summaryRow+2;
$discountRow = $mediaPriceRow+1;
$eCPMLabel = $discountRow+1;
$avgECPMAdtypeLabel = $eCPMLabel+1;
$netRow = $discountRow+1;
$mediaCostRow = $netRow+1;
$rebateRow = $mediaCostRow+1;
$productionValueRow = $rebateRow+1;
$techCostRow = $productionValueRow+1;
$totalCostRow = $techCostRow+1;
$marginRow = $totalCostRow+1;
$percentRow = $marginRow+1;

for($i=$summaryRow;$i<$summaryRow+12;$i++){
    $activeSheet->getRowDimension($i)->setRowHeight($summaryRowHeight);
    $c=1;
    foreach ($aColumnName as $column_name => $columns){
            $excel_column = getNameFromNumber($c);
            $activeSheet->getStyle($excel_column.$i)->applyFromArray($aStyleHead);
            $activeSheet->getColumnDimension($excel_column)->setWidth($columns['width']);
            $c++;
    }
}

$objPHPExcel->setActiveSheetIndex(0);
$activeSheet->setCellValue('A'.$summaryRow, 'Sale Summary');
$activeSheet->getStyle('A'.$summaryRow)->getFont()->setBold(true)->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE)->setName('Arial')->setSize(8);

$objPHPExcel->setActiveSheetIndex(0);
$activeSheet->setCellValue('F'.$summaryRow, 'Plan Statistics');
$activeSheet->getStyle('F'.$summaryRow)->getFont()->setBold(true)->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE)->setName('Arial')->setSize(8);

//$res->userdata[$column_name]

//Number of Site
$activeSheet->setCellValue('F'.$mediaPriceRow, 'Total Number of Site');
$activeSheet->setCellValue('H'.$mediaPriceRow, 'Sites');
$activeSheet->getStyle('F'.$mediaPriceRow.':H'.$mediaPriceRow)->getFont()->setBold(true)->setName('Arial')->setSize(6);
$activeSheet->getStyle('G'.$mediaPriceRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//ecpm
$activeSheet->setCellValue('F'. $eCPMLabel, 'eCPM');
$activeSheet->getStyle('F'. $eCPMLabel)->getFont()->setBold(true)->setName('Arial')->setSize(8);

if($type=='excel'){
    //media price
    $activeSheet->setCellValue('B'.$mediaPriceRow, $media_price)->getStyle('B'.$mediaPriceRow)->getNumberFormat()->setFormatCode("#,##0");


    //discount
    $activeSheet->setCellValue('B'.$netRow, '=B'.$mediaPriceRow.'*(100-B'.$discountRow.')/100')->getStyle('B'.$netRow)->getNumberFormat()->setFormatCode("#,##0");

    /*
     * Plan Statistics
     */
    //Number of Site
    $activeSheet->setCellValue('G'.$mediaPriceRow, $res->userdata['c_webiste'])->getStyle('G'.$mediaPriceRow)->getNumberFormat()->setFormatCode("#,##0");
    foreach ($res->userdata['ecpm_adtype'] as $adType => $avgECPMAdtype) {
        $activeSheet->setCellValue('F'.$avgECPMAdtypeLabel, "Avg. $adType eCPM");
        $activeSheet->setCellValue('G'.$avgECPMAdtypeLabel, $avgECPMAdtype);
        $activeSheet->setCellValue('H'.$avgECPMAdtypeLabel, 'Baht,');
        $activeSheet->setCellValue('I'.$avgECPMAdtypeLabel, $res->userdata['c_website_by_adtype'][$adType]);
        $activeSheet->setCellValue('J'.$avgECPMAdtypeLabel, 'Sites');
        $activeSheet->getStyle('F'.$avgECPMAdtypeLabel.':H'.$avgECPMAdtypeLabel)->getFont()->setBold(true)->setName('Arial')->setSize(6);
        $activeSheet->getStyle('G'.$avgECPMAdtypeLabel)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $activeSheet->getStyle('I'.$avgECPMAdtypeLabel)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $avgECPMAdtypeLabel++;
    }

    $activeSheet->setCellValue('F'.$avgECPMAdtypeLabel, "Avg. eCPM");
    $activeSheet->setCellValue('G'.$avgECPMAdtypeLabel, $res->userdata['avg_ecpm']);
    $activeSheet->setCellValue('H'.$avgECPMAdtypeLabel, 'Baht');
    $activeSheet->getStyle('F'.$avgECPMAdtypeLabel.':H'.$avgECPMAdtypeLabel)->getFont()->setBold(true)->setName('Arial')->setSize(6);
    $activeSheet->getStyle('G'.$avgECPMAdtypeLabel)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    /*
     * end Plan Statistics
     */

    $activeSheet->setCellValue('B'.$mediaCostRow, "={$cell_media_cost}")->getStyle('B'.$mediaCostRow)->getNumberFormat()->setFormatCode("#,##0");
    $activeSheet->setCellValue('B'.$rebateRow, $rebate)->getStyle('B'.$rebateRow)->getNumberFormat()->setFormatCode("#,##0");
    $activeSheet->setCellValue('B'.$productionValueRow, $production_value)->getStyle('B'.$productionValueRow)->getNumberFormat()->setFormatCode("#,##0");
    $activeSheet->setCellValue('B'.$techCostRow, $tech_cost)->getStyle('B'.$techCostRow)->getNumberFormat()->setFormatCode("#,##0");

    //Total Media Cost
    $activeSheet->setCellValue('B'.$totalCostRow, "=SUM(B{$mediaCostRow}:B{$techCostRow})")->getStyle('B'.$totalCostRow)->getNumberFormat()->setFormatCode("#,##0");


    $activeSheet->setCellValue('B'.$marginRow, '=B'.$netRow.'-B'.$totalCostRow)->getStyle('B'.$marginRow)->getNumberFormat()->setFormatCode("#,##0");
    //Net Margin
    $activeSheet->setCellValue('B'.$percentRow, '=B'.$marginRow.'*100/B'.$netRow);
}elseif($type=='pdf'){

    //media price
    $activeSheet->setCellValue('B'.$mediaPriceRow, number_format($media_price));
    //discount
    $netReceived = $media_price*(100-$discount)/100;
    //Net Received
    $activeSheet->setCellValue('B'.$netRow, number_format($netReceived, 0));

    $activeSheet->setCellValue('B'.$mediaCostRow,  number_format($sum_total_media_cost, 0));
    $activeSheet->setCellValue('B'.$rebateRow, number_format($rebate, 0));
    $activeSheet->setCellValue('B'.$productionValueRow,  number_format($production_value, 0));
    $activeSheet->setCellValue('B'.$techCostRow,  number_format($tech_cost, 0));
    $sum_total_media_cost = $sum_total_media_cost + $rebate + $production_value + $tech_cost;
    $activeSheet->setCellValue('B'.$totalCostRow, number_format($sum_total_media_cost, 0));
    //Total Media Cost
    $netMargin = $netReceived-$sum_total_media_cost;
    $activeSheet->setCellValue('B'.$marginRow, number_format($netMargin,0));
    //Net Margin
    $activeSheet->setCellValue('B'.$percentRow, number_format($netMargin*100/$netReceived, 2));

        /*
     * Plan Statistics
     */
    //Number of Site
    $activeSheet->setCellValue('G'.$mediaPriceRow, $res->userdata['c_webiste']);
    //var_dump($res->userdata['ecpm_adtype']);die();
    foreach ($res->userdata['ecpm_adtype'] as $adType => $avgECPMAdtype) {
        $activeSheet->setCellValue('F'.$avgECPMAdtypeLabel, "Avg. $adType eCPM");
        $activeSheet->setCellValue('G'.$avgECPMAdtypeLabel, number_format($avgECPMAdtype,2)      );
        $activeSheet->setCellValue('H'.$avgECPMAdtypeLabel, 'Baht, ');
        $activeSheet->setCellValue('I'.$avgECPMAdtypeLabel, $res->userdata['c_website_by_adtype'][$adType]);
        $activeSheet->setCellValue('J'.$avgECPMAdtypeLabel, 'Sites');
        $activeSheet->getStyle('F'.$avgECPMAdtypeLabel.':H'.$avgECPMAdtypeLabel)->getFont()->setBold(true)->setName('Arial')->setSize(6);
        $activeSheet->getStyle('G'.$avgECPMAdtypeLabel)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $activeSheet->getStyle('I'.$avgECPMAdtypeLabel)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $avgECPMAdtypeLabel++;
    }

    $activeSheet->setCellValue('F'.$avgECPMAdtypeLabel, "Avg. eCPM");
    $activeSheet->setCellValue('G'.$avgECPMAdtypeLabel, $res->userdata['avg_ecpm']);
    $activeSheet->setCellValue('H'.$avgECPMAdtypeLabel, 'Baht');
    $activeSheet->getStyle('F'.$avgECPMAdtypeLabel.':H'.$avgECPMAdtypeLabel)->getFont()->setBold(true)->setName('Arial')->setSize(6);
    $activeSheet->getStyle('G'.$avgECPMAdtypeLabel)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    /*
     * end Plan Statistics
     */
}

$activeSheet->setCellValue('A'.$mediaPriceRow, MSG_MEDIA_PRICE);
$activeSheet->setCellValue('C'.$mediaPriceRow, 'Baht');
$activeSheet->getStyle('A'.$mediaPriceRow.':C'.$mediaPriceRow)->getFont()->setBold(true)->setName('Arial')->setSize(6);
$activeSheet->getStyle('B'.$mediaPriceRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


$activeSheet->setCellValue('A'.$discountRow, MSG_DISCOUNT);
$activeSheet->setCellValue('B'.$discountRow, $discount);
$activeSheet->setCellValue('C'.$discountRow, '%');
$activeSheet->getStyle('A'.$discountRow.':C'.$discountRow)->getFont()->setItalic(true)->setColor($RedColor)->setName('Arial')->setSize(6);
$activeSheet->getStyle('B'.$discountRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$activeSheet->setCellValue('A'.$netRow, MSG_S_NET_RECEIVED);
$activeSheet->setCellValue('C'.$netRow, MSG_BAHT);
$activeSheet->getStyle('A'.$netRow.':C'.$netRow)->getFont()->setBold(true)->setName('Arial')->setSize(5.5);
$activeSheet->getStyle('B'.$netRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$activeSheet->setCellValue('A'.$mediaCostRow, MSG_S_MEDIA_COST);
$activeSheet->setCellValue('C'.$mediaCostRow, MSG_BAHT);
$activeSheet->getStyle('A'.$mediaCostRow.':C'.$mediaCostRow)->getFont()->setBold(true)->setName('Arial')->setSize(5.5);
$activeSheet->getStyle('B'.$mediaCostRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$activeSheet->setCellValue('A'.$rebateRow, MSG_REBATE);
$activeSheet->setCellValue('C'.$rebateRow, MSG_BAHT);
$activeSheet->getStyle('A'.$rebateRow.':C'.$rebateRow)->getFont()->setBold(true)->setName('Arial')->setSize(5.5);
$activeSheet->getStyle('B'.$rebateRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$activeSheet->setCellValue('A'.$productionValueRow, MSG_PRODUCTION_VALUE);
$activeSheet->setCellValue('C'.$productionValueRow, MSG_BAHT);
$activeSheet->getStyle('A'.$productionValueRow.':C'.$productionValueRow)->getFont()->setBold(true)->setName('Arial')->setSize(5.5);
$activeSheet->getStyle('B'.$productionValueRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$activeSheet->setCellValue('A'.$techCostRow, MSG_TECH_COST);
$activeSheet->setCellValue('C'.$techCostRow, MSG_BAHT);
$activeSheet->getStyle('A'.$techCostRow.':C'.$techCostRow)->getFont()->setBold(true)->setName('Arial')->setSize(5.5);
$activeSheet->getStyle('B'.$techCostRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$activeSheet->setCellValue('A'.$totalCostRow, MSG_S_TOTAL_MEDIA_COST);
$activeSheet->setCellValue('C'.$totalCostRow, MSG_BAHT);
$activeSheet->getStyle('A'.$totalCostRow.':C'.$totalCostRow)->getFont()->setBold(true)->setColor($RedColor)->setName('Arial')->setSize(6);
$activeSheet->getStyle('B'.$totalCostRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$activeSheet->setCellValue('A'.$marginRow, MSG_S_NET_MARGIN);
$activeSheet->getStyle('B'.$marginRow)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$activeSheet->getStyle('B'.$marginRow)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
$activeSheet->getStyle('B'.$marginRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
$activeSheet->setCellValue('C'.$marginRow, MSG_BAHT);
$activeSheet->getStyle('A'.$marginRow.':C'.$marginRow)->getFont()->setBold(true)->setName('Arial')->setSize(6);
$activeSheet->getStyle('B'.$marginRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$activeSheet->setCellValue('A'.$percentRow, '=');
$activeSheet->setCellValue('C'.$percentRow, '%');
$activeSheet->getStyle('A'.$percentRow.':C'.$percentRow)->getFont()->setBold(true)->setName('Arial')->setSize(6);
$activeSheet->getStyle('A'.$percentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$activeSheet->getStyle('B'.$percentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$activeSheet->getStyle('B'.$percentRow)->getNumberFormat()->setFormatCode("0.00");

array_push($aNoneBorder,
        'A'.$summaryRow,'H'.$summaryRow,'I'.$summaryRow,'J'.$summaryRow,'K'.$summaryRow,
        'A'.$mediaPriceRow,'B'.$mediaPriceRow,'C'.$mediaPriceRow,
        'A'.$discountRow,'B'.$discountRow,'C'.$discountRow,
        'A'.$netRow,'B'.$netRow,'C'.$netRow,
        'A'.$totalCostRow,'B'.$totalCostRow,'C'.$totalCostRow,
        'A'.$marginRow,'C'.$marginRow,
        'A'.$percentRow,'B'.$percentRow,'C'.$percentRow
        );

if($type=='pdf'){
    foreach ($aNoneBorder as $value){
            $activeSheet->getStyle($value)->getBorders()->getOutline()->setBorderStyle( PHPExcel_Style_Border::BORDER_NONE)->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
    }
}

// Rename worksheet
$activeSheet->setTitle(wrap_str($client.'-'.$brand, 28));

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$filename = $client.'-'.$brand;
if($type=='excel'){
    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
}elseif($type=='pdf'){
    $activeSheet->setShowGridLines(FALSE);
    $activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
    $activeSheet->getPageMargins()->setTop(.1);
    $activeSheet->getPageMargins()->setBottom(.1);
    $activeSheet->getPageMargins()->setLeft(0.3);
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline;filename="'.$filename.'.pdf"');
    //header('Content-Disposition: attachment;filename="'.$client.'-'.$brand.'.pdf"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
}
$objWriter->save('php://output');

exit;