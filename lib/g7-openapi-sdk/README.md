# api-gateway-demo-sign-php
openapi gateway request signature demo by php
# api-gateway-demo-sign-java
##OpenAPI 系统的认证机制

OpenAPI的认证方式提供 JWT 和 AccessId签名两种。其中AccessId的签名机制与Vega的签名是不一样的（更加严格）！
![Alt text](./1506567324611.png)


服务流程：

![Alt text](./1506567361509.png)

路径分为两部分：Baseurl 和 URI。Baseurl 是一个统一的访问入口，比如http://openapi.huoyunren.com/rest，URI是资源路径，比如/v1/device/gps_card/bind。

```
Baseurl+/{version}/{scope}/path 才是完整的访问路径。
```


### Http请求的head包含如下信息：
```
X-G7-OpenAPI-Timestamp: {Timestamp}
Authorization: g7ac {AccessId}:{Signature}
```


#### 签名字符串



1.  `String stringToSign=  `
2.  `HTTP-Verb  +  "\n"  +  `
3.  `Content-MD5 +  "\n"   +`
4.  `Content-Type  +  "\n"  +  `
5.  `Timestamp  +  "\n"  +  `
6.  `Headers  +  `
7.  `Url_String`


如果缺少对应的值，则为空。比如GET请求 没有 Content-MD5 ，Content-Type，则为：

1.  `String stringToSign=  `
2.  `HTTP-Verb  +  "\n"  +  `
3.  `"\n"  +    `
4.  `"\n"  +  `
5.  `Timestamp  +  "\n"  +  `
6.  `Headers  +  `
7.  `Url_String`


#### HTTP Verbs

HTTP Verb是OpenAPI 文档中规定的http 方法，我们会尽量让我们的接口符合Rest风格。

| Verb | Description |
| --- | --- |
| `HEAD` | Can be issued against any resource to get just the HTTP header info. |
| `GET` | Used for retrieving resources. |
| `POST` | Used for creating resources. |
| `PATCH` | Used for updating resources with partial JSON data. For instance, an Issue resource has `title` and `body` attributes. A PATCH request may accept one or more of the attributes to update the resource. PATCH is a relatively new and uncommon HTTP verb, so resource endpoints also accept `POST` requests. |
| `PUT` | Used for replacing resources or collections. For `PUT` requests with no `body` attribute, be sure to set the `Content-Length` header to zero. |
| `DELETE` | Used for deleting resources. |


#### Content-MD5

Content-MD5 是指 Body 的 MD5 值，**只有当 content-type非application/x-www-form-urlencoded才计算 MD5**，计算方式为：

 > String Content-MD5 =   Base64.encodeBase64(MD5(bodyStream.getbytes("UTF-8")));

bodyStream 为字节数组。

#### Content-Type

开放平台的链路目前只接受**application/json一种类型**，均要求 utf-8编码。例如：

```
Content-Type: application/json; charset=utf-8
```

#### Timestamp

X-G7-OpenAPI-Timestamp 头中的值，表示API 调用者传递时间戳，值为当前时间的毫秒数，也就是从1970年1月1日起至今的时间转换为毫秒，时间戳有效时间为15分钟。

#### Headers

Headers 是指参与 Headers 签名计算的 Header 的 Key、Value 拼接的字符串，只对要求具有 "X-G7-Ca-" 前缀的header进行拼接。由于RFT2616（4.2 Message Headers
）对于Header明确为大小写不敏感（case-insensitive)，在计算header头的拼接时，需要对header头名所有字母转换为小写字母。

*   Headers 组织方法：

先对参与 Headers 签名计算的 Header的Key **按照字典排序**升序排序后使用如下方式拼接，如果某个 Header 的 Value 为空，则使用 HeaderKey + “:” + “\n”参与签名，需要保留 Key 和英文冒号。

1.  `String headers =`
2.  `toLowCase(HeaderKey1)  +  ":"  +  HeaderValue1  +  "\n"+`
3.  `toLowCase(HeaderKey2)  +  ":"  +  HeaderValue2  +  "\n"+`
4.  `...`
5.  `toLowCase(HeaderKeyN)  +  ":"  +  HeaderValueN  +  "\n"`


#### Url_String

Url_String 指 **Path + Query + Body** 中 **Form** 参数，组织方法：对 **Query+Form** 参数按照字典升序排序对 **Key** 进行**排序**后按照如下方法拼接，如果 **Query** 或 **Form** 参数为空，则 URI不需要添加 **？**，如果某个参数的 **Value** 为空只保留 **Key** 参与签名，等号不需要再加入签名。

1.  `String Url_String =`
2.  `URI  +`
3.  `"?"  +`
4.  `Key1  +  "="  +  Value1  +`
5.  `"&"  +  Key2  +  "="  +  Value2  +`
6.  `...`
7.  `"&"  +  KeyN  +  "="  +  ValueN`

注意这里 **Query** 或 **Form** 参数的 **Value** 可能有多个，多个的时候只取第一个 Value 参与签名计算。


#### StringToSign举例



#### 计算签名



1.  `Mac hmacSha256 =  Mac.getInstance("HmacSHA256");`
2.  `byte[] keyBytes = secret.getBytes("UTF-8");`
3.  `hmacSha256.init(new  SecretKeySpec(keyBytes,  0, keyBytes.length,  "HmacSHA256"));`
4.  `String Signature =  new  String(Base64.encodeBase64(hmacSha256.doFinal(stringToSign.getBytes("UTF-8")),"UTF-8"));`

secret 为 AccessId对应的密钥。


#### 传递签名

将计算的签名结果放到 Request 的Authorization为Key的 Header 中，格式如下：
```
X-G7-OpenAPI-Timestamp: {Timestamp}
Authorization: g7ac {AccessId}:{Signature}
```



#### 签名错误排查方法

当签名校验失败时，API网关会将服务端的 StringToSign 放到 HTTP Response 的 msg 中返回到客户端，只需要将本地计算的 StringToSign 与服务端返回的 StringToSign 进行对比即可找到问题；

如果服务端与客户端的 StringToSign 一致请检查用于签名计算的密钥是否正确；

