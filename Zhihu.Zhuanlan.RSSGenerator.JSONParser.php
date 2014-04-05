<?php
     // Zhihu Zhuanlan(Column) Source Parser
	 // Author: imbushuo
	 
	 function GetSummary($column_id){
	 	// Get Summary
		if($column_id=='' || $column_id == null) return null;
		
		$urlbase = 'http://zhuanlan.zhihu.com/api/columns/'.$column_id;
		
		// 1. Get base info
		$ch  = curl_init($urlbase);
		$headers = array( 
             "GET /api/columns/".$column_id." HTTP/1.1", 
             "Referer: http://zhuanlan.zhihu.com/".$column_id, 
             "Accept: application/json, text/plain, */*", 
             "Accept-Language: en-US,en;q=0.8,zh-Hans-CN;q=0.5,zh-Hans;q=0.3",
             "Accept-Encoding: gzip, deflate",
			 "User-Agent: Mozilla/5.0(Windows NT 6.3; ARM; Trident/7.0; Touch; rv:11.0)",
			 "Host: zhuanlan.zhihu.com",
			 "UA-CPU: ARM",
			 "DNT: 1"
         );     
        curl_setopt($ch, CURLOPT_ENCODING, 'utf8');    
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$summarydata = curl_exec($ch);
		
		// 2. Process
		
		if($summarydata!=null){
		    $summaryjson = json_decode($summarydata);
			return $summaryjson;
			/*$name = $summaryjson -> name;
			$description = $summaryjson -> description;
			$url = 'http://zhuanlan.zhihu.com'.$summaryjson -> url;
			$creatorname = $summaryjson -> creator -> name;
			$postsCount = $summaryjson -> postsCount;*/
		}
		
		return null;
	 }
	
	 function GetRecentItems($column_id){
	    // Get Recent Items
	    if($column_id=='' || $column_id == null) return null;
	    $urlbase = 'http://zhuanlan.zhihu.com/api/columns/'.$column_id.'/posts/';
		$ch  = curl_init($urlbase);
		
		$headers = array( 
             "GET /api/columns/".$column_id."/posts/"." HTTP/1.1", 
             "Referer: http://zhuanlan.zhihu.com/".$column_id, 
             "Accept: application/json, text/plain, */*", 
             "Accept-Language: en-US,en;q=0.8,zh-Hans-CN;q=0.5,zh-Hans;q=0.3",
             "Accept-Encoding: gzip, deflate",
			 "User-Agent: Mozilla/5.0(Windows NT 6.3; ARM; Trident/7.0; Touch; rv:11.0)",
			 "Host: zhuanlan.zhihu.com",
			 "UA-CPU: ARM",
			 "DNT: 1"
         );     
		 
        curl_setopt($ch, CURLOPT_ENCODING, 'utf8');    
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$postsdata = curl_exec($ch);
		
		if($postsdata!=null) {
		    $postsarray = json_decode($postsdata);
			return $postsarray;
		}
		
		return null;
	 }