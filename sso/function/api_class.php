<?php
/**
 * API接口类_v2内部版本
 * @package DingStudio_API_Interface
 * @subpackage API/Class 核心类库
 * @copyright DingStudio 2016-2017 All Rights Reserved
 */

class Response {
	
	/**
	* 使用json方式输出通信数据
	* @param integer $code 状态码
	* @param string $message 提示信息
	* @param array $data 数据
	* return json
	*/
	public static function jsonEncode($code, $message = '', $data = array()) {
		if(!is_numeric($code)) {
			return '';
		}
		
		$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data,
			'requestID' => date("Ymd".time())
		);
		
		header("Content-Type: application/json; charset=UTF-8");
		echo json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
		exit;
	}
	
	/**
	* 使用xml方式输出通信数据
	* @param integer $code 状态码
	* @param string $message 提示信息
	* @param array $data 数据
	* return xml
	*/
	public static function xmlEncode($code, $message = '', $data = array()) {
		if(!is_numeric($code)) {
			return '';
		}
		
		$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data
		);
		$requestId = date("Ymd".time());
		header("Content-Type: text/xml; charset=UTF-8");
		$xml = "<?xml version='1.0' encoding='UTF-8'?>";
		$xml .= "<root>";
		
		$xml .= self::xmlToEncode($result);
		$xml .= "<requestId>{$requestId}</requestId>\n";
		$xml .= "</root>";
		
		echo $xml;
		exit;
	}
	
	/**
	* xml数据内容封装
	* @param array $data 数据
	* return string
	*/
	public static function xmlToEncode($data) {
		$xml = $attr = "";
		foreach($data as $key => $value) {
			if(is_numeric($key)) {
				$attr = " id='{$key}'";
				$key = "item";
			}
			$xml .= "<{$key}{$attr}>";
			$xml .= is_array($value) ? self::xmlToEncode($value) : $value;
			$xml .= "</{$key}>\n";
		}
		return $xml;
		exit;
	}

	/**
	 * json数据手动格式化输出封装（替代JSON_UNESCAPED_UNICODE、JSON_PRETTY_PRINT、JSON_UNESCAPED_SLASHES）
	 * @param mixed $data 数据
	 * @param string $indent 缩进字符数，缺省默认设置为4个空格。
	 * return json
	 */
	public static function jsonFormat($data, $indent=null) {
		// 设置JSON UTF-8 Header
		header("Content-Type: application/json; charset=UTF-8");
		// 对数组中每个元素递归进行urlencode操作，保护中文字符
    	array_walk_recursive($data, 'self::jsonFormatProtect');
    	// json encode
    	$data = json_encode($data);
    	// 将urlencode的内容进行urldecode
    	$data = urldecode($data);
    	// 缩进处理
    	$ret = '';
    	$pos = 0;
    	$length = strlen($data);
    	$indent = isset($indent)? $indent : '    ';
    	$newline = "\n";
    	$prevchar = '';
    	$outofquotes = true;
    	for($i=0; $i<=$length; $i++){
    	    $char = substr($data, $i, 1);
    	    if($char=='"' && $prevchar!='\\') {
    	        $outofquotes = !$outofquotes;
    	    }
    	    elseif(($char=='}' || $char==']') && $outofquotes) {
    	        $ret .= $newline;
    	        $pos --;
    	        for($j=0; $j<$pos; $j++) {
    	            $ret .= $indent;
    	        }
    	    }
    	    $ret .= $char;
    	    if(($char==',' || $char=='{' || $char=='[') && $outofquotes) {
    	        $ret .= $newline;
    	        if($char=='{' || $char=='[') {
    	            $pos ++;
    	        }
    	        for($j=0; $j<$pos; $j++) {
    	            $ret .= $indent;
    	        }
    	    }
    	    $prevchar = $char;
    	}
    	echo $ret;
    	return $ret;
	}

	/**
	 * 将数组元素进行urlencode
	 * @param string $val
	 */
	public static function jsonFormatProtect(&$val) {
		if($val!==true && $val!==false && $val!==null) {
			$val = urlencode($val);
		}
	}
	
	public static function errorHandler() {
		$requestId = date("Ymd".time());
		header("Content-Type: text/xml; charset=UTF-8");
		$xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml .= "<root>\n";
		$xml .= "<code>405</code>\n";
		$xml .= "<message>Method not allow.</message>\n";
		$xml .= "<requestId>{$requestId}</requestId>\n";
		$xml .= "</root>\n";
		echo $xml;
		exit;
	}

}

class JsonToXml
{
    private $root = 'document';
    private $indentation = '    ';
    // TODO: private $this->addtypes = false; // type="string|int|float|array|null|bool"
    public function export($data) {
		header("Content-Type: text/xml; charset=UTF-8");
        $data = array($this->root => $data);
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        $this->recurse($data, 0);
        echo PHP_EOL;
    }
	public function operate($json_string) {
		$json_data = @json_decode($json_string, false);
		if (!is_array($json_data) && !is_object($json_data)) {
			Response::errorHandler();
		}
		self::export($json_data);
	}
    private function recurse($data, $level) {
        $indent = str_repeat($this->indentation, $level);
        foreach ($data as $key => $value) {
            echo PHP_EOL . $indent . '<' . $key;
            if ($value === null) {
                echo ' />';
            } else {
                echo '>';
                if (is_array($value)) {
                    if ($value) {
                        $temporary = $this->getArrayName($key);
                        foreach ($value as $entry) {
                            $this->recurse(array($temporary => $entry), $level + 1);
                        }
                        echo PHP_EOL . $indent;
                    }
                } else if (is_object($value)) {
                    if ($value) {
                        $this->recurse($value, $level + 1);
                        echo PHP_EOL . $indent;
                    }
                } else {
                    if (is_bool($value)) {
                        $value = $value ? 'true' : 'false';
                    }
                    echo $this->escape($value);
                }
                echo '</' . $key . '>';
            }
        }
    }
    private function escape($value) {
        // TODO:
        return $value;
    }
    private function getArrayName($parentName) {
        // TODO: special namding for tag names within arrays
        return $parentName;
    }
}
