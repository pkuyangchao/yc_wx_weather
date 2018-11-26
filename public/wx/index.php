<?php
    //获得参数 signature nonce token timestamp echostr
    $nonce     = $_GET['nonce'];
    $token     = 'yc';
    $timestamp = $_GET['timestamp'];
    $echostr   = $_GET['echostr'];
    $signature = $_GET['signature'];
    //形成数组，然后按字典序排序
    $array = array();
    $array = array($nonce, $timestamp, $token);
    sort($array);
    //拼接成字符串,sha1加密 ，然后与signature进行校验
    $str = sha1( implode( $array ) );
    if( $str == $signature && $echostr ){
        //第一次接入weixin api接口的时候
        echo  $echostr;
        exit;
    }

	 //1.获取到微信推送过来post数据（xml格式）
    $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
    //2.处理消息类型，并设置回复类型和内容
	$postObj = simplexml_load_string( $postArr );
        //判断该数据包是否是订阅的事件推送
        if( strtolower( $postObj->MsgType) == 'event'){
            //如果是关注 subscribe 事件
            if( strtolower($postObj->Event == 'subscribe') ){
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = '欢迎关注我们的微信公众账号，用户：'.$postObj->FromUserName.'-服务器：'.$postObj->ToUserName;
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
            }
        }

	//判断该数据包是否是文本消息
        if( strtolower( $postObj->MsgType) == 'text'){
             //接受文本信息
    		$content = $postObj->Content;
         	if(!empty($content)){
            	$str = mb_substr($content,-2,2,"UTF-8");//截取倒数两个字符
                $str_key = mb_substr($content,0,-2,"UTF-8");//截掉末尾的“天气”
                if($str == '天气' && !empty($str_key))
                {
                      if($str_key!="北京"){
                          $contentStr = "抱歉，没有查到\"".$str_key."\"的天气信息！";
                      } else {
                          $contentStr = "【".$str_key."天气预报】\n"."2018年11月23日 9时发布"."\n\n实时天气\n"."晴 -1℃~10℃ 北风2级"."\n\n温馨提示：天气较冷，请多穿衣服，注意感冒"."\n\n明天\n"."晴 -3℃~9℃ 北风2级"."\n\n后天\n"."晴 -3℃~10℃ 北风3级";
                      }
                    
                  }else{
                		$contentStr = "感谢您关注【北京小天气】"."\n"."微信号：yangchao"."\n"."我们为您提供北京本地生活指南，北京相关信息查询，做最好的北京微信平台。"."\n"."目前平台功能如下："."\n"."【1】 查天气，如输入：北京天气"."\n"."【2】 查公交，如输入：北京公交"."\n"."【3】 北京信息查询，如输入：北京大学"."\n"."更多内容，敬请期待...";
                }
            }else{
                echo "Input something...";
            }
          	
             //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = $contentStr;
          		
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
        }
