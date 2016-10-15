#万能Api接口
版本：1.0
作者：wisonLau
日期：2016-10-15
说明：
以下代码只是为了方便大家参考学习而提供的样例代码，大家可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
该代码仅供学习和研究接口使用，只是提供一个参考。
使用接口必须开启签名，并且保证签名秘钥不被反编译泄露

>签名方式

参数列表

| 参数      | 说明                          |
| -------- |:-----------------------------:|
| username | 用户名                         |
| password | 密码                           |
| unique_id | 唯一标识 类似web pc端sessionid |
| time     | 当前时间戳                     |
| sign     | 签名秘钥                       |
| _api_secret_key| 秘钥                     |
### sign = md5($password.$unique_id.$username.秘钥);

> 接口调用示例

| Url地址                                       | 请求方式|
| --------------------------------------------- |:------:|
| http://www.wisonlau.com/index.php/Api/sql_api | post   |

参数列表

| 参数 | 说明         |
| --- |:------------:|
| sql | Mysql Sql语句 |