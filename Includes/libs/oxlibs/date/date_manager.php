<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'DaySpan.php';
require_once MAX_PATH . 'Includes/libs/oxlibs/function.util.php';
require_once MAX_PATH . 'Includes/libs/oxlibs/class.util.php';
class DateManager
{
    private $_name = "period";
    private $_value = "";

    private static $month_th_long = array ( 1=>'มกราคม',2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
                                5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฏาคม', 8 => 'สิงหาคม',
                                9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม');
    private static $month_th_short = array ( 1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
                                5 => 'พ.ค.',6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
                                9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.');
    private static $month_en_long = array (1=>'January', 2=>'February', 3=>'March', 4=>'April',5=>'May',6=>'June',
       7=>'July', 8=>'August', 9=>'September',10=> 'October', 11=>'November', 12=>'December');
    
    private static $month_en_short = array (1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May',6=> 'Jun',
        7=> 'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec');

    private static $month_th_sel = array ('มกราคม','กุมภาพันธ์','มีนาคม','เมษายน',
                                            'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม',
                                            'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');

    private static $month_en_sel = array ('January', 'February', 'March', 'April', 'May', 'June',
                                            'July', 'August', 'September', 'October', 'November', 'December');

    private static $dayofweek_th_long = array('อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์');
    private static $dayofweek_en_short = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
    public static $dayofweek_en_long = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    private $language = "";
    private $date;

    public function __construct($date=null) {
        global $session;
        $this->language = $session["user"]->language;
        $this->date = $date;
        $this->_value = new OA_Admin_DaySpan();

    }

    public function getDayOfWeek($format='S'){
        $day = $this->date->getDayOfWeek();
        $dayofweek_long = "dayofweek_{$_SESSION['language']}_long";
        $dayofweek_short = "dayofweek_{$_SESSION['language']}_short";
        if($format=='S')
            return   self::${$dayofweek_short}[$day];
        else
            return  self::${$dayofweek_long}[$day];
    }

    public function getDayOfWeekEn($format='S'){
        $day = $this->date->getDayOfWeek();
        $dayofweek_long = "dayofweek_en_long";
        $dayofweek_short = "dayofweek_en_short";
         if($format=='S')
            return   self::${$dayofweek_short}[$day];
        else
            return  self::${$dayofweek_long}[$day];
    }

    public function getDayOfWeekIndex($index, $format='S'){
        $dayofweek_long = "dayofweek_{$_SESSION['language']}_long";
        $dayofweek_short = "dayofweek_{$_SESSION['language']}_short";
        if($format=='S')
            return   self::${$dayofweek_short}[$index];
        else
            return  self::${$dayofweek_long}[$index];
    }
    
    public function dmmyyToyymmdd($strDate)
    {
        $arr = explode("/", $strDate);
        if(count($arr)<>3)
            return false;
        else
        {
            $day = sprintf("%02d",$arr[0]);
            $month = sprintf("%02d",$arr[1]);
            return "{$arr[2]}-{$month}-{$day}";
        }
    }
    
    public function d_Lmm_yyToyymmddHMS($strDate)
    {        
        $arr = explode(" ", $strDate);
        if(count($arr)<>4)
            return false;
        else
        {
            $month_long = "month_{$_SESSION['language']}_long";
            $month_short = "month_{$_SESSION['language']}_short";
            $month = 0;
            if(array_search($arr[1], self::${$month_long})){
                $month = array_search($arr[1], self::${$month_long});
            }else if(array_search($arr[1], self::${$month_short})){
                $month = array_search($arr[1], self::${$month_short});
            }
            $day = sprintf("%02d",$arr[0]);
            $month = sprintf("%02d", $month);
            return "{$arr[2]}-{$month}-{$day} {$arr[3]}";
        }
    }
    public function d_Lmm_yyToyymmdd($strDate)
    {
        $arr = explode(" ", $strDate);
        if(count($arr)<>3)
            return false;
        else
        {
            $month_long = "month_{$_SESSION['language']}_long";
            $month_short = "month_{$_SESSION['language']}_short";
            $month = 0;
            if(array_search($arr[1], self::${$month_long})){
                $month = array_search($arr[1], self::${$month_long});
            }else if(array_search($arr[1], self::${$month_short})){
                $month = array_search($arr[1], self::${$month_short});
            }
            $day = sprintf("%02d",$arr[0]);
            $month = sprintf("%02d", $month);
            return "{$arr[2]}-{$month}-{$day}";
        }
    }

    public function yymmddTod_Lmm_yy($strDate)
    {
        $arr = explode("-", $strDate);
        if(count($arr)<>3)
            return false;
        else
        {
            $month_long = "month_{$_SESSION['language']}_long";
            $month = self::${$month_long};
            $month = $month[(int)$arr[1]];
            return "{$arr[2]} {$month} {$arr[0]}";
        }
    }

    public function getMonthThLongIndex($index)
    {
        return self::$month_th_long[(int)$index];
    }
    
    public function getMonthEnLongIndex($index)
    {
        return self::$month_en_long[(int)$index];
    }

    public function getMonthLong(){
        global $session;
        $month = "month_{$session['user']->language}_long";
        return DateManager::${$month};
    }

    public function getMonthSel(){
        global $session;
        $month = "month_{$session['user']->language}_sel";
        return DateManager::${$month};
    }

    public function getMonthLongIndex($index){
        global $session;
        $month = "month_{$session['user']->language}_long";
        return DateManager::${$month}[$index];
    }

    public function getMonthThShortIndex($index)
    {
        return self::$month_th_short[(int)$index];
    }

    public function getMonthEnShortIndex($index)
    {
        return self::$month_en_short[(int)$index];
    }

    public static function  getSelectionDateNames()
    {
        return array(
            'today'       => $GLOBALS['strCollectedToday'],
            'yesterday'   => $GLOBALS['strCollectedYesterday'],
            'this_week'   => $GLOBALS['strCollectedThisWeek'],
            'last_week'   => $GLOBALS['strCollectedLastWeek'],
            'last_7_days' => $GLOBALS['strCollectedLast7Days'],
            'this_month'  => $GLOBALS['strCollectedThisMonth'],
            'last_month'  => $GLOBALS['strCollectedLastMonth'],
            'all_stats'   => $GLOBALS['strCollectedAllStats'],
            'specific'    => $GLOBALS['strCollectedSpecificDates']
          );
    }
    
    public static function  getSelectionDateNamesSpecify()
    {
        return array(
            'today'       => $GLOBALS['strCollectedToday'],
            'yesterday'   => $GLOBALS['strCollectedYesterday'],
            'this_week'   => $GLOBALS['strCollectedThisWeek'],
            'last_week'   => $GLOBALS['strCollectedLastWeek'],
            'last_7_days' => $GLOBALS['strCollectedLast7Days'],
            'this_month'  => $GLOBALS['strCollectedThisMonth'],
            'last_month'  => $GLOBALS['strCollectedLastMonth'],
            'specific'    => $GLOBALS['strCollectedSpecificDates']
          );
    }

    public static function  getSelectionDateNamesUV()
    {
        return array(
            'yesterday'   => $GLOBALS['strCollectedYesterday'],
            'this_week'   => $GLOBALS['strCollectedThisWeek'],
            'last_week'   => $GLOBALS['strCollectedLastWeek'],
            'last_7_days' => $GLOBALS['strCollectedLast7Days'],
            'this_month'  => $GLOBALS['strCollectedThisMonth'],
            'last_month'  => $GLOBALS['strCollectedLastMonth'],
            'all_stats'   => $GLOBALS['strCollectedAllStats'],
            'specific'    => $GLOBALS['strCollectedSpecificDates']
          );
    }

    /**
     * A method that retrieves the start date of this field's OA_Admin_DaySpan.
     *
     * @return Date the start date of the this field.
     */
    function getStartDate()
    {
        $oDaySpan = $this->_value;
        $value = is_null($oDaySpan) ? null : $oDaySpan->getStartDate();
        return $value;
    }

    /**
     * A method that retrieves the end date of this field's OA_Admin_DaySpan.
     *
     * @return Date the end date of the this field.
     */
    function getEndDate()
    {
        $oDaySpan = $this->_value;
        $value = is_null($oDaySpan) ? null : $oDaySpan->getEndDate();
        return $value;
    }

    function display_preriod()
    {
        $oStartDate = $this->getStartDate();
        $startDateStr = is_null($oStartDate) ? '' : $oStartDate->format('%d %B %Y ');
        $oEndDate = $this->getEndDate();
        $endDateStr = is_null($oEndDate) ? '' : $oEndDate->format('%d %B %Y');
        $period_preset_js = "";
        $period_preset_js =  "
<script type='text/javascript'>

jQuery(document).ready(function(){
        
        function {$this->_name}FormChange(bAutoSubmit)
        {            
            var {$this->_name}TabIndex = 2;

            var {$this->_name}SelectName = $('#{$this->_name}_preset').val();
  
            var specific = {$this->_name}SelectName == 'specific';";

        $oTmpDaySpan = new OA_Admin_DaySpan();
        $fieldSelectionNames = self::getSelectionDateNames();
        foreach ($fieldSelectionNames as $v => $n) {
            if ($v != 'specific') {
                if ($v != 'all_stats') {
                    $oTmpDaySpan->setSpanPresetValue($v);
                    $oTmpStartDate = $oTmpDaySpan->getStartDate();
                    $sTmpStartDate = $oTmpStartDate->format('%d %B %Y');
                    $oTmpEndDate   = $oTmpDaySpan->getEndDate();
                    $sTmpEndDate   = $oTmpEndDate->format('%d %B %Y');
                } else {
                    $sTmpStartDate = '';
                    $sTmpEndDate   = '';
                }
                $period_preset_js .= "
            if ({$this->_name}SelectName == '$v') {
                $('#{$this->_name}_start').val('$sTmpStartDate');
                $('#{$this->_name}_end').val('$sTmpEndDate');
            }
                ";
            }
        }

        $period_preset_js .= "

            $('#{$this->_name}_start').datepicker((specific ? 'enable' : 'disable')  );
            $('#{$this->_name}_end').datepicker((specific ? 'enable' : 'disable')  );
            if (!specific) {
                $('#{$this->_name}_start').css('background-color', '#CCCCCC');
                $('#{$this->_name}_end').css('background-color', '#CCCCCC');
                $('#{$this->_name}_start').attr('tabIndex', null);
                $('#{$this->_name}_end').attr('tabIndex', null);

                $('.ui-datepicker-trigger').attr('tabIndex', null);
            } else {
                $('#{$this->_name}_start').css('background-color', '#FFFFFF');
                $('#{$this->_name}_end').css('background-color', '#FFFFFF');
           
                $('#{$this->_name}_start').attr('tabIndex', {$this->_name}TabIndex);
                $('#{$this->_name}_end').attr('tabIndex', {$this->_name}TabIndex + 2);
                $('.ui-datepicker-trigger').attr('tabIndex', {$this->_name}TabIndex + 3);
            }
       
        }
        {$this->_name}FormChange(0);

               
            $('#{$this->_name}_preset').bind('change', {$this->_name}FormChange);
            $('#{$this->_name}_start').datepicker('disable');
            $('#{$this->_name}_end').datepicker('disable');
            
        });
        </script>";

        return $period_preset_js;
    }


    function setStartDate(&$oDate)
    {
        if (is_a($oDate, 'date')) {
            $oDate->setHour(0);
            $oDate->setMinute(0);
            $oDate->setSecond(0);
            $oDate->subtractSeconds(25200);
        }
    }

    /**
     * A private method to set a PEAR::Date object to have the time set to
     * 23:59:59, where the date is at the end of a day.
     *
     * @param PEAR::Date $oDate The date to "round".
     * @return void
     */
    function setEndDate(&$oDate)
    {
        if (is_a($oDate, 'date')) {
            $oDate->setHour(23);
            $oDate->setMinute(59);
            $oDate->setSecond(59);
            $oDate->subtractSeconds(25200);
        }
    }
}



?>
