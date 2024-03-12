<?php
require('../authcode.php');
function exJson($code, $msg) {
    echo json_encode(['code' => $code, 'msg' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}
function url_curl($url,$post=0,$referer=0,$cookie=0,$header=0,$ua=0,$nobaody=0,$addheader=0){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $httpheader[] = "Accept: */*";
    $httpheader[] = "Accept-Encoding: gzip,deflate,sdch";
    $httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
    $httpheader[] = "Connection: close";
    $httpheader[] = "Content-type: application/json; charset=utf-8";
    if($addheader){
        $httpheader = array_merge($httpheader, $addheader);
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    if($post){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    if($header){
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
    }
    if($cookie){
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if($referer){
        if($referer==1){
            curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
        }else{
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
    }
    if($ua){
        curl_setopt($ch, CURLOPT_USERAGENT,$ua);
    }else{
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
    }
    if($nobaody){
        curl_setopt($ch, CURLOPT_NOBODY,1);
    }
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $ret = curl_exec($ch);
    curl_close($ch);
    return $ret;
}
function mi_rc4($data,$pwd,$t=0) {//t=0加密，1=解密
    $cipher = '';
    $key[] = "";
    $box[] = "";
    $pwd = mi_rc4_encode($pwd);
    $data = mi_rc4_encode($data);
    $pwd_length = strlen($pwd);
    if($t == 1){
        $data = hex2bin($data);
    }
    $data_length = strlen($data);
    for ($i = 0; $i < 256; $i++) {
        $key[$i] = ord($pwd[$i % $pwd_length]);
        $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $key[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $data_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $k = $box[(($box[$a] + $box[$j]) % 256)];
        $cipher .= chr(ord($data[$i]) ^ $k);
    }
    if($t == 1){
        return $cipher;
    }else{
        return bin2hex($cipher);
    }
}
function mi_rc4_encode($str,$turn = 0){//turn=0,utf8转gbk,1=gbk转utf8
    if(is_array($str)){
        foreach($str as $k => $v){
            $str[$k] = array_iconv($v);
        }
        return $str;
    }else{
        if(is_string($str) && $turn == 0){
            return mb_convert_encoding($str,'GBK','UTF-8');
        }elseif(is_string($str) && $turn == 1){
            return mb_convert_encoding($str,'UTF-8','GBK');
        }else{
            return $str;
        }
    }
}
#获取IP
function getIp() {
    if(isset($_SERVER)){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $arr = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arr as $ip){
                $ip = trim($ip);
                if ($ip != 'unknown'){$realip = $ip; break;}
            }
        }elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }else{
            if (isset($_SERVER['REMOTE_ADDR'])){
                $realip = $_SERVER['REMOTE_ADDR'];
            }else{
                $realip = '0.0.0.0';
            }
        }
    }else{
        if (getenv('HTTP_X_FORWARDED_FOR')){
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        }elseif (getenv('HTTP_CLIENT_IP')){
            $realip = getenv('HTTP_CLIENT_IP');
        }else{
            $realip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/",$realip,$onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
    return $realip;
}
#取随机码
function createStr($length=25,$lx=0,$lx2=0){
    if($lx == 0){
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    }elseif($lx == 1){
        $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
    }elseif($lx == 2){
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    }elseif($lx == 9){
        $str = '0123456789';
    }else{
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    }
    $len = strlen($str)-1;
    $randstr = '';
    for ($i=0;$i<$length;$i++) {
        $num = mt_rand(0,$len);
        $randstr .= $str[$num];
        if (!(($i+1) % 5) && $i && ($i+1)<$length && $lx2){
            $randstr .= '-';
        }
    }
    return $randstr;
}

#以下为调用授权证书接口(JSON数据格式、RC4加密、验证客户端签名)

$softid = 'x8S1XZnd3D8kxYX6';
$comkey = 'W6AS5ULDBB3XX60Z2MXZFZ3H';
$version = '1';
$rc4key = 'jd3XQGenkMwiw2Kn8XXWCjGe2khNEmak';
$sign_khd = '8XwDgLaLfD[data]LOL4IOG1yb[key]SxCgKbW8wQ';
$sign_fwd = 'Hzw0HoMIyL[data]f6L2BYfsWP[key]W7kSgKF1dQ';

$postjk = "https://yz.isqynet.com/api/v1/licenses";



$mylicenses['record'] = $_SERVER['HTTP_HOST']; //获取当前域名或者IP地址(非80或443端口则会附加端口号)
$mylicenses['clientid'] = createStr(12,0);
$mylicenses['uuid'] = createStr(18,0);
$mylicenses['token'] = md5(createStr(24,0));
$mylicenses['ip'] = getIp();
$mylicenses['mac'] = md5($_SERVER['SERVER_NAME']);
$mylicenses['version'] = $version;
$tdata['soft'] = $softid;
$tdata['data'] =  mi_rc4(json_encode($mylicenses),$rc4key);
$sign = str_replace('[data]',$tdata['data'],$sign_khd);
$sign = str_replace('[key]',$comkey,$sign);
$sign = md5($sign);
$tdata['sign'] = $sign;
$xdata = json_encode($tdata);
$retdata = url_curl($postjk,$xdata);
$retdata = json_decode($retdata,true);
$sign = str_replace('[data]',$retdata['data'],$sign_fwd);
$sign = str_replace('[key]',$comkey,$sign);
$sign = md5($sign);
if($retdata['sign']!=$sign){
    exJson(-1,'啊哦~服务器开小差了，请过会儿再试');
}
$retdata = mi_rc4($retdata['data'],$rc4key,1);
$newdata = json_decode($retdata,true);
if($mylicenses['uuid']!=$newdata['uuid']){
    exJson(-1,'啊哦~服务器开小差了，请过会儿再试');
}
$newtoken = md5($mylicenses['token'].$newdata['t']);
if($newtoken!=$newdata['token']){
    exJson(-1,'啊哦~服务器开小差了，请过会儿再试');
}
if($newdata['code']!=200){
    exJson(-1,$newdata['msg']);
}
?>