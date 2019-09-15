<?php

//共通変数・関数ファイルを読込み
require('function.php');

//post送信されていた場合
if(!empty($_POST)){
  
  //変数にユーザー情報を代入
  $email = $_POST['email'];
  $pass= $_POST['pass'];
  $pass_re = $_POST['pass_re'];
  
  //未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');


if(empty($err_msg)){
  
  //email形式チェック
  validEmail($email,'email');
  //emailの最大文字数チェック
  validMaxLen($email, 'email');
  //email重複チェック
  validEmailDup($email);
  
  //パスワード半角英数字チェック
  validHalf($pass, 'pass');
  //パスワード最大文字数チェック
  validMaxLen($pass, 'pass');
  //パスワード最小文字数チェック
  validMinLen($pass, 'pass');
  
  //パスワード（再入力）の最大文字数チェック
  validMaxLen($pass_re,'pass_re');
  //パスワード（再入力）の最小文字数チェック
  validMinLen($pass_re, 'pass_re');

  if(empty($err_msg)){
    
    //パスワードとパスワード再入力があっているかをチェック
    validMatch($pass, $pass_re, 'pass_re');


  if(empty($err_msg)){
    
    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      $sql = 'INSERT INTO users (email,password,login_time,create_date) VALUES(:email,:pass,:login_time,:create_date)';
      $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT), ':login_time' => date('Y-m-d H:i:s'), ':create_date' => date('Y-m-d H:i:s'));
      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      
      //クエリ成功の場合
      if($stmt){
        debug('クエリ成功。');
        $_SESSION['msg_success'] = SUC04;
        
        //ログイン有効期限（デフォルトを1時間とする）
        $sesLimit = 60*60;
        // 最終ログイン日時を現在日時に
        $_SESSION['login_date'] =time();
        $_SESSION['login_limit'] = $sesLimit;
        //ユーザ-IDを格納
        $_SESSION['user_id'] = $dbh->lastInsertId();
        
        debug('セッション変数の中身：'.print_r($_SESSION,true));
        
        header("Location:mypage.php"); //マイページへ
      }else{
        error_log('クエリに失敗しました。');
        $err_msg['common'] = MSG07;
      }
      
    } catch (Exception $e) {
      error_log('エラー発生：' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
    
  }
}
}
}

?>
<?php
$siteTitle = 'ユーザー登録';
require('head.php'); 
?>

<body class="page-signup page-1colum">

  <!-- ヘッダー -->
  <?php
  require('header.php'); 
  ?>
  
  <!-- メインコンテンツ-->
  <div id="contents" class="site-width">
    
<!--    登録フォーム-->
    <section id="main">
     
     <div class="form-container">
     
      <form method="post" class="form">
      <h2 class="title">ユーザー登録</h2>
      
<!--       エラーメッセージ表示エリア-->
       <div class="area-msg">
         <?php if(!empty($err_msg['common'])) echo $err_msg['common']
         ?>
         </div>
         
         
<!--EMAILエリア-->
          <label　class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
            Email
            <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
        </label>
          <div class="area-msg">
            <?php
            if(!empty($err_msg['email'])) echo $err_msg['email'];
            ?>
          </div>
<!--パスワードエリア-->
        <label　class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
          パスワード
          <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
        </label>
        <div class="area-msg">
          <?php
          if(!empty($err_msg['pass'])) echo $err_msg['pass'];
          ?>
        </div>
<!--パスワード再入力エリア-->
          <label　class="<?php if(!empty($err_msg['pass_re'])) echo 'err'; ?>">
            パスワード
            <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
          </label>
          <div class="area-msg">
            <?php
            if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'];
            ?>
          </div>

          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="登録する">
          </div>
        
        
      </form>
      
      </div>
    </section>

  </div>

  <!-- footer -->
  <?php
  require('footer.php'); 
  ?>

