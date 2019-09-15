<?php

//共通変数・関数ファイル読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　トップページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// カレントページのGETパラメータを取得
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] :  1; //デフォルトは1ページ目
// カテゴリー
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
//ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';
//パラメータに不正な値が入っているかチェック
if(!is_int($currentPageNum)){
  error_log('エラー発生：指定ページに不正な値が入りました');
  header("Location:index.php"); //トップページへ
}
// 表示件数
$listSpan = 20;
// 現在の表示レコード先頭算出
$currentMinNum = (($currentPageNum-1)*$listSpan); 
//1ページ目なら（1-1）*20 = 0  2ページ目なら（2-1）*20 ＝20
//　DBから商品データを取得
$dbProductData = getProductList($currentMinNum, $category, $sort);
// DBからカテゴリデータを取得
$dbCategoryData = getCategory();
//debug('現在のページ：'.$currentPageNum);
//debug('フォーム用のDBデータ：'.print_r($dbFormData,true));
//debug('カテゴリデータ：'.print_r($dbCategoryData,true));

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = 'HOME';
require('head.php');
?>

<body class="page-home page-2colum">


<!-- メニュー -->
<?php
require('header.php'); 
?>

<!-- メインコンテンツ -->
<div id="contents" class="site-width">

<!--サイドバー-->
<section id="sidebar">
  <form>
    <h1 class="title">カテゴリー</h1>
    <div class="selectbox">
      <span class="icn_select"></span>
      <select name="category">
        <option value="1">自然</option>
        <option value="2">食事</option>
      </select>
    </div>
    <input type="submit" value="検索">
  </form>
  
</section>

<!-- Main -->
<section id="main">
<div class="search-title">
  <div class="search-left">
    <span class="total-num"><?php echo sanitize($dbProductData['total']); ?></span>件の商品が見つかりました
  </div>
  <div class="search-right">
    <span class="num"><?php (!empty($dbProductData['data'])) ? $currentMinNum+1 : 0; ?></span> - <span class="num"><?php echo $currentMinNum+count($dbProductData['data']); ?></span> 件 / <span class="num"><?php echo sanitize($dbProductData['total']); ?></span>件中
  </div>
</div>
<div class="panel-list">
  <?php
  foreach($dbProductData['data'] as $key => $val):
  ?>
  <a href="productDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id'.$val['id'] : '?p_id='. $val['id']; ?>" class="panel">
    <div class="panel-head">
      <img src="<?php echo sanitize($val['pic1']); ?>" alt="<?php echo sanitize($val['name']); ?>">
    </div>
    <div class="panel-body">
      <p class="panel-title"><?php echo sanitize($val['name']); ?> </p>
    </div>
  </a>
  <?php
  endforeach;
  ?>
</div>

  <?php pagination($currentPageNum, $dbProductData['total_page']); ?>
  
  </section>
</div>

<!-- footer -->
<?php
require('footer.php'); 
?>