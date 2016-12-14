<?php 

require_once('AuthRegisterClass.php');

class RegisterFormError extends AuthRegister {
	
    public $mf;

	public function __construct($classArg) {
    	$this->mf = $classArg; //MailFormClassのObject
        $this->session = $_SESSION[$this->mf->type]; 
    }
    
    //空入力に対しての返すテキスト
    private function returnRequireEmpty($strArg) {
    	$strArg = $this->mf->arTitleName[$strArg];
    	return '『' . $strArg . '』は必須です。'; 
    }
    
    public function checkMail($checkOrNot = TRUE, $isContact = FALSE) {
    
    	$keyName = ($isContact) ? 'mail_add' : 'username';
    
    	$mailError = array();
        $mail_add = $this->session[$keyName][1];
        $titleName = $this->mf->arTitleName;
        
        if($checkOrNot):
            if (trim($mail_add) =='') {
               $mailError[] = $this->returnRequireEmpty($keyName);
            }
            else {
                $pattern = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD';
                if (! preg_match($pattern, $mail_add)) {
                    $mailError[] = '『' .$titleName[$keyName] . '』形式が不正です。';
                }
                //else if(!is_page('edit') && ! is_page('login')) {
                else if ($this->mf->isType('register') || $this->mf->isType('reset-passwd')) {
                    $exist = $this->mf->getAuthId($mail_add);
                    
                    if($this->mf->isType('reset-passwd')) {
                        if(! $exist)
                            $mailError[] = '『' .$titleName[$keyName] . '』が登録されたものではありません。';
                    }
                    else if($exist) {
                        $mailError[] = '『' .$titleName[$keyName] . '』が既に登録されています。別のメールアドレスを入力して下さい。';
                    }
                }
            }
        endif;
        
        return $mailError;
    }
    
    public function checkPassword($checkOrNot = TRUE) {
    	$passError = array();
        
        if(isset($this->session['password'][1])) {
        	//echo "error";
            //$password = $this->deCrypt($this->session['password'][1]); //パスは暗号化してkeyと一緒にsessionに入れているので復元する
            $password = $this->session['password'][1];
            $titleName = $this->mf->arTitleName;
            
            if($checkOrNot):
            	
                if (trim($password) =='') {
                	//echo "error2";
                   $passError[] = $this->returnRequireEmpty('password');
                }
                else {
                	//echo "error3";
                    if($password != '' && strlen($password) < 6) {
                        $passError[] = '『' .$titleName['password'] . '』は6文字以上を入力して下さい。';
                    }
                }
            endif;
        }
//        else {
//        	echo "error2";
//        }
        
        return $passError;
    }
    
    private function checkName() {//nick_name
    	$nameError = array();
        
        $titleName = $this->mf->arTitleName;
        $name = $this->session['nick_name'][1];
        
        if (trim($name) =='') {
           $nameError[] = $this->returnRequireEmpty('nick_name');
        }
        elseif (mb_strlen($name) > 20) {
            //$error[] = mb_strlen($name);
            $nameError[] = '『' . $titleName['nick_name'] . '』の文字数は全角20文字以内で入力して下さい。';
        }
        
        return $nameError;
    }

	//nameとmailのチェック関数（ここはどれも共通なので１つの関数にする）
	private function checkNameAndMail() {
    	
        //MailFormオブジェクト
        //$m = $this->mfObj;
        
        //$titleName = $this->mf->arTitleName;
        
		//$mail_add = $this->session['username'][1];
        //$password = $this->session['password'][1];
    	//$name = $this->session['nick_name'][1];
        
        $error = array();
        
        //mail address
        $error = $this->checkMail();
        
        //password
        $error = array_merge($error, $this->checkPassword());
        
        //nick_name
        $error = array_merge($error, $this->checkName());
//        if (trim($name) =='') {
//           $error[] = $this->returnRequireEmpty('nick_name'); 
//        }
//        elseif (mb_strlen($name) > 20) {
//            //$error[] = mb_strlen($name);
//            $error[] = '『' . $titleName['nick_name'] . '』の文字数は全角20文字以内で入力して下さい。';
//        }
        
        return $error;
    }
    
