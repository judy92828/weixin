<?php

//获取分类名
if (! function_exists('jj_file_get_contents'))
{
    function jj_file_get_contents($url)
    {
        $context = stream_context_create ( array (
			'http' => array (
					'timeout' => 30 
			) 
	) ) // 超时时间，单位为秒

	;
	
	return file_get_contents ( $url, 0, $context );
    }
}
