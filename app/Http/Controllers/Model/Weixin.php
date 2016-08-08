<?php

namespace App\Http\Controllers\Model;

use Illuminate\Database\Eloquent\Model;

class Weixin extends Model
{
    var $data = array ();
    var $wxcpt, $sReqTimeStamp, $sReqNonce, $sEncryptMsg;
    public function __construct() {
//        if ($_REQUEST ['doNotInit']) return true;

        $content = file_get_contents ( 'php://input' );
        ! empty ( $content ) || die ( '这是微信请求的接口地址，直接在浏览器里无效' );

        $data = new \SimpleXMLElement ( $content );
        // $data || die ( '参数获取失败' );
        foreach ( $data as $key => $value ) {
            $this->data [$key] = strval ( $value );
        }
    }

    /* 获取微信平台请求的信息 */
    public function getData() {
        return $this->data;
    }

    /* ========================发送被动响应消息 begin================================== */
    /* 回复文本消息 */
    public function replyText($content) {
        $msg ['Content'] = $content;
        $this->_replyData ( $msg, 'text' );
    }

    /* 发送回复消息到微信平台 */
    private function _replyData($msg, $msgType) {
        $msg ['ToUserName'] = $this->data ['FromUserName'];
        $msg ['FromUserName'] = $this->data ['ToUserName'];
        $msg ['CreateTime'] = time();
        $msg ['MsgType'] = $msgType;

        if($_REQUEST ['doNotInit']){
            dump($msg);
            exit;
        }

        $xml = new \SimpleXMLElement ( '<xml></xml>' );
        $this->_data2xml ( $xml, $msg );
        $str = $xml->asXML ();

        // 记录日志
       // addWeixinLog ( $str, '_replyData' );

//        if ($_GET ['encrypt_type'] == 'aes') {
//            $sEncryptMsg = ""; // xml格式的密文
//            $errCode = $this->wxcpt->EncryptMsg ( $str, $this->sReqTimeStamp, $this->sReqNonce, $sEncryptMsg );
//            if ($errCode == 0) {
//                $str = $sEncryptMsg;
//            } else {
//                addWeixinLog ( $str, "EncryptMsg Error: " . $errCode );
//            }
//        }
        echo ($str);
    }

    /* 组装xml数据 */
    public function _data2xml($xml, $data, $item = 'item') {
        foreach ( $data as $key => $value ) {
            is_numeric ( $key ) && ($key = $item);
            if (is_array ( $value ) || is_object ( $value )) {
                $child = $xml->addChild ( $key );
                $this->_data2xml ( $child, $value, $item );
            } else {
                if (is_numeric ( $value )) {
                    $child = $xml->addChild ( $key, $value );
                } else {
                    $child = $xml->addChild ( $key );
                    $node = dom_import_simplexml ( $child );
                    $node->appendChild ( $node->ownerDocument->createCDATASection ( $value ) );
                }
            }
        }
    }
}