    public function checkLogin() {
    	$loginError = array();
        $username = $this->session['username'][1];
        //$password = $this->deCrypt($this->session['password'][1]); //パスは暗号化してkeyと一緒にsessionに入れているので復元する
        $password = $this->session['password'][1];
        $titleName = $this->mf->arTitleName;

        //mail address
        $loginError = $this->checkMail();
        
        //password
        $loginError = array_merge($loginError, $this->checkPassword());
        
        //DB
        if(count($loginError) == 0):

            $sql = "select * from auth where username = ? AND password = ?";
            $data = array($username, md5($password)); //getRowの引数は必ず配列 queryは単独変数でも可
            $res = $this->mf->db->getRow($sql, $data, DB_FETCHMODE_ASSOC);
            
            if (PEAR::isError($res))
                exit($res->getMessage());
            
            if(!$res)  //$res == ''
                $loginError[] = 'ログインID、もしくはパスワードが間違っています。';
        
        endif;
        
        $_SESSION[$this->mf->type]['errors'] = $loginError;
        
        return $loginError;
    }
    
    //日付　過去と形式をチェックする
    private function checkDatePastAndCorrect($date_time, $strArg) {
    	$array = array();
    	
        //setlocale(LC_TIME, 'ja_JP.UTF-8');
        date_default_timezone_set('Asia/Tokyo');
        
        //書式:2016年1月1日 8時をstrtotime用のフォーマットに変換
        $date_time = str_replace(array('年','月'), '/', $date_time);
        $date_time = str_replace('日', '', $date_time);
        $date_time = str_replace('時', ':00:00', $date_time);
        
        //$time = mktime(); 年月各数値からtimestampを取得する
        
        //dateからパースする関数 おかしい書式にはwarningが返されるのでそれを利用
        $dp = date_parse_from_format('Y-m-d H:i:s', $date_time);
        
        if( strtotime($date_time) < time()) {
            $array[] = '『'.$this->mf->arTitleName[$strArg]. '』は過去の日時は指定できません。';
        }
        if($dp['warning_count'] != 0) {
            $array[] = '『'.$this->mf->arTitleName[$strArg]. '』は正しい日時を入力して下さい。';
        }
        
        return $array;
    }
    
    
    //名前とメールアドレス以外のエラー処理
    private function checkOtherError() {
    	$error = array();
        
    	$tel_num = $this->session['tel_num'][1];
        $address = $this->session['address'][1];
        $company_name = $this->session['company_name'][1];
        $postcode = $this->session['postcode'][1];
        
        //$date_key = 'first_date_time';
        //${$date_key} = $this->session[$date_key][1]; //書式:2016年1月1日 8時が入っている
        //$first_date_time = $this->session['first_date_time'][1]; //書式:2016年1月1日 8時が入っている
        
        //必須項目のエラーチェックはここに追加する
        //会社
        if (trim($company_name) =='') {
           $error[] = $this->returnRequireEmpty('company_name');
        }
        //郵便番号
        if (trim($postcode) =='') {
           $error[] = $this->returnRequireEmpty('postcode');
        }
        //住所チェック
        if (trim($address) =='') {
           $error[] = $this->returnRequireEmpty('address');
        }
        //TEL番号チェック
        if (trim($tel_num) =='') {
           $error[] = $this->returnRequireEmpty('tel_num'); 
        }
        
        //First Dateチェック
//        if(strpos($first_date_time, '--') !== FALSE) {
//            $error[] = $this->returnRequireEmpty($date_key);
//        }
//        else { //過去日付と形式チェック
//        	$error = array_merge($error, $this->checkDatePastAndCorrect($first_date_time, $date_key));
//        }
        
        if($this->mf->isType('inspect')) { //視察フォーム時のエラーチェック
        	
            //Second Date
            $date_key = 'second_date_time';
        	${$date_key} = $this->session[$date_key][1];
        	//$second_date_time = $this->session['second_date_time'][1];
        	
            if(strpos($second_date_time, '--') !== FALSE) {
            	$error[] = $this->returnRequireEmpty($date_key);
        	}
            else {
            	$error = array_merge($error, $this->checkDatePastAndCorrect($second_date_time, $date_key));
            }
            
            //視察目的
            $purpose = $this->session['purpose'][1];
            if (trim($purpose) =='') {
               $error[] = $this->returnRequireEmpty('purpose'); 
            }
        }
        
        return $error; //array()
    }
    
