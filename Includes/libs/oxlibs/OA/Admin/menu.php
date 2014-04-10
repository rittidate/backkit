<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of menu
 *
 * @author ao
 */

define("MAIN_MENU", 1);
define("LEFT_MENU_NAV", 2);
define("LEFT_MENU_SUB_NAV", 3);
define('SECTION_NAV', 4);
define('SECTION_SUB_NAV', 5);
class Menu {
    //put your code here
    private $mdb2;
    private $user_id;
    private $parent_id;
    private $fileName;
    private $menuOnPage;
    private $language;

    public  $objSectionSubNav;

    private $queryMenu = "SELECT distinct m.* FROM
    [pf]cms_menu AS m
    Inner Join [pf]cms_roles_detail AS r ON r.menu_id = m.menu_id and m.level = ? and m.sectionid = ? and r.role_access='Y'
    Inner Join [pf]cms_user_roles AS ug ON ug.roles_id = r.roles_id and ug.user_id = ?
    where m.active='Y'
    order by m.seq";
    
    function  __construct($user_id, $language) { 
        $this->mdb2 = connectDB();
        $this->user_id = $user_id;
        $this->language = $language;
    }
    
    function getMenu(&$aMainNav, &$aLeftMenuNav, &$aLeftMenuSubNav, &$aSectionNav, &$aSectionSubNav){
        $aSectionSubNav = $this->setSectionSubNav();
        $aSectionNav = $this->setSectionNav();
        $aLeftMenuSubNav = $this->setLeftMenuSubNav();
        $aLeftMenuNav = $this->setLeftMenuNav();

        $aMainNav = $this->setMainNav();
    }

    private function setMainNav(){
        $query = "SELECT distinct m.* FROM
                [pf]cms_menu AS m
                Inner Join [pf]cms_roles_detail AS r ON r.menu_id = m.menu_id and m.level = ? and r.role_access='Y'
                Inner Join [pf]cms_user_roles AS ug ON ug.roles_id = r.roles_id and user_id = ?
                where m.active='Y'
                order by m.seq";    
        $sth = $this->mdb2->prepareQuery($query, array(DBTYPE_TEXT, DBTYPE_INT));
        $res = $this->mdb2->querySth($sth, array(MAIN_MENU, $this->user_id));
        return $this->fillMenu($res);
    }

    private function setLeftMenuNav(){   
        $sth = $this->mdb2->prepareQuery($this->queryMenu, array(DBTYPE_INT, DBTYPE_INT, DBTYPE_INT));
        $res = $this->mdb2->querySth($sth, array(LEFT_MENU_NAV, $this->menuOnPage, $this->user_id));

        $aLeftMenuNav = $this->fillMenu($res);
        if(!empty($aLeftMenuNav)){
            foreach($aLeftMenuNav as $key => $value)
            { $this->objLeftMenu[] = $value["menu"]; }
        }
        return $aLeftMenuNav;
    }

    private function setSubMenu($level){
        $parent_old = $this->parent_id;
        $sth = $this->mdb2->prepareQuery($this->queryMenu, array(DBTYPE_INT, DBTYPE_INT, DBTYPE_INT));
        $res = $this->mdb2->querySth($sth, array($level, $this->menuOnPage, $this->user_id));

        $this->fillMenu($res);//find parent_id

        $query = "SELECT distinct m.* FROM
                    [pf]cms_menu AS m
                    Inner Join [pf]cms_roles_detail AS r ON r.menu_id = m.menu_id and m.parent_id = ? and m.level = ? and r.role_access='Y'
                    Inner Join [pf]cms_user_roles AS ug ON ug.roles_id = r.roles_id and user_id = ?
                    where m.active='Y'
                    order by m.seq";

        $res = $this->mdb2->query($query, array(DBTYPE_INT, DBTYPE_INT, DBTYPE_INT), array($this->parent_id, $level,  $this->user_id));
        $this->parent_id = $parent_old;
        return $this->fillMenu($res);
    }

    private function setLeftMenuSubNav(){
    	$parent_old = $this->parent_id;
        $query = "SELECT distinct m.* FROM
                    [pf]cms_menu AS m
                    Inner Join [pf]cms_roles_detail AS r ON r.menu_id = m.menu_id and m.level = ? and r.role_access='Y'
                    Inner Join [pf]cms_user_roles AS ug ON ug.roles_id = r.roles_id and user_id = ?
                    where m.active='Y'
                    order by m.seq";

        $res = $this->mdb2->query($query, array(DBTYPE_INT, DBTYPE_INT, DBTYPE_INT), array(LEFT_MENU_SUB_NAV,  $this->user_id));
        $this->parent_id = $parent_old;
        return $this->fillMenu($res);
    }
    
    
    /* private function setLeftMenuSubNav(){
        return $this->setSubMenu(LEFT_MENU_SUB_NAV);
    }  */

