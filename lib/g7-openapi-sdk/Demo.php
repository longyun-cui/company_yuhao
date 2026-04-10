<?php
/**
 * Copyright (c) 2017 Chinaway ltd.
 *     Developed By Team-Public
 *
 * PHP Version 7.1
 *
 * @author chenhaibin <chenhaibin@huoyunren.com>
 * @since  2017/11/16 下午3:35
 */
use OpenApiSDK\Constant\ContentType;
use OpenApiSDK\Constant\HttpHeader;
use OpenApiSDK\Constant\HttpMethod;
use OpenApiSDK\Http\HttpClient;
use OpenApiSDK\Http\HttpRequest;
use OpenApiSDK\Util\SignUtil;

require 'Init.php';

$demo = new Demo();
$demo->doDelete();

/**
*请求示例
*如一个完整的url为http://api.aaaa.com/createobject?key1=value&key2=value2
*$host为http://api.aaaa.com
*$path为/createobject
*query为key1=value&key2=value2
*/
class Demo
{
	private static $appKey = "ebahp0";
    private static $appSecret = "IXMYUz6Oy4aXDvRF6saJHgV9RxmV3IzL";
	//协议(http或https)://域名:端口，注意必须有http://或https://
    private static $host = "http://172.22.34.202:6688";

	/**
	*method=GET请求示例
	*/
    public function doGet() {
		//域名后、query前的部分
		$path = "/v1/map/geocode/code";
		$request = new HttpRequest($this::$host, $path, HttpMethod::GET, $this::$appKey, $this::$appSecret);

        //设定Content-Type，根据服务器端接受的值来设置
		//$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_TEXT);

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
		//$request->setQuery("b-query2", "queryvalue2");
		//$request->setQuery("a-query1", "queryvalue1");
        $request->setQuerys(['param'=>1]);

		$response = HttpClient::execute($request);
		print_r($response);
	}

	/**
	*method=POST且是非表单提交，请求示例
	*/
	public function doPostString() {
		//域名后、query前的部分
		$path = "/v1/map/mapfence/match";
		$request = new HttpRequest($this::$host, $path, HttpMethod::POST, $this::$appKey, $this::$appSecret);
		//传入内容是json格式的字符串
		$bodyContent = json_encode(['aa'=>'bbb']);
        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_JSON);
		
        //设定Accept，根据服务器端接受的值来设置
		//$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);

        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
		$request->setHeader("X-G7-Ca-header2", "headervalue2");
		$request->setHeader("X-G7-Ca-header1", "headervalue1");

		//注意：业务body部分，不能设置key值，只能有value
		if (0 < strlen($bodyContent)) {
			$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5,SignUtil::md5Body($bodyContent));
			$request->setBodyString($bodyContent);
		}

		$response = HttpClient::execute($request);
		print_r($response->getBody());
	}


    public function doPutString() {
        //域名后、query前的部分
        $path = "/v1/map/mapfence/match";
        $request = new HttpRequest($this::$host, $path, HttpMethod::PUT, $this::$appKey, $this::$appSecret);
        //传入内容是json格式的字符串
        $bodyContent = json_encode(['aa'=>'bbb']);
        //设定Content-Type，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_JSON);

        //设定Accept，根据服务器端接受的值来设置
        //$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);

        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
        //mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        $request->setHeader("X-G7-Ca-header2", "headervalue2");
        $request->setHeader("X-G7-Ca-header1", "headervalue1");

        //注意：业务body部分，不能设置key值，只能有value
        if (0 < strlen($bodyContent)) {
            $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5,SignUtil::md5Body($bodyContent));
            $request->setBodyString($bodyContent);
        }

        $response = HttpClient::execute($request);
        print_r($response->getBody());
    }

	/**
	*method=DELETE请求示例
	*/
    public function doDelete() {
		//域名后、query前的部分
		$path = "/v1/map/mapfence/delete";
		$request = new HttpRequest($this::$host, $path, HttpMethod::DELETE, $this::$appKey, $this::$appSecret);

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_JSON);
		
        //设定Accept，根据服务器端接受的值来设置
		//$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_TEXT);

        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
		//$request->setHeader("b-header2", "headervalue2");
		//$request->setHeader("a-header1", "headervalue1");

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
		$request->setQuery("number", "431");
		//$request->setQuery("a-query1", "queryvalue1");

		$response = HttpClient::execute($request);
		print_r($response);
	}
}