    private function checkContactOtherError() {
    	$error = array();
        
    	$title = $this->session['title'][1];
        $company_name = $this->session['company_name'][1];
        $post_code = $this->session['post_code'][1];
        $address = $this->session['address'][1];
        $tel_num = $this->session['tel_num'][1];
        
//        'title' => 'お問い合わせ内容',
//        		'company_name' => '会社名',
//                'department' => '部署名',
//                'nick_name' => 'お名前',
//                'mail_add' => 'メールアドレス',
//                'post_code' => '郵便番号',
//                'address' => '住所',
//                'tel_num' => '電話番号',
//                'comment' => 'コメント',
        
        
        //$date_key = 'first_date_time';
        //${$date_key} = $this->session[$date_key][1]; //書式:2016年1月1日 8時が入っている
        //$first_date_time = $this->session['first_date_time'][1]; //書式:2016年1月1日 8時が入っている
        
        //必須項目のエラーチェックはここに追加する
        
        if (trim($title) =='--') {
           $error[] = $this->returnRequireEmpty('title');
        }
        
        //会社名チェック
        if (trim($company_name) =='') {
           $error[] = $this->returnRequireEmpty('company_name');
        }
        
        //nick_name
        $error = array_merge($error, $this->checkName());
        
        //mail address
        $error = array_merge($error, $this->checkMail(true, true));
        
        //郵便番号チェック
        if (trim($post_code) =='') {
           $error[] = $this->returnRequireEmpty('post_code');
        }
        //住所チェック
        if (trim($address) =='') {
           $error[] = $this->returnRequireEmpty('address');
        }
        
        //TEL番号チェック
        if (trim($tel_num) =='') {
           $error[] = $this->returnRequireEmpty('tel_num'); 
        }
        
        //First Dateチェック
//        if(strpos($first_date_time, '--') !== FALSE) {
//            $error[] = $this->returnRequireEmpty($date_key);
//        }
//        else { //過去日付と形式チェック
//        	$error = array_merge($error, $this->checkDatePastAndCorrect($first_date_time, $date_key));
//        }
        
        if($this->mf->isType('inspect')) { //視察フォーム時のエラーチェック
        	
            //Second Date
            $date_key = 'second_date_time';
        	${$date_key} = $this->session[$date_key][1];
        	//$second_date_time = $this->session['second_date_time'][1];
        	
            if(strpos($second_date_time, '--') !== FALSE) {
            	$error[] = $this->returnRequireEmpty($date_key);
        	}
            else {
            	$error = array_merge($error, $this->checkDatePastAndCorrect($second_date_time, $date_key));
            }
            
            //視察目的
            $purpose = $this->session['purpose'][1];
            if (trim($purpose) =='') {
               $error[] = $this->returnRequireEmpty('purpose'); 
            }
        }
        
        return $error; //array()
    }
    


	//最終実行の関数
    public function checkAllError($checkOrNot) { //$checkOrNot : エラー省略時にfalseを渡す
        //global $ar;
        //$ar = $this->getTitleAndName();
        
//        if($this->type == 'inspect')
//	        $array =  $this->arInspect;
//    	elseif($this->type == 'newshop')
//        	$array = $this->arNewshop;

		//$this->mf->setDataToSession();

        //エラーチェック＆出力
        $eAr = array();
        
        if($checkOrNot) { //$checkOrNot : エラー省略時にfalseを渡す
        	
            $eAr = $this->checkNameAndMail();
            
            //他エラー
            if(! $this->mf->isType('contact') ) { //コンタクト以外
                $eAr = array_merge($eAr, $this->checkOtherError());
            }
        }
        
        return $eAr; 
        
        //入力画面の先頭でエラーチェックをして、ページ遷移をさせないので、errorをSESSIONに入れる必要がない
        //エラー数確認 jQueryの場合は不要
//        if (count($error) > 0) {
//            $_SESSION['error'] = $error;
//            //return $error;
//            
//            //header前にechoを入れるとエラーになる echoで出力となるのでエラーになる	
//            //header('HTTP/1.1 303 See Other');
//            //header('Location: ' . $dirname);
//        }
//        else {
//            $_SESSION['error'] = NULL;
//            //return NULL;
//        }
        
        //$errors = isset($_SESSION['error']) ? $_SESSION['error'] : NULL;
        //return $_SESSION['error'];
    }
    
    public function checkContactNameMail($checkOrNot) {
        
        $error = array();
        
        if($checkOrNot) { //$checkOrNot : エラー省略時にfalseを渡す
            //mail address
            //$error = $this->checkMail(true, true);
            
            //nick_name
            //$error = array_merge($error, $this->checkName());
            
            $error = $this->checkContactOtherError();
        }
        
        return $error;
    }
    
    public function checkPasswordError($checkOrNot) {
    
    }



} //class End