    private function setSectionNav(){
//        $sth = $this->mdb2->prepareQuery($this->queryMenu, array(DBTYPE_TEXT, DBTYPE_INT, DBTYPE_INT));
//        $res = $this->mdb2->querySth($sth, array(SECTION_NAV, $this->menuOnPage, $this->user_id));
//        return $this->fillMenu($res);
        return $this->setSubMenu(SECTION_NAV);
    }

    private function setSectionSubNav(){
    	$parent_old = $this->parent_id;
        $query = "SELECT distinct m.* FROM
                    [pf]cms_menu AS m
                    Inner Join [pf]cms_roles_detail AS r ON r.menu_id = m.menu_id and m.level = ? and r.role_access='Y'
                    Inner Join [pf]cms_user_roles AS ug ON ug.roles_id = r.roles_id and user_id = ?
                    where m.active='Y'
                    order by m.seq";

        $res = $this->mdb2->query($query, array(DBTYPE_INT, DBTYPE_INT, DBTYPE_INT), array(SECTION_SUB_NAV,  $this->user_id));
        $this->parent_id = $parent_old;
        
        $sectionSubNav = $this->fillMenu($res);
        if(is_array($sectionSubNav)){
            foreach($sectionSubNav as $value){
                if(is_array($this->objSectionSubNav)){
                    if(!in_array($value["parent"], $this->objSectionSubNav)){
                        $this->objSectionSubNav[] = $value["parent"];
                    }
                }else{
                    $this->objSectionSubNav[] = $value["parent"];
                }
            }
        }
        return $sectionSubNav;
    }

    public function getSectionSubNavParent(){
        if(is_array($this->objSectionSubNav)){
            return $this->objSectionSubNav;
        }
        return;
    }

    function setFileActive($fileName){
        $this->fileName = $fileName;       
        $this->setMenuOnPage();
    }

    private function setMenuOnPage(){
        $query = "select sectionid, language from [pf]cms_menu where filename = '{$this->fileName}'";
        $res = $this->mdb2->query($query); 
        if ($res->numRows() > 0){
            $row = $res->fetchRow();
            $this->menuOnPage = $row->sectionid;
            $language = unserialize($row->language);       
            $GLOBALS["strCurrentCaption"] = $language[$this->language]["caption"];

            //insert recently access
            $sql = "insert into [pf]cms_recently_access(user_id, filename, language, last_access)values(?, ?, ?, '".OA::getNow() ."')" ;
            $this->mdb2->execute($sql, array(DBTYPE_INT, DBTYPE_TEXT, DBTYPE_TEXT), array($this->user_id, $this->fileName, $row->language));
        }
    }

    private function isSelected($menu_id, $fileName, $parent_id){
        $selected = ( $this->parent_id==$menu_id || $fileName==$this->fileName);
        if($selected) $this->parent_id = $parent_id;
        return $selected;
    }

    private function fillMenu($res){
        $aMenu = array();
        $aMenuInner = array();
        $i = 0;
        $patthen = '(\.php)';
        $conf = $GLOBALS['CONF'];
        $ismod = $conf['mod']['modrewrite'];
        if ($res->numRows() > 0){
            while($row = $res->fetchRow()){               
                $language = unserialize($row->language);          
                $aMenuInner["title"] = $language[$this->language]["title"];
                $aMenuInner["caption"] = $language[$this->language]["caption"];
                $aMenuInner["filename"] = ($ismod? preg_replace($patthen, '', $row->filename): $row->filename);
                $aMenuInner["selected"] = $this->isSelected($row->menu_id, $row->filename, $row->parent_id);
                $aMenuInner["first"] = $row->first;
                $aMenuInner["last"] = $row->last;

                $aMenuInner["class"] = empty($row->class) ? 'icon-tag' : $row->class;
                $aMenuInner["menu"] = $row->menu_id;
                $aMenuInner["parent"] = $row->parent_id;

                $aMenu[] = $aMenuInner;
                $aMenuInner = array();
            }
            return $aMenu;
        }

    }

}
?>
