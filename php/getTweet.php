<?php
require '../lib/TwistOAuth.php';

$consumer_key = 'B3nHeyxBEUrvqz0mj3qGCjtcP';
$consumer_secret = 'Yacibr07A57SQVHLngJxoLdY2WrO2gp4megsYacBhJvHVQlSaj';
$access_token = '1853767500-1PTXSQc8sErYtPlCVq2cgFnST6hfZhcQAWTvZp2';
$access_token_secret = 'pBsHsc23gxjHKjEKGsdCcjODLjqCIy54DicNfySfs2Vbk';

$url = 'http://jlp.yahooapis.jp/KeyphraseService/V1/extract?appid=dj0zaiZpPThySkRNQVpuUjZoZCZzPWNvbnN1bWVyc2VjcmV0Jng9ZDg-&sentence=';

$search_word = $_GET['search_word'];
$word_array = [];

//　形態素解析サンプル
// $result = file_get_contents($url."PHPからJavaScriptに変数を渡すまとめ");
// $parse_txt = simplexml_load_string($result);

$connection = new TwistOauth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
$search_result = $connection->get('search/tweets', ['q' => $search_word, 'count' => '30'])->statuses;
foreach($search_result as $result_text){
    $txt = str_replace([" ", "　", "#"], "", $result_text->text);
    $result = file_get_contents($url.$txt);
    $parse_txt = simplexml_load_string($result);
    $result_array = json_decode(json_encode($parse_txt))->Result;

    if(is_object($result_array)){
        $result_array->Keyphrase = str_replace([" ", "　"], "", $result_array->Keyphrase);
        if(count($word_array) === 0) {
            array_push($word_array, ['name' => $result_array->Keyphrase, 'score' => $result_array->Score]);
            continue;
        }
        for($i = 0; $i < count($word_array); $i++){
            if($word_array[$i]['name'] === $result_array->Keyphrase){
                $word_array[$i]['score'] += $result_array->Score;
                continue;
            }
        }
        array_push($word_array, ['name' => $result_array->Keyphrase, 'score' => $result_array->Score]);
    }else{
        foreach((array)$result_array as $word){
            $word->Keyphrase = str_replace([" ", "　"], "", $word->Keyphrase);
            if(count($word_array) === 0) {
                array_push($word_array, ['name' => $word->Keyphrase, 'score' => $word->Score]);
                continue;
            }
            for($i = 0; $i < count($word_array); $i++){
                if($word_array[$i]['name'] === $word->Keyphrase){
                    $word_array[$i]['score'] += $word->Score;
                    continue 2;
                }
            }
            array_push($word_array, ['name' => $word->Keyphrase, 'score' => $word->Score]);
        }
    }
}

// 　ユーザータイムライン
// $my_tweet = $connection->get('statuses/user_timeline', $user_params);
// $keywords = $my_tweet[0]->text;

$count = count($word_array);

for($i = 0; $i < $count; $i++){
    if($word_array[$i]['score'] < 80){
        unset($word_array[$i]);
    }
}

echo json_encode($word_array);
