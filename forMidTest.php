<?php
include_once('line-bot-api/php/line-bot.php');
require_once('db_connect.php');
//////////////////////////////////////////////////////////////////////////////////////////////
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\LocationTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\BubbleStylesBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
////////////////////////////////////////////////////////////////////////////////////////////

 $accessToken =  "XIEhlgy84s6dYwga0HP/ajjA2UnB2tdc8Tcqe66ynYbGk3w3L/lN8f+UnWXNG73l9XbMgwO8FI6jA1JQ022OTsWmIYN6lw2hWA/+wgXFHBf2aHNSiDCmdPLkhEXJpBlb5P+SF7tU38SJtE33zlnoSQdB04t89/1O/w1cDnyilFU=";
    
    $channelSecret = "8825ba5b1b40e9fa4c26e44ad8bd93fd";
    
    $userId = "U11848b8bc9035c8e9640f36f25463716";

    $bot = new BOT_API($channelSecret,$accessToken);


    //$bot->sendMessageNew($userId,"Hello");
	if($bot->isText)
	{
		//$bot->replyMessageNew($bot->replyToken,$bot->message->text);
		if($bot->message->text == 'multi' || $bot->message->text == 'Multi')
		{
			$multimessage = new MultiMessageBuilder();
			$text = new TextMessageBuilder("Hello",$message);
			$sticker = new StickerMessageBuilder(1,6);
			$multimessage->add($text);
			$multimessage->add($sticker);
			$bot->replyMessage($bot->replyToken,$multimessage);
		}
		else if($bot->message->text == 'temp' || $bot->message->text == 'Temp')
		{
			$action[] = new UriTemplateActionBuilder('Google','https://www.google.com');
			$action[] = new MessageTemplateActionBuilder('test','test template');
			$imge_template = "https://bygene.000webhostapp.com/image_line/U11848b8bc9035c8e9640f36f25463716.jpg";
			$btn = new ButtonTemplateBuilder('ButtonTemplate','Hi',$imge_template,$action);
			$obj = new TemplateMessageBuilder('Template alt text',$btn);
			$bot->replyMessage($bot->replyToken,$obj);
		}
		else if($bot->message->text == 'carou' || $bot->message->text == 'Carou')
		{
			$action[] = new UriTemplateActionBuilder('Google','https://www.google.com');
			$action[] = new MessageTemplateActionBuilder('test','test Carou');
			$imge_template = "https://bygene.000webhostapp.com/image_line/U11848b8bc9035c8e9640f36f25463716.jpg";
			$btn = new CarouselColumnTemplateBuilder('ButtonTemplate','Hi',$imge_template,$action);
			$arr[] = $btn;
			$action2[] = new UriTemplateActionBuilder('Youtube','https://www.youtube.com');
			$action2[] = new MessageTemplateActionBuilder('test 2','test Carou 2');
			$imge_template2 = "https://bygene.000webhostapp.com/image_line/U11848b8bc9035c8e9640f36f25463716.jpg";
			$btn2 = new CarouselColumnTemplateBuilder('ButtonTemplate','Hi2',$imge_template2,$action2);
			$arr[] = $btn2;
			$objtemplate = new CarouselTemplateBuilder($arr);
			$message = new TemplateMessageBuilder('Template alt text',$objtemplate);
			$bot->replyMessage($bot->replyToken,$message);
		}
		else if($bot->message->text == 'con' || $bot->message->text == 'Con')
		{
			$action[] = new MessageTemplateActionBuilder('Hi','Hi');
			$action[] = new MessageTemplateActionBuilder('Bye','Bye');
			$confirm = new ConfirmTemplateBuilder('Confirm temp',$action);
			$objcon = new TemplateMessageBuilder('Confirm alt text',$confirm);
			$bot->replyMessage($bot->replyToken,$objcon);
		}
		else if($bot->message->text == 'add' || $bot->message->text == 'Add')
		{
			$response = $bot->getProfile($bot->source->userId);
			$profile = $response->getJSONDecodedBody();
			$displayname = $profile['displayName'];
			$urlFile = $profile['pictureUrl'];
			$content = file_get_contents($urlFile);
			if($content)
			{
            	$output = "image_line/".$bot->source->userId.'.jpg';
            	file_put_contents($output, $content);
        	}
			$check = insertUser($bot->source->userId,$displayname);
			$bot->replyMessageNew($bot->replyToken,"Add! Success.");
		}
	}
	else if($bot->isSticker)
	{
		// https://developers.line.me/media/messaging-api/sticker_list.pdf  
		//1 for packerId 6 for Sticker
		//$sticker = new StickerMessageBuilder(1,6);
		//$bot->replyMessage($bot->replyToken,$sticker);
		// sent stickerid and packageid to user
		// $stickerID = new LINE\LINEBot\MessageBuilder\TextMessageBuilder($bot->message->stickerId);
		// $stickerID = new LINE\LINEBot\MessageBuilder\TextMessageBuilder($bot->message->packageId);
		// or 
		 $bot->replyMessageNew($bot->replyToken,$bot->message->packageId.' + '.$bot->message->stickerId);
	}
	else if($bot->isImage)
	{
		//$bot->replyMessageNew($bot->replyToken,"Image");
		//$messages = bot-message->id;} for get image and send the same image
		//getmessageConten($messages);}
		$response = $bot->getProfile($bot->source->userId);
		$profile = $response->getJSONDecodedBody();
		$urlFile = $profile['pictureUrl'];
		$content = file_get_contents($urlFile);
		if($content)
		{
            $output = "image_line/".$bot->source->userId.'.jpg';
            file_put_contents($output, $content);
        }
        $image_url = "https://bygene.000webhostapp.com/".$output;
        $outputText = new ImageMessageBuilder($image_url,$image_url);
        $bot->replyMessage($bot->replyToken,$outputText);
	}
	else if($bot->isLocation)
	{
		/*$title = "โรงพยาบาลสินแพทย์";
		$address = "9, 99 ถนน รามอินทรา แขวง คันนายาว เขต คันนายาว กรุงเทพมหานคร 10230";
		$latitude = "13.8311955";
		$longitude = "100.6684089";
		v*/
		$title = $bot->message->title;
		if(!$title)
		{
			$title = "Unknow Title";
		}
		$address = $bot->message->address;
		$latitude = $bot->message->latitude;
		$longitude = $bot->message->longitude;
		$location = new LocationMessageBuilder($title,$address,$latitude,$longitude);
		$bot->replyMessage($bot->replyToken,$location);
	}


    //////  replyMessageNew = NO USE OBJECT
	//////  replyMessage = USE OBJECT
?>