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
    require_once MAX_PATH.'Includes/libs/excel/Classes/PHPExcel.php';
    $mdb2 = connectDB();
    $type = 'excel';
    
    $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);
    
    $rsInput = $mdb2->mdb2->queryAll("select ss.section_slot_id, ssa.id slot_adtype, ssa.ad_type_id 
                , lower(REPLACE(REPLACE(REPLACE(REPLACE(w.url, 'www.', ''),'https://',''),'http://',''),'/','')) website
                  ,dt3.data_type_name section_name
                ,ss.section_slot_name 
                ,dt1.data_type_name ads_size
                ,lower(REPLACE(dt2.data_type_name, ' ', '_')) ad_type
                ,ssa.net_to_web, ssa.price_model_type, ssa.slot_rotate, ssa.period, time_period_impressions
                ,ss.slot_deployment,ss.payment_term
               from 
               website w
               join website_section ws on ws.website_id = w.website_id and w.is_delete = 'N' 
                and w.is_active = 'Y' and ws.is_delete = 'N' and ws.is_active = 'Y' and ws.data_layer='SYNE'
               join section_slot_group ssg on ssg.website_section_id = ws.website_section_id 
               join section_slot ss on ssg.section_slot_id = ss.section_slot_id and ss.is_delete = 'N' and ss.is_active = 'Y' and ss.data_layer='SYNE'
               join define_data_type dt1 on ss.slot_size_id = dt1.data_type_id and dt1.ref_data_type='SECTION_SLOT_SIZE' and dt1.is_delete='N'
               join sectionslot_adtype ssa on ssa.section_slot_id = ss.section_slot_id
               join define_data_type dt2 on ssa.ad_type_id = dt2.data_type_id and dt2.ref_data_type='SLOT_AD_TYPE' and dt2.is_delete='N'
               join define_data_type dt3 on ws.section_id = dt3.data_type_id and dt3.ref_data_type='WEB_SECTION_NAME' and dt2.is_delete='N'
							order by website_name, section_name, ads_size");
    
    
    $rsAdType = $mdb2->mdb2->queryAll("SELECT lower(REPLACE(dt2.data_type_name, ' ', '_')) ad_type
                                from define_data_type dt2
                                where dt2.ref_data_type='SLOT_AD_TYPE' and dt2.is_delete='N'
                                ");
    
    $rsComp = $mdb2->mdb2->queryAll("SELECT lower(REPLACE(REPLACE(REPLACE(REPLACE(c.website, 'www.', ''),'https://',''),'http://',''),'/','')) website,
                            c.flash,c.vdo,c.expandable,c.pushdown,c.sidekick,c.full_page_expand,c.slider,c.billboard,c.tvspot
                            FROM media_plan_price_compare AS c");
    
    $aComp = array();
    foreach ($rsComp as $rComp) {
        $aComp[$rComp['website']] = $rComp;
    }
    
    $aAdType = array();
    foreach ($rsAdType as $rAdType) {
        $aAdType[$rAdType['ad_type']] = 0;
    }
    
    $aWebsites = array();
    
    
    foreach ($rsInput as $rInput) {
        if(empty($aWebsites[$rInput['website']][$rInput['section_name']][$rInput['section_slot_name']])){
            $aWebsites[$rInput['website']][$rInput['section_name']][$rInput['section_slot_name']] = $aAdType;
        }
        $aWebsites[$rInput['website']][$rInput['section_name']][$rInput['section_slot_name']][$rInput['ad_type']] = $rInput['net_to_web']; 
    }
    

    
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

    $objPHPExcel->setActiveSheetIndex(0);
    
    $exRow = 1;
    $activeSheet->setCellValue('A'.$exRow, 'Website');
    $activeSheet->setCellValue('B'.$exRow, 'Section');
    $activeSheet->setCellValue('C'.$exRow, 'Slot');
    $c = 4;
    foreach ($aAdType as $adtype => $net_to_web) {
        $excel_column = getNameFromNumber($c++);
        $activeSheet->setCellValue($excel_column.$exRow, $adtype);
    }
                
    $exRow++;
    foreach ($aWebsites as $website => $sections) {
        foreach ($sections as $section_name => $slots) {
           
            foreach ($slots as $slot_name => $adtypes) {
                $activeSheet->setCellValue('A'.$exRow, $website);
                $activeSheet->setCellValue('B'.$exRow, $section_name);
                $activeSheet->setCellValue('C'.$exRow, $slot_name);
                 $c = 4;
                foreach ($adtypes as $adtype => $net_to_web) {
                    $c_net_to_web = 0;
                    $excel_column = getNameFromNumber($c++);
                    
                    if (array_key_exists($website, $aComp)) {
                        $rComp = $aComp[$website];
                        $c_net_to_web = $rComp[$adtype];
                        $net_to_web = str_replace(',','',(empty($net_to_web)?0:$net_to_web ));
                        $c_net_to_web = str_replace(',','',(empty($c_net_to_web)?0:$c_net_to_web ));
                        //unset($aComp[$website]);
                        
                        if($c_net_to_web!=$net_to_web){
                            $activeSheet->getStyle($excel_column.$exRow)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));
                            $activeSheet->setCellValue($excel_column.$exRow, $net_to_web.'-'.$c_net_to_web);
                        }else{
                            $activeSheet->getStyle($excel_column.$exRow)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
                            $activeSheet->setCellValue($excel_column.$exRow, $net_to_web);
                        }
                        
                    }else{
                        //not found website
                        $activeSheet->getStyle($excel_column.$exRow)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_YELLOW));
                        $activeSheet->setCellValue($excel_column.$exRow, $net_to_web);
                    }
                }
                $exRow++;
            }
        }
        
        if (array_key_exists($website, $aComp)) 
            unset($aComp[$website]);
        
    }
    
    $exRow++;
    $exRow++;
    $activeSheet->setCellValue('A'.$exRow, 'เว็บไซด์ที่ยังไม่ถูกป้อนเข้าระบบ');
    $exRow++;
    foreach ($aComp as $website => $value) {
        $activeSheet->getStyle('A'.$exRow)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));
        $activeSheet->setCellValue('A'.$exRow, $website);
        $exRow++;
    }
    

    if($type=='excel'){
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="compare_price.xlsx"');
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
        header('Content-Disposition: inline;filename="compare_price.pdf"');
        //header('Content-Disposition: attachment;filename="'.$client.'-'.$brand.'.pdf"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
    }
    $objWriter->save('php://output');

exit;