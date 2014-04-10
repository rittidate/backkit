<?php
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
include_once MAX_PATH. "ajax/libs/grid.php";
$mdb2 = connectDB();


class Kt_Order_Report extends Pager
{
    public function __construct() {
        //parent::__construct();
    }

    private function _print($where="", $datatype=null, $data=null){
        global $mdb2;
        /*
        $this->getPagers(" SELECT count(*) as count
                            FROM [pf]kt_order AS tb"
                .$where, $datatype, $data, DNS_RP);
                */
        $SQL = " SELECT tb.*, ship.data_type_name as shipment, pay.data_type_name as payment,ord.data_type_name as order_status,pay.id as paymentid
	                FROM [pf]kt_order AS tb
                        join kt_define_data_type as ship on (ship.id = tb.shipment_id)
                        join kt_define_data_type as pay on (pay.id = tb.payment_id)
                        join kt_define_data_type as ord on (ord.id = tb.order_status)
        $where ORDER BY tb.id";
        $result =& $mdb2->query($SQL, $datatype, $data);
        // constructing a JSON
        //$response->page = $this->page;
        //$response->total = $this->total_pages;
        //$response->records = $this->count;
        $response->payback = getPaybakPercent();
        $i=0;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->id;
            $response->rows[$i]['shipprice'] = $row->shipprice;
            $response->rows[$i]['subtotal'] = $row->subtotal;
            //$createdate = new Date($row->date_created);
            //$updatedate = new Date($row->date_last_login);

            $activeClass = 'btnAction';
            $activeTitle = 'Active';
            if($row->is_active == 'Y'){
                $activeClass = 'activestatus';
                $activeTitle = 'Inactive';
            }
            $orderDate = new DateTime($row->order_date);

            $response->rows[$i]['cell']=array($row->id, $orderDate->format('d/m/y H:i'), $row->firstname, $row->shipment, $row->payment, $row->shipprice, $row->subtotal);
            $response->pays[$i]['pay'] = $row->paymentid;
            $i++;
        }
        return $response;
    }


    public function search(){
        $cond = '';
        $period_start = trim($_REQUEST["period_start"]);
        $period_end = trim($_REQUEST["period_end"]);
        
//        $customerid= $_REQUEST["customerid"];
//        if($customerid !== NULL)
//        {
//            $param = "?";
//            $datatype[] = DBTYPE_INT;
//            $dataoftype[] = $customerid;
//
//            $cond .= " and tb.customer_id = ($param)";
//        }

        if(!( $period_start=="" || $period_end=="") && empty($_REQUEST["keyword"]) )
        {
                $ymd = DateManager::d_Lmm_yyToyymmdd($period_start);
                $d_search = new Date($ymd);
                $ymd = DateManager::d_Lmm_yyToyymmdd($period_end);
                $d_search2 = new Date($ymd);
                $cond .= "  and tb.order_date >= '{$d_search->getDate()}' and tb.order_date <= '{$d_search2->getDate()}' ";

         }

        if(!empty($_REQUEST["keyword"])){
              $keyword = trim($_REQUEST['keyword']);
              $cond .= "and ( lower(ifnull(tb.firstname,'')) like lower(?)  ) ";
           	  $cond .= "or ( lower(ifnull(tb.lastname,'')) like lower(?)  ) ";
              $cond .= "or ( lower(ifnull(tb.state,'')) like lower(?)  ) ";
			  $cond .= "or ( lower(ifnull(tb.telephone,'')) like lower(?)  ) ";
			  $cond .= "or ( lower(ifnull(tb.mobile,'')) like lower(?)  ) ";
			  $cond .= "or ( lower(ifnull(tb.fax,'')) like lower(?)  ) ";
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
        }

        $where = " WHERE tb.is_delete='N' and tb.order_status >= 12 and tb.order_status <= 13 ". $cond;

        return $this->_print($where, $datatype, $dataoftype);
    }

    public function get(){
        return $this->search();
    }

    public function loadData(){
        global $mdb2;
        extract($_REQUEST);
        $txt_fields = $GLOBALS["{$this->tb}_txt_fields"];
        $data = array();

        foreach ($txt_fields as $field) {
            $data[$field] = '';
        }

        $data['is_active'] = 'Y';

        if(!empty($pk_id)){
            $result = $mdb2->query("select * from [pf]{$this->tb} where {$this->pk}=$pk_id");
            $row = $result->fetchRow();
            foreach ($row as $key=>$value){
                $data[$key] = $value;
            }
        }
        return $data;
    }

    public function add()
    {
        global $mdb2;
        global $session;
        
        $subtotal = $_REQUEST['subtotal'];
        $shipprice = $_REQUEST['shipprice'];
        $grandtotal = $_REQUEST['grandtotal'];

        $address1 = $_REQUEST['address1'];
        $address2 = $_REQUEST['address2'];
        $address3 = $_REQUEST['address3'];
        $address4 = $_REQUEST['address4'];
        $city = $_REQUEST['city'];
        $country = $_REQUEST['country'];
        $email = $_REQUEST['email'];
        $fax = $_REQUEST['fax'];
        $firstname = $_REQUEST['firstname'];
        $middlename = $_REQUEST['middlename'];
        $lastname = $_REQUEST['lastname'];
        $mobile = $_REQUEST['mobile'];
        $name = $_REQUEST['name'];
        $state = $_REQUEST['state'];
        $telephone = $_REQUEST['telephone'];
        $zipcode = $_REQUEST['zipcode'];
        $order_status = $_REQUEST['order_status'];
        $payment_id = $_REQUEST['payment_id'];
        $shipment_id = $_REQUEST['shipment_id'];
        $is_active = $_REQUEST['is_active'];

        $ip = getRealIpAddr();
        $now = $this->getCurDate();
        
        $json = json_decode($_REQUEST['json']);
        
        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->subtotal = $subtotal;
        $tb->shipprice = $shipprice;
        $tb->grandtotal = $grandtotal;
        $tb->address1 = $address1;
        $tb->address2 = $address2;
        $tb->address3 = $address3;
        $tb->address4 = $address4;
        $tb->city = $city;
        $tb->country = $country;
        $tb->description = $description;
        $tb->email = $email;
        $tb->fax = $fax;
        $tb->firstname = $firstname;
        $tb->middlename = $middlename;
        $tb->lastname = $lastname;
        $tb->mobile = $mobile;
        $tb->name = $name;
        $tb->state = $state;
        $tb->telephone = $telephone;
        $tb->zipcode = $zipcode;
        $tb->order_status = $order_status;
        $tb->payment_id = $payment_id;
        $tb->shipment_id = $shipment_id;
        $tb->is_active = $is_active;
        $tb->order_date = $now;
        $this->setCreated();
        $id = $tb->insert();


        
        foreach ($json as $value){
            $sumweight = $value->qty * $value->weight;
            $sumtotal = $value->qty * $value->price;
            $volumn = empty($value->volumn)? 0: $value->volumn;
            $SQL = "INSERT INTO [pf]kt_orderdetail (order_id,pid,name_en,name_th, volumn, unit, price, qty, sumtotal, weight, sumweight, pack_id) VALUES
                    ({$id},{$value->pid},'{$value->name_en}', '{$value->name_th}', '{$volumn}', '{$value->unit}', '{$value->price}', '{$value->qty}', '{$sumtotal}', '{$value->weight}', '{$sumweight}', 0 )
                    ON DUPLICATE KEY UPDATE qty='{$value->qty}' , sumtotal='{$value->sumtotal}'";
            $mdb2->execute($SQL);
        }

        $response['error'] = '';
        $response['id'] = $id;
        return $response;
    }

    public function edit()
    {
        global $mdb2;
        global $session;

        $id = $_REQUEST['id'];
        $subtotal = $_REQUEST['subtotal'];
        $shipprice = $_REQUEST['shipprice'];
        $grandtotal = $_REQUEST['grandtotal'];
        
        $address1 = $_REQUEST['address1'];
        $address2 = $_REQUEST['address2'];
        $address3 = $_REQUEST['address3'];
        $address4 = $_REQUEST['address4'];
        $city = $_REQUEST['city'];
        $country = $_REQUEST['country'];
        $email = $_REQUEST['email'];
        $fax = $_REQUEST['fax'];
        $fax_ext = $_REQUEST['fax_ext'];
        $firstname = $_REQUEST['firstname'];
        $middlename = $_REQUEST['middlename'];
        $lastname = $_REQUEST['lastname'];
        $mobile = $_REQUEST['mobile'];
        $name = $_REQUEST['name'];
        $state = $_REQUEST['state'];
        $telephone = $_REQUEST['telephone'];
        $telephone_ext = $_REQUEST['telephone_ext'];
        $zipcode = $_REQUEST['zipcode'];
        $order_status = $_REQUEST['order_status'];
        $payment_id = $_REQUEST['payment_id'];
        $shipment_id = $_REQUEST['shipment_id'];
        $is_active = $_REQUEST['is_active'];
        $postcode = $_REQUEST['postcode'];
        $ip = getRealIpAddr();
        $now = new Date();

        $json = json_decode($_REQUEST['json']);

        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->subtotal = $subtotal;
        $tb->shipprice = $shipprice;
        $tb->grandtotal = $grandtotal;
        $tb->address1 = $address1;
        $tb->address2 = $address2;
        $tb->address3 = $address3;
        $tb->address4 = $address4;
        $tb->city = $city;
        $tb->country = $country;
        $tb->description = $description;
        //$tb->email = $email;
        $tb->fax = $fax;
        $tb->fax_ext = $fax_ext;
        $tb->firstname = $firstname;
        $tb->middlename = $middlename;
        $tb->lastname = $lastname;
        $tb->mobile = $mobile;
        $tb->name = $name;
        $tb->state = $state;
        $tb->telephone = $telephone;
        $tb->telephone_ext = $telephone_ext;
        $tb->zipcode = $zipcode;
        $tb->postcode = $postcode;
        $tb->order_status = $order_status;
        $tb->payment_id = $payment_id;
        $tb->shipment_id = $shipment_id;
        $tb->is_active = $is_active;
        $tb->get($this->pk, $id);
        $this->setUpdated();
        $tb->update();
        
        $SQL = "UPDATE [pf]kt_orderdetail set qty='0' , sumtotal='0' WHERE order_id = {$id}";
        $mdb2->query($SQL);

        $aDetail = array(
                            'firstname' =>$firstname,
                            'lastname' =>$lastname,
                            'address1' =>$address1,
                            'address2' =>$address2,
                            'address3' =>$address3,
                            'address4' =>$address4,
                            'city' => $city,
                            'state' => $state,
                            'country' => $country,
                            'zipcode' => $zipcode,
                            'mobile' =>$mobile,
                            'telephone' =>$telephone,
                            'telephone_ext' =>$telephone_ext,
                            'fax' =>$fax,
                            'fax_ext' =>$fax_ext,
                            'email' =>$email,
                            'payment_id' =>$payment_id,
                            'shipment_id' =>$shipment_id,
                            'subtotal' =>$subtotal,
                            'shipprice' =>$shipprice,
                            'grandtotal' =>$grandtotal,
                            );

        $this->checkEmailOrderStatus($order_status, $id, $email, $aDetail, $json);
        
        foreach ($json as $value){
            
            $sumweight = $value->qty * $value->weight;
            $sumtotal = $value->qty * $value->price;
            $volumn = empty($value->volumn)? 0: $value->volumn;
            $SQL = "INSERT INTO [pf]kt_orderdetail (order_id,pid,name_en,name_th, volumn, unit, price, qty, sumtotal, weight, sumweight, pack_id) VALUES
                    ({$id},{$value->pid},'{$value->name_en}', '{$value->name_th}', '{$volumn}', '{$value->unit}', '{$value->price}', '{$value->qty}', '{$sumtotal}', '{$value->weight}', '{$sumweight}', 0 )
                    ON DUPLICATE KEY UPDATE qty='{$value->qty}' , sumtotal='{$value->sumtotal}'";
            $mdb2->execute($SQL);
        }
        //$tbs = &$mdb2->get_factory("[pf]kt_orderdetail");

        $response['error'] = '';
        $response['id'] = $id;
        return $response;
    }

    public function checkEmailOrderStatus($order_status, $id, $email, $aDetail = array(), $json = array()){
        switch ($order_status)
        {
            case 10:
                $status = "Order Start";
                if($this->checkOrderStatusNotExist($id, $order_status)){
                    $this->emailSentOrderStart($id, $email, $aDetail, $json);
                }
                break;
            case 11:
                $status = "Wait for check payment";
                if($this->checkOrderStatusNotExist($id, $order_status)){
                    //$this->emailSentWaitPayment($id, $email);
                }
                break;
            case 12:
                $status = "On process";
                if($this->checkOrderStatusNotExist($id, $order_status)){
                    $this->emailSentOnProcess($id, $email, $json);
                }
                break;
            case 13:
                $status = "Sented";
                if($this->checkOrderStatusNotExist($id, $order_status) && !empty($postcode)){
                    $this->emailSentSented($id, $email, $json, $postcode);
                }
                break;
            case 14:
                $status = "Cancel";
                if($this->checkOrderStatusNotExist($id, $order_status)){
                    $this->emailSentCancel($id, $email, $json);
                }
                break;
            case 15:
                $status = "Customer cancel";
                if($this->checkOrderStatusNotExist($id, $order_status)){
                    $this->emailSentCustomerCancel($id, $email, $json);
                }
                break;
            case 16:
                $status = "Order reject";
                if($this->checkOrderStatusNotExist($id, $order_status)){
                    $this->emailSentReject($id, $email);
                }
                break;
            case 17:
                //in future for reserve product
                $status = "Send Email to confirm order";
                if($this->checkOrderStatusNotExist($id, $order_status)){
                    //$this->emailSentConfirmOrder($id, $email);
                }
                break;
            default:
                
                break;
        }
    }

    public function checkOrderStatusNotExist($order_id, $order_status){
        global $mdb2;
        return !$mdb2->isHaveRow("select * from [pf]kt_order_status_email WHERE is_active='Y' and is_delete = 'N' and order_id = '{$order_id}' and order_status = '{$order_status}'");
    }

    public function sendEmailOrder($email, $cc = '', $subject = '', $message = ''){
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

            // Additional headers
            $headers .= 'From: [KIITIVATE.COM] <arraieot@gmail.com>' . "\r\n";
            if(!empty($cc)){
                $headers .= "Cc: ".$cc."\r\n";
            }
            
            // Mail it
            if(mail($email, $subject, $message, $headers)){
                return true;
            }else{
                return false;
            }
    }

    public function emailSentOrderStart($id, $email, $aDetail = array(), $json = array()){
        global $session;
        global $mdb2;
        
        $conf = $GLOBALS["CONF"];
        
        $orderid = $id;

        $label_address1 = MSG_ADDRESS1;
        $label_address2 = MSG_ADDRESS2;
        $label_address3 = MSG_ADDRESS3;
        $label_address4 = MSG_ADDRESS4;
        $label_city = MSG_CITY;
        $label_state = MSG_STATE;
        $label_country = MSG_COUNTRY;
        $label_zipcode = MSG_ZIPCODE;
        $label_mobile = MSG_MOBILE;
        $label_telephone = MSG_TELEPHONE;
        $label_ext = MSG_EXT;
        $label_fax = MSG_FAX;
        $label_email = STR_EMAIL;

        $plabel_barcode = MSG_BARCODE;
        $plabel_product = MSG_PRODUCT;
        $plabel_price = MSG_PRICE;
        $plabel_baht = MSG_BAHT;
        $plabel_qty = MSG_QTY;
        $plabel_total = MSG_TOTAL;
        $plabel_subtotal = MSG_SUMTOTAL;
        $plabel_shipprice = MSG_SHIP_PRICE;
        $plabel_grandtotal = STR_GRANTOTAL;
        $plabel_image = MSG_IMAGE;

        $email_header = MSG_EMAIL_HEADER_CONFIRM;
        $email_document = MSG_EMAIL_DOCUMENT;
        $email_payment_tell = MSG_EMAIL_PAYMENT_TELL;
        $email_end = MSG_EMAIL_END;

        $subject = $email_header.$orderid;
        $message = '';

        $message .= "<table><tbody>";
        $message .= "<tr><td>";
        $message .= $email_header.$orderid."<br><br>";
        $message .= $aDetail['firstname']." ".$aDetail['lastname']."<br>";

        $message .= $label_address1." ".$aDetail['address1']."<br>";
        $message .= $label_address2." ".$aDetail['address2']." ".$label_address3." ".$aDetail['address3']." ".$label_address4." ".$aDetail['address4']."<br>";
        $message .= $label_city." ".$aDetail['city']." ".$label_state." ".$aDetail['state']." ".$label_zipcode." ".$aDetail['zipcode']." ".$label_country." ".$aDetail['country']."<br>";
        $message .= $label_mobile." ".$aDetail['mobile']." ".$label_telephone." ".$aDetail['telephone']." ".$label_ext." ".$aDetail['telephone_ext']." ".$label_fax." ".$aDetail['fax']." ".$label_ext." ".$aDetail['fax_ext'];
        $message .= "</td></tr>";
        $message .= "</tbody></table>";
        $message .= "<br><br>";
        $message .= "<table class='Table'><thead><tr>";
        $message .= "<th>".$plabel_image."</th>";
        $message .= "<th>".$plabel_barcode."</th>";
        $message .= "<th>".$plabel_product."</th>";
        $message .= "<th>".$plabel_price."</th>";
        $message .= "<th style='width:20px;'>".$plabel_qty."</th>";
        $message .= "<th align='right'>".$plabel_total."</th>";
        $message .= "</tr></thead><tbody>";

        foreach($json as $value){
               $product = $mdb2->queryRow("select kp.id as pid, kp.barcode, kp.name_en, kp.name_th, kp.volumn, kdt.data_type_name as unittype, kp.unit as unit,  kp.price, kp.weight, kp.image
                        from kt_product as kp
                        left join kt_define_data_type as kdt on (kp.unit = kdt.id)
                        WHERE kp.id = {$value->pid}");

                if($session["user"]->language == "en"){
                        $productName = $product->name_en." ".$product->volumn." ".$product->unittype;
                }else{
                        $productName = $product->name_th." ".$product->volumn." ".$product->unittype;
                }
        if($product->image == ''){
                $image = $conf["link"]["image_prefix"].'no_image.jpg';
        }else{
                $image = $conf["link"]["image_s_prefix"].$product->image;
        }

                $message .= "<tr>";
                $message .= "<td><img alt='".$productName."' style='width: 60px; height: 40px;' src='".$image."'></td>";
                $message .= "<td>".$product->barcode."</th>";
                $message .= "<td>".$productName."</th>";
                $message .= "<td align='center'>".$value->price."</td>";
                $message .= "<td align='center' style='width:20px;'>".$value->qty."</td>";
                $message .= "<td align='right'>".$value->total." ".$plabel_baht."</td>";
                $message .= "</tr>";
        }

        $message .= "<tr>";
        $message .= "<td colspan='4' align='right'>".$plabel_subtotal."</td>";
        $message .= "<td colspan='2' align='right'>".$aDetail['subtotal']." ".$plabel_baht."</td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan='4' align='right'>".$plabel_shipprice."</td>";
        $message .= "<td colspan='2' align='right'>".$aDetail['shipprice']." ".$plabel_baht."</td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan='4' align='right'>".$plabel_grandtotal."</td>";
        $message .= "<td colspan='2' align='right'>".$aDetail['grandtotal']." ".$plabel_baht."</td>";
        $message .= "</tr>";
        $message .= "</tbody></table>";
        $message .= "<br><br>";
        $message .= "<a href='http://www.kittivate.com/excel/pdf/".$orderid."/".$aDetail['email']."'>".$email_document."</a>";
        $message .= "<br><br>";

        $payment = $mdb2->queryRow("SELECT * FROM kt_define_data_type WHERE id = '{$aDetail['payment_id']}'");
        $message .= $payment->description."<br><br>";
        $message .= $email_payment_tell;
        $message .= "<br><br>";
        $message .= $email_end;


        if($email !== "arraieot@gmail.com")
            $this->sendEmailOrder($email, 'arraieot@gmail.com', $subject, $message);
        else
            $this->sendEmailOrder($email, '', $subject, $message);
    }

    public function emailSentWaitPayment($id, $email){
        global $session;
        global $mdb2;
        $subject = '';
        $message = '';
        $this->sendEmailOrder($email, '', $subject, $message);
    }

    public function emailSentOnProcess($id, $email, $json = array()){
        global $session;
        global $mdb2;
        $email_header = MSG_EMAIL_HEADER_ONPROCESS;
		$email_content = MSG_EMAIL_CONTENT_ONPROCESS;
		$email_end = MSG_EMAIL_END;
        $subject = $email_header.$id;

        $message .= $email_header.$id."<br><br>";
		
        $message .= $email_content;
        $message .= "<br><br>";
        $message .= $email_end;

        $cSented = $mdb2->isHaveRow("select * from [pf]kt_order_status_email WHERE is_active='Y' and is_delete = 'N' and order_id = '{$id}' and order_status = '13'");
        if(!$cSented){
            foreach($json as $value){
                $SQL = "UPDATE [pf]kt_product_stock set pstock=if(pstock < $value->qty, 0, pstock - $value->qty) WHERE pid = '{$value->pid}'";
                $mdb2->query($SQL);
            }
        }
        $NOW = date('Y-m-d H:i:s');
		
        $SQL = "INSERT INTO [pf]kt_order_status_email (order_id,order_status,email_send,is_active, is_delete, create_date) VALUES
                    ({$id},'12','{$NOW}', 'Y', 'N', '{$NOW}')";
        $mdb2->query($SQL);
        $this->sendEmailOrder($email, '', $subject, $message);
    }

    public function emailSentSented($id, $email, $json = array(), $postCode = ''){
        global $session;
        global $mdb2;
        $email_header = MSG_EMAIL_HEADER_SENTED;
		$email_content = MSG_EMAIL_CONTENT_SENTED;
		$email_end = MSG_EMAIL_END;
        $subject = $email_header.$id;

        $message .= $email_header.$id."<br><br>";

        $message .= $email_content;
        $message .= "<br><br>";
        $message .= MSG_POST_CODE." : ";
        $message .= $postCode;
        $message .= "<br><br>";
        $message .= $email_end;

        $cProcess = $mdb2->isHaveRow("select * from [pf]kt_order_status_email WHERE is_active='Y' and is_delete = 'N' and order_id = '{$id}' and order_status = '12'");
        if(!$cProcess){
            foreach($json as $value){
                $SQL = "UPDATE [pf]kt_product_stock set pstock=if(pstock < $value->qty, 0, pstock - $value->qty) WHERE pid = '{$value->pid}'";
                $mdb2->query($SQL);
            }
        }
        $NOW = date('Y-m-d H:i:s');

        $SQL = "INSERT INTO [pf]kt_order_status_email (order_id,order_status,email_send,is_active, is_delete, create_date) VALUES
                    ({$id},'13','{$NOW}', 'Y', 'N', '{$NOW}')";
        $mdb2->query($SQL);
        $this->sendEmailOrder($email, '', $subject, $message);
    }

    public function emailSentCancel($id, $email, $json = array()){
        global $session;
        global $mdb2;
        $email_header = MSG_EMAIL_HEADER_CANCEL;
        $email_content = MSG_EMAIL_CONTENT_CANCEL;
        $email_end = MSG_EMAIL_END;
        $subject = $email_header.$id;

        $message .= $email_header.$id."<br><br>";

        $message .= $email_content;
        $message .= "<br><br>";
        $message .= $email_end;

        $cOrder = $mdb2->isHaveRow("select * from [pf]kt_order_status_email WHERE is_active='Y' and is_delete = 'N' and order_id = '{$id}' and order_status = '12' or order_status = '13'");
        $cCancel = $mdb2->isHaveRow("select * from [pf]kt_order_status_email WHERE is_active='Y' and is_delete = 'N' and order_id = '{$id}' and order_status = '15'");
        if($cOrder && !$cCancel){
            foreach($json as $value){
                $SQL = "UPDATE [pf]kt_product_stock set pstock=pstock + $value->qty WHERE pid = '{$value->pid}'";
                $mdb2->query($SQL);
            }
        }
        $NOW = date('Y-m-d H:i:s');

        $SQL = "INSERT INTO [pf]kt_order_status_email (order_id,order_status,email_send,is_active, is_delete, create_date) VALUES
                    ({$id},'14','{$NOW}', 'Y', 'N', '{$NOW}')";
        $mdb2->query($SQL);
        $this->sendEmailOrder($email, '', $subject, $message);
    }

    public function emailSentCustomerCancel($id, $email, $json = array()){
        global $session;
        global $mdb2;
        $email_header = MSG_EMAIL_HEADER_CANCEL;
        $email_content = MSG_EMAIL_CONTENT_CANCEL;
        $email_end = MSG_EMAIL_END;
        $subject = $email_header.$id;

        $message .= $email_header.$id."<br><br>";

        $message .= $email_content;
        $message .= "<br><br>";
        $message .= $email_end;

        $cOrder = $mdb2->isHaveRow("select * from [pf]kt_order_status_email WHERE is_active='Y' and is_delete = 'N' and order_id = '{$id}' and order_status = '12' or order_status = '13'");
        $cCancel = $mdb2->isHaveRow("select * from [pf]kt_order_status_email WHERE is_active='Y' and is_delete = 'N' and order_id = '{$id}' and order_status = '14'");
        if($cOrder && !$cCancel){
            foreach($json as $value){
                $SQL = "UPDATE [pf]kt_product_stock set pstock=pstock + $value->qty WHERE pid = '{$value->pid}'";
                $mdb2->query($SQL);
            }
        }
        $NOW = date('Y-m-d H:i:s');

        $SQL = "INSERT INTO [pf]kt_order_status_email (order_id,order_status,email_send,is_active, is_delete, create_date) VALUES
                    ({$id},'15','{$NOW}', 'Y', 'N', '{$NOW}')";
        $mdb2->query($SQL);
        $this->sendEmailOrder($email, '', $subject, $message);
    }

    public function emailSentReject($id, $email, $json = array()){
        global $session;
        global $mdb2;
        $email_header = MSG_EMAIL_HEADER_REJECT;
        $email_content = MSG_EMAIL_CONTENT_REJECT;
        $email_end = MSG_EMAIL_END;
        $subject = $email_header.$id;

        $message .= $email_header.$id."<br><br>";

        $message .= $email_content;
        $message .= "<br><br>";
        $message .= $email_end;

        $cOrder = $mdb2->isHaveRow("select * from [pf]kt_order_status_email WHERE is_active='Y' and is_delete = 'N' and order_id = '{$id}' and order_status = '12' or order_status = '13'");
        $cCancel = $mdb2->isHaveRow("select * from [pf]kt_order_status_email WHERE is_active='Y' and is_delete = 'N' and order_id = '{$id}' and order_status = '14' or order_status = '14'");
        if($cOrder && !$cCancel){
            foreach($json as $value){
                $SQL = "UPDATE [pf]kt_product_stock set pstock=pstock + $value->qty WHERE pid = '{$value->pid}'";
                $mdb2->query($SQL);
            }
        }
        $NOW = date('Y-m-d H:i:s');

        $SQL = "INSERT INTO [pf]kt_order_status_email (order_id,order_status,email_send,is_active, is_delete, create_date) VALUES
                    ({$id},'16','{$NOW}', 'Y', 'N', '{$NOW}')";
        $mdb2->query($SQL);
        $this->sendEmailOrder($email, '', $subject, $message);
    }
    

    public function emailSentConfirmOrder($id, $email){
        $subject = '';
        $message = '';
        $this->sendEmailOrder($email, '', $subject, $message);
    }

    public function editStatus(){
        global $mdb2;
        extract($_REQUEST);
        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->get($this->pk,$pk_id);
        eval("\$tb->{$fieldname} = \$tb->{$fieldname}==Y?N:Y;");
        $tb->update();
        return $this->search();
    }

    public function del(){
        global $mdb2;
        $ids = $_REQUEST['ids'];
        foreach ($ids as $id) {
             $tb = &$mdb2->get_factory("[pf]{$this->tb}");
             $tb->whereAdd();
             $this->setDeleted();
             $tb->is_delete = 'Y';
             $tb->whereAdd("{$this->pk}=$id");
             $tb->update(DB_DATAOBJECT_WHEREADD_ONLY);
        }
        return $this->search();
    }

    public function getSelectCountry(){
		global $mdb2;
        $SQL = " SELECT id, name FROM [pf]kt_country
        WHERE is_delete = 'N' and is_active = 'Y' ORDER BY name ";
        $result =& $mdb2->query($SQL, $datatype, $data);
		$i=0;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->id;
            $response->rows[$i]['name']=$row->name;
			$i++;
		}
        return $response;
    }

    public function getSelectShipment(){
            global $mdb2;
        $SQL = " SELECT id, data_type_name FROM [pf]kt_define_data_type
        WHERE is_delete = 'N' and is_active = 'Y' and ref_data_type = 'SHIPMENT_TYPE' ORDER BY data_type_name ";
        $result =& $mdb2->query($SQL, $datatype, $data);
		$i=0;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->id;
            $response->rows[$i]['name']=$row->data_type_name;
			$i++;
		}
        return $response;
    }

    public function getSelectPayment(){
        global $mdb2;
        $SQL = " SELECT id, data_type_name FROM [pf]kt_define_data_type
        WHERE is_delete = 'N' and is_active = 'Y' and ref_data_type = 'PAYMENT_TYPE' ORDER BY data_type_name ";
        $result =& $mdb2->query($SQL, $datatype, $data);
		$i=0;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->id;
            $response->rows[$i]['name']=$row->data_type_name;
			$i++;
		}
        return $response;
    }

    public function getSelectOrderstatus(){
        global $mdb2;
        $SQL = " SELECT id, data_type_name FROM [pf]kt_define_data_type
        WHERE is_delete = 'N' and is_active = 'Y' and ref_data_type = 'ORDER_STATUS' ORDER BY id ";
        $result =& $mdb2->query($SQL, $datatype, $data);
		$i=0;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->id;
            $response->rows[$i]['name']=$row->data_type_name;
			$i++;
		}
        return $response;
    }

    public function getAutoCity(){
            global $mdb2;
            $keyword = $_REQUEST['keyword'];
            $country = $_REQUEST['country'];
            $state = $_REQUEST['state'];
            $where = '';
            if(!empty($keyword)){
                    $where .= "and ( lower(ifnull(city.name,'')) like lower('%{$keyword}%')  ) ";
            }

            if(!empty($country)){
                    $country = implode(',', $country);
                    $where .= "and country.name = '{$country}' ";
            }

            if(!empty($state)){
                    $where .= " and state.id = (select id from [pf]kt_state WHERE name_th = '{$state}' or name_en = '{$state}')";
            }

        $SQL = " SELECT city.name as name FROM [pf]kt_city as city
        JOIN [pf]kt_state as state ON (state.id = city.state_id)
        JOIN [pf]kt_country as country ON (country.id = state.country_id)
        WHERE city.is_delete = 'N' and city.is_active = 'Y' {$where} ORDER BY city.name";

        $result =& $mdb2->query($SQL, $datatype, $data);
                $i=0;
        while($row = $result->fetchRow()) {
            //$response->rows[$i]['id']=$row->id;
            $response->rows[$i]['name']=$row->name;
            $i++;
        }

        return $response;
    }

    public function getAutoState(){
            global $mdb2;
            $keyword = $_REQUEST['keyword'];
            $country = $_REQUEST['country'];
            $where = '';
            if(!empty($keyword)){
                    $where .= "and ( lower(ifnull(state.name_en,'')) like lower('%{$keyword}%')  ) ";
                    $where .= "or ( lower(ifnull(state.name_th,'')) like lower('%{$keyword}%')  ) ";
            }

            if(!empty($country)){
                    $country = implode(',', $country);
                    $where .= "and country.name = '{$country}' ";
            }

        $SQL = " SELECT COALESCE(state.name_th, state.name_en) as name FROM [pf]kt_state as state
        JOIN [pf]kt_country as country ON (country.id = state.country_id)
        WHERE state.is_delete = 'N' and state.is_active = 'Y' {$where} ORDER BY name";
        //var_dump($SQL);
        $result =& $mdb2->query($SQL, $datatype, $data);
        $i=0;
        while($row = $result->fetchRow()) {
            //$response->rows[$i]['id']=$row->id;
            $response->rows[$i]['name']=$row->name;
            $i++;
        }

        return $response;
    }

    public function getAutoZipcode(){
        global $mdb2;
        $city = $_REQUEST['city'];
        $country = $_REQUEST['country'];
        $state = $_REQUEST['state'];
        $where = '';

        if(!empty($country)){
            $country = implode(',', $country);
            $where .= "and country.name = '{$country}' ";
        }

        if(!empty($state)){
            $where .= " and state.id = (select id from [pf]kt_state WHERE name_th = '{$state}' or name_en = '{$state}')";
        }

        if(!empty($city) && !empty($state)){
            $where .= "and city.name ='{$city}' ";
            $SQL = " SELECT city.zipcode as zipcode FROM [pf]kt_city as city
            JOIN [pf]kt_state as state ON (state.id = city.state_id)
            JOIN [pf]kt_country as country ON (country.id = state.country_id)
            WHERE city.is_delete = 'N' and city.is_active = 'Y' {$where} ORDER BY city.name";
            $result =& $mdb2->query($SQL, $datatype, $data);
            //$i=0;
            while($row = $result->fetchRow()) {
                $response->zipcode =$row->zipcode;
            }
        }


        return $response;
    }

     public function getSelectShipType(){
         global $mdb2;
         $companyid = $_REQUEST['companyid'];
//         $aShiptype = array();
//         if(!empty($companyid)){
//             $SQL_SHIPTYPE = "SELECT data_type_id FROM [pf]kt_ship_type WHERE ship_company_id = '{$companyid}'";
//             $result_shiptype = $mdb2->query($SQL_SHIPTYPE);
//             $i=0;
//             while($row_shiptype = $result_shiptype->fetchRow()) {
//                 $aShiptype[] = $row_shiptype->data_type_id;
//                 $i++;
//             }
//         }

         $SQL = "SELECT st.id as id, d.data_type_name, st.data_type_id, st.ship_company_id, st.is_active FROM [pf]kt_define_data_type as d
                         JOIN [pf]kt_ship_type as st ON (st.data_type_id = d.id)
         WHERE st.is_delete = 'N' ORDER BY d.data_type_name ";
         $result = $mdb2->query($SQL, $datatype, $data);
         $i=0;
         while($row = $result->fetchRow()) {
             $response->rows[$i]['id']=$row->data_type_id;
             $response->rows[$i]['name']=$row->data_type_name;
             $response->rows[$i]['disabled'] = "";
             $response->rows[$i]['selected'] = "";
             if($companyid != $row->ship_company_id){
                 $response->rows[$i]['disabled'] = "disabled";
             }else{
                 if($row->is_active == 'Y'){
                     $response->rows[$i]['selected'] = "selected";
                 }
             }
             $i++;
        }
        return $response;
     }

    public function gridOrderDetail(){
         global $mdb2;
         $orderid = $_REQUEST['orderid'];
         $where = '';
         if(!empty($orderid)){
                $where .= " and kord.order_id = '{$orderid}'";
                $SQL = "SELECT kord.*, pd.barcode FROM kt_orderdetail as kord
                        join kt_product as pd on (pd.id = kord.pid)
                        WHERE kord.is_delete = 'N' and kord.qty > 0 $where";

              $result = $mdb2->query($SQL, $datatype, $data);
              $i=0;

              while($row = $result->fetchRow()) {
                    $response->rows[$i]['pid'] = $row->pid;
                    $response->rows[$i]['barcode'] = $row->barcode;
                    $response->rows[$i]['name_en'] = $row->name_en;
                    $response->rows[$i]['name_th'] = $row->name_th;
                    $response->rows[$i]['volumn'] = $row->volumn;
                    $response->rows[$i]['unit'] = $row->unit;
                    $response->rows[$i]['price'] = $row->price;
                    $response->rows[$i]['qty'] = $row->qty;
                    $response->rows[$i]['sumtotal'] = $row->sumtotal;
                    $response->rows[$i]['weight'] = $row->weight;
                    //$response->rows[$i]['sumweight'] = $row->sumweight;
                    $i++;
             }
         }
        return $response;
     }
     
     private function getMaxWeightPrice($shipid){
         global $mdb2;
         if(!empty($shipid)){
            $SQL = "SELECT max(shr.max) as maxweight, max(shr.rate_price) as maxprice  FROM kt_ship_rate as shr
                    join kt_ship_type as sht on (sht.id = shr.ship_type_id)
                    WHERE sht.data_type_id = {$shipid} and shr.is_active = 'Y' and shr.is_delete = 'N'";

            $result = $mdb2->query($SQL, $datatype, $data);
            $row = $result->fetchRow();
            return $row;
         }
     }
     
     public function getRatePrice(){
         global $mdb2;
         $subtotal = $_REQUEST['subtotal'];
         $weight = $_REQUEST['weight'];
         $shipment_id = $_REQUEST['shipment_id'];
         $city = $_REQUEST['city'];
         $state = $_REQUEST['state'];
         $country = $_REQUEST['country'];
         
         $includeWeight = getIncludeWeight();
         $serviceCharge = getServiceCharge();
         
         if($shipment_id == 7){
            $SQL = "SELECT shr.rate_price as shipprice FROM kt_ship_rate as shr
                    join kt_ship_type as sht on (sht.id = shr.ship_type_id)
                    WHERE sht.data_type_id = {$shipment_id} and shr.min < '{$subtotal}' and shr.max >= '{$subtotal}'
                    and shr.zone = (
                    SELECT ci.zone from kt_country as c
                    join kt_state as s on (s.country_id = c.id)
                    join kt_city as ci on (ci.state_id = s.id)
                    WHERE c.name = '{$country}' and s.name_th = '{$state}' and ci.name = '{$city}') and shr.is_active = 'Y' and shr.is_delete = 'N'";
            $result = $mdb2->query($SQL, $datatype, $data);
            $row = $result->fetchRow();
            $shipprice = $row->shipprice;
         }else{
            $maxWeightPrice = $this->getMaxWeightPrice($shipment_id);
            $maxWeight = $maxWeightPrice->maxweight;
            $maxPrice = $maxWeightPrice->maxprice;
            
            $weigth += $weight*$includeWeight;
            $weight_over = floor($weight/$maxWeight);
            $shipprice = $weight_over*$maxPrice;
            
            $queryWeight =  $weight % $maxWeight;
            
            $SQL = "SELECT shr.rate_price as shipprice FROM kt_ship_rate as shr
                    join kt_ship_type as sht on (sht.id = shr.ship_type_id)
                    WHERE sht.data_type_id = {$shipment_id} and shr.min < '{$queryWeight}' and shr.max >= '{$queryWeight}' and shr.is_active = 'Y' and shr.is_delete = 'N'";
            $result = $mdb2->query($SQL, $datatype, $data);
            $row = $result->fetchRow();
            $shipprice = $shipprice + $row->shipprice + $serviceCharge;
         }
         return $shipprice;
     }

     public function searchAddProduct(){
         global $mdb2;
         $pid = $_REQUEST['pid'];
         $search_barcode = $_REQUEST['search_barcode'];
         $search_name = $_REQUEST['search_name'];
         $where = '';
         
         if(!empty($pid)){
             $pid = implode(',', $pid);
             $where .= "and pd.id not in ($pid) ";
         }
         
         if(!empty($search_barcode)){
             $where .= " and ( lower(ifnull(pd.barcode,'')) like lower('%{$search_barcode}%')  )";
         }
         
        if(!empty($search_name)){
             $where .= " and ( lower(ifnull(pd.name_th,'')) like lower('%{$search_name}%')  )
                        or ( lower(ifnull(pd.name_en,'')) like lower('%{$search_name}%')  )";
         }

         $SQL = "SELECT pd.* FROM kt_product as pd
                    WHERE pd.is_active and pd.is_delete = 'N' $where";

         $result = $mdb2->query($SQL, $datatype, $data);
         $i=0;

         while($row = $result->fetchRow()) {
                $response->rows[$i]['pid'] = $row->id;
                $response->rows[$i]['barcode'] = $row->barcode;
                $response->rows[$i]['name_en'] = $row->name_en;
                $response->rows[$i]['name_th'] = $row->name_th;
                $response->rows[$i]['volumn'] = $row->volumn;
                $response->rows[$i]['unit'] = $row->unit;
                $response->rows[$i]['price'] = $row->price;
                $response->rows[$i]['qty'] = $row->qty;
                //$response->rows[$i]['sumtotal'] = $row->sumtotal;
                $response->rows[$i]['weight'] = $row->weight;
                //$response->rows[$i]['sumweight'] = $row->sumweight;
                $i++;
         }
         
        return $response;
     }

}

?>