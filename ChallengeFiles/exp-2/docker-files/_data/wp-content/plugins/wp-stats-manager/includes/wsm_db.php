<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
class wsmDatabase{
    private $wsmDB,$tablePrefix,$arrTables,$arrCachedStats;
    private $arrInsertLogVisit=array(),$arrInsertLogUniqueVisit=array();
    function __construct(){
        global $wpdb,$arrCashedStats;
        $this->wsmDB=$wpdb;
        $this->tablePrefix=$this->wsmDB->base_prefix.WSM_PREFIX;
        $this->arrTables=get_option(WSM_PREFIX.'_tables');
        $this->arrInsertLogUniqueVisit=array('siteId','visitorId','visitLastActionTime','configId','ipAddress','userId','firstActionVisitTime','daysSinceFirstVisit','returningVisitor','visitCount','visitEntryURLId','visitExitURLId','visitTotalActions','refererUrlId','browserLang','browserId','deviceType','oSystemId','currentLocalTime','daysSinceLastVisit','totalTimeVisit','resolutionId','cookie','director','flash','gears','java','pdf','quicktime','realplayer','silverlight','windowsmedia','city','countryId','latitude','longitude','regionId');
        $this->arrInsertLogVisit=array('siteId','visitorId','visitId','refererUrlId','serverTime','timeSpentRef','URLId','keyword');
        $this->arrCachedStats=$arrCashedStats;
        //$this->fnCorrectDatabaseTables();
        //print_r($this->arrTables);
    }
	
    function fnLogError($extra=''){
        if ($this->wsmDB->last_error) {
            $error=$this->wsmDB->last_query.PHP_EOL.$this->wsmDB->last_error.PHP_EOL;            
            if($extra!='' && is_array($extra)){
              $error.=print_r($extra,true);
            }else{
              $error.=$extra;  
            }
             wsmFNUpdateLogFile('MySQL ERROR',$error); 
        }
    }
    function fnInsertNewUniqueVisit($properties){
        $fields=implode(',',$this->arrInsertLogUniqueVisit);
        $sql  = "INSERT INTO {$this->tablePrefix}{$this->arrTables['LOG_UNIQUE']} ($fields) VALUES (";
        foreach($this->arrInsertLogUniqueVisit as $key){
			
			if(isset($properties[$key]))
			{
				if($key=='visitorId' || $key=='configId'){
					$sql.="'".$properties[$key]."',";
				}else if(is_numeric($properties[$key]) || $properties[$key]=='0'){
					$sql.=$properties[$key].',';
				}else if(!isset($properties[$key]) || is_null($properties[$key])){
					$sql.="'',";
				}else{
					$sql.= isset($properties[$key]) ?  "'".addslashes($properties[$key])."'," : "'',";
				}
			}else{
				$sql.="'',";
			}
        }
        $sql=rtrim($sql,',').')';
        //echo '<br>'.$sql;
        $this->wsmDB->query($sql);
        $this->fnLogError();
        return intval($this->wsmDB->insert_id);
    }
    function fnInsertNewVisit($properties){
       
        $fields=implode(',',$this->arrInsertLogVisit);
        $sql  = "INSERT INTO {$this->tablePrefix}{$this->arrTables['LOG_VISIT']} ($fields) VALUES (";
        if(isset($properties['visitId']) && $properties['visitId']!=0){            
            foreach($this->arrInsertLogVisit as $key){ 
                if($key=='visitorId'){
                    $sql.="'".$properties[$key]."',";
                }else if(is_numeric($properties[$key]) || $properties[$key]=='0'){
                    $sql.=$properties[$key].',';
                }else{
                    $sql.="'".addslashes($properties[$key])."',";
                }            
            }
                        
            $sql=rtrim($sql,',').')';
            //echo '<br>'.$sql;
            $this->wsmDB->query($sql);        
            $this->fnLogError($properties);
            return intval($this->wsmDB->insert_id);
        }
    }
    function fnGetLastLinkVisited($visitId){
        $sqlQuery="SELECT id FROM {$this->tablePrefix}{$this->arrTables['LOG_VISIT']} WHERE visitId={$visitId} ORDER BY id DESC LIMIT 1";
        $id=$this->wsmDB->get_var($sqlQuery);
        return intval($id);
    }
    function fnIsNotDuplicateLinkVisit($properties,$urlId){
        $sqlQuery="SELECT * FROM {$this->tablePrefix}{$this->arrTables['LOG_VISIT']} WHERE visitId={$properties['visitId']} AND refererUrlId={$properties['refererUrlId']} AND URLId={$urlId} ORDER BY id DESC LIMIT 1";
        $result=$this->wsmDB->get_row($sqlQuery,ARRAY_A);
        if($result==null){
            return 'yes';
        }else{
            $firstTime=strtotime($result['serverTime']);
            $secondTime=time();
            $differenceInSeconds = $secondTime - $firstTime;
            if($differenceInSeconds>60){
                return 'yes';
            }else{
                return intval($result['id']);
            }
        }
        return 'yes';
    }
    function fnUpdateExistingLinkVisit($properties, $linkId){
        $sqlQuery  = "UPDATE {$this->tablePrefix}{$this->arrTables['LOG_VISIT']} SET timeSpentRef=".$properties['timeSpentRef'];
        $sqlQuery .= " WHERE id = $linkId";
        $this->wsmDB->query($sqlQuery);
        $this->fnLogError();
    }
    function fnUpdateExistingVisit($properties,$visitId){
        $sqlQuery  = "UPDATE {$this->tablePrefix}{$this->arrTables['LOG_UNIQUE']} SET ";
        foreach($properties as $key=>$value){
            if($key=='timeSpentRef' || $key=='URLId' || $key=='keyword' || $key=='visitorId'){
                continue;
            }
            $sqlQuery .=$key."=";
            if(is_numeric($value) || $key=='visitTotalActions'){
                $sqlQuery.=$value.",";
            }else{
                $sqlQuery.="'".addslashes($value)."',";
            }
        }
        $sqlQuery=rtrim($sqlQuery,',');
        $sqlQuery .= " WHERE id = $visitId";
        $this->wsmDB->query($sqlQuery);
        $this->fnLogError();
    }
	
    function fnGetColumnsOfLogUniqueVisit(){
        $sql='SHOW COLUMNS FROM '.$this->tablePrefix.$this->arrTables['LOG_UNIQUE'];
        $result=$this->wsmDB->get_results($sql,ARRAY_A);
        return $result;
    }
    function fnGetColumnsOfLogVisit(){
        $sql='SHOW COLUMNS FROM '.$this->tablePrefix.$this->arrTables['LOG_VISIT'];
        $result=$this->wsmDB->get_results($sql,ARRAY_A);
        return $result;
    }
    function fnGetCountryIdByCode($code){
        $id=0;
        if(isset($code) && $code!=''){
            $sql= $this->wsmDB->prepare('SELECT id FROM '.$this->tablePrefix.$this->arrTables['COUNTRY'].' WHERE alpha2Code = %s',$code);
            $id=$this->wsmDB->get_var($sql);
            if(is_null($id) || $id==''){
                $id=0;
            }
        }
        return intval($id);
    }
    function fnGetAllCountries(){
        $sql='SELECT * FROM '.$this->tablePrefix.$this->arrTables['COUNTRY'].' ORDER BY name';
        $results=$this->wsmDB->get_results($sql,ARRAY_A);
        return $results;
    }
    function fnGetRegionIdByCode($code){
        $id=0;
        if(isset($code) && $code !=''){
            $sql= $this->wsmDB->prepare('SELECT id FROM '.$this->tablePrefix.$this->arrTables['RG'].' WHERE code = %s',$code);
            $id=$this->wsmDB->get_var($sql);
            if(is_null($id) || $id==''){
                $id=0;
            }
        }
        return intval($id);
    }
    function fnFindVisitorById($idVisitor,$lookBackSec,$lookAheadSec ){
		
		$sql = $this->wsmDB->prepare('SELECT * FROM '.$this->tablePrefix.$this->arrTables['LOG_UNIQUE'].' WHERE visitorId= %d AND visitLastActionTime>= %s AND visitLastActionTime<= %s ORDER BY visitLastActionTime DESC LIMIT 1', $idVisitor,$lookBackSec, $lookAheadSec);
        
		$row=$this->wsmDB->get_row($sql,ARRAY_A);
        if(is_null($row)){
            $row=0;
        }
        return $row;
    }
    function fnFindVisitorByConfigId($configId,$lookAheadSec, $lookBackSec){
		
		$this->wsmDB->prepare('SELECT * FROM '.$this->tablePrefix.$this->arrTables['LOG_UNIQUE'].' WHERE configId= %s AND visitLastActionTime>= %s AND visitLastActionTime<= %s ORDER BY visitLastActionTime DESC LIMIT 1', $configId, $lookAheadSec, $lookBackSec);
        $row=$this->wsmDB->get_row($sql,ARRAY_A);
        if(is_null($row)){
            $row=0;
        }
        return $row;
    }
    function fnGetBrowserIDByTitle($title=''){
        $id=0;
        if(isset($title) && $title !=''){
            $sql= $this->wsmDB->prepare('SELECT id FROM '.$this->tablePrefix.$this->arrTables['BROW'].' WHERE name like %s','%'.$title.'%');
            $id=$this->wsmDB->get_var($sql);
            if(is_null($id) || $id==''){
                $id=0;
                $newSql='INSERT INTO '.$this->tablePrefix.$this->arrTables['BROW'].' (name) VALUES ("'.$title.'")';
                $this->wsmDB->query($newSql);
                $this->fnLogError();
                $id=$this->wsmDB->insert_id;
            }
        }
        return intval($id);
    }
    function fnGetOSIDByTitle($title=''){
        $id=0;
        if(isset($title) && $title !=''){
            $sql= $this->wsmDB->prepare('SELECT id FROM '.$this->tablePrefix.$this->arrTables['OS'].' WHERE name like %s','%'.$title.'%');
            $id=$this->wsmDB->get_var($sql);
            if(is_null($id) || $id==''){
                $id=0;
                $newSql= $this->wsmDB->prepare('INSERT INTO '.$this->tablePrefix.$this->arrTables['OS'].' (name) VALUES (%s)', $title);
                $this->wsmDB->query($newSql);
                $this->fnLogError();
                $id=$this->wsmDB->insert_id;
            }
        }
        return intval($id);
    }
    function fnGetResolutionIDByTitle($title=''){
        $id=0;
        if(isset($title) && $title !=''){
            $sql= $this->wsmDB->prepare('SELECT id FROM '.$this->tablePrefix.$this->arrTables['RSOL'].' WHERE name = %s',$title);
            $id=$this->wsmDB->get_var($sql);
            if(is_null($id) || $id==''){
                $id=0;
                $newSql='INSERT INTO '.$this->tablePrefix.$this->arrTables['RSOL'].' (name) VALUES ("'.$title.'")';
                $this->wsmDB->query($newSql);
                $this->fnLogError();
                $id=$this->wsmDB->insert_id;
            }
        }
        return intval($id);
    }
    function fnGetURLogID($arrLog){
        $id=0;
		$sql = '';
        if(isset($arrLog['url']) && $arrLog['url']!=''){
            $arrURL=$this->fnReturnURLElements($arrLog['url']);
            if($arrURL['url']!='' && $arrURL['url']!='0'){
				
				
				if(isset($arrURL['hash']) && isset($arrLog['pageId']))
                if(trim($arrLog['pageId'])!='' && trim($arrURL['hash']) !=''){
                    $sql= $this->wsmDB->prepare('SELECT * FROM '.$this->tablePrefix.$this->arrTables['LOG_URL'].' WHERE pageId = %d OR  hash=%s',$arrLog['pageId'],$arrURL['hash']);
                } else{
                    $sql= $this->wsmDB->prepare('SELECT * FROM '.$this->tablePrefix.$this->arrTables['LOG_URL'].' WHERE hash = %s',$arrURL['hash']);
                }
				
				
				if(isset($sql))
				{
                $rowResult=$this->wsmDB->get_row($sql,ARRAY_A);
                // print_r($rowResult);
					if(is_null($rowResult) || $rowResult==null){
						$this->wsmDB->insert(
							$this->tablePrefix.$this->arrTables['LOG_URL'],
							array(
								'pageId' => isset($arrLog['pageId'])?$arrLog['pageId']:'',
								'title' => isset($arrLog['title'])?$arrLog['title']:'',
								'hash' => $arrURL['hash'],
								'protocol' => $arrURL['protocol'] ,
								'url' => $arrURL['url']
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);
						$id=$this->wsmDB->insert_id;
					}else{
						$pageId=$arrLog['pageId']!=''?$arrLog['pageId']:($rowResult['pageId']!=''?$rowResult['pageId']:null);
						$this->wsmDB->update(
							$this->tablePrefix.$this->arrTables['LOG_URL'],
							array(
								'pageId' => $pageId,
								'title' => ($arrLog['title']=='')?$rowResult['title']:$rowResult['title']
								),
							array( 'id' => $rowResult['id'] ),
							array(
								'%s',
								'%s'
							),
							array( '%d' )
						);
						$id=$rowResult['id'];
					}
				}
            }
        }
       // echo '<br>'.$this->wsmDB->last_query;
        return intval($id);
    }
    function fnGetRefLogID($url){
        $id=0;
        if($url!=''){
            $arrURL=$this->fnReturnURLElements($url);
            /*$refURL=get_transient('wsm_'.wsmGetVisitorIdFromCookie());
            if(!$refURL){
                $refURL=$arrURL['url'];
            }*/
            if($arrURL['url']!='' && $arrURL['url']!='0' && isset($arrURL['hash'])){
                $sql= $this->wsmDB->prepare('SELECT id FROM '.$this->tablePrefix.$this->arrTables['LOG_URL'].' WHERE hash = %s',$arrURL['hash']);
                $id=$this->wsmDB->get_var($sql);
                if(is_null($id) || $id==''){
					
					$myurl = !empty($arrURL['url']) ? $this->fnGetToolBarID($arrURL['url']) : 0;
					
                    $this->wsmDB->insert(
                        $this->tablePrefix.$this->arrTables['LOG_URL'],
                        array(
                            'hash' => $arrURL['hash'],
                            'protocol' => $arrURL['protocol'] ,                            
                            'url' => $arrURL['url'],
                            'searchEngine'=>$this->fnGetSearchEngineID($arrURL['url']),
                            'toolBar'=>$myurl
                        ),
                        array(
                            '%s',
                            '%s',
                            '%s',
                            '%d',
                            '%d'
                        )
                    );
                    $id=$this->wsmDB->insert_id;
                }
            }
        }
        return intval($id);
    }
    function fnUpdateURLParameters($id,$arrParam){
        if(is_array($arrParam) && count($arrParam)>0){
            $this->wsmDB->update(
                $this->tablePrefix.$this->arrTables['LOG_URL'],
                $arrParam,
                array( 'id' => $id ),
                array_values(array_map(function ($k,$v){if(is_numeric($v)){return '%d';}else{return '%s';}},array_keys($arrParam),$arrParam)),
                array( '%d' )
            );
        }
    }
    function fnReturnURLElements($url){
        //$url=strtolower($url);
        $url = rtrim($url,"/");
        $arrURL=parse_url($url);
        $arrURL['host']=str_replace('www.','',$arrURL['host']);        
        $newURL=str_replace($arrURL['scheme'].'://','',$url); 
        $newURL=str_replace('www.','',$newURL);              
        $hash=substr(md5($newURL),0,16);
        return array('protocol'=>$arrURL['scheme'].'://','url'=>$newURL,'hash'=>$hash);
    }
    function fnGetSearchEngineID($url){
		global $wpdb;
        $id=0;
		//$url = sanitize_text_field($url);
		$url = addslashes($url);
		
		if(!empty($url))
		{
			$sql="SELECT id FROM {$this->tablePrefix}{$this->arrTables['SE']} WHERE  '%s' LIKE CONCAT('%',CONCAT(name,'%'))";
			$id=$this->wsmDB->get_var($wpdb->prepare($sql, $url));
		}
		



        if(is_null($id) || $id==''){
            $id=0;
        }
        return intval($id);
    }
    function fnGetToolBarID($url){
		global $wpdb;
		
		$url = sanitize_text_field($url);
		if(!empty($url))
		{
			$id=0;
			$sql="SELECT id FROM {$this->tablePrefix}{$this->arrTables['TOOL']} WHERE  '%s' LIKE CONCAT('%',CONCAT(name,'%'))";
			$id=$this->wsmDB->get_var($wpdb->prepare($sql, $url));
			if(is_null($id) || $id==''){
				$id=0;
			}
			return intval($id);
		}else
		{
			return 0;
		}
    }
    function fnGetSearchEngineList(){
        if(isset($this->arrCachedStats['searchEngines']) && count($this->arrCachedStats['searchEngines'])>0){
            return $this->arrCachedStats['searchEngines'];
        }
        $sql= 'SELECT id,name FROM '.$this->tablePrefix.$this->arrTables['SE'];
        $arrResult=$this->wsmDB->get_results($sql,ARRAY_A);
        $this->arrCachedStats['searchEngines']=$arrResult;
        return $arrResult;
    }
    function fnGetToolBarList(){
        $sql= 'SELECT id,name FROM '.$this->tablePrefix.$this->arrTables['TOOL'];
        $arrResult=$this->wsmDB->get_results($sql,ARRAY_A);
        return $arrResult;
    }
    function fnGetMostActiveVisitors($limit=""){
        if(isset($this->arrCachedStats['mostActiveVisitors']) && is_array($this->arrCachedStats['mostActiveVisitors']) && count($this->arrCachedStats['mostActiveVisitors'])>0){
          return $this->arrCachedStats['mostActiveVisitors'];
        }
        $newTimeZone=wsmCurrentGetTimezoneOffset();
        $visitLastActionTime="CONVERT_TZ(VI.visitLastActionTime,'+00:00','".$newTimeZone."')";
        $serverTime="CONVERT_TZ(VI.serverTime,'+00:00','".$newTimeZone."')";
        $currentDate=wsmGetCurrentDateByTimeZone();
        $sqlQuery="SELECT VI.visitId, VI.ipAddress,VI.hits, VI.city, VI.alpha2Code,VI.country, VI.browser ,VI.osystem, VI.deviceType, VI.title, VI.url,VI.latitude, VI.longitude, VI.resolution, VI.refUrl";
        $sqlQuery.=",TIMEDIFF('{$currentDate}',{$visitLastActionTime}) as timeDiff";        
        $sqlQuery.=" FROM {$this->tablePrefix}_visitorInfo VI";        
        $sqlQuery.=" WHERE {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.WSM_ONLINE_SESSION.' minutes')."'";
        $sqlQuery.=" ORDER BY VI.hits DESC";
        
        if($limit!='')
			$sqlQuery.=" limit 0, $limit";
			
        $arrResult=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
        $this->arrCachedStats['mostActiveVisitors']=$arrResult;
        return $arrResult;
    }
    function fnGetActiveVisitorsCount($groupBy='country'){        
        if(isset($this->arrCachedStats['activeVisitorsCount'.$groupBy]) && is_array($this->arrCachedStats['activeVisitorsCount'.$groupBy]) && count($this->arrCachedStats['activeVisitorsCount'.$groupBy])>0){
          return $this->arrCachedStats['activeVisitorsCount'.$groupBy];
        }
        $newTimeZone=wsmCurrentGetTimezoneOffset();
        $visitLastActionTime="CONVERT_TZ(visitLastActionTime,'+00:00','".$newTimeZone."')";        
        $currentDate=wsmGetCurrentDateByTimeZone();
        $sqlQuery="SELECT COUNT(visitId) as visitors,alpha2Code,country";
        $groupByQuery=' GROUP BY country';
        if($groupBy=='city'){
            $sqlQuery.=',city';
            $groupByQuery.=',city';
        }
        $sqlQuery.=' FROM '.$this->tablePrefix.'_visitorInfo';
         $sqlQuery.=" WHERE {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.WSM_ONLINE_SESSION.' minutes')."'";
        $sqlQuery.=$groupByQuery.' ORDER BY visitors DESC';
        $arrResult=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
        $this->arrCachedStats['activeVisitorsCount'.$groupBy]=$arrResult;
        return $arrResult;
    }
    function fnGetTotalVisitorsCount($condition="",$arrParam=array()){
        $sqlQuery="SELECT COUNT(*) FROM {$this->tablePrefix}{$this->arrTables['LOG_UNIQUE']} ";
		$sqlReportQuery = "SELECT SUM(total_visitors) FROM {$this->tablePrefix}_datewise_report WHERE normal = 1 ";
		$whereCondition = '';
        $visitLastActionTime="CONVERT_TZ(visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";

        if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine'] ){
           $sqlQuery .= " LEFT JOIN {$this->tablePrefix}_url_log on {$this->tablePrefix}_url_log.id =   {$this->tablePrefix}{$this->arrTables['LOG_UNIQUE']}.refererUrlId ";
			$sqlReportQuery = "SELECT SUM(total_visitors) FROM {$this->tablePrefix}_datewise_report WHERE 1 = 1 ";
        }
		
		$sqlQuery .= " WHERE 1=1 ";
        switch($condition){
            case 'Hour':
                $whereCondition.="AND {$visitLastActionTime} >= '".wsmGetDateByInterval('-1 hour')."'";
                break;
            case 'Today':
                $whereCondition.="AND {$visitLastActionTime} >= '".wsmGetCurrentDateByTimeZone('Y-m-d 00:00:00')."'";
                break;
            case 'Last2Months':
                $whereCondition.="AND {$visitLastActionTime} >= '".wsmGetDateByInterval('-2 months','Y-m-d 00:00:00')."'";
                break;
            case 'Online':
                $whereCondition.="AND {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.WSM_ONLINE_SESSION.' minutes')."'";
                break;
            case '7dayBeforeHour':
                $whereCondition.="AND {$visitLastActionTime} >= '".wsmGetDateByInterval('-7 days','Y-m-d '.wsmGetCurrentDateByTimeZone('H').':00:00')."' AND {$visitLastActionTime}<='".wsmGetDateByInterval('-7 days','Y-m-d '.wsmGetCurrentDateByTimeZone('H').':59:59')."'";
                break;
            case '14dayBeforeHour':
                $whereCondition.="AND {$visitLastActionTime} >= '".wsmGetDateByInterval('-14 days','Y-m-d '.wsmGetCurrentDateByTimeZone('H').':00:00')."' AND {$visitLastActionTime}<='".wsmGetDateByInterval('-14 days','Y-m-d '.wsmGetCurrentDateByTimeZone('H').':59:59')."'";
                break;
            case '7dayBefore':
                $whereCondition.="AND {$visitLastActionTime} >= '".wsmGetDateByInterval('-7 days','Y-m-d 00:00:00')."' AND {$visitLastActionTime}<='".wsmGetDateByInterval('-7 days','Y-m-d 23:59:59')."'";
                break;
            case '14dayBefore':
                $whereCondition.="AND {$visitLastActionTime} >= '".wsmGetDateByInterval('-14 days','Y-m-d 00:00:00')."' AND {$visitLastActionTime}<='".wsmGetDateByInterval('-14 days','Y-m-d 23:59:59')."'";
            break;
            case 'Compare':
                if(isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date'])){
                    $whereCondition.="AND {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            case 'Normal':
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                    
                     $whereCondition.="AND {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";  
                }                
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $whereCondition.=" AND {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
                if(wsmValidateDateTime($condition)){
                    $whereCondition.=" AND {$visitLastActionTime} >= '".$condition.' 00:00:00'."'";
                }
                break;
        }
		$sqlQuery .= $whereCondition;
		$sqlReportQuery .= $whereCondition;
		
        if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine'] ){
            $sqlQuery .= ' AND ( ';
            $searchEngineResult = $this->fnGetSearchEngine();
            if( $searchEngineResult ){
                $sepeartor = '';
                foreach( $searchEngineResult as $searchEngine ){
                    $sqlQuery .= " $sepeartor {$this->tablePrefix}_url_log.url LIKE '%".$searchEngine['name']."%' ";
                    $sepeartor = ' OR ';
                }
            }
            $sqlQuery .= ' ) ';
			$sqlReportQuery .= ' AND search_engine != "" ';
        }
		//echo $sqlQuery.'<br />';
		$sqlReportQuery = str_replace('visitLastActionTime','date', $sqlReportQuery);
		//echo $sqlReportQuery.'<br/>';
		$count=$this->wsmDB->get_var($sqlReportQuery);
		if( is_null( $count ) || $count == 0 ){
			//echo "in<br/>";
	        $count=$this->wsmDB->get_var($sqlQuery);
	        if(is_null($count)){
	            $count=0;
	        }
		}else{
	        if($condition!='' && is_numeric($condition)){
	            $whereCondition = "";
	        }	
        
			/* to get current date data */
			if($whereCondition=="")
			{
				$count +=$this->fnGetTotalVisitorsCount('Today');	
			}
		}
        
