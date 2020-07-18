<?php
	// Mol Bozor Bot
	define('API_KEY', "1249266736:AAHO9TC8CilXjkMpcbTjEk2vBNADRmUeTLM");
	
	function bot($method, $datas=[]){
		$url = "https://api.telegram.org/bot".API_KEY."/".$method;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
		
		$res = curl_exec($ch);
		
		if(curl_error($ch)){
			var_dump(curl_error($ch));
		}else{
			return json_decode($res);
		}
		
	}
	
	function del($nomi){
        array_map('unlink', glob("step/$nomi.*"));
    }
    function put($fayl, $nima){
        file_put_contents("$fayl", "$nima");
    }

    function pstep($cid,$zn){
        file_put_contents("step/$cid.step",$zn);
    }

    function step($cid){
        $step = file_get_contents("step/$cid.step");
        $step += 1;
        file_put_contents("step/$cid.step",$step);
    }

    function nextTx($cid,$txt){
        $step = file_get_contents("step/$cid.txt");
        file_put_contents("step/$cid.txt","$step\n$txt");
    }

    function ACL($callbackQueryId, $text = null, $showAlert = false)
    {
        return bot('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
            'text' => $text,
            'show_alert' => $showAlert,
        ]);
    }
	
	function typing($ch){
		return bot('sendChatAction',[
				'chat_id' => $ch,
				'action' => 'typing'
			]);
	}
	$update = json_decode(file_get_contents('php://input'));
    $message = $update->message;
    $cid = $message->chat->id;
    $cidtyp = $message->chat->type;
    $miid = $message->message_id;
    $name = $message->chat->first_name;
    $user = $message->from->username;
    $tx = $message->text;
    $callback = $update->callback_query;
    $mmid = $callback->inline_message_id;
    $mes = $callback->message;
    $mid = $mes->message_id;
    $cmtx = $mes->text;
    $mmid = $callback->inline_message_id;
    $idd = $callback->message->chat->id;
    $cbid = $callback->from->id;
    $cbuser = $callback->from->username;
    $data = $callback->data;
    $ida = $callback->id;
    $cqid = $update->callback_query->id;
    $cbins = $callback->chat_instance;
    $cbchtyp = $callback->message->chat->type;
    $step = file_get_contents("step/$cid.step");
    $menu = file_get_contents("step/$cid.menu");
    $stepe = file_get_contents("step/$cbid.step");
    $menue = file_get_contents("step/$cbid.menu");
	// php.ini allow_fopen = 1
	$chat_id = $message->chat->id;
	$text = $message->text;
	
	$button = json_encode([
			'resize_keyboard' => true,
			'keyboard' => [
					[['text' => "Qo'ylar"], ['text' => "Mollar"], ['text' => "Otlar"], ['text' => "Boshqa"]],
					[['text' => "E'lon berish"]]
				]
		]);
	$cencel = json_encode([
			'resize_keyboard' => true,
			'keyboard' => [
					[['text' => "Ortga"]]
				]	
		]);	
	
	if(isset($text)){
		typing($chat_id);
	}
	
	if($text == "/start"){
		bot('sendMessage', [
				'chat_id' => $chat_id,
				'text' => 'Sirdaryo viloyati chorva bozori',
				'parse_mode' => 'markdown',
				'reply_markup' => $button
			]);
	}
	
	if($text == "Bot haqida"){
		bot('sendMessage', [
				'chat_id' => $chat_id,
				'text' => 'Bu bot orqali Sirdaryo viloyati bo\'ylab chorva mollarini sotib olishingiz yoki sotishingiz mumkin.',
				'parse_mode' => 'markdown',
				'reply_markup' => $cencel
			]);
	}
	
	if($text == "Manzil"){
		bot('sendLocation', [
				'chat_id' => $chat_id,
				'latitude' => 40.4997661,
				'longitude' => 68.7727377,
				'reply_markup' => $cencel
			]);
	}
	
	if($text == "Ortga"){
		bot('sendMessage', [
				'chat_id' => $chat_id,
				'text' => 'Salom!!! Sirdaryo viloyati chorva bozoriga Hush kelibsiz! ',
				'parse_mode' => 'markdown',
				'reply_markup' => $button
			]);
	}
	
	// register uchun
	$otex = "Bekor qilish";
	$otmen = json_encode([
        'resize_keyboard'=>true,
        'keyboard'=>[
            [['text'=>"$otex"],],
        ]
    ]);
    if($tx == "E'lon berish"){
        bot('sendMessage', [
            'chat_id' => $cid,
            'text' => "Ismingiz?\n(Masalan : Azamat)",
            'parse_mode' => 'markdown',
            'reply_markup' => $otmen,
        ]);
        pstep($cid,"0");
        put("step/$cid.menu","register");
    }

    if($step == "0" and $menu == "register"){
        if($tx == $otex){}else{
            bot('sendMessage', [
                'chat_id' => $cid,
                'text' => "Manzilingiz?\n(Masalan: Mirzaobod tumani Beruniy maxallasi)",
                'parse_mode' => 'markdown',
                'reply_markup' => $otmen,
            ]);
        nextTx($cid, "Mol egasi: ". $tx);
        step($cid);
        }
    }

    if($step == "1" and $menu == "register"){
        if($tx == $otex){}else{
            bot('sendMessage', [
                'chat_id' => $cid,
                'text' => "Sotmoqchi bo'lgan molingiz haqida yozing.\n(Masalan : Yoshi, vazni, zoti...)",
                'parse_mode' => 'markdown',
                'reply_markup' => $otmen,
            ]);
        nextTx($cid, "Manzil: ".$tx);
        step($cid);
        }
    }

    if($step == "2" and $menu == "register"){
        if($tx == $otex){}else{
            bot('sendMessage', [
                'chat_id' => $cid,
                'text' => "Mol suratini yuboring.",
                'parse_mode' => 'markdown',
                'reply_markup' => $cancel,
            ]);
            nextTx($cid, "Mol haqida: ".$tx);
            step($cid);
        }
    }

    if($step == "3" and $menu == "register"){
        bot('sendMessage', [
                'chat_id' => $cid,
                'text' => "Telefon raqamingizni kiriting?\n(Masalan : +99897 1234567)",
                'parse_mode' => 'markdown',
                'reply_markup' => $cancel,
            ]);
        nextTx($cid, "Surati: ".$tx);
        step($cid);
    }

    if($step == "4" and $menu == "register"){
        if($tx == $otex){}else{
            if(mb_stripos($tx,"9989")!==false){
            bot('sendMessage', [
                    'chat_id'=>$cid,
                    'text'=>"*Ma'lumotlar muvaffaqiyatli saqlandi*, Iltimos bot faoliyatini baholang?",
                    'parse_mode'=>'markdown',
                    'reply_markup' => $manzil,
                ]);
                nextTx($cid, "Aloqa: ".$tx);
                step($cid);
            }else{
                bot('sendMessage', [
                'chat_id' => $cid,
                'text' => "Telefon raqamingizni kiriting?\n(Masalan : 99897 1234567)",
                'parse_mode' => 'markdown',
                'reply_markup' => $cancel,
            ]);
            }
        }
    }

    if(isset($data) and $stepe == "5" and $menue == "register"){
        ACL($ida);
        $baza = file_get_contents("step/$cbid.txt");
        bot('sendMessage',[
            'chat_id'=>$cbid,
            'text'=>"<b>Sizning Anketa tayyor bo'ldi, barchasi ma'lumotlaringiz tasdiqlaysizmi?</b>
            $baza\n☑️ Rating : $data",
            'parse_mode'=>'html',
            'reply_markup'=>$tasdiq,
        ]);
        nextTx($cbid, "☑️ Rating: ".$data);
        step($cbid);
    }

    if($data == "ok" and $stepe == "6" and $menue == "register"){
        ACL($ida);
        $baza = file_get_contents("step/$cbid.txt");
        bot('sendMessage',[
            'chat_id'=>$admin,
            'text'=>"<b>Yangi o'quvchi!</b>
            Username: @$cbuser
            <a href='tg://user?id=$cbid'>Zaxira profili</a><code>$baza</code>",
            'parse_mode'=>'html',
        ]);
        bot('sendMessage',[
            'chat_id'=>$cbid,
            'text'=>"✅ Sizning Anketangiz xodimlarimizga muvaffaqiyatli jo'natildi, qisqa fursatlarda sizga aloqaga chiqamiz! E'tiboringiz uchun rahmat",
            'parse_mode'=>'html',
            'reply_markup'=>$keys,
        ]);
        del($cbid);
    }
?>
