<?php
/*
*源码作者:阿豆子(QQ:1355967533)
*项目开始时间:2020年2月3日
*
*When I wrote this code, only God and I knew what it meant.
*Only God knows now.
*/

/*
配置信息
注:变量名请勿乱改，否则可能导致程序无法正常运行
*/
@header('content-type:text/html;charset=utf-8;');
$Upimages = 'true';
//上传图片 默认true
$Uppassword = "adouzi";
//上传文件密码 默认adouzi

//统计总使用次数
$counter = intval(@file_get_contents("times.txt"));
$counter++;
$fp = fopen("times.txt", "w");
fwrite($fp, $counter);
fclose($fp);

//ajax获取信息专用接口
if (@$_GET['id'] == "msg") {
  echo @file_get_contents("news.txt");
  exit();
} else if (@$_GET['id'] == "message") {
  //软件获取信息接口
  $AppMsg = @file_get_contents("news.txt");
  //正则匹配
  @preg_match_all('/<p class="message">(.*?)<\/p>/', $AppMsg, $AppMsg);
  foreach ($AppMsg[1] as $count => $Appmsg) {
    @preg_match('/<user>(.*?)<\/user>/', $Appmsg, $name);
    @preg_match('/<time>(.*?)<\/time>/', $Appmsg, $time);
    @preg_match('/<msg>(.*?)<\/msg>/', $Appmsg, $msg);
    //设置数组
    $arr = array(
      'name' => $name[1],
      'time' => $time[1],
      'msg' => $msg[1],
    );
    //数组套娃
    $Return[$count] = $arr;
  }
  //输出json
  echo json_encode($Return);
  //print_r($Return);
  exit();
} else if (@$_GET['id'] == "[清除]") {
  //清除用户名
  setcookie("用户名", '');
  //删除聊天文件
  unlink("news.txt");
  //刷新ajax参数
  ajaxfile();


  $UFiles = scandir(dirname(__FILE__) . "/upload/images/");
  foreach ($UFiles as $un) {
    if ($un == "." or $un == "..") {
    } else {
      unlink("upload/images/$un");
    }
  }
  //处理图片
  header("Location:index.php");
  exit;
} else if (@$_GET['id'] == 'systemset') {
  echo '
<!DOCTYPE html>
<html>
  <head>
	  <meta charset="utf-8">
	  <link rel="icon" href="http://cdn.u1.huluxia.com/g3/M01/EA/20/wKgBOV4k70aAVFkMAACSNDCr8Yk703.jpg_160x160.jpeg">
	  <title>高级设置</title>
  </head>
  <body>
	<fieldset>
	<form action="?id=set" method="post">
	  <p>链接地址:<input type="url" name="url" placeholder="自定义背景图片" value="' . @$_COOKIE['背景图片'] . '"></p>
	  <hr>
	  <p>请选择颜色:<input type="color" name="color" value="' . @$_COOKIE['背景颜色'] . '" placeholder="自定义背景颜色"></p>
	  <hr>
	  <p>自定义css:<textarea type="text" name="styles" autocomplete="off" placeholder="自定义css，不懂就别写!">' . @$_COOKIE['自定义样式'] . '</textarea></p>
	  <hr>
	  <input type="submit" style="margin-left:80%;" value="提交">
	</form>
	<hr>
	<br>
	<a href="?id=unset">重置</a><br>
	<a href="?id=cd">菜单</a><br>
	</fieldset>
	</body>
</html>		
';
  exit;
} else if (@$_GET['id'] == 'set') {
  @setcookie('背景图片', $_POST['url'], time() + 3600 * 24 * 365, '/');
  @setcookie('背景颜色', $_POST['color'], time() + 3600 * 24 * 365, '/');
  @setcookie('自定义样式', $_POST['styles'], time() + 3600 * 24 * 365, '/');
  echo '
<script>
alert("     配置成功！\n如果没有效果请检查浏览器cookie是否开启");
window.location.href="index.php";
</script>';
  exit;
} else if (@$_GET['id'] == 'unset') {
  //重置高级设置
  @setcookie('背景图片', null, -1, '/');
  @setcookie('背景颜色', null, -1, '/');
  @setcookie('自定义样式', null, -1, '/');
  echo '
<script>
alert("     配置成功！\n如果没有效果请手动删除cookie");
window.location.href="index.php";
</script>';
  exit;
} else if (@$_GET['id'] == 'weilai') {
  echo '
<html> 
<head>
<meta charset="utf-8">
<link rel="icon" href="http://cdn.u1.huluxia.com/g3/M01/EA/20/wKgBOV4k70aAVFkMAACSNDCr8Yk703.jpg_160x160.jpeg">
<title>展望未来</title>
<style>
*{
text-align:center;
}
a{
font-size:8pt;
text-decoration:none;
}
</style>
</head>
<body>
<p id="wl_1" onclick="wl_1();">联机系统</p>
<p id="wl_2" onclick="wl_2();">后台系统</p>
<p id="wl_3" onclick="wl_3();">调用api</p>
<br>
<p id="jy" onclick="jy();">开发者寄语:</p>
<script>
function wl_1(){
	var timeElement = document.getElementById("wl_1");
	timeElement.innerHTML = "可以和别的聊天室联机。";	
}
function wl_2(){
	var timeElement = document.getElementById("wl_2");
	timeElement.innerHTML = "整一个后台，不用每次改代码。";	
}
function wl_3(){
	var timeElement = document.getElementById("wl_3");
	timeElement.innerHTML = "方便对接，做成app什么的。";	
}
function jy(){
	var timeElement = document.getElementById("jy");
	timeElement.innerHTML = "<p>2024/10/06</p><p>作者从摔坏的旧手机中翻到了几年前写的代码，然后修了点bug。</p><p>2021/02/11</p><p>这套系统的开发目的是快捷聊天!<br>其实刚写这个的时候我的目的很单一，就是和朋友在课上聊聊天。因为我们那边的电脑每次重启都会重置，所以才搞的这个。<br>这个源码本身也是疫情的时候上网课的时候无聊写的，代码也是看我和我朋友的需求写的，有不足之处，需要的自己改吧。<br>现在所有娱乐课程都没了，也没多少时间写代码了。<br>每个版本我都在葫芦侠和b站更新了，需要自提。<br></p>";	
}
</script>
<p style="margin-top:3%; font-size:5pt; text-shadow: 3px 5px 5px #656B79;">Copyright © ' . date("Y") . ' Design by 阿豆子</p>
<a href="?id=cd">菜单</a><br>
</body>
</html>
';
  exit;
} else if (@$_GET['id'] == 'system') {
  //系统设置菜单
  echo '<html> <head><meta charset="utf-8"><link rel="icon" href="http://cdn.u1.huluxia.com/g3/M01/EA/20/wKgBOV4k70aAVFkMAACSNDCr8Yk703.jpg_160x160.jpeg"><title>设置</title><style>
*{
text-align:center;
text-decoration:none;
}
</style></head><body>';
  if (@$_COOKIE['ajax'] == 'on') {
    echo '<a href="index.php?ajax=off" class="a">关闭自动刷新</a><br>';
  } else if (@$_COOKIE['ajax'] == 'off') {
    echo '<a href="index.php?ajax=on" class="a">打开自动刷新</a><br>';
  }

  echo '
<a href="?id=[清除]" onclick="alert(\'此操作会清空聊天室所有信息，请谨慎操作!\');">清除所有信息</a><br>
<a href="?id=systemset">高级设置</a><br>
<a href="?id=sc">上传文件</a><br>
<a href="?id=源码信息">源码信息</a><br>
<a href="?id=cd">菜单</a><br>';
  exit;
} else if (@$_GET['id'] == 'sc') {
  /*上传文件*/
  echo '
<html> 
<head>
<meta charset="utf-8">
<link rel="icon" href="http://cdn.u1.huluxia.com/g3/M01/EA/20/wKgBOV4k70aAVFkMAACSNDCr8Yk703.jpg_160x160.jpeg">
<title>上传文件</title>
<style>
*{
text-align:center;
text-decoration:none;
}
</style>
</head>
<body>
<form action="?id=sc" method="post" enctype="multipart/form-data">
<p>请选择要上传的文件:<br><input type="file" name="myFile"></p>
<p>请输入密码:<input type="password" name="password" autocomplete="new-password" value="' . @$_POST['password'] . '"></p>
<input type="submit" value="上传">
</form>
';

  if (@$_POST['password'] == $Uppassword) {
    $wenjian = $_FILES['myFile']['name'];
    $lj = $_FILES['myFile']['tmp_name'];
    $wen = strrpos($wenjian, '.');
    //查找字符串在另一字符串中最后一次出现的位置
    $jian = substr($wenjian, $wen);
    //返回字符串的一部分
    @copy($lj, 'upload/' . base64_encode(time() . $wenjian) . "$jian");
    echo '<a href="index.php" class="a">返回</a><br></body></html>';
  } else {
    if (@$_POST == null) {
      echo "<p>上传试试吧！</p>";
    } else {
      echo '<script>alert("你无权操作");</script>';
    }
    echo '<a href="index.php" class="a">返回</a><br></body></html>';
  }
  exit;
} else if (@$_GET['id'] == '源码信息') {
  /*源码基本信息*/
  if ($Upimages == 'true') {
    $YN = 'yes';
  } else {
    $YN = 'no';
  }
  echo '
<html> 
<head>
<meta charset="utf-8">
<link rel="icon" href="http://cdn.u1.huluxia.com/g3/M01/EA/20/wKgBOV4k70aAVFkMAACSNDCr8Yk703.jpg_160x160.jpeg">
<title>源码信息</title>
<style>
*{
text-align:center;
}
a{
font-size:8pt;
text-decoration:none;
}
</style>
</head>
<body>
<h3>源码信息</h3>
<p>源码版本:<font color="red"><b>v7.01</b></font><br>
统计：共使用' . $counter . '次<br>
是否允许上传图片:' . $YN . '</p><br>
<a href="?id=gengxin">检查更新</a><br>
<a href="?id=weilai">展望未来</a><br>
<a href="index.php">返回</a>';
  //检测upload目录
  if (!is_dir('upload')) {
    mkdir('upload');
    echo '<p>系统检测到没有上传目录，已自动创建</p>';
  }
  //检测upload/images目录
  if (!is_dir('upload/images')) {
    mkdir('upload/images');
    echo '<p>系统检测到没有图片目录，已自动创建</p>';
  }

  echo '<p style="margin-top:3%; font-size:5pt; text-shadow: 3px 5px 5px #656B79;">Copyright © ' . date("Y") . ' Design by 阿豆子</p></body></html>';
  exit;
} else if (@$_GET['id'] == 'gengxin') {
  //检查更新
  //此功能待维护
  $yuan = @file_get_contents("http://www.adouzi56.ml/api/gengxin/?id=gengxin-lts&banben=7.01");
  @preg_match('/<p id="zhuangtai">(.*?)<\/p>/', $yuan, $ma);
  if (@$ma[1] == 'ok') {
    echo '<html> <head><meta charset="utf-8">
<link rel="icon" href="http://cdn.u1.huluxia.com/g3/M01/EA/20/wKgBOV4k70aAVFkMAACSNDCr8Yk703.jpg_160x160.jpeg"><title>源码最新信息</title><style>
*{
text-align:center;
}
a{
font-size:8pt;
text-decoration:none;
}
</style></head><body>';
    echo $yuan;
  } else {
    echo '<html style="text-align:center;"><head><meta charset="utf-8"><link rel="icon" href="http://cdn.u1.huluxia.com/g3/M01/EA/20/wKgBOV4k70aAVFkMAACSNDCr8Yk703.jpg_160x160.jpeg"><title>错误</title></head><body><p><font color="red">error!<br>获取版本信息失败!<br>请手动查询<br>请到葫芦侠社区or哔哩哔哩搜索php聊天室</font></p></body></html>';
  }
  echo '<a href="index.php" class="a">返回</a><br></body></html>';
  exit;
}