        return intval($count);
    } 
    function fnGetReferralTotalVisitorsCountByRefURL($condition="",$arrParam=array()){               
        $sqlQuery="SELECT DISTINCT LU.visitorId FROM {$this->tablePrefix}{$this->arrTables['LOG_UNIQUE']} LU LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} UL ON LU.refererUrlId=UL.id WHERE UL.url LIKE '{$arrParam['refUrl']}%' AND";
        $visitLastActionTime="CONVERT_TZ(LU.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        switch($condition){            
            case 'Compare':
                if(isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date'])){
                    $sqlQuery.=" {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            case 'Normal':
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                    
                     $sqlQuery.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";  
                }                
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $sqlQuery.="  {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
                if(wsmValidateDateTime($condition)){
                    $sqlQuery.="  {$visitLastActionTime} >= '".$condition.' 00:00:00'."'";
                }
                break;
        } 
//        echo $sqlQuery.'<br />';
        $result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);        
        $count=$this->wsmDB->num_rows;
        if(is_null($count)){
            $count=0;
        }
        return intval($count);
    } 
    function fnGetReferralTotalVisitorsCount($condition="",$arrParam=array()){
        $protocol='http://';
        if (is_ssl()) {
            $protocol='https://';
        }
		$whereCondition = '';
        $homeURL=str_replace('www.','',site_url());     
        $homeURL=str_replace($protocol,'',$homeURL);
        
        //$sqlQuery="SELECT DISTINCT LU.visitorId FROM {$this->tablePrefix}{$this->arrTables['LOG_UNIQUE']} LU LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} UL ON LU.refererUrlId=UL.id WHERE LU.refererUrlId<>0 AND UL.url NOT LIKE '{$homeURL}%' AND";
		
		$sqlReportQuery = "SELECT count(1) FROM {$this->tablePrefix}_logVisit lv   WHERE keyword NOT LIKE '{$homeURL}%' AND";
		
		
        //$visitLastActionTime="CONVERT_TZ(LU.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        switch($condition){            
            case 'Compare':
                if(isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date'])){
                    $whereCondition.=" serverTime >= '".$arrParam['date'].' 00:00:00'."' AND serverTime<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            case 'Normal':
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                    
                     $whereCondition.=" serverTime >= '".$arrParam['from'].' 00:00:00'."' AND serverTime<='".$arrParam['to'].' 23:59:59'."'";  
                }                
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $whereCondition.="  serverTime >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
                if(wsmValidateDateTime($condition)){
                    $whereCondition.="  serverTime >= '".$condition.' 00:00:00'."'";
                }
                break;
        }
		
		$sqlReportQuery .= $whereCondition; 
		//$sqlQuery .= $whereCondition;
		
        /*if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine'] ){
            $sqlQuery .= ' AND ( ';
            $searchEngineResult = $this->fnGetSearchEngine();
            if( $searchEngineResult ){
                $sepeartor = '';
                foreach( $searchEngineResult as $searchEngine ){
                    $sqlQuery .= " $sepeartor UL.url LIKE '%".$searchEngine['name']."%' ";
                    $sepeartor = ' OR ';
                }
            }
            $sqlQuery .= ' ) ';
			$sqlReportQuery .= ' AND DR.search_engine != "" ';
        }*/
		
		//echo $sqlReportQuery.'<br />';
		//$sqlReportQuery = str_replace('LU.visitLastActionTime','DR.date', $sqlReportQuery);
		//echo $sqlReportQuery.'<br />';
        $count=$this->wsmDB->get_var($sqlReportQuery);
        
        
        return intval($count);
    }
    function fnGetFirstTimeVisitorCount($condition="",$arrParam=array()){
        $sqlQuery="SELECT COUNT(visitorId) FROM {$this->tablePrefix}_uniqueVisitors ";
		$sqlReportQuery = "SELECT SUM(total_first_time_visitors) FROM {$this->tablePrefix}_datewise_report WHERE normal=1 ";
		$whereCondition = '';

        if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine'] ){
           $sqlQuery .= " LEFT JOIN {$this->tablePrefix}_url_log on {$this->tablePrefix}_url_log.id =   {$this->tablePrefix}_uniqueVisitors.refererUrlId ";
		   $sqlReportQuery = "SELECT SUM(total_first_time_visitors) FROM {$this->tablePrefix}_datewise_report WHERE 1=1 ";
        }
		$sqlQuery .= ' WHERE 1=1 ';
		
        $firstVisitTime="CONVERT_TZ(firstVisitTime,'+00:00','".WSM_TIMEZONE."')";
        switch($condition){
            case 'Today':
                $whereCondition.="AND {$firstVisitTime} >= '".wsmGetCurrentDateByTimeZone('Y-m-d 00:00:00')."'";
                break;
            case 'Last2Months':
                $whereCondition.="AND {$firstVisitTime} >= '".wsmGetDateByInterval('-2 months','Y-m-d 00:00:00')."'";
                break;
            case 'Compare':
                if(isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date'])){
                    $whereCondition.="AND {$firstVisitTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$firstVisitTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break; 
            case 'Normal':           
            case 'Range':            
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){
                     $whereCondition.="AND {$firstVisitTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$firstVisitTime}<='".$arrParam['to'].' 23:59:59'."'";
                }
               
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $whereCondition.=" AND {$firstVisitTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }                
                break;
	    }
		$sqlQuery .= $whereCondition;
		$sqlReportQuery .= $whereCondition;
		
 
        if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine'] ){
            $sqlQuery .= ' AND ( ';
            $searchEngineResult = $this->fnGetSearchEngine();
            if( $searchEngineResult ){
                $sepeartor = '';
                foreach( $searchEngineResult as $searchEngine ){
                    $sqlQuery .= " $sepeartor {$this->tablePrefix}_url_log.url LIKE '%".$searchEngine['name']."%' ";
                    $sepeartor = ' OR ';
                }
            }
            $sqlQuery .= ' ) ';
			$sqlReportQuery .= ' AND search_engine != "" ';
        }
		//echo $sqlQuery.'<br />';
		$sqlReportQuery = str_replace('firstVisitTime','date', $sqlReportQuery);
		//echo $sqlReportQuery;
		$count=$this->wsmDB->get_var($sqlReportQuery);
		if( is_null( $count ) || $count == 0 ){
	        $count=$this->wsmDB->get_var($sqlQuery);
	        if(is_null($count)){
	            $count=0;
	        }
		}else{
        	if($condition!='' && is_numeric($condition)){
            	$whereCondition = "";
        	}
			/* to get current date data */
			if($whereCondition=="")
			{
				$count +=$this->fnGetFirstTimeVisitorCount('Today');	
			}
			
		}
        return intval($count);
    }
    function fnGetReferralFirstTimeVisitorCount($condition="",$arrParam=array()){
        $protocol='http://';
        if (is_ssl()) {
            $protocol='https://';
        }
        $homeURL=str_replace('www.','',site_url());     
        $homeURL=str_replace($protocol,'',$homeURL);
        $sqlQuery="SELECT COUNT(visitorId) FROM {$this->tablePrefix}_uniqueVisitors UV LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} UL ON UL.id=UV.refererUrlId WHERE UV.refererUrlId<>0 AND UL.url NOT LIKE '{$homeURL}%' ";
		
		$sqlReportQuery = "SELECT SUM( DR.total_first_time_visitors ) FROM {$this->tablePrefix}_datewise_report DR LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} UL ON DR.url_id=UL.id  WHERE UL.url NOT LIKE '{$homeURL}%' ";
		$whereCondition = '';
		
        $firstVisitTime="CONVERT_TZ(firstVisitTime,'+00:00','".WSM_TIMEZONE."')";
        switch($condition){
            case 'Today':
                $whereCondition.="AND {$firstVisitTime} >= '".wsmGetCurrentDateByTimeZone('Y-m-d 00:00:00')."'";
                break;
            case 'Last2Months':
                $whereCondition.="AND {$firstVisitTime} >= '".wsmGetDateByInterval('-2 months','Y-m-d 00:00:00')."'";
                break;
            case 'Compare':
                if(isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date'])){
                    $whereCondition.="AND {$firstVisitTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$firstVisitTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break; 
            case 'Normal':           
            case 'Range':            
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){
                     $whereCondition.="AND {$firstVisitTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$firstVisitTime}<='".$arrParam['to'].' 23:59:59'."'";
                }
               
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $whereCondition.=" AND {$firstVisitTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }                
                break;
        }        
		
		$sqlQuery .= $whereCondition; 
		$sqlReportQuery .= $whereCondition; 
		   
        if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine'] ){
            $sqlQuery .= ' AND ( ';
            $searchEngineResult = $this->fnGetSearchEngine();
            if( $searchEngineResult ){
                $sepeartor = '';
                foreach( $searchEngineResult as $searchEngine ){
                    $sqlQuery .= " $sepeartor UL.url LIKE '%".$searchEngine['name']."%' ";
                    $sepeartor = ' OR ';
                }
            }
            $sqlQuery .= ' ) ';
			$sqlReportQuery .= ' AND DR.search_engine != "" ';
        }
