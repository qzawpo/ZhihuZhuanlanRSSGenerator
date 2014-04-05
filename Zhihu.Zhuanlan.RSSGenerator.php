<?php
	
    include 'Zhihu.Zhuanlan.RSSGenerator.JSONParser.php';
	
	
	header('Content-Type: text/xml; charset=UTF-8');
	date_default_timezone_set('Asia/Shanghai');
	
	$column_id = $_GET["columnid"];
	
	$summary = GetSummary($column_id);
	
	$posts = GetRecentItems($column_id);
	
	$dom = new DOMDocument('1.0','utf-8');
	
	$r = $dom -> createElement('rss');
	$r -> setAttribute('version','2.0');
	
	// Preparation: namespace
	$r -> setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:content','http://purl.org/rss/1.0/modules/content/');
	$r -> setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:wfw','http://wellformedweb.org/CommentAPI/');
	$r -> setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:dc','http://purl.org/dc/elements/1.1/');
	$r -> setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:atom','http://www.w3.org/2005/Atom');
	$r -> setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:sy','http://purl.org/rss/1.0/modules/syndication/');
	$r -> setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:slash','http://purl.org/rss/1.0/modules/slash/');
	
	
	$channel = $dom -> createElement('channel');
	
	// Title
	$title = $dom -> createElement('title');
	$title -> appendChild($dom->createTextNode($summary -> name.' - 知乎专栏'));
	$channel -> appendChild($title);
	
	// Link
	$link = $dom -> createElement('link');
	$link -> appendChild($dom->createTextNode('http://zhuanlan.zhihu.com'.$summary -> url));
	$channel -> appendChild($link);
	
	// Description
	$description = $dom -> createElement('description');
	$description -> appendChild($dom->createTextNode($summary -> description));
	$channel -> appendChild($description);
	
	// Language: zh-CN
	$lang = $dom -> createElement('language');
	$lang -> appendChild($dom->createTextNode('zh-CN'));
	$channel -> appendChild($lang);
	
	// Author
	$author = $dom -> createElement('author');
	$author -> appendChild($dom->createTextNode($summary -> creator -> name));
	$channel -> appendChild($author);
	
	// Freq
	$updatePeriod = $dom -> createElement('sy:updatePeriod');
	$updatePeriod -> appendChild($dom->createTextNode('hourly'));
	$updateFreq = $dom -> createElement('sy:updateFrequency');
	$updateFreq -> appendChild($dom->createTextNode('1'));
	$channel -> appendChild($updatePeriod);
	$channel -> appendChild($updateFreq);
	
	// Generator
	$generator = $dom -> createElement('generator');
	$generator -> appendChild($dom->createTextNode('http://imbushuo.net/archives/17'));
	$channel -> appendChild($generator);
	
	if($posts!=null)
	{
	// Posts
	foreach ($posts as $article) {
	    // Data
		
		$item = $dom -> createElement('item');
		
		$title = $dom -> createElement('title');
		$title -> appendChild($dom->createTextNode($article -> title));
		$item -> appendChild($title);
		
		$publishedTime = $dom -> createElement('pubDate');
		$pubDate = new DateTime( $article -> publishedTime);
		$publishedTime -> appendChild($dom->createTextNode($pubDate -> format('D, d M Y H:i:s O')));
		$item -> appendChild($publishedTime);
		
		$summary = $dom -> createElement('description');
		$summary -> appendChild($dom->createCDATASection($article -> summary));
		$item -> appendChild($summary);
		
		$guid = $dom -> createElement('guid');
		$guid -> appendChild($dom->createTextNode('http://zhuanlan.zhihu.com'.$article -> url));
		$item -> appendChild($guid);
		
		$url = $dom -> createElement('link');
		$url -> appendChild($dom->createTextNode('http://zhuanlan.zhihu.com'.$article -> url));
		$item -> appendChild($url);
		
		$author = $dom -> createElement('author');
		$author -> appendChild($dom->createTextNode($article -> author -> name));
		$item -> appendChild($author);
		
		$content = $dom -> createElement('content:encoded');
		$content -> appendChild($dom->createCDATASection($article -> content));
		$item -> appendChild($content);
		
		$channel -> appendChild($item);
    }
	}

	$r -> appendChild($channel);
	
	$dom -> appendChild($r);
	
	echo $dom->saveXML();

	