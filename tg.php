<?php
	$tk = 'BCBOTPASSWORD';
	$url = 'https://api.telegram.org/bot'.$tk.'/';
	$chanID = '@barbearclassico';

	function sendPhoto($chatID,$file){
		global $url;
		$meth = 'sendPhoto';
		$args = array(
			'chat_id'	=> $chatID,
			'photo'		=> array(
				'mime'		=> 'image/jpeg',
				'file'		=> $file
			),
		);

		$eol = "\r\n";
		$data = '';
		
		$mime_boundary=md5(time());

		foreach($args as $k => $v){
			if(is_array($v)){
				$data .= '--' . $mime_boundary . $eol;
				$data .= 'Content-Disposition: form-data; name="'.$k.'"; filename="'.$k.'.jpg"' . $eol;
				$data .= 'Content-Type: '. $v['mime'] . $eol. $eol;
				if(isset($v['data']))
					$data .= $v['data'];
				elseif(isset($v['file'])){
					$data .= file_get_contents($v['file']);
				}
				$data .= $eol;
			}else{
				$data .= '--' . $mime_boundary . $eol;
				$data .= 'Content-Disposition: form-data; name="'.$k.'"' . $eol . $eol;
				$data .= $v . $eol;
			}
		}

		$data .= "--" . $mime_boundary . "--" . $eol . $eol; // finish with two eol's!!

		$data = @file_get_contents ($url.$meth, 
			false, 
			stream_context_create (
				array (
					'http'=> array (
						'method'	=> 'POST', 
						'header'	=> 'Content-Type: multipart/form-data; boundary=' . $mime_boundary . $eol, 
						'content'	=> $data
					)
				)
			)
		);

		if($data!==false){
			$data = json_decode($data);
		
			if(!$data->ok){
				var_dump($data->result);
				throw new exception("API ERROR!!");
			}
			return $data->result->photo[0]->file_id;
		}else{
			echo "Ocorreu excepção no pedido!!";
		}
		
		return null;
	}

	$pos = 0;
	if(($fl = @file_get_contents('pos'))!==false)
		$pos = $fl;
	$handle = @fopen('queue', 'r');
	if($handle){
		fseek($handle,$pos);
		if(($buffer = fgets($handle)) !== false) {
			$fl = trim($buffer);
			$rt = sendPhoto($chanID,$fl);
			echo $rt."\n";
		}
		file_put_contents('pos',ftell($handle));
		fclose($handle);
	}
	


?>
