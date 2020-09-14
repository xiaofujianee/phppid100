<?php 

$threads=50;//并发请求次数
$url='http://localhost/c.php?a=';//请求的url

//创建一个未定义的curl句柄数组
$ch=array();

//创建批处理cURL的句柄
$mh = curl_multi_init();


//创建并发请求次数个url用于后面给curl分配
for ($i=0; $i <$threads ; $i++) {
		//有多少请求,创建多少curl会话
		$ch[$i]=curl_init();
		curl_setopt($ch[$i], CURLOPT_URL, $url.rand(1,1000));//随机参数,避免缓存
		curl_setopt($ch[$i], CURLOPT_HEADER, 0);		
		//创建的会话分配给curl批处理句柄
		curl_multi_add_handle($mh,$ch[$i]);
}


$running=null;
//所有的curl会话分配给$mh这个curl批量处理句柄来执行
do {
    usleep(10000);
    curl_multi_exec($mh,$running);
} while ($running > 0);


//关闭已经创建的会话句柄
for ($i=0; $i <$threads ; $i++) { 
	curl_multi_remove_handle($mh, $ch[$i]);
}

//关闭批处理句柄
curl_multi_close($mh);

?>
