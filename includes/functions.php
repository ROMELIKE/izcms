<?php 
	//xác định hằng số chó địa chỉ tuyệt đối
	define('BASE_URL','http://localhost:8080/izcms/');
	//kiểm tra xem kết quả có trả về đúng hay không?
	function confirm_query($result,$query){
		if(!$result){
			global $conn;
			die("Truy vấn: {$query}\n </br> MYSQLI Error: " .mysqli_error($conn));
		}
	}
	function redirect_to($page = 'index.php') {
        $url = BASE_URL . $page;
        header("location: {$url}");
        exit();
    }
	//the excerpt
	function the_excerpt($text){
		//chống khai thác XSS
		$sanitized=htmlentities($text,ENT_COMPAT,'UTF-8');
		if(strlen($sanitized)>400){
			$cutstring=substr($sanitized,0,400);
			$word=substr($sanitized,0,strrpos($cutstring,' '));
			return $word;
		}else{
			return $sanitized;
		}
	  
	}
	//tạo paragrahp
	function the_content($text){
		//chống khai thác XSS
		$sanitized=htmlentities($text,ENT_COMPAT,'UTF-8');
		return str_replace(array("\r\n","\n"),array("<p>","</p>"),$sanitized);
	}
	//validate id
	function validate_id($id){
		if(isset($id) && filter_var($id, FILTER_VALIDATE_INT, array('min_range' =>0))){
			$val_id = $id;
			return $val_id;
		}else{
			return NULL;
		}
	}
	function get_page_by_id($id){
		global $conn;
		$q= " SELECT p.page_name, p.page_id, p.content AS content, ";
        $q.= " DATE_FORMAT(p.post_on,'%b %d, %y') AS date, ";
        $q.= "  CONCAT_WS(' ',u.first_name,u.last_name) AS name, u.user_id ";
        $q.= " FROM pages AS p ";
        $q.= " INNER JOIN users AS u ";
        $q .= " USING (user_id) ";//lưu ý hay thiếu
        $q.= " WHERE p.page_id={$id} ";
        $q.= " ORDER BY date ASC LIMIT 1 ";
        $result=mysqli_query($conn,$q);
        confirm_query($result,$q); 
        return $result;
	}
	//tạo câu hỏi xác thực
	function captcha(){
		$qna=array(
			1=>array('question'=>'1 + 1','answer'=>2),
			2=>array('question'=>'3 + 1','answer'=>4),
			3=>array('question'=>'7 - 1','answer'=>6),
			4=>array('question'=>'5 - 1','answer'=>4),
			5=>array('question'=>'4 - 1','answer'=>3),
			6=>array('question'=>'4 + 5','answer'=>9),
			7=>array('question'=>'1 + 5','answer'=>6),
			8=>array('question'=>'2 + 1','answer'=>3)
			);
		//lấy ngẫu nhiên 1 key...1 or 2...or 3...
		$rand_key= array_rand($qna);
		$_SESSION['q']=$qna[$rand_key];
		return $question=$qna[$rand_key]['question'];
	}
	//
	function clean_email($value){
		//tạo biến lưu những nhận dạng, có dấu hiệu spam
		$suspects=array('to:','bcc:','cc:','content-type:','mime-version:','multipart-mixed:','content-transfer-encoding:');
	}

	function sendMail($title, $content, $nTo, $mTo,$diachicc=''){
    $nFrom = 'ROME system support';
    $mFrom = 'thetung.pdca@gmail.com';  //dia chi email cua ban 
    $mPass = 'khongbaogionhutchi12';       //mat khau email cua ban
    $mail             = new PHPMailer();
    $body             = $content;
    $mail->IsSMTP(); 
    $mail->CharSet   = "utf-8";
    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                    // enable SMTP authentication
    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";        
    $mail->Port       = 465;
    $mail->Username   = $mFrom;  // GMAIL username
    $mail->Password   = $mPass;               // GMAIL password
    $mail->SetFrom($mFrom, $nFrom);
    //chuyen chuoi thanh mang
    $ccmail = explode(',', $diachicc);
    $ccmail = array_filter($ccmail);
    if(!empty($ccmail)){
        foreach ($ccmail as $k => $v) {
            $mail->AddCC($v);
        }
    }
    $mail->Subject    = $title;
    $mail->MsgHTML($body);
    $address = $mTo;
    $mail->AddAddress($address, $nTo);
    $mail->AddReplyTo('thetung.pdca@gmail.com', 'ROME SYS');
    if(!$mail->Send()) {
        return 0;
    } else {
        return 1;
    }
}
//kiểm tra người dùng đăng nhập hay chưa?
function is_logged_in(){
	if(!isset($_SESSION['uid'])){
		redirect_to('login.php');
	}
 }
 //kiểm tra xem có phải là admin hay không
