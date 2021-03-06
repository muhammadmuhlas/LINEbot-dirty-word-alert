<?php

require __DIR__ . '/../lib/vendor/autoload.php';

use \LINE\LINEBot\SignatureValidator as SignatureValidator;

// load config
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// initiate app
$configs =  [
	'settings' => ['displayErrorDetails' => true],
];
$app = new Slim\App($configs);

/* ROUTES */
$app->get('/', function ($request, $response) {
	return "Lanjutkan!";
});

$app->post('/', function ($request, $response)
{
	// get request body and line signature header
	$body 	   = file_get_contents('php://input');
	$signature = $_SERVER['HTTP_X_LINE_SIGNATURE'];

	// log body and signature
	file_put_contents('php://stderr', 'Body: '.$body);

	// is LINE_SIGNATURE exists in request header?
	if (empty($signature)){
		return $response->withStatus(400, 'Signature not set');
	}

	// is this request comes from LINE?
	if($_ENV['PASS_SIGNATURE'] == false && ! SignatureValidator::validateSignature($body, $_ENV['CHANNEL_SECRET'], $signature)){
		return $response->withStatus(400, 'Invalid signature');
	}
    
	// init bot
	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
    
    
    //user
    $ehe = $bot->getProfile('<userId>');
    $profile = json_decode($ehe, true);
    $dp = $profile['displayName'];
                        
    //dirty word
$dirtyWords=array(
'/\ba+(|[\s\S])s+(|[\s\S])s+\b/i',//ass
'/\ba+(|[\s\S])s+(|[\s\S])s+(|[\s\S])h+(|[\s\S])o+(|[\s\S])l+(|[\s\S])e+\b/i',//asshole
'/\ba+(|[\s\S])s+(|[\s\S])s+(|[\s\S])h+(|[\s\S])a+(|[\s\S])t+\b/i',//asshat
'/\ba+(|[\s\S])s+(|[\s\S])u+\b/i',//asu
'/\ba+(|[\s\S])s+(|[\s\S])u+(|[\s\S])w+\b/i',//asuw
'/\ba+(|[\s\S])s+(|[\s\S])y+(|[\s\S])u+\b/i',//asyu
'/\ba+(|[\s\S])f+(|[\s\S])u+\b/i',//afu
'/\bb+(|[\s\S])a+(|[\s\S])j+(|[\s\S])i+(|[\s\S])n+(|[\s\S])g+(|[\s\S])a+(|[\s\S])n+\b/i',//bajingan
'/\bb+(|[\s\S])a+(|[\s\S])n+(|[\s\S])g+(|[\s\S])s+(|[\s\S])a+(|[\s\S])t+\b/i',//bangsat
'/\bb+(|[\s\S])a+(|[\s\S])s+(|[\s\S])t+(|[\s\S])a+(|[\s\S])r+(|[\s\S])d+\b/i',//bastard
'/\bb+(|[\s\S])e+(|[\s\S])w+(|[\s\S])b+\b/i',//bewb
'/\bb+(|[\s\S])e+(|[\s\S])w+(|[\s\S])b+(|[\s\S])s+\b/i',//bewbs
'/\bb+(|[\s\S])i+(|[\s\S])t+(|[\s\S])c+(|[\s\S])h+\b/i',//bitch
'/\bb+(|[\s\S])o+(|[\s\S])l+(|[\s\S])o+(|[\s\S])t+\b/i',//bolot
'/\bb+(|[\s\S])o+(|[\s\S])o+(|[\s\S])b+\b/i',//boob
'/\bb+(|[\s\S])o+(|[\s\S])o+(|[\s\S])b+(|[\s\S])s+\b/i',//boobs
'/\bb+(|[\s\S])r+(|[\s\S])e+(|[\s\S])n+(|[\s\S])g+(|[\s\S])s+(|[\s\S])e+(|[\s\S])k+\b/i',//brengsek
'/\bb+(|[\s\S])y+(|[\s\S])l+(|[\s\S])a+(|[\s\S])t+\b/i',//bylat
'/\bc+(|[\s\S])u+(|[\s\S])k+\b/i',//cuk
'/\bc+(|[\s\S])u+(|[\s\S])n+(|[\s\S])t+\b/i',//cunt
'/\bc+(|[\s\S])o+(|[\s\S])c+(|[\s\S])o+(|[\s\S])t+(|[\s\S])e+\b/i',//cocote
'/\bc+(|[\s\S])o+(|[\s\S])c+(|[\s\S])o+(|[\s\S])t+(|[\s\S])m+(|[\s\S])u+\b/i',//cocotmu
'/\bc+(|[\s\S])o+(|[\s\S])k+\b/i',//cok
'/\bc+(|[\s\S])y+(|[\s\S])k+(|[\s\S])a+\b/i',//cyka
'/\bd+(|[\s\S])e+(|[\s\S])m+(|[\s\S])a+\b/i',//dema
'/\bd+(|[\s\S])i+(|[\s\S])c+(|[\s\S])k+\b/i',//dick
'/\bd+(|[\s\S])i+(|[\s\S])c+(|[\s\S])k+(|[\s\S])h+(|[\s\S])e+(|[\s\S])a+(|[\s\S])d+\b/i',//dickhead
'/\bd+(|[\s\S])o+(|[\s\S])b+(|[\s\S])o+(|[\s\S])l+\b/i',//dobol
'/\bf+(|[\s\S])u+(|[\s\S])c+(|[\s\S])k+\b/i',//fuck
'/\bf+(|[\s\S])u+(|[\s\S])c+(|[\s\S])k+(|[\s\S])e+(|[\s\S])d+\b/i',//fucked
'/\bf+(|[\s\S])u+(|[\s\S])c+(|[\s\S])k+(|[\s\S])e+(|[\s\S])r+\b/i',//fucker
'/\bf+(|[\s\S])u+(|[\s\S])c+(|[\s\S])k+(|[\s\S])h+(|[\s\S])e+(|[\s\S])a+(|[\s\S])d+\b/i',//fuckhead
'/\bf+(|[\s\S])u+(|[\s\S])c+(|[\s\S])k+(|[\s\S])i+(|[\s\S])n+(|[\s\S])g+\b/i',//fucking
'/\bi+(|[\s\S])t+(|[\s\S])i+(|[\s\S])l+\b/i',//itil
'/\bj+(|[\s\S])a+(|[\s\S])m+(|[\s\S])p+(|[\s\S])u+(|[\s\S])t+\b/i',//jamput
'/\bj+(|[\s\S])a+(|[\s\S])n+(|[\s\S])c+(|[\s\S])o+(|[\s\S])k+\b/i',//jancok
'/\bj+(|[\s\S])a+(|[\s\S])n+(|[\s\S])c+(|[\s\S])u+(|[\s\S])k+\b/i',//jancuk
'/\bj+(|[\s\S])e+(|[\s\S])m+(|[\s\S])b+(|[\s\S])u+(|[\s\S])t+\b/i',//jembut
'/\bk+(|[\s\S])a+(|[\s\S])m+(|[\s\S])p+(|[\s\S])r+(|[\s\S])e+(|[\s\S])t+\b/i',//kampret
'/\bk+(|[\s\S])e+(|[\s\S])n+(|[\s\S])t+(|[\s\S])u+\b/i',//kentu
'/\bk+(|[\s\S])e+(|[\s\S])n+(|[\s\S])t+(|[\s\S])o+(|[\s\S])t+\b/i',//kentot
'/\bk+(|[\s\S])i+(|[\s\S])m+(|[\s\S])c+(|[\s\S])i+(|[\s\S])l+\b/i',//kimcil
'/\bk+(|[\s\S])o+(|[\s\S])n+(|[\s\S])t+(|[\s\S])o+(|[\s\S])l+\b/i',//kontol
'/\bk+(|[\s\S])o+(|[\s\S])t+(|[\s\S])o+(|[\s\S])r+\b/i',//kotor
'/\bk+(|[\s\S])u+(|[\s\S])n+(|[\s\S])y+(|[\s\S])u+(|[\s\S])k+\b/i',//kunyuk
'/\bm+(|[\s\S])e+(|[\s\S])k+(|[\s\S])i+\b/i',//meki
'/\bm+(|[\s\S])e+(|[\s\S])m+(|[\s\S])e+(|[\s\S])k+\b/i',//memek
'/\bm+(|[\s\S])o+(|[\s\S])t+(|[\s\S])h+(|[\s\S])e+(|[\s\S])r+(|[\s\S])f+(|[\s\S])u+(|[\s\S])c+(|[\s\S])k+(|[\s\S])e+(|[\s\S])r+\b/i',//motherfucker
'/\bm+(|[\s\S])o+(|[\s\S])t+(|[\s\S])h+(|[\s\S])e+(|[\s\S])r+(|[\s\S])f+(|[\s\S])u+(|[\s\S])c+(|[\s\S])k+(|[\s\S])e+(|[\s\S])r+(|[\s\S])s+\b/i',//motherfuckers
'/\bn+(|[\s\S])d+(|[\s\S])e+(|[\s\S])s+\b/i',//ndes
'/\bn+(|[\s\S])d+(|[\s\S])e+(|[\s\S])z+\b/i',//ndez
'/\bn+(|[\s\S])g+(|[\s\S])e+(|[\s\S])h+(|[\s\S])e+\b/i',//ngehe
'/\bn+(|[\s\S])g+(|[\s\S])e+(|[\s\S])n+(|[\s\S])t+(|[\s\S])o+(|[\s\S])t+\b/i',//ngentot
'/\bn+(|[\s\S])g+(|[\s\S])e+(|[\s\S])w+(|[\s\S])e+\b/i',//ngewe
'/\bn+(|[\s\S])j+(|[\s\S])i+(|[\s\S])n+(|[\s\S])g+\b/i',//njing
'/\bn+(|[\s\S])y+(|[\s\S])i+(|[\s\S])n+(|[\s\S])g+\b/i',//nying
'/\bp+(|[\s\S])a+(|[\s\S])n+(|[\s\S])t+(|[\s\S])e+(|[\s\S])k+\b/i',//pantek
'/\bp+(|[\s\S])e+(|[\s\S])c+(|[\s\S])u+(|[\s\S])n+\b/i',//pecun
'/\bp+(|[\s\S])e+(|[\s\S])l+(|[\s\S])i+\b/i',//peli
'/\bp+(|[\s\S])e+(|[\s\S])r+(|[\s\S])e+(|[\s\S])k+\b/i',//perek
'/\bp+(|[\s\S])u+(|[\s\S])k+(|[\s\S])i+\b/i',//puki
'/\bp+(|[\s\S])u+(|[\s\S])t+(|[\s\S])a+\b/i',//puta
'/\bp+(|[\s\S])u+(|[\s\S])t+(|[\s\S])a+(|[\s\S])n+(|[\s\S])g+\b/i',//putang
'/\bp+(|[\s\S])u+(|[\s\S])s+(|[\s\S])s+(|[\s\S])y+\b/i',//pussy
'/\bs+(|[\s\S])h+(|[\s\S])i+(|[\s\S])t+\b/i',//shit
'/\bs+(|[\s\S])h+(|[\s\S])i+(|[\s\S])t+(|[\s\S])h+(|[\s\S])e+(|[\s\S])a+(|[\s\S])d+\b/i',//shithead
'/\bs+(|[\s\S])h+(|[\s\S])i+(|[\s\S])t+(|[\s\S])h+(|[\s\S])o+(|[\s\S])l+(|[\s\S])e+\b/i',//shithole
'/\bs+(|[\s\S])h+(|[\s\S])i+(|[\s\S])t+(|[\s\S])t+(|[\s\S])y+\b/i',//shitty
'/\bt+(|[\s\S])a+(|[\s\S])e+\b/i',//tae
'/\bt+(|[\s\S])a+(|[\s\S])e+(|[\s\S])k+\b/i',//taek
'/\bt+(|[\s\S])a+(|[\s\S])i+\b/i',//tai
'/\bt+(|[\s\S])a+(|[\s\S])i+(|[\s\S])k+\b/i',//taik
'/\bt+(|[\s\S])a+(|[\s\S])h+(|[\s\S])i+\b/i',//tahi
'/\bt+(|[\s\S])e+(|[\s\S])m+(|[\s\S])p+(|[\s\S])i+(|[\s\S])k+\b/i',//tempik
'/\bt+(|[\s\S])i+(|[\s\S])t+(|[\s\S])s+\b/i',//tits
'/\bt+(|[\s\S])o+(|[\s\S])k+(|[\s\S])e+(|[\s\S])t+\b/i',//toket
'/\bt+(|[\s\S])o+(|[\s\S])l+(|[\s\S])o+(|[\s\S])l+\b/i',//tolol
'/\bu+(|[\s\S])d+(|[\s\S])i+(|[\s\S])k+\b/i',//udik
);    
    //get event webhook
	$data = json_decode($body, true);
    
   
	foreach ($data['events'] as $event)
	{
		if ($event['type'] == 'message')
		{   
            
			if($event['message']['type'] == 'text')
			{    
            foreach ($dirtyWords as $dirtyWord) {
                if (preg_match($dirtyWord, $event['message']['text'])) {
                    $result = $bot->replyText($event['replyToken'], "Astaghfirullahaladzim, jangan berkata kotor");
                }
                
                
            //            if (stripos($event['message']['text'], $dirtyWord ) !== false) {
            //                $result = $bot->replyText($event['replyToken'], "Astaghfirullahaladzim, jangan berkata kotor");
            //            
            //            $stickerMessageBuilder = new \LINE\LINEbot\MessageBuilder\StickerMessageBuilder("2","152");
            //            $rp = $bot->pushMessage($event['source']['userId'], $stickerMessageBuilder);
            //        //    $rp = $bot->pushMessage($event['source']['groupId'], $stickerMessageBuilder);
            //        //    $rp = $bot->pushMessage($event['source']['roomId'], $stickerMessageBuilder);    
            //            }
                    }
                
                        
                
				// send same message as reply to user
				//$result = $bot->replyText($event['replyToken'], $event['message']['text']);

				// or we can use pushMessage() instead to send reply message
                //$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("Tolong jangan gunakan kata kasar");
                //$pm = $bot->pushMessage($event['source']['userId'], $textMessageBuilder);
				
				return $result->getHTTPStatus() . ' ' . $result->getRawBody();
              //  return $rp->getHTTPStatus() . ' ' . $rp->getRawBody();
                //return $pm->getHTTPStatus() . ' ' . $pm->getRawBody();
			}
		}
	}

});

// $app->get('/push/{to}/{message}', function ($request, $response, $args)
// {
// 	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
// 	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);

// 	$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($args['message']);
// 	$result = $bot->pushMessage($args['to'], $textMessageBuilder);

// 	return $result->getHTTPStatus() . ' ' . $result->getRawBody();
// });

/* JUST RUN IT */
$app->run();