/*用户协议*/
if (@$_GET['id'] == "xy") {
  $time = date("Y年m月d日H时i分s秒");
  echo '
<script>
alert("用户协议:\n 请勿涉及黄，赌，毒等违法的事\n 请勿在本站聊天框输入html和php代码\n 2020年2月6日-' . $time . ' \n \n by 阿豆子");
window.location.href="index.php";
</script>
';
}
?>

<html>

<head>
  <meta charset="utf-8">
  <link rel="icon" href="http://cdn.u1.huluxia.com/g3/M01/EA/20/wKgBOV4k70aAVFkMAACSNDCr8Yk703.jpg_160x160.jpeg">
  <title>阿豆子的聊天室</title>
  <style>
    body {
      text-align: center;
      
      font-size: 15pt;
      background-color: <?php
                        //自定义背景颜色
                        if ($_COOKIE['背景颜色'] == null) {
                          echo '#1f9baa';
                        } else {
                          echo $_COOKIE['背景颜色'];
                        }
                        ?>;
    }

    h1 {
      margin-top: 8%;
    }

    .main {
      width: 90%;
      margin: 3% 5%;
      border-radius: 5px / 5px;
    }




body {
    text-align:center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background: url('https://api.adou-web.eu.org/?id=background_mobile') no-repeat center center fixed;
    background-size: cover;
    color: #333;
    line-height: 1.6;
}

