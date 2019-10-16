

<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　Ajax　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// Ajax処理
//================================

// post送信があり、ユーザーIDがあり、ログインしている状態
if(isset($_POST['productId']) && isset($_SESSION['user_id']) && isLogin()){
  debug('POST送信があります。');
  $p_id = $_POST['productId'];
  debug('商品ID：'.$p_id);
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    
    $sql = 'SELECT * FROM `like` WHERE product_id = :p_id AND user_id = :u_id';
    $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $resultCount = $stmt->rowCount();
    debug($resultCount);
   
    if(!empty($resultCount)){
      //レコードを削除する
      $sql = 'DELETE FROM `like` WHERE product_id = ;p_id AND user_id = :u_id';
      $data = array(':u_id' =>$_SESSION['user_id'],':p_id' => $p_id);
      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      $_SESSION['msg_success'] = SUC01;
    }else{
      //レコードを挿入する
      $sql = 'INSERT INTO `like` (product_id, user_id, create_date) VALUES (:p_id, :u_id, :data)';
      $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id, ':date' =>date('Y-m-d H:i:s' ));
      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
    }
    
  } catch (Exception $e) {
    error_log('エラー発生：' . $e->getMessage());
  }
}
debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