//		echo $sqlQuery.'<br />';

		$sqlReportQuery = str_replace('firstVisitTime','DR.date', $sqlReportQuery);
		//echo $sqlReportQuery.'<br />';
        $count=$this->wsmDB->get_var($sqlReportQuery);
        if(is_null($count)){
	        $count=$this->wsmDB->get_var($sqlQuery);
	        if(is_null($count)){
	            $count=0;
	        }
		}
        
        return intval($count);
    }
    function fnGetTotalPageViewCount($condition="",$arrParam=array()){
        $count=0;          
        $sqlQuery="SELECT SUM(totalViews) FROM {$this->tablePrefix}_pageViews ";
		$sqlReportQuery = "SELECT SUM(total_page_views) FROM {$this->tablePrefix}_datewise_report WHERE normal = 1 ";
        $visitLastActionTime="CONVERT_TZ(visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
		$whereCondition = '';
        if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine'] ){
            $sqlQuery .= " LEFT JOIN {$this->tablePrefix}_url_log on {$this->tablePrefix}_url_log.id =   {$this->tablePrefix}_pageViews.refererUrlId ";
		
			$sqlReportQuery = "SELECT SUM(total_page_views) FROM {$this->tablePrefix}_datewise_report WHERE 1 = 1 ";
        }
        
		$sqlQuery .= "WHERE 1 = 1 ";
        switch($condition){
            case 'Today':
                $whereCondition.="AND {$visitLastActionTime} >= '".wsmGetCurrentDateByTimeZone('Y-m-d 00:00:00')."'";
            break;
            case 'Last2Months':
                $whereCondition.="AND {$visitLastActionTime} >= '".wsmGetDateByInterval('-2 months','Y-m-d 00:00:00')."'";
            break;              
            case 'Normal':        
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                                      
                     $whereCondition.="AND {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
                }               
            break;
            case 'Compare':
                if((isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date']))){
                    $whereCondition.="AND {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $whereCondition.="AND {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
            break;
        }
		$sqlQuery .= $whereCondition;
		$sqlReportQuery .= $whereCondition;
        if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine'] ){
            $sqlQuery .= ' AND ( ';
            $searchEngineResult = $this->fnGetSearchEngine();
            if( $searchEngineResult ){
                $sepeartor = '';
                foreach( $searchEngineResult as $searchEngine ){
                    $sqlQuery .= " $sepeartor {$this->tablePrefix}_url_log.url LIKE '%".$searchEngine['name']."%' ";
                    $sepeartor = ' OR ';
                }
            }
            $sqlQuery .= ' ) ';
			$sqlReportQuery .= ' AND search_engine != "" ';
        }
        //echo $sqlQuery.'<br>';    
		$sqlReportQuery = str_replace('visitLastActionTime','date', $sqlReportQuery);
		$count=$this->wsmDB->get_var($sqlReportQuery);
		if( is_null( $count ) || $count == 0 ){
			
	        $count=$this->wsmDB->get_var($sqlQuery);
	        if(is_null($count)){
	            $count=0;
	        }
		}else{
	        if($condition!='' && is_numeric($condition)){
	            $whereCondition = "";
	        }
			/* to get current date data */
			if($whereCondition=="")
			{
				$count += $this->fnGetTotalPageViewCount('Today');	
			}	
		}
		
        return intval($count);
    }
    /*
     * Get serch engine list
     */
    function fnGetSearchEngine(){
        $sqlQuery = "SELECT name as oName, LCASE(replace(name, ' ','')) AS name FROM `{$this->tablePrefix}_searchEngines`";
        $arrResult=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
        return $arrResult;
    }
    
	
	
	
	
   
    function fnGetReferralList($condition="",$arrParam=array()){
		
        $protocol='http://';
        if (is_ssl()) {
            $protocol='https://';
        }
        $homeURL=str_replace($protocol.'www.','',site_url());   
        $arrReferralList=array();          
        $visitLastActionTime="CONVERT_TZ(PV.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        
        $sqlQuery="select id, keyword as refUrl, keyword AS searchEngine, count(*) AS total from  {$this->tablePrefix}_logVisit where keyword NOT LIKE '%{$homeURL}%' AND keyword NOT LIKE 'https://0'  AND keyword NOT LIKE '-'  AND ";
        
        //$sqlReportQuery = "select UL.id,SUBSTRING_INDEX(UL.url,'/',1) as refUrl, count(*) AS total, sum(DR.total_page_views) as totalPageViews,max(LV.keyword) from {$this->tablePrefix}_datewise_report DR LEFT JOIN {$this->tablePrefix}_url_log UL ON UL.id=DR.url_id LEFT JOIN {$this->tablePrefix}_logVisit LV on LV.URLId = UL.id WHERE UL.url IS NOT NULL AND UL.url NOT LIKE '{$homeURL}%' AND ";
        
        switch($condition){                      
            case 'Normal': 
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){
					$whereCondition = " serverTime>= '".$arrParam['from'].' 00:00:00'."' AND serverTime<='".$arrParam['to'].' 23:59:59'."'";
                   
                }
            break;
            case 'Compare':
                if((isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date']))){
					$whereCondition = " serverTime >= '".$arrParam['date'].' 00:00:00'."' AND serverTime<='".$arrParam['date'].' 23:59:59'."'";
                   
                }
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $whereCondition.=" serverTime >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
            break;
        }
			
			
		$sqlQuery .= $whereCondition;
		//$sqlReportQuery .= $whereCondition;
		/*	
        if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine']!='' ){
            $sqlQuery .= ' AND ( ';
            $sqlReportQuery .= ' AND ( ';
            $searchEngineResult = $this->fnGetSearchEngine();
            if( $searchEngineResult ){
                $sepeartor = '';
                foreach( $searchEngineResult as $searchEngine ){
                    $sqlQuery .= " $sepeartor UL.url LIKE '%".$searchEngine['name']."%' ";
                    $sqlReportQuery .= " $sepeartor UL.url LIKE '%".$searchEngine['name']."%' ";
                    $sepeartor = ' OR ';
                }
            }
            $sqlQuery .= ' ) ';
            $sqlReportQuery .= ' ) ';
            //$sqlReportQuery .= ' AND search_engine != "" ';
        }
        
       
        $sqlReportQuery.=" GROUP BY refUrl ORDER by totalPageViews DESC";
        
		$sqlReportQuery = str_replace('PV.visitLastActionTime','DR.date', $sqlReportQuery);
		*/
	    //echo $sqlQuery."<br/>";
		//echo $sqlReportQuery."<br/>";
		
		 $sqlQuery.=" GROUP BY refUrl ORDER by total DESC";
        $mainQuery = 0;
		$allResult=$this->wsmDB->get_results($sqlReportQuery,ARRAY_A);		
		if( !$allResult){
			
			$mainQuery=1;
			$allResult=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		}
		
		$totalRecords=$this->wsmDB->num_rows;
        $cPage=isset($arrParam['currentPage'])?$arrParam['currentPage']:1;
        $offset=($cPage-1)*WSM_PAGE_LIMIT;
        $sqlReportQuery.=" LIMIT {$offset},".WSM_PAGE_LIMIT;
        if($mainQuery==1){
			$sqlQuery.=" LIMIT {$offset},".WSM_PAGE_LIMIT;
			$allResult=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		}
		else
			$allResult=$this->wsmDB->get_results($sqlReportQuery,ARRAY_A);
        
        $arrResult['data']=$allResult;
        if(isset($arrParam['currentPage']) && isset($arrParam['adminURL'])){
            $arrResult['pagination']=wsmFnGetPagination($totalRecords,$arrParam['currentPage'],$arrParam['adminURL'],10);
        }
        return $arrResult;           
    }
    function fnGetListOfUrlsByReferral($condition="",$arrParam=array()){
        $visitLastActionTime="CONVERT_TZ(PV.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        
		$refURL = sanitize_url($arrParam['refUrl']);
		$refURL = str_ireplace ("'", "", $refURL);
		
        $sqlQuery="select PV.refererUrlId, UL2.title, CONCAT(UL2.protocol,UL2.url) as fullURL, COUNT(PV.visitId) as totalViews  from {$this->tablePrefix}_pageViews PV LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} UL ON UL.id=PV.refererUrlId  LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} UL2 ON UL2.id=PV.URLId WHERE UL.url LIKE '{$refURL}%' AND "; 
        
        $sqlReportQuery="select LV.refererUrlId, UL2.title, CONCAT(UL2.protocol,UL2.url) as fullURL, sum(PV.total_page_views) as totalViews  from {$this->tablePrefix}_datewise_report PV Left JOIN {$this->tablePrefix}_url_log UL ON UL.id=PV.url_id LEFT JOIN {$this->tablePrefix}_url_log UL2 ON UL2.id=PV.url_id LEFT JOIN {$this->tablePrefix}_logVisit LV on LV.URLId = UL.id WHERE UL.url LIKE '{$refURL}%' AND "; 
        
        
        //$sqlQuery="select CONCAT(UL.host,UL.url) as fullUrl, totalViews  from  {$this->tablePrefix}_pageViews PV LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} UL ON UL.id=PV.refererUrlId AND UL.id=PV.URLId WHERE UL.url LIKE '{$arrParam['refUrl']}%' AND ";
        switch($condition){                      
            case 'Normal':        
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                                      
                     $sqlQuery.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
                     $sqlReportQuery.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
                }
            break;
            case 'Compare':
                if((isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date']))){
                    $sqlQuery.=" {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                    $sqlReportQuery.=" {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $sqlQuery.=" {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                    $sqlReportQuery.=" {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
            break;
        }    
		$sqlReportQuery = str_replace('PV.visitLastActionTime','PV.date', $sqlReportQuery);
		
        $sqlQuery.=" GROUP BY UL2.url ORDER BY totalViews DESC";    
        $sqlReportQuery.=" GROUP BY UL2.url ORDER BY totalViews DESC";    
        
        //echo $sqlQuery."<br/>";
        //echo $sqlReportQuery."<br/>";
        
        $allRecords=$this->wsmDB->get_results($sqlReportQuery,ARRAY_A);        
        if(!$allRecords)
			$allRecords=$this->wsmDB->get_results($sqlQuery,ARRAY_A);        
			
        return $allRecords;
    }
    function fnGetTotalReferralsByRefURL($condition="",$arrParam=array()){        
        $visitLastActionTime="CONVERT_TZ(PV.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        $sqlQuery="select count(*) AS total from  {$this->tablePrefix}_pageViews PV LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} UL ON UL.id=PV.refererUrlId WHERE UL.url LIKE '{$arrParam['refUrl']}%' AND ";
        switch($condition){                      
            case 'Normal':        
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                                      
                     $sqlQuery.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
                }
            break;
            case 'Compare':
                if((isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date']))){
                    $sqlQuery.=" {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $sqlQuery.=" {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
            break;
        }        
        $totalRecords=$this->wsmDB->get_var($sqlQuery);        
        return $totalRecords;
    }
    function fnGetReferralTotalPageViewCount($condition="",$arrParam=array()){
        $count=0; 
        $protocol='http://';
        if (is_ssl()) {
            $protocol='https://';
        }
        $homeURL=str_replace('www.','',site_url());     
        $homeURL=str_replace($protocol,'',$homeURL);  
        //$arrSearchEngines=$this->fnGetSearchEngineList();       
      //  $sqlQuery="SELECT COUNT(DISTINCT LU.refererUrlId) FROM {$this->tablePrefix}{$this->arrTables['LOG_UNIQUE']} LU LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} UL ON LU.refererUrlId=UL.id WHERE LU.refererUrlId!=0 AND UL.url NOT LIKE '{$homeURL}%' AND";
        $sqlQuery="SELECT SUM(totalViews) FROM {$this->tablePrefix}_pageViews PV LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} UL ON PV.refererUrlId=UL.id WHERE PV.refererUrlId!=0 AND UL.url NOT LIKE '{$homeURL}%' AND";

        if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine'] ){
			$sqlReportQuery = "SELECT SUM( DR.total_page_views ) AS pageViews FROM {$this->tablePrefix}_datewise_report DR WHERE ";
		}else{
			$sqlReportQuery = "SELECT SUM( DR.total_page_views ) AS pageViews FROM {$this->tablePrefix}_datewise_report DR LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} UL ON DR.url_id=UL.id  WHERE DR.url_id!=0 AND UL.url NOT LIKE '{$homeURL}%' AND";
		}
        $visitLastActionTime="CONVERT_TZ(PV.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
		$whereCondition = '';
		if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine'] ){
//            $sqlQuery .= " LEFT JOIN {$this->tablePrefix}_url_log on {$this->tablePrefix}_url_log.id = PV.refererUrlId ";
        }
        
        switch($condition){                      
            case 'Normal':        
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                                      
                     $whereCondition.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
                }               
            break;
            case 'Compare':
                if((isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date']))){
                    $whereCondition.=" {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $whereCondition.=" {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
            break;
        }                
		$sqlQuery .= $whereCondition;
		$sqlReportQuery .= $whereCondition;
        if( isset( $arrParam['searchengine'] ) && $arrParam['searchengine'] ){
            $sqlQuery .= ' AND ( ';
            $searchEngineResult = $this->fnGetSearchEngine();
            if( $searchEngineResult ){
                $sepeartor = '';
                foreach( $searchEngineResult as $searchEngine ){
                    $sqlQuery .= " $sepeartor UL.url LIKE '%".$searchEngine['name']."%' ";
                    $sepeartor = ' OR ';
                }
            }
            $sqlQuery .= ' ) ';
			$sqlReportQuery .= ' AND DR.search_engine != "" ';
        }
		
		$sqlReportQuery = str_replace('PV.visitLastActionTime','DR.date', $sqlReportQuery);
		
        $count=$this->wsmDB->get_var($sqlReportQuery);
        if(is_null($count) || $count == 0){
	        $count=$this->wsmDB->get_var($sqlQuery);
	        if(is_null($count)){
	            $count=0;
	        }
        }
        
        return intval($count);
    }
    function fnGetTotalVisitorsByCountries($limit=0,$countryId=''){
        $count=0;
        $sqlQuery="SELECT PV.countryId, C.name, count(*) as visitors FROM {$this->tablePrefix}_logUniqueVisit PV LEFT JOIN {$this->tablePrefix}_countries C ON PV.countryId=C.id WHERE 1";
        
		$sqlReportQuery = "SELECT DR.country AS countryId, C.name, SUM( DR.total_visitors ) AS visitors FROM {$this->tablePrefix}_datewise_report DR LEFT JOIN {$this->tablePrefix}_countries C ON DR.country=C.id  WHERE DR.country > 0 ";
		
        if($countryId!='' && is_numeric($countryId)){
            $sqlQuery.=" AND PV.countryId={$countryId}";
			$sqlReportQuery .= " AND DR.country={$countryId}";
        }else{
            $sqlQuery.=" GROUP BY PV.countryId";
			$sqlReportQuery .= " GROUP BY countryId";
        }
        $sqlQuery.=" ORDER BY visitors DESC";
        $sqlReportQuery.=" ORDER BY visitors DESC";
        if($limit!=0){
            $sqlQuery.=" LIMIT 0,{$limit}";
            $sqlReportQuery.=" LIMIT 0,{$limit}";
        }

		//echo $sqlQuery ."<br/>";
		//echo $sqlReportQuery ."<br/>";
		$result=$this->wsmDB->get_results($sqlReportQuery,ARRAY_A);
		if( !$result ){
			$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		}
		$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
        return $result;
    }
    function fnGetTotalPageViewsByCountries($limit=0,$countryId=''){
        $count=0;
        $sqlQuery="SELECT PV.countryId, C.name, SUM(totalViews) as pageViews FROM {$this->tablePrefix}_pageViews PV LEFT JOIN {$this->tablePrefix}_countries C ON PV.countryId=C.id WHERE 1";
		$sqlReportQuery = "SELECT DR.country AS countryId,   C.name, SUM( DR.total_page_views ) AS pageViews FROM {$this->tablePrefix}_datewise_report DR LEFT JOIN {$this->tablePrefix}_countries C ON DR.country=C.id  WHERE DR.country > 0 ";
		
        if($countryId!='' && is_numeric($countryId)){
            $sqlQuery.=" AND PV.countryId={$countryId}";
			$sqlReportQuery .= " AND DR.country={$countryId}";
        }else{
            $sqlQuery.=" GROUP BY PV.countryId";
			$sqlReportQuery .= " GROUP BY countryId";
        }
        $sqlQuery.=" ORDER BY pageViews DESC";
        $sqlReportQuery.=" ORDER BY pageViews DESC";
        if($limit!=0){
            $sqlQuery.=" LIMIT 0,{$limit}";
            $sqlReportQuery.=" LIMIT 0,{$limit}";
        }

		//echo $sqlReportQuery;
		$result=$this->wsmDB->get_results($sqlReportQuery,ARRAY_A);
		if( !$result ){
			$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		}
		$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
        return $result;
    }
    function fnGetTotalPageViewsByRegion($regionId=""){
        $sqlQuery="SELECT PV.regionId, R.name, SUM(totalViews) as pageViews FROM {$this->tablePrefix}_pageViews PV LEFT JOIN {$this->tablePrefix}_regions R ON PV.regionId=R.id WHERE 1";
        if($regionId!='' && is_numeric($regionId)){
            $sqlQuery.=" AND PV.regionId={$countryId}";
        }else{
            $sqlQuery.=" GROUP BY PV.regionId";
        }
        $result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
        return $result;
    }
    function fnGetTodaysPageViewsByHour($hour=""){
		global $wpdb;
        //$sqlQuery="SELECT hour, pageViews FROM {$this->tablePrefix}_hourWisePageViews";
		$hourCondition = '';
		$timezone = wsmCurrentGetTimezoneOffset();
		$date = wsmGetCurrentUTCDate('Y-m-d');
        if($hour!=""){
			$hourCondition = " AND hour(convert_tz(`{$this->tablePrefix}_pageViews`.`visitLastActionTime`,'+00:00','$timezone')) = ".sanitize_text_field($hour);
            //$sqlQuery.=" WHERE hour=".$hour;
            //$result=$this->wsmDB->get_row($sqlQuery,ARRAY_A);
            //return $result['pageViews'];
        }
		$sqlQuery = $wpdb->prepare( "select hour(convert_tz(`{$this->tablePrefix}_pageViews`.`visitLastActionTime`,'+00:00','%s')) AS `hour`,sum(`{$this->tablePrefix}_pageViews`.`totalViews`) AS `pageViews` from `{$this->tablePrefix}_pageViews` where (convert_tz(`{$this->tablePrefix}_pageViews`.`visitLastActionTime`,'+00:00','%s') >= convert_tz(%s,'+00:00','%s' ) ) $hourCondition group by hour(convert_tz(`{$this->tablePrefix}_pageViews`.`visitLastActionTime`,'+00:00','%s')) ", $timezone, $timezone, $date.' 00:00:00', $timezone, $timezone );

		if($hour!=""){
			$result=$this->wsmDB->get_row($sqlQuery,ARRAY_A);
			return $result['pageViews'];
		}
        $result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
        $retArray=wsmFormatHourlyStats('pageViews',$result);
        return $retArray;
    }
    function fnGetTodaysVisitorsByHour($condition="",$hour=""){
		global $wpdb;
        $viewName=$this->tablePrefix.'_hourWiseVisitors';
        if($condition=='FirstTime'){
            $viewName=$this->tablePrefix.'_hourWiseFirstVisitors';
        }
        //$sqlQuery="SELECT hour,visitors FROM {$viewName}";
		$hourCondition = '';

		$timezone = wsmCurrentGetTimezoneOffset();
		$date = wsmGetCurrentUTCDate('Y-m-d');
        if($hour!=""){
			$hourCondition = " AND hour(convert_tz(`{$this->tablePrefix}_logUniqueVisit`.`firstActionVisitTime`,'+00:00','$timezone')) = ".sanitize_text_field($hour) ;
			if($condition=='FirstTime'){
				$hourCondition = " AND hour(convert_tz(`{$this->tablePrefix}_uniqueVisitors`.`firstVisitTime`,'+00:00','$timezone')) = ".sanitize_text_field($hour);
			}
            //$sqlQuery.=" WHERE hour=".$hour;
            //$result=$this->wsmDB->get_row($sqlQuery,ARRAY_A);
            //return $result['visitors'];
        }

		$sqlQuery = $wpdb->prepare("select hour(convert_tz(`{$this->tablePrefix}_logUniqueVisit`.`firstActionVisitTime`,'+00:00','%s')) AS `hour`,count(0) AS `visitors` from `{$this->tablePrefix}_logUniqueVisit` where (convert_tz(`{$this->tablePrefix}_logUniqueVisit`.`firstActionVisitTime`,'+00:00','%s') >= convert_tz(%s,'+00:00','%s') ) $hourCondition group by hour(convert_tz(`{$this->tablePrefix}_logUniqueVisit`.`firstActionVisitTime`,'+00:00','%s'))", $timezone, $timezone, $date.' 00:00:00', $timezone, $timezone );
		if($condition=='FirstTime'){
			$sqlQuery = $wpdb->prepare("select hour(convert_tz(`{$this->tablePrefix}_uniqueVisitors`.`firstVisitTime`,'+00:00','%s')) AS `hour`,count(0) AS `visitors` from `{$this->tablePrefix}_uniqueVisitors` where (convert_tz(`{$this->tablePrefix}_uniqueVisitors`.`firstVisitTime`,'+00:00','%s') >= convert_tz(%s,'+00:00','%s') ) $hourCondition group by hour(convert_tz(`{$this->tablePrefix}_uniqueVisitors`.`firstVisitTime`,'+00:00','%s'))", $timezone, $timezone, $date.' 00:00:00', $timezone,  $timezone );
		}
		if($hour!=""){
			$result=$this->wsmDB->get_row($sqlQuery,ARRAY_A);
			return $result['visitors'];
		}
        $result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		$retArray=wsmFormatHourlyStats('visitors',$result);
        return $retArray;
    }
    function fnGetTodaysBounceRateByHour($hour=""){
        $viewName=$this->tablePrefix.'_hourWiseBounceRate';
        $sqlQuery="SELECT * FROM {$viewName}";
        if($hour!=""){
            $sqlQuery.=" WHERE hour=".$hour;
            $result=$this->wsmDB->get_row($sqlQuery,ARRAY_A);
			if(isset($result))
			{
            return $result['bRateVisitors'];
			}else
			{
			return null;
			}
        }

        $result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
        $retArray=wsmFormatHourlyStats('bounceRate',$result);
        return $retArray;
    }
    function fnGetDayWisePageViewsByNumberOfDays($days=30, $where = array()){
		
		/*$sqlQuery = "SELECT date as recordDate, total_page_views AS pageViews FROM {$this->tablePrefix}_datewise_report WHERE normal = 1 AND date >= '".wsmGetDateByInterval('-'.$days.' days','Y-m-d')."' AND date <='".wsmGetCurrentDateByTimeZone('Y-m-d')."' ORDER BY date ASC";
		$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);*/
		//if( !$result || !$days ){
	        $sqlQuery="SELECT recordDate, pageViews FROM {$this->tablePrefix}_dateWisePageViews WHERE ";
	        $sqlQuery.="recordDate >= '".wsmGetDateByInterval('-'.$days.' days','Y-m-d')."' AND recordDate<='".wsmGetCurrentDateByTimeZone('Y-m-d')."' ORDER BY recordDate ASC";
	        $result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		//}
		
        $retArray=array();
        if(count($result)<$days){
            /*for($i=$days;$i>0;$i--){
                $date=wsmGetDateByInterval('-'.($i).' days','Y-m-d');
                $retArray[$date]=0;
            }*/
        }
        foreach($result as $key=>$row){
            $retArray[$row['recordDate']]=(int)$row['pageViews'];
        }      
        return $retArray;
    }
    function fnGetDayWiseFirstTimeVisitorCount($days=30, $where = array()){
		$sqlQuery = "SELECT date AS firstVisitTime, total_first_time_visitors AS visitors FROM {$this->tablePrefix}_datewise_report WHERE normal = 1 AND date >= '".wsmGetDateByInterval('-'.$days.' days','Y-m-d')."' AND date <='".wsmGetCurrentDateByTimeZone('Y-m-d')."' ORDER BY date ASC";
		$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		if( !$result ){
	        $sqlQuery="SELECT recordDate as firstVisitTime, visitors FROM {$this->tablePrefix}_dateWiseFirstVisitors WHERE 1 ";
	        $sqlQuery.="AND recordDate >= '".wsmGetDateByInterval('-'.$days.' days','Y-m-d')."' AND recordDate<='".wsmGetCurrentDateByTimeZone('Y-m-d')."'  ORDER BY recordDate ASC";
	        $result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		}
		/*echo $sqlQuery."<br/>";*/
        $retArray=array();
        if(count($result)<$days){
            /*for($i=$days;$i>0;$i--){
                $date=wsmGetDateByInterval('-'.($i).' days','Y-m-d');
                $retArray[$date]=0;
            }*/
        }
        foreach($result as $key=>$row){
            $retArray[$row['firstVisitTime']]=(int)$row['visitors'];
        }
        return $retArray;
    }
    function fnGetDayWiseVisitorsByNumberOfDays($days=30, $where = array()){
		/*$sqlQuery = "SELECT date, total_visitors AS visitors FROM {$this->tablePrefix}_datewise_report WHERE normal = 1 AND date >= '".wsmGetDateByInterval('-'.$days.' days','Y-m-d')."' AND date <='".wsmGetCurrentDateByTimeZone('Y-m-d')."' ORDER BY date ASC";
		$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);*/
		
		//if( !$result ){
	        $sqlQuery="SELECT recordDate as date, visitors FROM {$this->tablePrefix}_dateWiseVisitors WHERE ";
	        $sqlQuery.="recordDate >= '".wsmGetDateByInterval('-'.$days.' days','Y-m-d')."' AND recordDate<='".wsmGetCurrentDateByTimeZone('Y-m-d')."'  ORDER BY recordDate ASC";
	        $result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		//}
		/*echo $sqlQuery."<br/>";*/
        $retArray=array();
        if(count($result)<$days){
            /*for($i=$days;$i>0;$i--){
                $date=wsmGetDateByInterval('-'.($i).' days','Y-m-d');
                $retArray[$date]=0;
            }*/
        }
        foreach($result as $key=>$row){
            $retArray[$row['date']]=(int)$row['visitors'];
        }
        return $retArray;
    }
    function fnGetDayWiseBounceRateByNumberOfDays($days=30, $where = array()){
		$sqlQuery = "SELECT date AS recordDate, ( (total_bounce/total_visitors) * 100 ) AS bRateVisitors FROM {$this->tablePrefix}_datewise_report WHERE normal = 1 AND date >= '".wsmGetDateByInterval('-'.$days.' days','Y-m-d')."' AND date <='".wsmGetCurrentDateByTimeZone('Y-m-d')."' ORDER BY date ASC";
		$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		if( !$result ){
	        $sqlQuery="SELECT * FROM {$this->tablePrefix}_dateWiseBounceRate WHERE 1 ";
	        $sqlQuery.="AND recordDate >= '".wsmGetDateByInterval('-'.$days.' days','Y-m-d')."' AND recordDate<='".wsmGetCurrentDateByTimeZone('Y-m-d')."'  ORDER BY recordDate ASC";
	        $result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		}
        
        $retArray=array();
        if(count($result)<$days){
            for($i=$days;$i>0;$i--){
                $date=wsmGetDateByInterval('-'.($i).' days','Y-m-d');
                $retArray[$date]=0;
            }
        }
        foreach($result as $key=>$row){
            $retArray[$row['recordDate']]=(int)$row['bRateVisitors'];
        }
        return $retArray;
    }
    function fnGetBounceRateByDate($date){
        $sqlQuery="SELECT * FROM {$this->tablePrefix}_dateWiseBounceRate WHERE recordDate='".$date."'";
        $result=$this->wsmDB->get_row($sqlQuery,ARRAY_A);
        if($result){
            return (int)$result['bRateVisitors'];
        }
        return 0;
    }
    function fnGetAverageVisitLength($condition=""){
        $visitLastActionTime="CONVERT_TZ(visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        $sqlQuery="SELECT AVG(TIMESTAMPDIFF(SECOND,firstActionVisitTime,visitLastActionTime)) as avgTotalLength FROM {$this->tablePrefix}{$this->arrTables['LOG_UNIQUE']} ";
        switch($condition){
            case 'Today':
                $sqlQuery.="WHERE {$visitLastActionTime} >= '".wsmGetCurrentDateByTimeZone('Y-m-d 00:00:00')."'";
                break;
            case 'Last2Months':
                $sqlQuery.="WHERE {$visitLastActionTime} >= '".wsmGetDateByInterval('-2 months','Y-m-d 00:00:00')."'";
                break;
            default:
                break;
        }
        $time=$this->wsmDB->get_var($sqlQuery);
        return $time;
    }
    function fnGetHourlyReportByDateNameTimeZone($date,$name = '',$newTimeZone = ''){
		
		if( empty( $name ) ){
			$sqlQuery = 'SELECT hour, total_page_views, total_visitors, total_first_time_visitors, total_bounce FROM '.$this->tablePrefix.'_datewise_report WHERE hour > 0 AND date = "'.$date.'" ORDER BY hour ASC';
			$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);          
			if( $result ){
				return $result;
			}	
		}
		
        $sqlQuery='SELECT * FROM '.$this->tablePrefix.'_dailyHourlyReport WHERE name="'.$name.'" AND reportDate="'.$date.'" AND timezone="'.$newTimeZone.'"';
        $result=$this->wsmDB->get_row($sqlQuery,ARRAY_A);
        $startDateTime=$date.' 00:00:00';
        $endDateTime=$date.' 23:59:59';
        $visitLastActionTime="CONVERT_TZ(visitLastActionTime,'+00:00','".$newTimeZone."')";
        $action='insert';
        $updateId=0;
        if($this->wsmDB->num_rows > 0){
            $arrResult=unserialize($result['content']);
            if(count($arrResult)<24){
                $action='update';
                $updateId=$result['id'];
            }else{
                return $arrResult;
            }
        }
        $sql='';
        switch($name){
            case 'hourWisePageViews':
                $sql="SELECT HOUR({$visitLastActionTime}) as hour, SUM(totalViews) as pageViews FROM ".$this->tablePrefix."_pageViews WHERE {$visitLastActionTime} >= '".$startDateTime."' AND {$visitLastActionTime} <= '".$endDateTime."' GROUP BY HOUR({$visitLastActionTime})";
                break;
            case 'hourWiseBounce':
                $sql="SELECT HOUR({$visitLastActionTime}) as hour, COUNT(*) as bounce FROM ".$this->tablePrefix."_bounceVisits WHERE {$visitLastActionTime} >= '".$startDateTime."' AND {$visitLastActionTime} <= '".$endDateTime."' GROUP BY HOUR({$visitLastActionTime})";
                break;
            case 'hourWiseVisitors':
                $sql="SELECT HOUR({$visitLastActionTime}) as hour, COUNT(*) as visitors FROM ".$this->tablePrefix."_logUniqueVisit WHERE {$visitLastActionTime} >= '".$startDateTime."' AND {$visitLastActionTime} <= '".$endDateTime."' GROUP BY HOUR({$visitLastActionTime})";
                break;
            case 'hourWiseFirstVisitors':
                $firstVisitTime="CONVERT_TZ(firstVisitTime,'+00:00','".$newTimeZone."')";
                $sql="SELECT HOUR({$firstVisitTime}) as hour, COUNT(*) as visitors FROM ".$this->tablePrefix."_uniqueVisitors WHERE {$firstVisitTime} >= '".$startDateTime."' AND {$firstVisitTime} <= '".$endDateTime."' GROUP BY HOUR({$firstVisitTime})";
                break;
            case 'hourWiseBounceRate':
                $sql="SELECT hwb.hour, hwb.bounce, hwp.pageViews, hwv.visitors, ((hwb.bounce/hwp.pageViews)*100) AS bRatePageViews, ((hwb.bounce/hwv.visitors)*100) AS bRateVisitors FROM ".$this->tablePrefix."_hourWiseBounce hwb LEFT JOIN ".$this->tablePrefix."_hourWisePageViews hwp ON hwb.hour=hwp.hour LEFT JOIN ".$this->tablePrefix."_hourWiseVisitors hwv ON hwb.hour=hwv.hour";
                break;
        }
        if($sql!=''){
            $newResult=$this->wsmDB->get_results($sql,ARRAY_A);
            if($this->wsmDB->num_rows > 0){
                $sResult=serialize($newResult);
                if($action=='update' && $updateId!=0){
                    $newSql="UPDATE {$this->tablePrefix}_dailyHourlyReport SET content='{$sResult}' WHERE id={$updateId}";
                }else{
                     $newSql="INSERT INTO {$this->tablePrefix}_dailyHourlyReport (name, reportDate, content, timezone) VALUES ('{$name}','{$date}','{$sResult}','{$newTimeZone}')";
                     $this->fnLogError();
                }
                $this->wsmDB->query($newSql);
                return $newResult;
            }
        }
        return false;
    } 
    function fnGetMonthlyReportByYear($year){
        if(isset($this->arrCachedStats['monthly_'.$year]) && is_array($this->arrCachedStats['monthly_'.$year]) && count($this->arrCachedStats['monthly_'.$year])>0){
            return $this->arrCachedStats['monthly_'.$year];
        }       
        $newTimeZone=wsmCurrentGetTimezoneOffset();
        $arrLineData=array('pageViews'=>array(),'visitors'=>array(),'firstTime'=>array(),'Bounce'=>array(),'ppv'=>array(),'newVisitor'=>array(),'avgOnline'=>array(),'XLabels'=>array());
		
		$allInfo = $this->fnGetMonthlyReportByYearNameTimeZone( $year );
		$pageViews = $visitors = $firstTimeVisitors = $bounceRate = $arrKeys = array();
		if( $allInfo ){
			foreach( $allInfo as $info ){
				if( $info['month'] - 1 > count($visitors)  ){
					for( $i = 0; $i < ( $info['month'] - 1 ) ; $i++ ){
						$visitors[ $i ] = 0;
						$pageViews[ $i ] = 0;
						$firstTimeVisitors[ $i ] = 0;
						$bounceRate[ $i ] = 0;	
					}
				}
				$visitors[ $info['month'] ] = $info['total_visitors'];
				$pageViews[ $info['month'] ] = $info['total_page_views'];
				$firstTimeVisitors[ $info['month'] ] = $info['total_first_time_visitors'];
				$bounceRate[ $info['month'] ] = $info['total_bounce'];
			}
		}else{
	        $visitors=$this->fnGetMonthlyReportByYearNameTimeZone($year,'monthWiseVisitors',$newTimeZone);
	        $pageViews=$this->fnGetMonthlyReportByYearNameTimeZone($year,'monthWisePageViews',$newTimeZone);
	        $firstTimeVisitors=$this->fnGetMonthlyReportByYearNameTimeZone($year,'monthWiseFirstVisitors',$newTimeZone);
	        $bounceRate=$this->fnGetMonthlyReportByYearNameTimeZone($year,'monthWiseBounceRate',$newTimeZone);
		}
	    $arrKeys=array_keys($pageViews);	
		
        for($i=0;$i<12;$i++){
			if( $allInfo ){
				$j = $i + 1;
				if( $arrKeys[ $i ] != $j ){
					$visitors[ $i ] = 0;
					$pageViews[ $i ] = 0;
					$firstTimeVisitors[ $i ] = 0;
					$bounceRate[ $i ] = 0;
				}else{
					$visitors[ $i ] = $visitors[ $arrKeys[ $j ] ];
					$pageViews[ $i ] = $pageViews[ $arrKeys[ $j ] ];
					$firstTimeVisitors[ $i ] = $firstTimeVisitors[ $arrKeys[ $j ] ];
					$bounceRate[ $i ] = $bounceRate[ $arrKeys[ $j ] ];
				}
			}
			
            array_push($arrLineData['pageViews'],$pageViews[$arrKeys[$i]]);
            array_push($arrLineData['visitors'],$visitors[$arrKeys[$i]]);
            array_push($arrLineData['firstTime'],$firstTimeVisitors[$arrKeys[$i]]);
            array_push($arrLineData['Bounce'],(float)number_format_i18n($bounceRate[$arrKeys[$i]],2));
            $arrAddStats=wsmFnStatCalculations('Montly',$pageViews[$arrKeys[$i]],$firstTimeVisitors[$arrKeys[$i]],$visitors[$arrKeys[$i]]);            
            
            array_push($arrLineData['ppv'],(float)number_format_i18n($arrAddStats['ppv'],2));
            array_push($arrLineData['newVisitor'],(float)number_format_i18n($arrAddStats['newVisitor'],2));
            array_push($arrLineData['avgOnline'],(float)number_format_i18n($arrAddStats['avgOnline'],2));
            array_push($arrLineData['XLabels'],(string)($i+1));
        }
        $this->arrCachedStats['monthly_'.$year]=$arrLineData;
        return $arrLineData;
    }
    function fnGetMonthlyStatsByRange($fromYear,$toYear){
        $newTimeZone=  new DateTimeZone(wsmGetTimezoneString());
        $fromDate=$fromYear.'-01-01';
        $toDate=$toYear.'12-31';
        $begin = new DateTime( $fromDate,$newTimeZone );
        $end = new DateTime( $toDate,$newTimeZone );
        $end = $end->modify( '+1 year' ); 
        $interval = new DateInterval('P1Y');
        $daterange = new DatePeriod($begin, $interval ,$end);
        $arrDailyStats=array();
        foreach($daterange as $date){
             $year=$date->format('Y');
             $arrStats=$this->fnGetMonthlyReportByYear($year);
             array_push($arrDailyStats,array('date'=>$date->format("Y"),'stats'=>$arrStats));
        }
        return $arrDailyStats;
    }
    function fnGetTotalStatsByMonth($name,$recordMonth=""){
        if($recordMonth==""){
            $recordMonth=wsmGetCurrentDateByTimeZone('Y-m');
        }        
        switch($name){
            case 'monthWisePageViews':                
                $sql="SELECT pageViews FROM {$this->tablePrefix}_monthWisePageViews WHERE recordMonth='".$recordMonth."'";
                $columnName='pageViews';
                break;
            case 'monthWiseVisitors':                
                $sql="SELECT visitors FROM {$this->tablePrefix}_monthWiseVisitors WHERE recordMonth='".$recordMonth."'";  
                $columnName='visitors';
                break;
            case 'monthWiseFirstVisitors':
                $sql="SELECT visitors FROM {$this->tablePrefix}_monthWiseFirstVisitors WHERE recordMonth='".$recordMonth."'";
                $columnName='visitors';
                break;
            case 'monthWiseBounceRate':
                $sql="SELECT bRateVisitors FROM {$this->tablePrefix}_monthWiseBounceRate WHERE recordMonth = '".$recordMonth."'"; 
                $columnName='bRateVisitors';             
                break;
        }        
        $returnCount=0;       
        $count=$this->wsmDB->get_var($sql);
        if(!is_null($count)){
            $returnCount=$count;
        }        
        return $returnCount;  
    }
    function fnGetMonthlyReportByYearNameTimeZone($year,$name = '',$newTimeZone = ''){    
		
		$sqlQuery = 'SELECT DATE_FORMAT( date, "%c" ) AS month, total_page_views, total_visitors, total_first_time_visitors, total_bounce FROM '.$this->tablePrefix.'_monthwise_report WHERE normal = 1 AND YEAR(date) = '.$year.' ORDER BY date ASC';
		$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);          
		if( $result ){
			return $result;
		}
		
        $sqlQuery='SELECT * FROM '.$this->tablePrefix.'_yearlyMonthlyReport WHERE name="'.$name.'" AND reportYear="'.$year.'" AND timezone="'.$newTimeZone.'"';
        $result=$this->wsmDB->get_row($sqlQuery,ARRAY_A);          
        $currentDate=wsmGetCurrentDateByTimeZone('Y-m-d');
        $arrDate=explode('-',$currentDate);
        $arrResult=array();                
        if($this->wsmDB->num_rows > 0){
            $arrResult=unserialize($result['content']);
            if($year==$arrDate[0]){
                $currentMonth=$arrDate[0].'-'.$arrDate[1];
                $cMonthStats=$this->fnGetTotalStatsByMonth($name,$currentMonth);
                $arrResult[$currentMonth]=$cMonthStats;
            }
            return $arrResult;
        }
        $maxMonth=$year==$arrDate[0]?$arrDate[1]:12;
        for($i=0;$i<12;$i++){
            $month=$year.'-'.sprintf('%02d', ($i+1));
            $cMonthStats=$this->fnGetTotalStatsByMonth($name,$month);
            $arrResult[$month]=$cMonthStats;
        }
        if(count($arrResult)>0){
            $sResult=serialize($arrResult);
            $newSql="INSERT INTO {$this->tablePrefix}_yearlyMonthlyReport (name, reportYear, content, timezone) VALUES ('{$name}','{$year}','{$sResult}','{$newTimeZone}')";
            $this->wsmDB->query($newSql);
            $this->fnLogError();
            return $arrResult;
        }        
        return false;
    }
    function fnGetDailyReportByMonth($yearMonth){
		
        if(isset($this->arrCachedStats['daily_'.$yearMonth]) && is_array($this->arrCachedStats['daily_'.$yearMonth]) && count($this->arrCachedStats['daily_'.$yearMonth])>0){
            return $this->arrCachedStats['daily_'.$yearMonth];
        }
        $newTimeZone=wsmCurrentGetTimezoneOffset();
        $arrLineData=array('pageViews'=>array(),'visitors'=>array(),'firstTime'=>array(),'Bounce'=>array(),'ppv'=>array(),'newVisitor'=>array(),'avgOnline'=>array(),'XLabels'=>array());
		$allInfo = $this->fnGetDailyReportByMonthNameTimeZone( $yearMonth );
		if( $allInfo ){
			$checkFirstRow = true;
			foreach( $allInfo as $row ){
				if( $checkFirstRow ){
					$checkFirstRow = false;
					$currentDate = date('j', strtotime($row['date']));
					$currentMonthYear = date('Y-m', strtotime($row['date']));
					if( $currentDate > 1 ){
						for( $i = 1; $i <= $currentDate; $i++ ){
							$date = $currentMonthYear.( $i < 10 ? '0'.$i : $i );
							$visitors[ $date ] = 0;
							$pageViews[ $date ] = 0;
							$firstTimeVisitors[ $date ] = 0;
							$bounceRate[ $date ] = 0;
						}	
					}
				}
				
				$visitors[ $row['date'] ] = $row['total_visitors'];
				$pageViews[ $row['date'] ] = $row['total_page_views'];
				$firstTimeVisitors[ $row['date'] ] = $row['total_first_time_visitors'];
				$bounceRate[ $row['date'] ] = $row['total_bounce'];
			}
		}else{
	        /*$visitors=$this->fnGetDailyReportByMonthNameTimeZone($yearMonth,'dayWiseVisitors',$newTimeZone);
	        $pageViews=$this->fnGetDailyReportByMonthNameTimeZone($yearMonth,'dayWisePageViews',$newTimeZone);
	        $firstTimeVisitors=$this->fnGetDailyReportByMonthNameTimeZone($yearMonth,'dayWiseFirstVisitors',$newTimeZone);
	        $bounceRate=$this->fnGetDailyReportByMonthNameTimeZone($yearMonth,'dayWiseBounceRate',$newTimeZone);	*/
		}
        
        $arrKeys= is_array($pageViews) ? array_keys($pageViews) : $pageViews ;
        $noOfDays=wsmGetDateByTimeStamp('t',strtotime($yearMonth.'-01'));
        for($i=0;$i<$noOfDays;$i++){
            array_push($arrLineData['pageViews'],$pageViews[$arrKeys[$i]]);
            array_push($arrLineData['visitors'],$visitors[$arrKeys[$i]]);
            array_push($arrLineData['firstTime'],$firstTimeVisitors[$arrKeys[$i]]);
            array_push($arrLineData['Bounce'],(float)number_format_i18n($bounceRate[$arrKeys[$i]],2));
            $arrAddStats=wsmFnStatCalculations('Daily',$pageViews[$arrKeys[$i]],$firstTimeVisitors[$arrKeys[$i]],$visitors[$arrKeys[$i]]);            
            
            array_push($arrLineData['ppv'],(float)number_format_i18n($arrAddStats['ppv'],2));
            array_push($arrLineData['newVisitor'],(float)number_format_i18n($arrAddStats['newVisitor'],2));
            array_push($arrLineData['avgOnline'],(float)number_format_i18n($arrAddStats['avgOnline'],2));
            array_push($arrLineData['XLabels'],(string)($i+1));
        }
        $this->arrCachedStats['daily_'.$yearMonth]=$arrLineData;
        return $arrLineData;
    }   
    function fnGetDailyReportByMonthNameTimeZone($yearMonth,$name = '',$newTimeZone = ''){
		
		if( empty( $name ) ){
			$sqlQuery = 'SELECT dr.`date`, dr.total_page_views, dr.total_visitors, dr.total_first_time_visitors, dr.total_bounce FROM '.$this->tablePrefix.'_monthwise_report AS dr WHERE date_format(dr.date,"%Y-%m") = "'.$yearMonth.'"  AND dr.hour > 0';
			$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
			if( $result ){
				return $result;
			}else{
				$sqlQuery = 'SELECT dr.`date`, sum(dr.total_page_views) AS total_page_views , sum(dr.total_visitors) AS total_visitors, sum(dr.total_first_time_visitors) AS total_first_time_visitors , sum(dr.total_bounce) AS total_bounce FROM '.$this->tablePrefix.'_datewise_report AS dr WHERE date_format(dr.date,"%Y-%m") = "'.$yearMonth.'"  AND dr.hour > 0 GROUP BY dr.date';
				$result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
				if( $result ){
					return $result;
				}
			}	
		}
		
        $sqlQuery='SELECT * FROM '.$this->tablePrefix.'_monthlyDailyReport WHERE name="'.$name.'" AND reportMonthYear="'.$yearMonth.'" AND timezone="'.$newTimeZone.'"';
        $result=$this->wsmDB->get_row($sqlQuery,ARRAY_A);  
        $startDateTime=$yearMonth.'-01';
        $requestedMonthEnd=wsmGetDateByTimeStamp('Y-m-t',strtotime($startDateTime));
        $arrMonthEnd=explode('-',$requestedMonthEnd);
        $noOfDays=$arrMonthEnd[2];
        $endDateTime=$yearMonth.'-'.$noOfDays;
        $currentMonthEnd=wsmGetCurrentDateByTimeZone('Y-m-t');
        $visitLastActionTime="CONVERT_TZ(visitLastActionTime,'+00:00','".$newTimeZone."')";
        $action='insert';
        $updateId=0;
        if($this->wsmDB->num_rows > 0 && ($requestedMonthEnd!=$currentMonthEnd)){
            $arrResult=unserialize($result['content']);
            return $arrResult;
        }
        $sql='';
        $columnName='';
        switch($name){
            case 'dayWisePageViews':                
                $sql="SELECT recordDate, pageViews FROM {$this->tablePrefix}_dateWisePageViews WHERE recordDate >= '".$startDateTime."' AND recordDate<='".$endDateTime."'";
                $columnName='pageViews';
                break;
            case 'dayWiseVisitors':                
                $sql="SELECT recordDate, visitors FROM {$this->tablePrefix}_dateWiseVisitors WHERE recordDate >= '".$startDateTime."' AND recordDate<='".$endDateTime."'";
                $columnName='visitors';
                break;
            case 'dayWiseFirstVisitors':
                $sql="SELECT recordDate, visitors FROM {$this->tablePrefix}_dateWiseFirstVisitors WHERE 1 AND recordDate >= '".$startDateTime."' AND recordDate<='".$endDateTime."'";
                $columnName='visitors';
                break;
            case 'dayWiseBounceRate':
                $sql="SELECT recordDate, bRateVisitors FROM {$this->tablePrefix}_dateWiseBounceRate WHERE 1 AND recordDate >= '".$startDateTime."' AND recordDate<='".$endDateTime."'";   
                $columnName='bRateVisitors';             
                break;
        }
       
        if($sql!=''){            
            $retArray=array();
            for($i=0;$i<$noOfDays;$i++){
                $date=$yearMonth.'-'.sprintf('%02d',($i+1));
                $retArray[$date]=0;
            }        
            $newResult=$this->wsmDB->get_results($sql,ARRAY_A);            
            if($this->wsmDB->num_rows > 0){                              
                foreach($newResult as $key=>$row){
                    $retArray[$row['recordDate']]=(int)$row[$columnName];
                }               
                $sResult=serialize($retArray);
                if($requestedMonthEnd!=$currentMonthEnd){                   
                     $newSql="INSERT INTO {$this->tablePrefix}_monthlyDailyReport (name, reportMonthYear, content, timezone) VALUES ('{$name}','{$yearMonth}','{$sResult}','{$newTimeZone}')";
                     $this->wsmDB->query($newSql);
                     $this->fnLogError();
                }                                
            }
            return $retArray;
        }
        return false;
    }
    function fnGetHistoricalDayStatsByDays($days, $where = array()){
        if(isset($this->arrCachedStats['daily']) && is_array($this->arrCachedStats['daily']) && count($this->arrCachedStats['daily'])>0){
            //return $this->arrCachedStats['daily'];
        }
        $arrLineData=array('pageViews'=>array(),'visitors'=>array(),'firstTimeVisitors'=>array(),'Bounce'=>array(),'ppv'=>array(),'newVisitor'=>array(),'avgOnline'=>array());
        $pageViews=$this->fnGetDayWisePageViewsByNumberOfDays($days, $where );
        $visitors=$this->fnGetDayWiseVisitorsByNumberOfDays($days, $where );
        $firstTimeVisitors=$this->fnGetDayWiseFirstTimeVisitorCount($days, $where );
        $bounceRate=$this->fnGetDayWiseBounceRateByNumberOfDays($days, $where );
		
        $currentDayPageViews=$this->fnGetDayWisePageViewsByNumberOfDays(0, $where );
        $currentDayVisitors=$this->fnGetDayWiseVisitorsByNumberOfDays(0, $where );
        $currentDayFirstTimeVisitors=$this->fnGetDayWiseFirstTimeVisitorCount(0, $where );
        $currentDayBounceRate=$this->fnGetDayWiseBounceRateByNumberOfDays(0, $where );
		
		$pageViews = array_merge( $pageViews, $currentDayPageViews );		
		$visitors = array_merge( $visitors, $currentDayVisitors );		
		$firstTimeVisitors = array_merge( $firstTimeVisitors, $currentDayFirstTimeVisitors );		
		$bounceRate = array_merge( $bounceRate, $currentDayBounceRate );		
		
        //$pageViews=array_reverse($pageViews,true);
        //$visitors=array_reverse($visitors,true);
        //$firstTimeVisitors=array_reverse($firstTimeVisitors,true);
        $arrKeys=array_keys($pageViews);
        for($i=0;$i<=$days;$i++){
            @array_push($arrLineData['pageViews'],array($arrKeys[$i],$pageViews[$arrKeys[$i]]));
            @array_push($arrLineData['visitors'],array($arrKeys[$i],$visitors[$arrKeys[$i]]));
            @array_push($arrLineData['firstTimeVisitors'],array($arrKeys[$i],$firstTimeVisitors[$arrKeys[$i]]));
            @array_push($arrLineData['Bounce'],array($arrKeys[$i],(float)number_format_i18n($bounceRate[$arrKeys[$i]],2)));
            //$arrAddStats=wsmFnStatCalculations('Daily',$pageViews[$arrKeys[$i]],$firstTimeVisitors[$arrKeys[$i]],$visitors[$arrKeys[$i]]);            
            if(isset($arrAddStats['ppv']))
			{
            @array_push($arrLineData['ppv'],array($arrKeys[$i],(float)number_format_i18n($arrAddStats['ppv'],2)));
            @array_push($arrLineData['newVisitor'],array($arrKeys[$i],(float)number_format_i18n($arrAddStats['newVisitor'],2)));
            @array_push($arrLineData['avgOnline'],array($arrKeys[$i],(float)number_format_i18n($arrAddStats['avgOnline'],2)));
			}
        }
        $this->arrCachedStats['daily']=$arrLineData;
        return $arrLineData;
    }
    function fnGetHistoricalHourlyStatsByDay($day){
        $startDateTime=wsmGetDateByInterval('-'.$day.' days','Y-m-d');
        $newTimeZone=wsmCurrentGetTimezoneOffset();
        $arrStats=array('pageViews'=>array(),'visitors'=>array(),'firstTime'=>array());
        $pageViews=$this->fnGetHourlyReportByDateNameTimeZone($startDateTime,'hourWisePageViews',$newTimeZone);
        $visitors=$this->fnGetHourlyReportByDateNameTimeZone($startDateTime,'hourWiseVisitors',$newTimeZone);
        $firstTime=$this->fnGetHourlyReportByDateNameTimeZone($startDateTime,'hourWiseFirstVisitors',$newTimeZone);
        $arrStats['pageViews']=wsmFormatHourlyStats('pageViews',$pageViews);
        $arrStats['visitors']=wsmFormatHourlyStats('visitors',$visitors);
        $arrStats['firstTime']=wsmFormatHourlyStats('visitors',$firstTime);
        return $arrStats;
    } 
    function fnGetHistoricalHourlyStatsByDate($date){
        if(isset($this->arrCachedStats['hourlyDate_'.$date]) && is_array($this->arrCachedStats['hourlyDate_'.$date]) && count($this->arrCachedStats['hourlyDate_'.$date])>0){
            //return $this->arrCachedStats['hourlyDate_'.$date];
        }
        $startDateTime=$date;
        $newTimeZone=wsmCurrentGetTimezoneOffset();
        $arrStats=array('pageViews'=>array(),'visitors'=>array(),'firstTime'=>array());
		$allInfo = $this->fnGetHourlyReportByDateNameTimeZone( $startDateTime );
		if( $allInfo ){
			foreach( $allInfo as $key => $row ){
				$arrStats['pageViews'][]['pageViews'] = $row['total_page_views'];	
				$arrStats['visitors'][]['visitors'] = $row['total_visitors'];	
				$arrStats['firstTime'][]['visitors'] = $row['total_first_time_visitors'];	
				$arrStats['bounce'][]['bounce'] = $row['total_bounce'];	
			}
		}else{
	        $pageViews=$this->fnGetHourlyReportByDateNameTimeZone($startDateTime,'hourWisePageViews',$newTimeZone);
	        $visitors=$this->fnGetHourlyReportByDateNameTimeZone($startDateTime,'hourWiseVisitors',$newTimeZone);
	        $firstTime=$this->fnGetHourlyReportByDateNameTimeZone($startDateTime,'hourWiseFirstVisitors',$newTimeZone);
	        $bounce=$this->fnGetHourlyReportByDateNameTimeZone($startDateTime,'hourWiseBounce',$newTimeZone);
			
	        $arrStats['pageViews']=wsmFormatHourlyStats('pageViews',$pageViews);
	        $arrStats['visitors']=wsmFormatHourlyStats('visitors',$visitors);
	        $arrStats['firstTime']=wsmFormatHourlyStats('visitors',$firstTime);
	        $arrStats['bounce']=wsmFormatHourlyStats('bounce',$bounce);
		}
        $arrData=array('pageViews'=>array(),'visitors'=>array(),'firstTime'=>array(),'Bounce'=>array(),'ppv'=>array(),'newVisitor'=>array(),'avgOnline'=>array(),'XLabels'=>array());
        for($j=0;$j<24;$j++){
			 $bounceRate = 0;
            $arrStats['pageViews'][$j]['pageViews']=(int)$arrStats['pageViews'][$j]['pageViews'];
            $arrStats['visitors'][$j]['visitors']=(int)$arrStats['visitors'][$j]['visitors'];
            $arrStats['firstTime'][$j]['visitors']=(int)$arrStats['firstTime'][$j]['visitors'];
            
            if($arrStats['bounce'][$j]['bounce']>0 && $arrStats['visitors'][$j]['visitors']>0){
                $bounceRate=($arrStats['bounce'][$j]['bounce']/$arrStats['visitors'][$j]['visitors'])*100;
            }
            $arrAddStats=wsmFnStatCalculations('Hourly',$arrStats['pageViews'][$j]['pageViews'],$arrStats['firstTime'][$j]['visitors'],$arrStats['visitors'][$j]['visitors']);  
            array_push($arrData['visitors'],$arrStats['visitors'][$j]['visitors']);
            array_push($arrData['pageViews'],$arrStats['pageViews'][$j]['pageViews']);
            array_push($arrData['firstTime'],$arrStats['firstTime'][$j]['visitors']);
            array_push($arrData['Bounce'],(float)number_format_i18n($bounceRate,2));
            array_push($arrData['ppv'],(float)number_format_i18n($arrAddStats['ppv'],2));
            array_push($arrData['newVisitor'],(float)number_format_i18n($arrAddStats['newVisitor'],2));
            array_push($arrData['avgOnline'],(float)number_format_i18n($arrAddStats['avgOnline'],2));
            array_push($arrData['XLabels'],(string)$j);             
        }
        $this->arrCachedStats['hourlyDate_'.$date]=$arrData;
        return $arrData;
    }    
    function fnGetCurrentHourStats(){
        $h=wsmGetCurrentDateByTimeZone('H');
        $arrCurrentStats=array('pageViews'=>0,'visitors'=>0,'firstTime'=>0,'Bounce'=>0,'ppv'=>0,'newVisitor'=>0,'avgOnline'=>0);
        $arrCurrentStats['pageViews']=intval($this->fnGetTodaysPageViewsByHour($h));
        $arrCurrentStats['firstTime']=intval($this->fnGetTodaysVisitorsByHour('FirstTime',$h));
        $arrCurrentStats['visitors']=intval($this->fnGetTodaysVisitorsByHour(null,$h));
        $arrCurrentStats['Bounce']=number_format_i18n($this->fnGetTodaysBounceRateByHour($h),0);
        $arrAddStats=wsmFnStatCalculations('Hourly',$arrCurrentStats['pageViews'],$arrCurrentStats['firstTime'],$arrCurrentStats['visitors']);
        $arrCurrentStats['ppv']=number_format_i18n($arrAddStats['ppv'],2);
        $arrCurrentStats['newVisitor']=number_format_i18n($arrAddStats['newVisitor'],2);
        $arrCurrentStats['avgOnline']=number_format_i18n($arrAddStats['avgOnline'],2);        
        return $arrCurrentStats;
    }
    function fnGetCurrentDayStats(){
        $arrCurrentStats=array('pageViews'=>0,'visitors'=>0,'firstTime'=>0,'ppv'=>0,'newVisitor'=>0,'avgOnline'=>0,'Bounce'=>0);
        $todayPageViews=$this->fnGetTotalPageViewCount('Today');
        $todayVisitors=$this->fnGetTotalVisitorsCount('Today');
        $firstTimeVisitors=$this->fnGetFirstTimeVisitorCount('Today');
        $arrAddStats=wsmFnStatCalculations('Daily',$todayPageViews,$firstTimeVisitors,$todayVisitors);        
        $arrCurrentStats['pageViews']=number_format_i18n($todayPageViews,0);
        $arrCurrentStats['visitors']=number_format_i18n($todayVisitors,0);
        $arrCurrentStats['firstTime']=number_format_i18n($firstTimeVisitors,0);
        $arrCurrentStats['ppv']=number_format_i18n($arrAddStats['ppv'],2);
        $arrCurrentStats['newVisitor']=number_format_i18n($arrAddStats['newVisitor'],2);
        $arrCurrentStats['avgOnline']=number_format_i18n($arrAddStats['avgOnline'],2);
        $arrCurrentStats['Bounce']=$this->fnGetBounceRateByDate(wsmGetCurrentDateByTimeZone('Y-m-d'));
        return $arrCurrentStats;
    }
    function fnGetCurrentDayHourlyStats(){
        $arrCommon=array('pageViews'=>array(),'visitors'=>array(),'firstTime'=>array());
        $arrAdd=array('Bounce'=>array(),'ppv'=>array(),'newVisitor'=>array(),'avgOnline'=>array(),'XLabels'=>array(),'forecast'=>$arrCommon);
        $arrChartStats=array('today'=>array_merge($arrCommon,$arrAdd),'yesterday'=>$arrCommon,'day7before'=>$arrCommon,'day14before'=>$arrCommon);
        $arrTodaysStats=array_merge($arrCommon,array('Bounce'=>array()));
        $arrTodaysStats['pageViews']=$this->fnGetTodaysPageViewsByHour();
        $arrTodaysStats['firstTime']=$this->fnGetTodaysVisitorsByHour('FirstTime');
        $arrTodaysStats['visitors']=$this->fnGetTodaysVisitorsByHour();
        $arrTodaysStats['Bounce']=$this->fnGetTodaysBounceRateByHour();
        $arrTodaysStats['forecast']=$this->fnGetTodaysForeCastData();

        $arr1DayBefore=$this->fnGetHistoricalHourlyStatsByDay(1);
        $arr7DayBefore=$this->fnGetHistoricalHourlyStatsByDay(7);
        $arr14DayBefore=$this->fnGetHistoricalHourlyStatsByDay(14);
        for($i=0; $i<24; ++$i){
            $ppv=$newVisitor=$avgOnline=0;
            $h=wsmGetCurrentDateByTimeZone('H');
            if($i>$h){
                $tFirstTime=(int)$arrTodaysStats['forecast']['firstTime'][$i];
                $tVisitors=(int)$arrTodaysStats['forecast']['visitors'][$i];
                $tPageViews=(int)$arrTodaysStats['forecast']['pageViews'][$i];
            }else{
                $tFirstTime=(int)$arrTodaysStats['firstTime'][$i]['visitors'];
                $tVisitors=(int)$arrTodaysStats['visitors'][$i]['visitors'];
                $tPageViews=(int)$arrTodaysStats['pageViews'][$i]['pageViews'];
            }
            array_push($arrChartStats['today']['firstTime'],$tFirstTime);
            array_push($arrChartStats['today']['visitors'],$tVisitors);
            array_push($arrChartStats['today']['pageViews'],$tPageViews);
            array_push($arrChartStats['today']['Bounce'],(int)$arrTodaysStats['Bounce'][$i]['bounceRate']);
            array_push($arrChartStats['today']['XLabels'],(string)$i);
            $arrAddStats=wsmFnStatCalculations('Hourly',$tPageViews,$tFirstTime,$tVisitors);            
            array_push($arrChartStats['today']['ppv'],(float)number_format_i18n($arrAddStats['ppv'],2));
            array_push($arrChartStats['today']['newVisitor'],(float)number_format_i18n($arrAddStats['newVisitor'],2));
            array_push($arrChartStats['today']['avgOnline'],(float)number_format_i18n($arrAddStats['avgOnline'],2));

            array_push($arrChartStats['yesterday']['pageViews'],(int)$arr1DayBefore['pageViews'][$i]['pageViews']);
            array_push($arrChartStats['yesterday']['visitors'],(int)$arr1DayBefore['visitors'][$i]['visitors']);
            array_push($arrChartStats['yesterday']['firstTime'],(int)$arr1DayBefore['firstTime'][$i]['visitors']);

            array_push($arrChartStats['day7before']['pageViews'],(int)$arr7DayBefore['pageViews'][$i]['pageViews']);
            array_push($arrChartStats['day7before']['visitors'],(int)$arr7DayBefore['visitors'][$i]['visitors']);
            array_push($arrChartStats['day7before']['firstTime'],(int)$arr7DayBefore['firstTime'][$i]['visitors']);

            array_push($arrChartStats['day14before']['pageViews'],(int)$arr14DayBefore['pageViews'][$i]['pageViews']);
            array_push($arrChartStats['day14before']['visitors'],(int)$arr14DayBefore['visitors'][$i]['visitors']);
            array_push($arrChartStats['day14before']['firstTime'],(int)$arr14DayBefore['firstTime'][$i]['visitors']);
        }
        return $arrChartStats;
    }
    function fnGetLastDaysHourlyAverageStats($cDate="",$noOfDays=7){
        if($cDate=""){
            $cDate=wsmGetCurrentDateByTimeZone();
        }
        $arrStats=array('pageViews'=>array(),'visitors'=>array(),'firstTime'=>array());
        $arrAverage=array('pageViews'=>array(),'visitors'=>array(),'firstTime'=>array());
        for($i=0;$i<=$noOfDays;++$i){
            $arrDayBefore=$this->fnGetHistoricalHourlyStatsByDay($i);
            for($j=0; $j<24; ++$j){
                $arrStats['pageViews'][$j][$i]=(int)$arrDayBefore['pageViews'][$j]['pageViews'];
                $arrStats['visitors'][$j][$i]=(int)$arrDayBefore['visitors'][$j]['visitors'];
                $arrStats['firstTime'][$j][$i]=(int)$arrDayBefore['firstTime'][$j]['visitors'];
            }
        }
       /* for($j=0; $j<24; ++$j){
            $arrAverage['pageViews'][$j]=round(wsmGetAverageOfArray($arrStats['pageViews'][$j]));
            $arrAverage['visitors'][$j]=round(wsmGetAverageOfArray($arrStats['visitors'][$j]));
            $arrAverage['firstTime'][$j]=round(wsmGetAverageOfArray($arrStats['firstTime'][$j]));
        }*/
        return ($arrStats);
    }
    function fnGetDailyStatsByMonthRange($fromMonthYear,$toMonthYear){
        $newTimeZone=  new DateTimeZone(wsmGetTimezoneString());
        $fromDate=$fromMonthYear.'-01';
        $toDate=$toMonthYear.'-01';
        $begin = new DateTime( $fromDate,$newTimeZone );
        $end = new DateTime( $toDate,$newTimeZone );
        $end = $end->modify( '+1 month' ); 
        $interval = new DateInterval('P1M');
        $daterange = new DatePeriod($begin, $interval ,$end);
        $arrDailyStats=array();
         foreach($daterange as $date){
             $monthYear=$date->format('Y-m');
             $arrStats=$this->fnGetDailyReportByMonth($monthYear);
             array_push($arrDailyStats,array('date'=>$date->format("F Y"),'stats'=>$arrStats));
        }
        return $arrDailyStats;
    }
    function fnGetHourlyStatsByDateRange($fromDate,$toDate){
        $newTimeZone=  new DateTimeZone(wsmGetTimezoneString());
        $begin = new DateTime( $fromDate,$newTimeZone );
        $end = new DateTime( $toDate,$newTimeZone );
        $end = $end->modify( '+1 day' ); 
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);
        $arrHourlyStats=array();
        foreach($daterange as $date){
            $arrStats=$this->fnGetHistoricalHourlyStatsByDate($date->format('Y-m-d'));
            array_push($arrHourlyStats,array('date'=>$date->format("d F Y"),'stats'=>$arrStats));
        }
        return $arrHourlyStats;
    }
    function fnGetTodaysForeCastData(){
        if(isset($this->arrCachedStats['todayHourly']) && is_array($this->arrCachedStats['todayHourly']) && count($this->arrCachedStats['todayHourly'])>0){
            return $this->arrCachedStats['todayHourly'];
        }
        $noOfDays= $this->fnGetNDayFromFirstVisitActionTime();
        $arrAverage=$this->fnGetLastDaysHourlyAverageStats($noOfDays);
        
        $arrForeCast=array('pageViews'=>array(),'visitors'=>array(),'firstTime'=>array());
        $xArray=array_keys($arrAverage['pageViews'][0]);
        
       
        for($i=0;$i<24;$i++){
            $arrForeCast['firstTime'][$i]=round(wsmFnCalculateForeCastData($xArray,$arrAverage['firstTime'][$i],count($arrAverage['firstTime'][$i])+1));
            $arrForeCast['visitors'][$i]=round(wsmFnCalculateForeCastData($xArray,$arrAverage['visitors'][$i],count($arrAverage['visitors'][$i])+1));
            $arrForeCast['pageViews'][$i]=round(wsmFnCalculateForeCastData($xArray,$arrAverage['pageViews'][$i],count($arrAverage['pageViews'][$i])+1));
        }
        
       /*
        //$rArray=array_keys($arrAverage['pageViews'][0]);
        $h=wsmGetCurrentDateByTimeZone('H');
        if($h>1){
            //$rArray=array_slice($xArray,$h);
            //array_splice($xArray,$h);
           // array_splice($arrAverage['firstTime'],$h);
           // array_splice($arrAverage['visitors'],$h);
           // array_splice($arrAverage['pageViews'],$h);
        }
        $arrForeCast['firstTime']=array_fill(0,$h,0);
        $arrForeCast['visitors']=array_fill(0,$h,0);
        $arrForeCast['pageViews']=array_fill(0,$h,0);
          echo '<pre>';
        print_r($arrForeCast);
        echo '</pre>';

        $arrForeCast['firstTime']=array_merge($arrForeCast['firstTime'],array_map('round',wsmFnCalculateForeCastData($xArray,$arrAverage['firstTime'],$rArray)));
        $arrForeCast['visitors']=array_merge($arrForeCast['visitors'],array_map('round',wsmFnCalculateForeCastData($xArray,$arrAverage['visitors'],$rArray)));
        $arrForeCast['pageViews']=array_merge($arrForeCast['pageViews'],array_map('round',wsmFnCalculateForeCastData($xArray,$arrAverage['pageViews'],$rArray)));*/
        $this->arrCachedStats['todayHourly']= $arrForeCast;
        return $arrForeCast;
    }
    function fnGetFirstVisitDate(){
        $sql='SELECT MIN(visitLastActionTime) FROM '.$this->tablePrefix.$this->arrTables['LOG_UNIQUE'];
        $date=$this->wsmDB->get_var($sql);
        return $date;
    }
    function fnGetNDayFromFirstVisitActionTime($total=false){
        $date=$this->fnGetFirstVisitDate();
        $noOfDays=7;
        $diffInDays=7;
        if(!is_null($date) || $date!=''){
            $diffInDays=wsmDateDifference($date);
        }
        if($diffInDays<$noOfDays){
            $noOfDays=$diffInDays;
        }
        if($total){
            return $diffInDays;
        }
        return $noOfDays;
    }
    function fnGetRecentVisitedPages($limit=10){
       /* if(isset($this->arrCachedStats['recentPages']) && is_array($this->arrCachedStats['recentPages']) && count($this->arrCachedStats['recentPages'])>0){
            return $this->arrCachedStats['recentPages'];
        }*/
        $newTimeZone=wsmCurrentGetTimezoneOffset();
        $serverTime="CONVERT_TZ(serverTime,'+00:00','".$newTimeZone."')";
        $currentDate=wsmGetCurrentDateByTimeZone();
        $sql='SELECT *,TIMEDIFF("'.$currentDate.'",'.$serverTime.') as timeDiff FROM '.$this->tablePrefix.'_visitorInfo';
        $sql.=' WHERE '.$serverTime.' >= "'.wsmGetDateByInterval('-'.WSM_ONLINE_SESSION.' minutes').'"';
        $sql.=' ORDER BY serverTime DESC limit 0,'.$limit;        
        $result=$this->wsmDB->get_results($sql,ARRAY_A);
        return $result;
    }
    function fnGetTotalBrowsingPages(){
        $newTimeZone=wsmCurrentGetTimezoneOffset();        
        $serverTime="CONVERT_TZ(serverTime,'+00:00','".$newTimeZone."')";
        $sql='SELECT COUNT(DISTINCT urlId) FROM '.$this->tablePrefix.'_visitorInfo';
        $sql.=' WHERE '.$serverTime.' >= "'.wsmGetDateByInterval('-'.WSM_ONLINE_SESSION.' minutes').'"';
        //echo $sql.=' GROUP by URLId';                
        $result=$this->wsmDB->get_var($sql);
        return $result;
    }
    function fnGetPopularPages($limit=10){
        /*if(isset($this->arrCachedStats['recentPages']) && is_array($this->arrCachedStats['recentPages']) && count($this->arrCachedStats['recentPages'])>0){
            return $this->arrCachedStats['recentPages'];
        }*/
        $newTimeZone=wsmCurrentGetTimezoneOffset();
        $serverTime="CONVERT_TZ(VI.serverTime,'+00:00','".$newTimeZone."')";
        $sql='SELECT VI.URLId, VI.title, VI.url as fullURL, sum(VI.hits) as stotalViews, VI.visitLastActionTime FROM '.$this->tablePrefix.'_visitorInfo VI';
        $sql.=' WHERE '.$serverTime.' >= "'.wsmGetDateByInterval('-'.WSM_ONLINE_SESSION.' minutes').'"';
        $sql.=' GROUP by VI.URLId ORDER BY stotalViews DESC limit 0,'.$limit;                
        $result=$this->wsmDB->get_results($sql,ARRAY_A);
        return $result;
    }
    function fnGetPopularReferrers($limit=10){
        /*if(isset($this->arrCachedStats['recentPages']) && is_array($this->arrCachedStats['recentPages']) && count($this->arrCachedStats['recentPages'])>0){
            return $this->arrCachedStats['recentPages'];
        }*/
        $newTimeZone=wsmCurrentGetTimezoneOffset();
        $visitLastActionTime="CONVERT_TZ(visitLastActionTime,'+00:00','".$newTimeZone."')";
        $sql='SELECT LU.refererUrlId, CONCAT(UL.protocol,UL.url) as fullURL, '.$visitLastActionTime.' as visitLastActionTime,COUNT(LU.refererUrlId) AS totalReferrers FROM '.$this->tablePrefix.'_logUniqueVisit  LU  LEFT JOIN '.$this->tablePrefix.'_url_log UL ON LU.refererUrlId=UL.id  WHERE LU.refererUrlId!=0';
       // $sql='SELECT * FROM '.$this->tablePrefix.'_popularReferrers ORDER BY totalReferrers DESC';
        $sql.=' AND '.$visitLastActionTime.' >= "'.wsmGetDateByInterval('-'.WSM_ONLINE_SESSION.' minutes').'"';        
        $sql.='GROUP BY LU.refererUrlId ORDER BY totalReferrers DESC, visitLastActionTime  DESC limit 0,'.$limit;        
        $result=$this->wsmDB->get_results($sql,ARRAY_A);
        return $result;
    }
    function fnCorrectDatabaseTables(){
        $sql='SELECT * FROM '.$this->tablePrefix.$this->arrTables['LOG_URL'].' WHERE title IS NULL OR searchEngine=0 OR searchEngine IS NULL or toolBar=0 OR toolBar IS NULL';
        $result=$this->wsmDB->get_results($sql,ARRAY_A);
        $arrParam=array();
        $arrSiteURL=$this->fnReturnURLElements(site_url());
        foreach($result as $row){
            if($row['pageId']!=0 AND !is_null($row['pageId'])){
                $arrParam['title']=get_the_title( $row['pageId'] );
            }
            if($row['searchEngine']==0 OR is_null($row['searchEngine'])){
                $arrParam['searchEngine']=$this->fnGetSearchEngineID($row['url']);
            }
            if($row['toolBar']==0 OR is_null($row['toolBar'])){
				
				$myurl = !empty($row['url']) ? $this->fnGetToolBarID($row['url']) : 0;
                $arrParam['toolBar']= $myurl;
            }
            if($row['pageId']==0 OR is_null($row['pageId'])){
                $fullURL=$row['protocol'].$row['url'];
                if (strpos($row['protocol'].$row['url'],$arrSiteURL['url']) > -1) {
                    $pageId=wsmUrlToPostid($fullURL);
                    if($pageId!=0){
                        $arrParam['pageId']=wsmUrlToPostid($fullURL);
                    }
                }
            }
            $this->fnUpdateURLParameters($row['id'],$arrParam);
        }
        $sql='SELECT id,CONCAT(protocol,url) as fullURL FROM '.$this->tablePrefix.$this->arrTables['LOG_URL'].' WHERE url LIKE "%'.$arrSiteURL['url'].'%" and (pageId IS NULL OR pageId=0)';
        $result=$this->wsmDB->get_results($sql,ARRAY_A);
        foreach($result as $row){
            $pageId=wsmUrlToPostid($row['fullURL']);
            if($pageId!=0){
                $this->fnUpdateURLParameters($row['id'],array('pageId'=>$pageId));
            }
        }
    }
    
    /**
     * get referral site states
     */
    function getReferralSiteStats( $condition, $arrRequest ){

        $visitLastActionTime="CONVERT_TZ(PV.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        $conditional_query = '';
        //$sqlQuery="select date_format( PV.visitLastActionTime, '%Y-%m-%d') AS accessDate,  count( PV. visitId) AS total_visitors,  SUM(PV.totalViews)  AS total_page_views from  {$this->tablePrefix}_pageViews PV WHERE uv.refererUrlId = ".$arrRequest['id']." AND ";
		//if( isset($arrRequest['searchengine']) && $arrRequest['searchengine'] ){
			$arrRequest['from'] = wsmGetDateByInterval('-1 Month','Y-m-d');
		//}
		$arrParam = $arrRequest;
        switch($condition){                      
            case 'Normal':        
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                                      
                     $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
                }
            break;
            case 'Compare':
                if((isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date']))){
                    $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $conditional_query.=" {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
            break;
        }
        //$sqlQuery.="GROUP BY accessDate";
        //*/
        
        $sql = 'select date_format( PV.visitLastActionTime, "%Y-%m-%d") AS accessDate,  count( PV. visitId) AS total_visitors,  SUM(PV.totalViews)  AS total_page_views from '.$this->tablePrefix.'_pageViews PV WHERE PV.refererUrlId = '.$arrRequest['id'].'  AND '.$conditional_query.' GROUP BY accessDate order by accessDate DESC';
       // echo $sql."<br>";
        $referralUrlData = $this->wsmDB->get_results($sql,ARRAY_A);
        
        $conditional_query = str_replace( 'PV.visitLastActionTime', 'uv.firstVisitTime', $conditional_query ); //"CONVERT_TZ(uv.firstVisitTime,'+00:00','".WSM_TIMEZONE."')";
        $sql = 'select date_format( uv.firstVisitTime, "%Y-%m-%d") AS accessDate, count( uv.id ) AS total_unique_visitors from '.$this->tablePrefix.'_uniqueVisitors uv WHERE uv.refererUrlId = '.$arrRequest['id'].' AND '.$conditional_query.' GROUP BY accessDate order by accessDate DESC';
        
        //echo $sql."<br>";
        $referralUrlData2 = $this->wsmDB->get_results($sql,ARRAY_A);
        
        $dataResult = array();
        
        foreach( $referralUrlData as $data ){
            $dataResult[ $data['accessDate'] ] = array( 'total_visitors' => $data['total_visitors'], 'total_page_views' => $data['total_page_views'] );   
        }
        
        foreach( $referralUrlData2 as $data ){
            if( key_exists( $data['accessDate'], $dataResult )  ){
                $dataResult[ $data['accessDate'] ]['total_unique_visitors'] = $data['total_unique_visitors'];
            }else{
                $dataResult[ $data['accessDate'] ]['total_unique_visitors'] = 0;
            }
        }
        
        return $dataResult;
        
    }
	
	/*
	*	Get first and last access date
	*/
	function getReferralSiteStartEndVisit( $id ){
		$visitLastActionTime="CONVERT_TZ(PV.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
		$result = array( 'first_visit' => '', 'last_visit' => '' );
        $sql = 'select '.$visitLastActionTime.' AS accessDate from '.$this->tablePrefix.'_pageViews PV WHERE PV.refererUrlId = '.$id.' order by accessDate ASC LIMIT 0,1';
		$data = $this->wsmDB->get_var($sql);
		if( $data ){
			$result['first_visit'] = date('d M Y', strtotime( $data ) );
		}
        $sql = 'select '.$visitLastActionTime.' AS accessDate from '.$this->tablePrefix.'_pageViews PV WHERE PV.refererUrlId = '.$id.' order by accessDate DESC LIMIT 0,1';
		$data = $this->wsmDB->get_var($sql);
		if( $data ){
			$result['last_visit'] = date('d M Y', strtotime( $data ) );
		}
		return $result;
	}
	
	/**
	* Get visitor's info
	*/
	function getVisitorsInfo( $condition, $arrRequest ){

        $visitLastActionTime="CONVERT_TZ(LU.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        $conditional_query = '';
		
		$arrParam = $arrRequest;
        /*switch($condition){                      
            case 'Normal':        
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                                      
                     $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
                }
            break;
            case 'Compare':
                if((isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date']))){
                    $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $conditional_query.=" {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
            break;
        }*/
            if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                                      
                 $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
            }
		$data = array();
		$sql = 'SELECT LU.oSystemId AS id, OS.name, LU.deviceType, count(*) as total from '.$this->tablePrefix.'_logUniqueVisit LU 
		left join '.$this->tablePrefix.'_oSystems OS on LU.oSystemId = OS.id WHERE '.$conditional_query.' AND LU.oSystemId > 0 and OS.name is not null and OS.name!="-" group by LU.oSystemId order by total desc';

		$sqlOSReportQuery = 'SELECT LU.operating_system AS id, OS.name, sum(total_page_views) as total from '.$this->tablePrefix.'_datewise_report LU left join '.$this->tablePrefix.'_oSystems OS on OS.id = LU.operating_system WHERE '.$conditional_query.' and OS.name is not null and OS.name!="-" AND LU.operating_system > 0 group by LU.operating_system order by total desc';
		$sqlOSReportQuery = str_replace( 'LU.visitLastActionTime', 'LU.date', $sqlOSReportQuery ); 	
		//echo $sql."<br/>";
		//echo $sqlOSReportQuery."<br/>";
		$osResult=$this->wsmDB->get_results($sqlOSReportQuery,ARRAY_A);
		if( !$osResult ){
			$osResult = $this->wsmDB->get_results($sql,ARRAY_A);
		}
		$data[__('OS','wp-stats-manager')] = $osResult;
		
		$sql = 'SELECT LU.browserId AS id, WB.name, count(*) as total from '.$this->tablePrefix.'_logUniqueVisit LU 
		left join '.$this->tablePrefix.'_browsers WB on LU.browserId = WB.id WHERE '.$conditional_query.' AND LU.browserId > 0 and WB.name is not null and WB.name!="-" group by LU.browserId order by total desc';
		
		$sqlBroReportQuery = 'SELECT LU.browser AS id, WB.name, sum(total_page_views) as total from '.$this->tablePrefix.'_datewise_report LU left join '.$this->tablePrefix.'_browsers WB on LU.browser = WB.id WHERE '.$conditional_query.' AND LU.browser > 0 and WB.name is not null and WB.name!="-" group by LU.browser order by total desc';
		$sqlBroReportQuery = str_replace( 'LU.visitLastActionTime', 'LU.date', $sqlBroReportQuery ); 	
		//echo $sqlBroReportQuery."<br/>";
		$broResult=$this->wsmDB->get_results($sqlBroReportQuery,ARRAY_A);
		if( !$broResult ){
			$broResult = $this->wsmDB->get_results($sql,ARRAY_A);
		}
		$data[__('Browser','wp-stats-manager')] = $broResult;
		 
		$sql = 'SELECT LU.resolutionId AS id, LU.deviceType, WR.name, count(*) as total from '.$this->tablePrefix.'_logUniqueVisit LU 
		left join '.$this->tablePrefix.'_resolutions WR on LU.resolutionId = WR.id WHERE '.$conditional_query.' AND LU.resolutionId > 0 and WR.name is not null and WR.name!="-" group by LU.resolutionId order by total desc';
		
		$sqlResReportQuery = 'SELECT DR.screen AS id, LU.deviceType, WR.name, sum(total_page_views) as total from '.$this->tablePrefix.'_datewise_report DR 
		left join '.$this->tablePrefix.'_resolutions WR on DR.screen = WR.id left join '.$this->tablePrefix.'_logUniqueVisit LU on LU.id=DR.screen WHERE '.$conditional_query.' AND DR.screen > 0 and WR.name is not null and WR.name!="-" group by DR.screen order by total desc';
		$sqlResReportQuery = str_replace( 'LU.visitLastActionTime', 'DR.date', $sqlResReportQuery ); 
		if( ! current_user_can('edit_others_pages') ){
			$sqlResReportQuery .= ' LIMIT 0, 10 ';
		}
		//echo $sqlResReportQuery."<br/>";
		$resResult=$this->wsmDB->get_results($sqlResReportQuery,ARRAY_A);
		if( !$resResult ){
			$resResult = $this->wsmDB->get_results($sql,ARRAY_A);
		}
		$data[__('Screen Resolution','wp-stats-manager')] = $resResult;
		return $data;
			 
	}

    function fnGetReferralTotalVisitorsCountByOSBrowser($condition="",$arrParam=array()){               

        $visitLastActionTime="CONVERT_TZ(LU.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        $conditional_query = '';		
		//$arrParam = $arrRequest;
        /*switch($condition){                      
            case 'Normal':        
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                                      
                     $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
                }
            break;
            case 'Compare':
                if((isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date']))){
                    $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $conditional_query.=" {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
            break;
        }*/
            if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                                      
                 $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
            }
		$data = array();
      
		if( isset( $arrParam['rtype'] ) ){
		    switch( $arrParam['rtype'] ){
			    case 'OS':
				    $conditional_query .= ' AND LU.oSystemId = '.$arrParam['id'];
				    break;
			    case 'Browser':
				    $conditional_query .= ' AND LU.browserId = '.$arrParam['id'];
				    break;
			    case 'Screen Resolution':
				    $conditional_query .= ' AND LU.resolutionId = '.$arrParam['id'];
				    break;
		    }
        }
		$sql = 'SELECT LU.* from '.$this->tablePrefix.'_logUniqueVisit LU WHERE '.$conditional_query;
	  
        $result=$this->wsmDB->get_results($sql,ARRAY_A);        
        $count=$this->wsmDB->num_rows;
        if(is_null($count)){
            $count=0;
        }
        return intval($count);
    }
	
	
	/*
	*	Get first and last access date by visitor device
	*/
	function getReferralDeviceStartEndVisit( $id, $type ){
      
		if( isset( $type ) ){
		    switch( $type ){
			    case 'OS':
				    $conditional_query .= ' LU.oSystemId = '.$id;
				    break;
			    case 'Browser':
				    $conditional_query .= '  LU.browserId = '.$id;
				    break;
			    case 'Screen Resolution':
				    $conditional_query .= '  LU.resolutionId = '.$id;
				    break;
		    }
        }
		
		$result = array( 'first_visit' => '', 'last_visit' => '' );
		$sql = 'SELECT LU.firstActionVisitTime AS accessDate from '.$this->tablePrefix.'_logUniqueVisit LU WHERE '.$conditional_query.' order by accessDate ASC LIMIT 0,1';
		$data = $this->wsmDB->get_var($sql);
		if( $data ){
			$result['first_visit'] = date('d M Y', strtotime( $data ) );
		}
		$sql = 'SELECT LU.firstActionVisitTime AS accessDate from '.$this->tablePrefix.'_logUniqueVisit LU WHERE '.$conditional_query.' order by accessDate DESC LIMIT 0,1';
		$data = $this->wsmDB->get_var($sql);
		if( $data ){
			$result['last_visit'] = date('d M Y', strtotime( $data ) );
		}
		return $result;
	}

    function fnGetReferralTotalVisitorsCountByBroswerOS($condition="",$arrParam=array()){
             
        $sqlQuery="SELECT DISTINCT LU.visitorId FROM {$this->tablePrefix}{$this->arrTables['LOG_UNIQUE']} LU WHERE 1  AND ";
        $visitLastActionTime="CONVERT_TZ(LU.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        switch($condition){            
            case 'Compare':
                if(isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date'])){
                    $sqlQuery.=" {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            case 'Normal':
            case 'Range':
                if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                    
                     $sqlQuery.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";  
                }                
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $sqlQuery.="  {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
                if(wsmValidateDateTime($condition)){
                    $sqlQuery.="  {$visitLastActionTime} >= '".$condition.' 00:00:00'."'";
                }
                break;
        } 
		if( isset( $arrParam['rtype'] ) ){
			$id = 0;
			$compare = '>';
			if( isset( $arrParam['id'] ) ){
				$id = $arrParam['id'];
				$compare = '=';
			}

			switch( $arrParam['rtype'] ){
				case 'OS':
					$sqlQuery .= ' AND LU.oSystemId '.$compare.$id;
					break;
				case 'Browser':
					$sqlQuery .= ' AND LU.browserId '.$compare.$id;
					break;
				case 'Screen Resolution':
					$sqlQuery .= ' AND LU.resolutionId '.$compare.$id;
					break;
			}
		}
 
		//echo $sqlQuery.'<br />';
        $result=$this->wsmDB->get_results($sqlQuery,ARRAY_A);        
        $count=$this->wsmDB->num_rows;
        if(is_null($count)){
            $count=0;
        }
        return intval($count);
    }
	
	function fnGetReferralKeywords( $arrParam=array() ){
		$sqlQuery = "SELECT LV.id, LV.keyword, UL.protocol, UL.url , LV.serverTime, LUV.ipAddress
						FROM {$this->tablePrefix}{$this->arrTables['LOG_VISIT']} AS LV 
						LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} AS UL ON LV.refererUrlId = UL.id 
						LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_UNIQUE']} AS LUV ON LUV.id = LV.visitId
						WHERE UL.searchEngine > 0 AND LV.keyword != '-' AND LV.keyword != ''
						ORDER BY UL.id DESC";
		$sqlCountQuery = "SELECT COUNT(*) FROM {$this->tablePrefix}{$this->arrTables['LOG_VISIT']} AS LV 
						LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_URL']} AS UL ON LV.refererUrlId = UL.id 
						LEFT JOIN {$this->tablePrefix}{$this->arrTables['LOG_UNIQUE']} AS LUV ON LUV.id = LV.visitId
						WHERE UL.searchEngine > 0 AND LV.keyword != '-' AND LV.keyword != ''
						ORDER BY UL.id DESC";
		//$allResult=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		$totalRecords=$this->wsmDB->get_var($sqlCountQuery);
		$cPage=isset($arrParam['currentPage'])?$arrParam['currentPage']:1;
		$offset=($cPage-1)*WSM_PAGE_LIMIT;
		$sqlQuery.=" LIMIT {$offset},".WSM_PAGE_LIMIT;
		$arrResult['data']=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		if(isset($arrParam['currentPage']) && isset($arrParam['adminURL'])){
			$arrResult['pagination']=wsmFnGetPagination($totalRecords,$arrParam['currentPage'],$arrParam['adminURL']);
		}
		
		return $arrResult;           
	}
	
	function getReferralOSStats( $condition = 'Normal', $arrRequest = array() ){

        $visitLastActionTime="CONVERT_TZ(PV.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        $conditional_query = '';
        //$sqlQuery="select date_format( PV.visitLastActionTime, '%Y-%m-%d') AS accessDate,  count( PV. visitId) AS total_visitors,  SUM(PV.totalViews)  AS total_page_views from  {$this->tablePrefix}_pageViews PV WHERE uv.refererUrlId = ".$arrRequest['id']." AND ";
		//if( isset($arrRequest['searchengine']) && $arrRequest['searchengine'] ){
			$arrRequest['from'] = wsmGetDateByInterval('-1 Month','Y-m-d');
		//}
		
		$arrParam = $arrRequest;
        switch($condition){                      
            case 'Normal':        
            case 'Range':
                if( (isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from']) ) && 
					( isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']) )
				)
				{          
                     $conditional_query.=" AND {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
                }
            break;
            case 'Compare':
                if((isset($arrParam['date']) && $arrParam['date']!='' && wsmValidateDateTime($arrParam['date']))){
                    $conditional_query.="  AND  {$visitLastActionTime} >= '".$arrParam['date'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['date'].' 23:59:59'."'";
                }
            break;
            default:
                if($condition!='' && is_numeric($condition)){
                    $conditional_query.="  AND  {$visitLastActionTime} >= '".wsmGetDateByInterval('-'.$condition.' days','Y-m-d 00:00:00')."'";
                }
            break;
        }
        //$sqlQuery.="GROUP BY accessDate";
        //*/
		$sqlQuery = '';
		if( isset( $arrParam['rtype'] ) ){
			$id = 0;
			$compare = '>';
			if( isset( $arrParam['id'] ) ){
				$id = $arrParam['id'];
				$compare = '=';
			}
			switch( $arrParam['rtype'] ){
			case 'OS':
				$sqlQuery .= ' AND LU.oSystemId '.$compare.$id;
				break;
			case 'Browser':
				$sqlQuery .= ' AND LU.browserId '.$compare.$id;
				break;
			case 'Screen Resolution':
				$sqlQuery .= ' AND LU.resolutionId '.$compare.$id;
				break;
			}
		}
		
        $sql = 'select date_format( PV.visitLastActionTime, "%Y-%m-%d") AS accessDate,  count( PV. visitId) AS total_visitors,  
SUM(PV.totalViews)  AS total_page_views from '.$this->tablePrefix.'_pageViews AS PV LEFT JOIN '.$this->tablePrefix.'_logUniqueVisit AS LU on PV.visitid = LU.id WHERE 1=1  '.$conditional_query.$sqlQuery.' GROUP BY accessDate order by accessDate DESC';


        $referralUrlData = $this->wsmDB->get_results($sql,ARRAY_A);
        
        $conditional_query = str_replace( 'PV.visitLastActionTime', 'UV.firstVisitTime', $conditional_query ); 
		
		$sql = 'select date_format( UV.firstVisitTime, "%Y-%m-%d") AS accessDate, count( UV.id ) AS total_unique_visitors 
from '.$this->tablePrefix.'_uniqueVisitors AS UV LEFT JOIN '.$this->tablePrefix.'_logUniqueVisit AS LU on UV.id = LU.id WHERE 1=1  '.$conditional_query.$sqlQuery.' GROUP BY accessDate order by accessDate DESC';
		
        $referralUrlData2 = $this->wsmDB->get_results($sql,ARRAY_A);
        
        $dataResult = array();
        
        foreach( $referralUrlData as $data ){
            $dataResult[ $data['accessDate'] ] = array( 'total_visitors' => $data['total_visitors'], 'total_page_views' => $data['total_page_views'] );   
        }
        
        foreach( $referralUrlData2 as $data ){
            if( key_exists( $data['accessDate'], $dataResult )  ){
                $dataResult[ $data['accessDate'] ]['total_unique_visitors'] = $data['total_unique_visitors'];
            }else{
                $dataResult[ $data['accessDate'] ]['total_unique_visitors'] = 0;
            }
        }
		return $dataResult;
	}
	
	function getGeoLocationInfo( $condition, $arrRequest ){

        $visitLastActionTime="CONVERT_TZ(LUV.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        $conditional_query = '';
		
		$arrParam = $arrRequest;
        
        if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                                      
             $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
        }
		//LEFT JOIN {$this->tablePrefix}_uniqueVisitors AS UV ON UV.id = LUV.id 
		if( isset( $arrParam['location'] ) ){
			
			$sql = "select LUV.city AS name,  WC.alpha2Code,  LUV3.total_visitors,  SUM(PV.totalViews)  AS total_page_views, LUV2.total_unique_visitors from {$this->tablePrefix}_pageViews AS PV 
					LEFT JOIN {$this->tablePrefix}_logUniqueVisit AS LUV on PV.visitid = LUV.id 
					LEFT JOIN {$this->tablePrefix}_countries AS WC ON WC.id = LUV.countryId 
					LEFT JOIN ( select  LUV.city, count( UV.id ) AS total_unique_visitors 
					from {$this->tablePrefix}_uniqueVisitors AS UV 
					LEFT JOIN {$this->tablePrefix}_logUniqueVisit AS LUV on UV.id = LUV.id WHERE $conditional_query AND LUV.city != '' GROUP BY LUV.city ) AS LUV2 ON LUV2.city = LUV.city
					LEFT JOIN ( select LUV.city, count( LUV.id ) AS total_visitors 
					from {$this->tablePrefix}_logUniqueVisit AS LUV WHERE $conditional_query AND LUV.city != '' GROUP BY LUV.city ) AS LUV3 ON LUV3.city = LUV.city    
					WHERE $conditional_query AND LUV.city != '' GROUP BY LUV.city ORDER BY total_visitors DESC";
			
		}else{
				$sql = "select WC.id AS countryId, WC.name,  WC.alpha2Code,  LUV3.total_visitors,  						SUM(PV.totalViews)  AS total_page_views, LUV2.total_unique_visitors 
						FROM {$this->tablePrefix}_pageViews AS PV 
						LEFT JOIN {$this->tablePrefix}_logUniqueVisit AS LUV on PV.visitid = LUV.id 
						LEFT JOIN {$this->tablePrefix}_countries AS WC ON WC.id = LUV.countryId 
						LEFT JOIN ( select LUV.countryId, count( UV.id ) AS total_unique_visitors 
						FROM {$this->tablePrefix}_uniqueVisitors AS UV LEFT JOIN {$this->tablePrefix}_logUniqueVisit AS LUV on UV.id = LUV.id 
						WHERE $conditional_query AND LUV.countryId > 0 GROUP BY countryId ) AS LUV2 ON LUV2.countryId = LUV.countryId 
						LEFT JOIN ( select LUV.countryId, count( LUV.id ) AS total_visitors 
						FROM {$this->tablePrefix}_logUniqueVisit AS LUV
						WHERE $conditional_query AND LUV.countryId > 0 GROUP BY countryId ) AS LUV3 ON LUV3.countryId = LUV.countryId 
						WHERE $conditional_query AND LUV.countryId > 0 
						GROUP BY LUV.countryId ORDER BY total_visitors DESC";

		}
		
		
		if( isset( $arrParam['limit'] ) ){
			$sql .= ' LIMIT 0, '.$arrParam['limit'];	
		}		
		//echo $sql;
		$result = $this->wsmDB->get_results($sql,ARRAY_A);
		return $result;
	}
	
	function fnGetReferralTotalVisitorsCountByCountry( $arrParam  ){
        $visitLastActionTime="CONVERT_TZ(LUV.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        $conditional_query = '';
        if((isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from'])) && (isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']))){                                      
             $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
        }
		$compare = '=';
		if( isset( $arrParam['compare'] ) && $arrParam['compare'] ){
			$compare = $arrParam['compare'];
		}
		if( isset( $arrParam['location'] ) ){		
			$city = $arrParam['city'] ? $compare."'$arrParam[city]'" : $compare.'\'\'';
			$sql = "SELECT LUV.id FROM {$this->tablePrefix}_logUniqueVisit AS LUV 
				WHERE $conditional_query AND LUV.city $city";
		}else{
			$sql = "SELECT LUV.id FROM {$this->tablePrefix}_logUniqueVisit AS LUV 
				WHERE $conditional_query AND LUV.countryId $compare $arrParam[countryId]";
		}
		
		$result = $this->wsmDB->get_results($sql,ARRAY_A);
        $count=$this->wsmDB->num_rows;
        if(is_null($count)){
            $count=0;
        }
        return intval($count);
	
	}
	
	function getReferralCountryStats( $arrRequest ){
        $visitLastActionTime="CONVERT_TZ(PV.visitLastActionTime,'+00:00','".WSM_TIMEZONE."')";
        $conditional_query = '';
		
		$arrParam = $arrRequest;
        if( (isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from']) ) && 
			( isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']) )
		){          
             $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
        }
		
		$whereCondition = '';
		
		if( isset( $arrParam['city'] ) ){
			$whereCondition = 'LU.city = "'.$arrParam['city'].'"';
		}else{
			$whereCondition = 'LU.countryId = '.$arrParam['countryId'].'';	
		}
		
        $sql = 'select date_format( PV.visitLastActionTime, "%Y-%m-%d") AS accessDate,  count( PV. visitId) AS total_visitors,  
SUM(PV.totalViews)  AS total_page_views from '.$this->tablePrefix.'_pageViews AS PV LEFT JOIN '.$this->tablePrefix.'_logUniqueVisit AS LU on PV.visitid = LU.id WHERE '.$conditional_query.' AND '.$whereCondition.' GROUP BY accessDate order by accessDate DESC';

        $referralUrlData = $this->wsmDB->get_results($sql,ARRAY_A);
        
        $conditional_query = str_replace( 'PV.visitLastActionTime', 'UV.firstVisitTime', $conditional_query ); 
		
		$sql = 'select date_format( UV.firstVisitTime, "%Y-%m-%d") AS accessDate, count( UV.id ) AS total_unique_visitors 
from '.$this->tablePrefix.'_uniqueVisitors AS UV LEFT JOIN '.$this->tablePrefix.'_logUniqueVisit AS LU on UV.id = LU.id WHERE '.$conditional_query.' AND  '.$whereCondition.' GROUP BY accessDate order by accessDate DESC';
		
        $referralUrlData2 = $this->wsmDB->get_results($sql,ARRAY_A);
        
        $dataResult = array();
        
        foreach( $referralUrlData as $data ){
            $dataResult[ $data['accessDate'] ] = array( 'total_visitors' => $data['total_visitors'], 'total_page_views' => $data['total_page_views'] );   
        }
        
        foreach( $referralUrlData2 as $data ){
            if( key_exists( $data['accessDate'], $dataResult )  ){
                $dataResult[ $data['accessDate'] ]['total_unique_visitors'] = $data['total_unique_visitors'];
            }else{
                $dataResult[ $data['accessDate'] ]['total_unique_visitors'] = 0;
            }
        }
		return $dataResult;
	}

	function getReferralCountryStartEndVisit( $id, $where = 'countryId' ){
      
		$result = array( 'first_visit' => '', 'last_visit' => '' );
		$sql = 'SELECT LU.firstActionVisitTime AS accessDate from '.$this->tablePrefix.'_logUniqueVisit LU WHERE LU.'.$where.' = "'.$id.'" order by accessDate ASC LIMIT 0,1';
		$data = $this->wsmDB->get_var($sql);
		if( $data ){
			$result['first_visit'] = date('d M Y', strtotime( $data ) );
		}
		$sql = 'SELECT LU.firstActionVisitTime AS accessDate from '.$this->tablePrefix.'_logUniqueVisit LU WHERE LU.'.$where.' = "'.$id.'" order by accessDate DESC LIMIT 0,1';
		$data = $this->wsmDB->get_var($sql);
		if( $data ){
			$result['last_visit'] = date('d M Y', strtotime( $data ) );
		}
		return $result;
	}
	
	function getContentByURLStats( $arrRequest, $limit = 50 ){

		//define( WSM_PAGE_LIMIT, $limit );
		
        $visitLastActionTime="CONVERT_TZ(LV.serverTime,'+00:00','".WSM_TIMEZONE."')";
        $conditional_query = '';
		
		$arrParam = $arrRequest;
        if( (isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from']) ) && 
			( isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']) )
		){          
             $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
        }
		
		$site_url =  str_replace( array(  'http://www.', 'https://wwww.', 'http://', 'https://','www.'), array(), site_url() );
		$where_condition = 'UL.url LIKE  "'.$site_url.'%"';
		if( isset($arrParam['id']) ){
			$where_condition = ' UL.id = '. $arrParam['id'];
		}
		if( isset($arrParam['search']) && $arrParam['search'] ){
			$where_condition = ' UL.title LIKE "%'. $arrParam['search'] .'%"';
		} 
		$order = 'DESC';
		if( isset( $_GET['order'] ) ){
			$order =  sanitize_text_field($_GET['order']);
		}
		$sqlQuery = 'SELECT UL.id, UL.pageId, UL.protocol, UL.title, count( UL.id ) AS hits, UL.url FROM '.$this->tablePrefix.'_url_log AS UL 
					LEFT JOIN '.$this->tablePrefix.'_logVisit AS LV ON LV.URLId = UL.id
					WHERE '.$conditional_query.' AND '.$where_condition.' GROUP BY UL.id ORDER BY hits '.$order;

        $allResult=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
        $totalRecords=$this->wsmDB->num_rows;
		$arrResult['totalRecords'] = $totalRecords;
        $cPage=isset($arrParam['currentPage'])?$arrParam['currentPage']:1;
        $offset=($cPage-1)*$limit;
        $sqlQuery.=" LIMIT {$offset},".$limit;
        $arrResult['data']=$this->wsmDB->get_results($sqlQuery,ARRAY_A);
        if(isset($arrParam['currentPage']) && isset($arrParam['adminURL']) && $totalRecords > $limit ){
            $arrResult['pagination']=wsmFnGetPagination($totalRecords,$arrParam['currentPage'],$arrParam['adminURL'], $limit );
        }
		
        return $arrResult;           
		
	}
	
	function getContentByURLVisitors( $arrRequest ){
        $visitLastActionTime="CONVERT_TZ(firstActionVisitTime,'+00:00','".WSM_TIMEZONE."')";
        $conditional_query = '';
		
		$arrParam = $arrRequest;
        if( (isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from']) ) && 
			( isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']) )
		){          
             $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
        }
		
		$sqlQuery 	= 	'SELECT count(visitorId) As visitors, Count(Distinct visitorId) As newVisitors FROM '.$this->tablePrefix.'_logUniqueVisit WHERE visitEntryURLId='. $arrParam['id'] .' AND '.$conditional_query;
		//echo $sqlQuery.'<br />';
		$result		=	$this->wsmDB->get_row($sqlQuery,ARRAY_A);
		return $result;
	}
	
	function getContentByURLTotalRecords( $arrRequest ){
		$limit = 100;
        $visitLastActionTime="CONVERT_TZ(LV.serverTime,'+00:00','".WSM_TIMEZONE."')";
        $conditional_query = '';
		
		$arrParam = $arrRequest;
        if( (isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from']) ) && 
			( isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']) )
		){          
             $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
        }
		$site_url =  str_replace( array(  'http://www.', 'https://wwww.', 'http://', 'https://','www.'), array(), site_url() );
		
		$sqlQuery = 'SELECT count( UL.id ) AS total FROM '.$this->tablePrefix.'_url_log AS UL 
					LEFT JOIN '.$this->tablePrefix.'_logVisit AS LV ON LV.URLId = UL.id
					WHERE '.$conditional_query;
		
		if( isset( $arrParam['id'] ) ){
			$sqlQuery .= ' AND UL.id ='.$arrParam['id'];
		}
//		echo $sqlQuery.'<br />';
        $allResult=$this->wsmDB->get_row($sqlQuery,ARRAY_A);
//		print_r($allResult);
//echo $allResult['total'];
        return $allResult['total'];           
		
	}

	function getContentURLDayWiseStats( $arrRequest ){
        $visitLastActionTime="CONVERT_TZ(firstActionVisitTime,'+00:00','".WSM_TIMEZONE."')";
        $serverTime="CONVERT_TZ(serverTime,'+00:00','".WSM_TIMEZONE."')";
        $conditional_query = $conditional_query2 = '';
		
		$arrParam = $arrRequest;
        if( (isset($arrParam['from']) && $arrParam['from']!='' && wsmValidateDateTime($arrParam['from']) ) && 
			( isset($arrParam['to']) && $arrParam['to']!='' && wsmValidateDateTime($arrParam['to']) )
		){          
             $conditional_query.=" {$visitLastActionTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$visitLastActionTime}<='".$arrParam['to'].' 23:59:59'."'";
             $conditional_query2.=" {$serverTime} >= '".$arrParam['from'].' 00:00:00'."' AND {$serverTime}<='".$arrParam['to'].' 23:59:59'."'";
        }
		
		$sqlQuery = 'SELECT date_format( serverTime, "%Y-%m-%d") AS accessDate, count( URLId ) AS hits FROM '.$this->tablePrefix.'_logVisit WHERE '.$conditional_query2.' AND URLId = '. $arrParam['id'] .' GROUP BY accessDate ORDER BY accessDate DESC';
		
		$referralUrlData		=	$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		
		//print_r($referralUrlData);
		$sqlQuery 	= 	'SELECT date_format( firstActionVisitTime, "%Y-%m-%d") AS accessDate, count(visitorId) As visitors, Count(Distinct visitorId) As newVisitors FROM '.$this->tablePrefix.'_logUniqueVisit WHERE visitEntryURLId='. $arrParam['id'] .' AND '.$conditional_query.' GROUP BY accessDate ORDER BY accessDate DESC';
		
		$referralUrlData2		=	$this->wsmDB->get_results($sqlQuery,ARRAY_A);
		
        
        $dataResult = array();
        
        foreach( $referralUrlData2 as $data ){
            $dataResult[ $data['accessDate'] ] = array( 'total_visitors' => $data['visitors'], 'total_unique_visitors' => $data['newVisitors'] );   
        }
        
        foreach( $referralUrlData as $data ){
            if( key_exists( $data['accessDate'], $dataResult )  ){
                $dataResult[ $data['accessDate'] ]['total_page_views'] = $data['hits'];
            }else{
                $dataResult[ $data['accessDate'] ]['total_page_views'] = 0;
            }
        }
		return $dataResult;
	}

}
