<?php
namespace Home\Controller;
use Think\Controller;
/* *
 * 万能Api接口
 * 版本：1.0
 * 作者:wisonLau
 * 日期：2016-10-15
 * 说明：
 * 以下代码只是为了方便大家参考学习而提供的样例代码，大家可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究接口使用，只是提供一个参考。
 */
class ApiController extends Controller{
    private $_api_secret_key;
    private $_not_check_action_name;

    public function _initialize(){
        $this->_api_secret_key = 'www.wisonLau.com';// app调用的签名秘钥,可以写在配置里C('api_secret_key');
        $this->_not_check_action_name = array('get_server_time', 'test');
        $sign = $this->_getSign();
        /* 不参与签名验证的方法 */
        if(!in_array(strtolower(ACTION_NAME), $this->_not_check_action_name)){/* 使用 __ACTION__ 可以更精确 Api/test */
            if($sign != $_POST['sign']){
                self::_message(-1, '签名失败!!!', '');
            }
            if(time() - $_POST['time'] > 600){
                self::_message(-1, '请求超时!!!', '');
            }
        }
    }

    /**
     * sign 生成
     * @param $data['username'] 调用者帐号
     * @param $data['password'] 调用者密码
     * @param $data['unique_id'] 设备ID
     * @return string
     */
    private function _getSign(){
        $data = $_POST;
        $sign = $data['password'].$data['unique_id'].$data['username'].$this->_api_secret_key;
        return md5($sign);
    }

    /**
     * @param $status
     * @param $msg
     * @param $result
     */
    static private function _message($status, $msg, $result){
        $json_arr = array('status'=>$status, 'msg'=>$msg, 'result'=>$result);
        exit(json_encode($json_arr));
    }

    /**
     * app 端万能接口 传递 sql 语句 sql 错误 或者查询 错误 result 都为 false 否则 返回 查询结果 或者影响行数
     * @param string $sign
     * @param int $time 当前时间
     * @param int $unique_id 客户端标识
     * @param string $password 调用者密码
     * @param string $username 调用者帐号
     * @param string $sql 查询语句
     * @return mixed
     */
    public function sql_api(){
        $Model = new \Think\Model();
        $sql = $_REQUEST['sql'];
        $result = '';
        try{
            if(preg_match("/select/insert|update|delete/i", $sql))
                $result = $Model->execute($sql);
            else
                $result = $Model->query($sql);
        }catch (\Exception $e) {
            self::_message(-1, '系统错误', '');
        }
        if($result === false) /* 数据非法或者sql语句错误 */
            self::_message(-1, '系统错误', '');
        else
            self::_message(1, '成功!', $result);
    }

    /**
     * 获取服务器时间
     */
    public function get_server_time(){
        self::_message(1, '成功!', time());
    }

    /**
     * test
     */
    public function test(){
        $password = 'wison';
        $unique_id = 123;
        $username = 'wison';
        $api_secret_key = $this->_api_secret_key;
        $time = time();
        echo md5($password.$unique_id.$username.$api_secret_key);//sign
        echo '<br />'.$time;
    }

}