function is_admin(){
	if(isset($_SESSION['user_level'])&&$_SESSION['user_level']==2){
		return true;
	}else{
		return false;
	}
}
//hàm xèm có phải admin truy cạp vào hay không?
function admin_access(){
	if(!is_admin()){
		redirect_to();
	}
}
//hàm đếm số lượt xem bài viết
function view_counter($pg_id){
	$ip=$_SERVER['REMOTE_ADDR'];
	global $conn;
	//truy vấn CSDL để xem page view
	$q="SELECT number_views,user_ip FROM page_view WHERE page_id={$pg_id}";
	$r=mysqli_query($conn,$q);
	confirm_query($r,$q);
	if(mysqli_num_rows($r)>0){
		//nếu kết quả trả về, update page view
		list($number_view,$db_ip)=mysqli_fetch_array($r,MYSQLI_NUM);
		if($db_ip != $ip){
			//nếu ip không bị trùng , + lượt view
			$q="UPDATE page_view SET number_views=(number_views+1) WHERE page_id={$pg_id} LIMIT 1";
			$r=mysqli_query($conn,$q);
			confirm_query($r,$q);
		}//nếu ip trùng nhau, không làm gì hết
		
	}else{
		//nếu chưa có page , insert vào table
		$q="INSERT INTO page_view (page_id,number_views,user_ip) VALUES({$pg_id},1,'{$ip}')";
		$r=mysqli_query($conn,$q);
		confirm_query($r,$q);
		$number_view=1;
	}
	return $number_view;
}//END
//hàm lấy ra thông tin user từ user id
function fetch_user($user_id){
	global $conn;
	if(isset($user_id)&&filter_var($user_id,FILTER_VALIDATE_INT,array('min_range'=>1))){
		$q="SELECT * FROM users WHERE user_id={$user_id}";
	$r=mysqli_query($conn,$q);
	confirm_query($r,$q);
	//nếu có user
	if(mysqli_num_rows($r)>0){
		return $result=mysqli_fetch_assoc($r);
	}else{
		//nếu không có user trả về false
		return FALSE;
	}
	}else{
		return FALSE;
	}
	
}
function fetch_all_user($sort){
	global $conn;
	$q="SELECT user_id,first_name,last_name,email,user_level,registration_date FROM users";
    $q.=" ORDER BY {$sort} ASC ";
    $r=mysqli_query($conn,$q);confirm_query($r,$q);
    			if(mysqli_num_rows($r)>0){
    				$result=array();
    				while ($row=mysqli_fetch_assoc($r)) {
    					$result[]=$row;
    				}
    				return $result;
    			}else{
    				return false;
    			}
}
//hàm kiểm tra sắp xếp
function sort_by($order){
	switch ($order) {
    				case 'fn':
    					$order_by='first_name';
    					break;
    				case 'ln':
                        $order_by='last_name';
                        break;
    				case 'em':
    					$order_by='email';
    					break;
    				
    				default:
    					$order_by='registration_date';
    					break;
    			}/*END Switch*/
    			return $order_by;
}//END hàm

function current_page($url_name){
	if(basename($_SERVER['SCRIPT_NAME'])==$url_name){
		echo "class='here'";
	}
}
?>
