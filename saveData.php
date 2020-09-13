<?php

$host = "localhost";
$username = "root";
$pwd = "";
$dbname = "test";
$db = "";
function connectDB(){
    
    if(!empty($GLOBALS['db']))
        return $GLOBALS['db'];

    $GLOBALS['db'] = @mysqli_connect($GLOBALS['host'],$GLOBALS['username'],$GLOBALS['pwd'],$GLOBALS['dbname']);
    if(!$GLOBALS['db'])
        return false;
    else 
        return $GLOBALS['db'];
}

function saveData(){
    $db = connectDB();
    if(!$db) {
        echo  "Unable to connect to db, Please check credentials";die;
    }

    if(!empty($_POST['src'])){
        $sql = "INSERT INTO dual_list_src(src) values";
        foreach($_POST['src'] as $e){
            $sql .= "('".$db->real_escape_string($e)."'),";
        }
        
        $sql = rtrim($sql,",");
        $db->query("TRUNCATE TABLE dual_list_src");
        $db->query($sql);
    }
    
	if( empty($_POST['src'])){
		$db->query("TRUNCATE TABLE dual_list_src");
	}
	
    if(!empty($_POST['dest'])){
        $sql = "INSERT INTO dual_list_dest(dest) values";
        foreach($_POST['dest'] as $e){
            $sql .= "('".$db->real_escape_string($e)."'),";
        }
        $sql = rtrim($sql,",");
        $db->query("TRUNCATE TABLE dual_list_dest");
        $db->query($sql);
    }
	
	if(empty($_POST['dest'])){
		$db->query("TRUNCATE TABLE dual_list_dest");
	}
}

function fetchData($db){

    $src_obj = $db->query("SELECT src FROM dual_list_src");
    $src_data = $src_obj->fetch_all();
    $src_html = createHtml($src_data);

    $dest_obj = $db->query("SELECT dest FROM dual_list_dest");
    $dest_data = $dest_obj->fetch_all();
    $dest_html = createHtml($dest_data);
    return ['src'=>$src_html,'dest'=>$dest_html];
    
}

function createHtml($data){
    $html = "";
    if(!empty($data)){

        foreach($data as $row){
            
            if(!empty($row[0]))
                $value = $row[0];    

            $html  .= "<option value='".$value."'>".$value."</option>";
        }

    }
    return $html;
}

if(!empty($_POST['l']) && $_POST['l'] == 'save'){
    saveData();
}

echo json_encode(fetchData(connectDB()));die;