/* 响应式设计 - 手机尺寸 */
@media screen and (min-width: 320px) and (max-width: 767px) {
    body {
        background: url('https://api.adou-web.eu.org/?id=background_mobile') no-repeat center center fixed;
        background-size: cover;
    }
}

/* 响应式设计 - 平板尺寸 */
@media screen and (min-width: 768px) and (max-width: 1024px) {
    body {
        background: url('https://api.adou-web.eu.org/?id=background_tablet') no-repeat center center fixed;
        background-size: cover;
    }
}

/* 响应式设计 - 大屏幕设备尺寸 */
@media screen and (min-width: 1025px) {
    body {
        background: url('https://api.adou-web.eu.org/?id=background_pc') no-repeat center center fixed;
        background-size: cover;
    }
}

body {
      <?php
                              //自定义背景图片
                              if (@$_COOKIE['背景图片'] == null) {
                                //echo 'https://api.adou-web.eu.org/?id=background_mobile';
                              } else {
                                echo "background-image: url('".@$_COOKIE['背景图片']."') no-repeat center center fixed;
        background-size: cover;";
                              }
                              ?>
      

    }
    .form {
      text-align: center;
      margin: 3% 30%;
    }

    .a {
      font-size: 8pt;
      text-align: center;
    }

    .from-input {
      color: black;
      border-radius: 15px / 15px;
    }

    .form-submit {
      color: black;
      border-radius: 50px / 50px;
      margin-left: 60%;
      height: 25px;
      width: 75px;
    }

    a {
      text-decoration: none;
    }

    .msg {
      height: 60%;
      width: 90%;
      margin-left: 5%;
      border-radius: 10px / 10px;
      color: black;
      text-align: left;
      font-size: 9pt;
      overflow: auto;
      white-space: pre-wrap;
      text-overflow: ellipsis;
      background-color: white;

    }

    .fieldset {
      border-radius: 8px / 8px;
    }

    .ajaxcanshu {
      font-size: 50%;
    }

    .textarea-input {
      border-radius: 10px / 10px;
      color: black;
      font-size: 9pt;
    }

    .message {
      border-radius: 10px / 10px;
      border-style: solid;
      border-width: thin;
      /*margin-top:1%;*/
      width: 70%;
      margin-left: 3%;
    }

    <?php


    //自定义css
    if ($_COOKIE['自定义样式']) {
      echo $_COOKIE['自定义样式'];
    }
    ?>
  </style>
</head>

<body onLoad="Autofresh()">

  <?php
  // //https://m.jb51.net/article/72175.htm
  // $_GET     && SafeFilter(@$_GET);
  // $_POST    && SafeFilter(@$_POST);
  // $_COOKIE  && SafeFilter(@$_COOKIE);

  // function SafeFilter (&$arr) 
  // {

  //    $ra=Array('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/','/script/','/javascript/','/vbscript/','/expression/','/applet/','/meta/','/xml/','/blink/','/link/','/style/','/embed/','/object/','/frame/','/layer/','/title/','/bgsound/','/base/','/onload/','/onunload/','/onchange/','/onsubmit/','/onreset/','/onselect/','/onblur/','/onfocus/','/onabort/','/onkeydown/','/onkeypress/','/onkeyup/','/onclick/','/ondblclick/','/onmousedown/','/onmousemove/','/onmouseout/','/onmouseover/','/onmouseup/','/onunload/');

  //    if (is_array($arr))
  //    {
  //      foreach ($arr as $key => $value) 
  //      {
  //         if (!is_array($value))
  //         {
  //           if (!get_magic_quotes_gpc())             //不对magic_quotes_gpc转义过的字符使用addslashes(),避免双重转义。
  //           {
  //              $value  = addslashes($value);           //给单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）加上反斜线转义
  //           }
  //           $value       = preg_replace($ra,'',$value);     //删除非打印字符，粗暴式过滤xss可疑字符串
  //           $arr[$key]     = htmlentities(strip_tags($value)); //去除 HTML 和 PHP 标记并转换为 HTML 实体
  //         }
  //         else
  //         {
  //           SafeFilter($arr[$key]);
  //         }
  //      }
  //    }
  // }
  ?>


  <h1>聊天室</h1>
  <div class="main">

    <tt>
      <?php
      //随机生成一串数字
      // function number($length) 
      // {
      //  $chars = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'); 
      //  $keys = array_rand($chars, $length); 
      //  $n = ''; 
      //  for($i = 0; $i <= $length; $i++)
      //  {
      //   $n .= $chars[$keys[$i]]; 
      //  }
      //  return $n;
      // }

      function number($length)
      {
        $chars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $keys = array_rand($chars, $length);
        $n = '';
        for ($i = 0; $i < $length; $i++) {
          $n .= $chars[$keys[$i]];
        }
        return $n;
      }
      //fix some bugs


      //操作ajax.txt
      function ajaxfile()
      {
        $filewenjian = fopen("ajax.txt", "w w+");
        fputs($filewenjian, number(5));
        fclose($filewenjian);
      }

      /*聊天室主程序*/
      date_default_timezone_set('Asia/Shanghai');
      $time = date("Y年m月d日-H时i分s秒");
      if (@$_COOKIE['用户名'] == null) {
        $user = @htmlspecialchars(@$_POST['username'], ENT_QUOTES, 'UTF-8');
      } else {
        $user = @htmlspecialchars(@$_COOKIE['用户名'], ENT_QUOTES, 'UTF-8');
      }

      //$news = @$_POST['news'];
      $news = @htmlspecialchars(@$_POST['news'], ENT_QUOTES, 'UTF-8');
    
      $id = @$_GET['id'];
      $url = "index.php";
      @setcookie('用户名', $user, time() + 10 * 60, '/');

      if ($id == "send" && $user != "" && $news != "") {

        if ($_FILES && $Upimages == 'true') {
          //检验是否有图片上传
          $allowedExts = array("gif", "jpeg", "jpg", "png");
          $temp = explode(".", $_FILES["file"]["name"]);
          $extension = end($temp);
          if ((($_FILES["file"]["type"] == "image/gif")
              || ($_FILES["file"]["type"] == "image/jpeg")
              || ($_FILES["file"]["type"] == "image/jpg")
              || ($_FILES["file"]["type"] == "image/pjpeg")
              || ($_FILES["file"]["type"] == "image/x-png")
              || ($_FILES["file"]["type"] == "image/png"))
            && ($_FILES["file"]["size"] < 10 * 1024 * 1024)
            && in_array($extension, $allowedExts)
          ) {
            //检验图片大小，格式
            if ($_FILES["file"]["error"] > 0) {
              //error("文件错误");
              exit("未知错误");
            }
            $filename = $_FILES['file']['name'];
            $tmpname = $_FILES['file']['tmp_name'];
            //保存路径
            $save_path = 'upload/images/';
            $SaveName = $save_path . time() . number(6) . '.' . $extension;
            //重命名保存
            if (move_uploaded_file($tmpname, $SaveName)) {
              $img = '<br><img src="' . $SaveName . '" width="500px" height="500px">';
            } else {
              $img = "<br>图片上传失败<br>";
            }
          }
        } 

        if(@$img==null){
          $img="";
        }

        $wenjian=@file_get_contents("news.txt");
        $a=fopen("news.txt","w w+");
        $WriteDown='<p class="message">'.'<user>'.$user.'</user>'."(<time>$time</time>):<br>".'<msg>'.$news.$img.'</msg>'."</p>\n".$wenjian;
        fputs($a,$WriteDown);
        fclose($a);
        ajaxfile();
        @header("Location:$url");


        // $wenjian = file_put_contents("news.txt", "");
        // $WriteDown = '<p class="message">' . '<user>' . $user . '</user>' . "(<time>$time</time>):<br>" . '<msg>' . $news . $img . '</msg>' . "</p>\n";
        // file_put_contents("news.txt", $WriteDown, FILE_APPEND);
        // ajaxfile();
        // header("Location:$url");
      }
      ?>
      <br>

      <form action="?id=send" method="post" class="form" enctype="multipart/form-data">
        <?php

        if (@$_COOKIE['用户名'] == null) {
          echo '
<p>请输入昵称：<br><input type="text" name="username" value="guest' . number(6) . '" required placeholder="  请输入用户名" class="from-input"></p>
';
        } else {
          echo '<p style="margin-top:3%; font-size:9pt; text-shadow: 5px 5px 4px rgba(0,0,0,.5);">用户名：' . $_COOKIE['用户名'] . '</p>';
        }
        ?>

        <textarea type="text" name="news" autocomplete="off" required placeholder="请输入消息" class="textarea-input">
</textarea>

        <?php
        if ($Upimages == 'true') {
          echo '<br><input type="file" name="file">';
        }
        ?>

        <p><input type="submit" value="发送" class="form-submit"></p>

      </form>
      <hr>
      <div class="msg" id="msg">

        <?php
        echo @file_get_contents("news.txt");
        ?>

      </div>

      <div>
        <p>统计:<br>

          <?php
          /*统计访问次数*/
          $fwcs = @$_COOKIE['访问次数'];
          if (isset($fwcs)) {
            $fwcs++;
            setcookie('访问次数', $fwcs, time() + 3600 * 24 * 365, '/');
            echo '访问次数' . $fwcs . '</p>';
          } else {
            echo '您好像是第一次访问</p>';
            @setcookie('访问次数', '1', time() + 3600 * 24 * 365, '/');
          }
          ?>
      </div>

      <br>
      <fieldset class="fieldset">
        <br>

        <div>
          <?php

          /*设置ajax*/
          if (!isset($_COOKIE['ajax'])) {
            @setcookie('ajax', 'on', time() + 3600 * 24 * 365, '/');
            @header("Location:index.php");
          } else if (@$_GET['ajax'] == 'on') {
            @setcookie('ajax', 'on', time() + 3600 * 24 * 365, '/');
            echo '<script>alert("已开启自动刷新"); window.location.href="index.php";</script>';
          } else if (@$_GET['ajax'] == 'off') {
            @setcookie('ajax', 'off', time() + 3600 * 24 * 365, '/');
            echo '<script>alert("已关闭自动刷新"); window.location.href="index.php";</script>';
          }

          if (@$_COOKIE['ajax'] == 'on') {
            //获取ajax参数
            echo '
<script>
ajaxcanshu=' . @file_get_contents("ajax.txt") . ';
var xmlobj;
var ajax;
    function createXMLHttpRequest(){
      if(window.ActiveXObject){
        xmlobj=new ActiveXObject("Microsoft.XMLHTTP");
        ajax=new ActiveXObject("Microsoft.XMLHTTP");
      }
      else if(window.XMLHttpRequest){
        xmlobj=new XMLHttpRequest();
        ajax=new XMLHttpRequest();
      }
    }
    //设置ajax
    function Autofresh(){
      createXMLHttpRequest();     
      xmlobj.open("GET","ajax.txt",true);
      xmlobj.onreadystatechange=doAjax;
      xmlobj.send();
    }
    //定时获取ajax参数
    function doAjax(){
      if(xmlobj.readyState==4 && xmlobj.status==200){
        var time_span=document.getElementById("ajax");
        time_span.innerHTML=xmlobj.responseText;
        if(time_span.innerHTML==ajaxcanshu){
        }else{
        aa();
        }
        setTimeout("Autofresh()",3000);
      }
    }
    //获取新信息
    function aa(){
      ajax.open("GET","?id=msg",true);
      ajax.onreadystatechange=goajax;
      ajax.send();
 }
 //更新信息
	function goajax(){
    	if(ajax.readyState==4 && ajax.status==200){
		var ok=document.getElementById("msg");
		ok.innerHTML=ajax.responseText;
		setTimeout("aa()",1000);
		ajaxcanshu=xmlobj.responseText;
}
}
  </script>
  
  <p class="ajaxcanshu">ajax参数:<kbd id="ajax">无</kbd></p>
';
          }

          /*菜单*/
          if (@$_GET['id'] == 'cd') {
            echo '
<a href="?id=system" class="a">系统设置</a><br>
<a href="?id=xy" class="a">用户协议</a><br>
';
          }
          echo '<a href="?id=cd" class="a">菜单</a><br>';
          ?>
        </div>

      </fieldset>
      <br>
    </tt>
  </div>
</body>

</html>
