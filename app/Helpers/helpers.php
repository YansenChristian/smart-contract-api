<?php

use App\User as userModel;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use App\Models\BuildingMaterial\OfficialMember;
use App\Models\BuildingMaterial\PriceLocation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;

/**
 * @param null $uri
 * @return string
 */
function cdn($uri=null)
{
    if(!$uri) {
        $uri = '/assets/img/no-image.png';
    }

    $base_url = env('URL_IMAGE_PASSTHROUGH');

    return sprintf('%s/%s', $base_url, $uri);
}

/**
 * Function error handling log error only
 *
 * @param $e, methods inherited from Exception
 * @param $data, get additional data [array]
 */
function logError($e, $data = [], $class = '')
{
    $log = json_encode($data);
    Log::error($class . ' fails ' . $log, ['line' => @$e->getLine(), 'file' => @$e->getFile(), 'message' => @$e->getMessage()]);
}

/**
 * @param string $string
 * @param string $replacement
 * @return null|string|string[]
 */
function removeWhiteSpaceAndSymbol(string $string, string $replacement = '_')
{
    // Replace any symbol | whitespace | duplicate dash
    $patterns = [
        '([\!\#\$\%\&\\\'\*\+\=\?\^\_\`\{\|\}\~\@\.\[\]]+)',
        '(\s+)',
        '('.$replacement.'+)'
    ];
    $string = preg_replace($patterns, $replacement, $string);
    return $string;
}

function orderSerialToOriginal($order_serial_alias) {
    $order_serial = str_replace('---', '/', $order_serial_alias);

    return $order_serial;
}

function parseNumber($number)
{
    if ($number && intval($number) != $number) {
        return doubleval($number);
    }
    return intval($number);
}

function uploadImage($imageName, $imageData, $bucketPath)
{
    $filePath = $bucketPath . $imageName;

    try {
        $s3 = \Storage::disk("s3");
        $s3->put($filePath, $imageData, "public");
    } catch (\Exception $e) {
        $log = ['Helpers' => 'helpers', 'function' => 'uploadImage'];
        logError($e, $log);
        throw $e;
    }
}

function convertBase64ToImage($base64)
{
    $pathImage = env('LOCATION_TMP') . str_random() . "_" . strtotime(date('C'));
    $ifp = fopen($pathImage, 'wb');
    fwrite($ifp, base64_decode($base64));
    fclose($ifp);

    return $pathImage;
}

function uploadImageFromUrl($imageName, $imageUrl, $bucketPath)
{
    // Encode UTF8 character from URL
    $imageUrlExplode = explode('/', $imageUrl);
    $imageUrlFile = collect($imageUrlExplode)->last();
    array_pop($imageUrlExplode);
    array_push($imageUrlExplode, urlencode($imageUrlFile));
    $imageUrl = implode('/', $imageUrlExplode);

    $image = Image::make($imageUrl);
    $image->encode("png");

    uploadImage($imageName, $image->__toString(), $bucketPath);
}

function sendgridMailAttachment(
    $fromEmail = null,
    $fromName = null,
    $toEmail,
    $toName,
    $replyTo = null,
    $templateId,
    $subs = [],
    $subject = null,
    $attachments = [],
    $scheduleTime = null,
    $status = 1,
    $statusLog = null,
    $force = false
 )
 {
    $sg = new \SendGrid(env('SENDGRID_API_KEY'));
    $from = new SendGrid\Email($fromName, $fromEmail);
    $to = new SendGrid\Email($toName, $toEmail);
    $content = new SendGrid\Content("text/html", "1");
    $mail = new SendGrid\Mail($from, $subject, $to, $content);
    try {
        $mail->setTemplateId($templateId);   
        $mail->setReplyTo($replyTo);
        $response = $sg->client->templates()->_($templateId)->get();
        $response = json_decode($response->body());
        # versioning template sendgrid | get latest version of email template and assign it to $html
        if (env('APP_ENV') == 'production') { # template production is the active version
            foreach ($response->versions as $email) {
                if ($email->active == 1) {
                    $html = $email->html_content;
                    break;
                }
            }
        } else { # if version more than one | template dev is the newest version
            $html = $response->versions[0]->html_content;
        }
        if (count($subs) > 0) {
            foreach ($subs as $sub) {
                $mail->personalization[0]->addSubstitution($sub['keyTemplate'], $sub['valueTemplate']);
                $html = str_replace($sub['keyTemplate'], $sub['valueTemplate'], $html);
            }
        }
        $attachmentValue = []; 
        $mailAttachments = [];
        if ($attachments!= null && count($attachments) > 0) { 
            if (env('APP_ENV') == 'production') {
                foreach ($attachments as $key => $attach) {
                    $file = $attach['path'];
                    $file_contents = file_get_contents($file);
                    $file_encoded = base64_encode($file_contents); 
                    $a = new SendGrid\Attachment();
                    $a->setContent($file_encoded);
                    $a->setType($attach['content_type']);
                    $a->setDisposition("attachment");
                    $a->setFilename($attach['name'].$attach["extension"]);
                    $mail->addAttachment($a);
                    $attachmentValue[] = $file_encoded;   
                }
            } else {
                foreach ($attachments as $key => $attach) {
                    $file = $attach['path'];
                    $file_contents = file_get_contents($file);
                    $file_encoded = base64_encode($file_contents); 
                    $mailAttachments[] = (new Swift_Attachment())
                            ->setFilename($attach['name'] . $attach['extension'])
                            ->setContentType($attach['content_type'])
                            ->setBody($file_contents);
                    $attachmentValue[] = $file_encoded;   
                }
            }  
        }   
        # Send mail
        if (env('APP_ENV') == 'production') {
            $response = $sg->client->mail()->send()->post($mail);
            $status = intval(substr($response->statusCode(), 0, 1)) == 2 ? 1 : 0;
        } else {
            Mail::send([], $subs, function($message) use ($toEmail, $toName, $fromEmail, $subject, $html, $mailAttachments)
            {
                $message = $message->to($toEmail, $toName)
                    ->from($fromEmail)
                    ->subject($subject)
                    ->setBody($html, 'text/html');                    
                foreach ($mailAttachments as $mailAttachment) {
                    $message = $message->attach($mailAttachment);
                }
            });
            $status = 0;
        }
        # Insert to database
        $insertion = [
            'email_sender' => ($fromEmail) ? $fromEmail : 'support@ralali.com',
            'email_sender_name' => ($fromName) ? $fromName : env('INFO_APP_DOMAIN'),
            'email_recipient' => $toEmail,
            'email_recipient_name' => $toName,
            'email_subject' => $subject,
            'email_replyto' => $replyTo,
            'email_body' => $html,
            'channel' => rand(1, 50),
            'email_attach' => (!isset($attachments)) ?: ((count($attachments) > 0) ? implode(";", $attachmentValue) : null),
            'schedule_time' => ($scheduleTime) ? $scheduleTime : date('Y-m-d'),
            'status' => $status,
            'status_log' => $statusLog
        ];
        DB::table('bulk_mail')->insert($insertion);
        DB::commit();
        return array("subject" => $subject, "body" => $html);
    } catch (Exception $e) {
        $log = ['Helpers' => 'helpers', 'function' => 'sendgridMailAttachment'];
        $dataError['email_recipient'] = $toEmail;
        $dataError['email_recipient_name'] = $toName;
        $dataError['email_subject'] = $subject;
        array_push($log, $dataError);
        logError($e, $log);
        return false;
    }
 }

// Old Helpers
function cdnMediaflex($uri=null)
{
    $base_url= str_replace('1', rand(1,4), env('CDN_MEDIAFLEX_URL')) ? : asset('');

    if(!empty($uri)){
        $base_url.=$uri;
    }
    return $base_url;
}

function remoteFileExists($url) {
    $curl = curl_init($url);

    //don't fetch the actual page, you only want to check the connection is ok
    curl_setopt($curl, CURLOPT_NOBODY, true);

    //do request
    $result = curl_exec($curl);

    $ret = false;

    //if request did not fail
    if ($result !== false) {
        //if request was ok, check response code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($statusCode == 200) {
            $ret = true;
        }
    }

    curl_close($curl);

    return $ret;
}

function checkPath($path) {
    $string = $path."/";
    if (strpos($string, '//') !== false) {
        return $path;
    }

    return $string;
}


function displayNumeric($number)
{
    return 'Rp '.number_format($number, 0, ',', '.');
}

function displayNumericWithoutRp($number)
{
    return number_format($number, 0, ',', '.');
}

function displayNum($number)
{
    return number_format($number, 2, ',', '.');
}

function getSelect($name, $list = array(), $selected = null, $options = array()) {

    $list = (array('' => '-- Please Select --') + $list);
    return \Form::select($name, $list, $selected, $options);
}

function get_enums($table, $column, $value = null, $options)
{
    $result = \DB::select(" SELECT COLUMN_TYPE
				FROM INFORMATION_SCHEMA.COLUMNS
				WHERE TABLE_SCHEMA = 'ralaliweb'
				AND TABLE_NAME = '$table'
				AND COLUMN_NAME = '$column' ");

    $str = str_replace("enum","", $result[0]->COLUMN_TYPE);
    $str = str_replace("('","", $str);
    $str = str_replace("')","", $str);
    $str = str_replace("'","", $str);
    $enums = explode(',', $str);
    $_enums = array_combine($enums, $enums);
    return getSelect($column, $_enums, $selected = null, $options);
}

function responses($rows = null)
{
    if(is_object($rows)){ // for listing all and row for editing
        $result = array(
            "status"		=> "success",
            "message"		=> "Tindakan berhasil dieksekusi",
            "result"		=> $rows,
        );
    }
    elseif($rows==1){ //for deleting and saving row
        $result = array(
            "status"		=> "success",
            "message"		=> "Tindakan berhasil dieksekusi",
            "message_flash"	=> \Session::flash('success', 'Data Successfully Saved'),
            "result"		=> $rows,
        );
    }
    else {
        $result = array( // handling all error
            "status"		=> "failed",
            "message"		=> "Something Went Wrong",
            "message_flash"	=> \Session::flash('warning', 'Something Wrong With Your Data'),
            "result"		=> 'Unknown Result',
        );
    }
    return $result;
}

function terbilang($n)
{
    $dasar = array(1 => 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam','tujuh', 'delapan', 'sembilan');
    $angka = array(1000000000, 1000000, 1000, 100, 10, 1);
    $satuan = array('milyar', 'juta', 'ribu', 'ratus', 'puluh', '');
    $str="";
    $i = 0;
    if($n==0){
        $str = "nol";
    }else{
        while ($n != 0) {
            $count = (int)($n/$angka[$i]);
            if ($count >= 10) {
                $str .= terbilang($count). "".$satuan[$i]." ";
            }else if($count > 0 && $count < 10){
                $str .= $dasar[$count] . " ".$satuan[$i]." ";
            }
            $n -= $angka[$i] * $count;
            $i++;
        }
        $str = preg_replace("/satu puluh (\w+)/i", "\\1 belas", $str);
        $str = preg_replace("/satu (ribu|ratus|puluh|belas)/i", "se\\1", $str);
    }
    return $str;
}

function defaultNumeric($num)
{
    if ($num != null || $num != '') {
        // if (Config::get('format.currency') == 'id')
        return str_replace(array('.', ','), array('', '.'), $num);
        // else
        // return str_replace(',', '', $num);
    } else
        return $num;
}

function to_mysql_date($date)
{
    return date('Y-m-d', strtotime($date));
}

function to_user_date($date)
{
    return date('d M Y', strtotime($date));
}

// My common functions
function simpleArray($array, $valCol) {
    $data = array();
    foreach ($array as $row) {
        $data[] = $row[$valCol];
    }

    return $data;
}

function listArray($array, $keyCol, $valCol) {
    $data = array();
    foreach ($array as $row) {
        if(isset($row->$keyCol))
        {
            $key = $row->$keyCol;
            $val = $row->$valCol;
        }
        else
        {
            $key = $row[$keyCol];
            $val = $row[$valCol];
        }
        $data[$key] = $val;
    }

    return $data;
}

function encode($string) {
    $key = config('constants.encode_key');
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j = 0;
    $hash = '';
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr($string, $i, 1));
        if ($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
    }
    return $hash;
}

function decode($string) {
    $key = config('constants.encode_key');
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j = 0;
    $hash = '';
    for ($i = 0; $i < $strLen; $i+=2) {
        $ordStr = hexdec(base_convert(strrev(substr($string, $i, 2)), 36, 16));
        if ($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    return $hash;
}

function generateImage($image) {

    $arr =  explode(".",$image);
    $count = count($arr);
    $temp= $image;
    $path = '';

    foreach ($arr as $key =>  $value) {
        if($key < $count-1){
            if($key == 0){
                $path .= $arr[$key];
            }else{
                $path .= '.'.$arr[$key];
            }
        }
    }

    if($count > 1){
        $temp = $path."_130x130.".$arr[$count-1];
    }

    return $temp;

}

function findDataName($file, $extension){
    $name = null;
    if(strpos($file->name, $extension) !== false){
        $name = $file->path.'/'.$file->name;
    }else{
        $name = $file->path.'/'.$file->name.'.'.$file->extension;
    }
    return $name;
}

function normalizeStringForFile($string) {
    $patterns = [
        '/^\s+/',
        '/\s+$/',
        '/[\s\W]+/',
    ];
    $replace = [
        '',
        '',
        '-'
    ];

    return strtolower(preg_replace($patterns, $replace, $string));
}

/**
 * Function for generate log system
 *
 * @param $content string
 * @param $table string
 * @param $table_id int
 * @param $name string
 */
function general_log($content,$table = null,$table_id = null,$name){
    $date = date('Y-m-d');
    $hour = date('H');
    $client_id = 1;
    $user_id = Auth::check() ? Auth::user()->id : NULL;

    try{

        $inserted = [
            'date_report' => $date,
            'hour_report' => $hour,
            'client_id' => $client_id,
            'user_id' => $user_id,
            'method' => Request::method(),
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s'),
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ];

        if($table) $inserted['table'] = $table;
        if($table_id) $inserted['table_id'] = $table_id;
        if($name) $inserted['name'] = $name;

        DB::table('general_logs')->insert($inserted);
    }catch(\Exception $e){
        $log = ['Helpers' => 'helpers', 'function' => 'general_log'];
        logError($e,$log);
    }
}


/**
 * Get a record and keep them in cache
 *
 * @param $model
 * @param $id
 * @param array $fields
 * @return mixed
 */
function cachedFindById($model, $id, $fields = [], $default = null) {
    $cacheKey = $model.':'.$id. (count($fields) ? ':'.join('|',$fields) : '');
    return Cache::remember($cacheKey, 60 * 24, function() use ($model, $id, $fields, $default) {
        if (count($fields)) {
            $query = call_user_func_array([$model, 'whereId'], [$id]);
            $result = call_user_func_array([$query, 'select'], $fields)->first();
        } else {
            $result = call_user_func_array([$model, 'find'], [$id]);
        }
        if (!$result)
            $result = $default;
        return $result;
    });
}


/**
 * Get a bunch of records and keep them in cache
 *
 * @param $model
 * @param $ids
 * @param array $fields
 * @return array
 */
function cachedFindByIds($model, $ids, $fields = [], $default = null) {
    $result = [];
    foreach ($ids as $id) {
        $result[$id] = cachedFindById($model, $id, $fields, $default);
    }
    return $result;
}

function randomPassword(){
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';

    $password = '';
    for($i = 0; $i < 10; $i++){
        $password .= $characters[
        rand(0, strlen($characters) - 1)
        ];
    }

    return $password;
}

/**
 * Helper for save email data to database
 * @param $view
 * @param $data
 * @param $contact_email
 * @param $contact_name
 * @param null $attachment_path
 * @param $subject
 * @param null $schedule_time
 * @return bool
 */
function saveEmail($view, $data, $email_sender = null, $email_sender_name = null, $email_recipient, $email_recipient_name, $subject, $attachment_path = null, $schedule_time = null, $status = 0, $status_log = null){
    try{
        $insertion = [
            'email_sender' => ($email_sender) ? $email_sender : 'support@ralali.com',
            'email_sender_name' => ($email_sender_name) ? $email_recipient_name : 'Ralali.com',
            'email_recipient' => $email_recipient,
            'email_recipient_name' => $email_recipient_name,
            'email_subject' => $subject,
            'email_body' => view($view, $data)->render(),
            'channel' => rand(1,50),
            'email_attach' => ($attachment_path) ? $attachment_path : null,
            'schedule_time' => ($schedule_time) ? $schedule_time : date('Y-m-d'),
            'status' => $status,
            'status_log' => $status_log
        ];

        $query = DB::table('bulk_mail')->insert($insertion);
        return true;
    }catch(Exception $e){
        $log = ['Helpers' => 'helpers', 'function' => 'saveEmail'];
        logError($e, $log );
        return false;
    }
}

/**
 * Helper for save emails data to database
 * @param $view
 * @param $data
 * @param $contacts
 * @param null $attachment_path
 * @param null $schedule_time
 * @return bool
 */
function saveEmails($view, $data, $contacts, $attachment_path = null, $schedule_time = null){
    try{
        $insertion = null;
        foreach($contacts as $contact){
            $insertion[] = [
                'email_sender' => @$contact['email_sender']?$contact['email_sender']:'support@ralali.com',
                'email_sender_name' => @$contact['email_sender_name']?$contact['email_sender_name']:'Ralali.com',
                'email_recipient' => $contact['email'],
                'email_recipient_name' => $contact['name'],
                'email_subject' => $contact['subject'],
                'email_body' => view($view, $data),
                'channel' => rand(1,50),
                'email_attach' => ($attachment_path) ? $attachment_path : null,
                'schedule_time' => ($schedule_time) ? $schedule_time : date('Y-m-d')
            ];
        }

        $query = DB::table('bulk_mail')->insert($insertion);
        return true;
    }catch(Exception $e){
        $log = ['Helpers' => 'helpers', 'function' => 'saveEmails'];
        logError($e, $log );
        return false;
    }
}

function curlSealTrack($url, $fields, $cacheKey = null){
    $user = '';
    $password = '';
    $url = env('SEAL_URL').$url;
    $data = null;

    $post = curl_init();

    curl_setopt($post, CURLOPT_URL, $url);
    curl_setopt($post, CURLOPT_TIMEOUT,5);
    curl_setopt($post, CURLOPT_POST, 1);
    curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($post, CURLOPT_HEADER, 1);
    curl_setopt($post, CURLOPT_USERPWD, "$user:$password");
    curl_setopt($post, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    //curl_setopt($post, CURLOPT_TIMEOUT_MS, 500);

    $response = curl_exec($post);
    $http_code = curl_getinfo($post, CURLINFO_HTTP_CODE);

    // Then, after your curl_exec call:
    $header_size = curl_getinfo($post, CURLINFO_HEADER_SIZE);
    $body = substr($response, $header_size);

    curl_close($post);
    if($http_code == 200) {
        $data = json_decode($body);
    }

    $expiresAt = new \DateTime( date('Y-m-d H:i:s'), new \DateTimeZone('Asia/Jakarta') );
    $expiresAt->modify('+30 day');
    if($cacheKey){
        Cache::put($cacheKey, $data, $expiresAt);
    }

    return $data;
}

/**
 * Helper for save email data to database and send using sendgrid
 * @param $view
 * @param $data
 * @param $contact_email
 * @param $contact_name
 * @param null $attachment_path
 * @param $subject
 * @param null $schedule_time
 * @return bool
 */
function sendgridMail(
    $fromEmail = null,
    $fromName = null,
    $toEmail,
    $toName,
    $replyTo = null,
    $templateId,
    $subs = null,
    $subject = null,
    $attachment = null,
    $scheduleTime = null,
    $status = 1,
    $statusLog = null
) {
    # [INFO] get boolean result from validating toEmail variable
    $validator = Validator::make([$toEmail], ['required|email|max:255']);
    if($validator->fails()){
        return false;
    }

    # [INFO] retrieve substitution variable for html in array form
    # [SUGGESTION] bisa dipersimple dengan (array) json_decode($subs) supaya lebih mudah dimengerti
    $arraySubs = get_object_vars(json_decode($subs));
    $apiKey    = env('SENDGRID_API_KEY');
    $sg        = new \SendGrid($apiKey);

    # [INFO] retrieve sendgrid template from sendgrid server using templateId
    $response = $sg->client->templates()->_($templateId)->get();

    # [INFO] reassign the result of parsing response data to response variable
    $response = json_decode($response->body());

    // versioning template sendgrid
    if (env('APP_ENV') == 'production') {
        # [INFO] get latest version of email template and assign it to $html
        // template production is the active version
        foreach ($response->versions as $email) {
            if ($email->active == 1) {
                $html = $email->html_content;
                break;
            }
        }
    } else {
        // if version more than one
        // template dev is the newest version
        $html = $response->versions[0]->html_content;
    }

    # [INFO] set email subject if it has not been set
    if (!$subject) {
        $subject = $response->versions[0]->subject;
    }

    # [INFO] subtitute variable in email body template and subject template
    foreach ($arraySubs as $k => $v) {
        $subject = str_replace($k, $v, $subject);
        $html = str_replace($k, $v, $html);
    }

    $request_body = json_decode('{
        "personalizations": [{
            "to": [
                {
                    "email": "'.$toEmail.'",
                    "name": "'.$toName.'"
                }
            ],
            "subject": "'.$subject.'",
            "substitutions": '.$subs.'
        }],
        "from": {
            "email": "'.$fromEmail.'",
            "name": "'.$fromName.'"
        },
        "reply_to": {
            "email": "'.$replyTo.'"
        },
        "template_id": "'.$templateId.'"
    }');

    try {
        # [INFO] send email using Mail service with GuzzleHtttp laravel library
        if (env('APP_ENV') == 'production') {
            $response = $sg->client->mail()->send()->post($request_body);
        } else {
            Mail::send([], $arraySubs, function($message) use ($request_body, $html)
            {
                $message->to($request_body->personalizations[0]->to[0]->email,
                    $request_body->personalizations[0]->to[0]->name)
                    ->from($request_body->from->email)
                    ->subject($request_body->personalizations[0]->subject)
                    ->setBody($html, 'text/html');
            });
        }

        $insertion = [
            'email_sender' => ($fromEmail) ? $fromEmail : 'support@ralali.com',
            'email_sender_name' => ($fromName) ? $fromName : 'Ralali.com',
            'email_recipient' => $toEmail,
            'email_recipient_name' => $toName,
            'email_subject' => $subject,
            'email_replyto' => $replyTo,
            'email_body' => $html,
            'channel' => rand(1,50),
            'email_attach' => ($attachment) ? $attachment : null,
            'schedule_time' => ($scheduleTime) ? $scheduleTime : date('Y-m-d'),
            'status' => env('APP_ENV') == 'production' ? 1 : 0,
            'status_log' => $statusLog
        ];

        # [INFO] log email detail to the database
        DB::table('bulk_mail')->insert($insertion);

        return true;

    } catch (\Exception $e) {
        $log = ['Helpers' => 'helpers', 'function' => 'sendgridMail'];
        $dataError['email_recipient'] = $toEmail;
        $dataError['email_recipient_name'] = $toName;
        $dataError['email_subject'] = $subject;
        array_push($log, $dataError);
        logError($e, $log );
        return false;
    }
}

function sendgridMailTemplate($templateId) {
    $template_data = [];
    $apiKey = env('SENDGRID_API_KEY');
    $sg = new \SendGrid($apiKey);

    try {
        $response = $sg->client->templates()->_($templateId)->get();
        $response = json_decode($response->body());

        if (env('APP_ENV') == 'production') {
            foreach ($response->versions as $email) {
                if ($email->active == 1) {
                    $html = $email->html_content;
                    $subject = $email->subject;
                    break;
                }
            }
        } else {
            $html = $response->versions[0]->html_content;
            $subject = $response->versions[0]->subject;
        }
        $template_data = ['subject'=>$subject, 'body'=>$html];
    } catch (Exception $e) {
        $log = ['Helpers' => 'helpers', 'function' => 'sendgridMailTemplate'];
        $dataError['template_id'] = $templateId;
        array_push($log, $dataError);
        logError($e, $log );
    }
    return $template_data;
}

function checkIsOfficial(){
    /* checking store is official member */
    $isOfficial = false;
    $check = OfficialMember::where("vendor_id",Auth::user()->id)->count();
    
    if($check > 0){
        $isOfficial = true;
    }

    return $isOfficial;
}

function checkPriceLocation($itemId){
    $check = PriceLocation::where("item_id",$itemId)->count();
    return ($check > 0) ? true:false;
}

/**
 * [doCache tools simplify the use of Cache]
 * @param  string  $method  ['get','put','add','forget']
 * @param  string  $key     [key of cache]
 * @param  array   $value   [value]
 * @param  integer $minutes [expiration time]
 * @return [mixed]           [description]
 */
function doCache($method='get',$key='', $value=array(), $minutes=100)
{
    try {
        $value = json_encode($value);
        $array = array('get','put','add','forget');

        if( $method=='get' ){
            $data = Cache::$method($key);
            if($data){
                return json_decode($data);
            }
        } elseif( $method=='add' ){

            Cache::$method($key, $value, $minutes);
            return 1;

        } elseif( $method=='put' ){
            Cache::$method($key, $value, $minutes);
            return 1;
        } else {
            Cache::$method($key);
            return 1;
        }
        return false;
    } catch (Exception $e) {
        //Do something when redis fails.
        $log = ['Helper' => 'helpers', 'function' => 'doCache'];
        $data = array('method'=> $method, 'key'=>$key,'value'=>$value);
        array_push($log, $data);
        logError($e, $log );
        return false;
    }
}

function haveWallet() {
    try {
        $client = new Client();
        $res = $client->get(env('CLOSE_WALLET').'api/v1/wallet/balance',[
            'headers'        => ['x-access-token' => getAccessToken()],
        ]);
        $response_body = json_decode($res->getBody()->getContents());
        $response_code = $res->getStatusCode();
        if($response_code == 200){
            return $response_body;
        }
        return false;
    } catch (RequestException $e) {
        return false;
    } catch (ClientException $e) {
        return false;
    }
}

function features(){
    try {
        $features = null;
        /* check user asigned features*/
        if(Auth::user()){
            $assigned_feature = DB::table('feature_flag_users')
                ->leftJoin('feature_flags','feature_flags.id','=','feature_flag_users.feature_flag_id')
                ->where('feature_flag_users.user_id',Auth::user()->id)
                ->where('feature_flags.deleted_at',null)
                ->where('feature_flag_users.deleted_at',null)
                ->where('feature_flags.status',1)
                ->select('feature_flags.id','feature_flags.feature_code','feature_flags.feature_name')
                ->groupBy('feature_flags.id')
                ->get();
            if($assigned_feature){
                foreach ($assigned_feature as $value) {
                    $features[$value->feature_code] = $value;
                }
            }
        }
        /* collecting all non limited active features  */
        $active_features = DB::table('feature_flags')
            ->leftJoin(DB::raw('(SELECT * FROM rl_feature_flag_users WHERE deleted_at is null) as rl_feature_flag_users'), function($join){
                $join->on('feature_flags.id','=','feature_flag_users.feature_flag_id');
            })
            ->where('feature_flags.deleted_at',null)
            ->where('feature_flags.status',1)
            ->where('feature_flag_users.id',null)
            ->select('feature_flags.id','feature_flags.feature_code','feature_flags.feature_name')
            ->groupBy('feature_flags.id')
            ->get();
        if($active_features){
            foreach ($active_features as $value) {
                $features[$value->feature_code] = $value;
            }
        }
        return $features;
    } catch(Exception $e){
        //Do something when query fails.
        $log = ['Helper' => 'helpers', 'function' => 'features'];
        logError($e, $log );
    }

}

// Return visitor IP
function getIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

function currency($v, $currency = 'Rp') {
    $formatted =  number_format($v,0, ',' , '.');
    return sprintf('%s %s', $currency, $formatted);
}

function order_serial_to_original($order_serial_alias) {
    $order_serial = str_replace('---', '/', $order_serial_alias);

    return $order_serial;
}

function order_serial_to_alias($order_serial_original) {
    $order_serial = str_replace('/', '---', $order_serial_original);

    return $order_serial;
}

function imageMediaflexCdn($uri=null, $width=null)
{
    $base_url= str_replace('1', rand(1,4), env('URL_IMAGE_MEDIAFLEX')) ? : asset('/');

    if(!empty($uri)){
        $base_url.=$width."/".$uri;
    }
    else {
        $base_url .= "/1000/assets/img/no-image.png";
    }

    return $base_url;
}

function dateIndFormat($date)
{
  $patterns      = ['/January/','/February/','/March/','/May/','/June/','/July/','/August/','/October/','/December/'];
  $replacements  = ['Januari','Februari','Maret','Mei','Juni','Juli','Agustus','Oktober','Desember'];
  $formatDateInd = preg_replace($patterns, $replacements, $date);
  return $formatDateInd;
}

function dataMapper($fillable, $request) 
{ 
    $data_intersect = array_intersect_key($fillable, $request); 

    $data = [];
    array_map(function($k, $v) use($request, &$data) {
        return $data[$v] = $request[$k];
    }, array_keys($data_intersect), $data_intersect);

    return $data;
}

function embedVideo($video_url)
{
    try{
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $request_api = $client->get('https://www.youtube.com/oembed?url='.$video_url.'&format=json');
        $responses = json_decode($request_api->getBody()->getContents(), true);
        $response = [
            'status' => 'success',
            'result' => $responses
        ];
    } catch(BadResponseException $e) {
        $response = [
            'status' => 'failed',
            'message'=> trans('validation-onboarding-store-information.video_not_found')
        ];
    }

    return $response;
}

function JWT_setResponse($status = null, $message = null, $result = null){
    return [
        'status' => ($status) ? $status : 'failed',
        'message' => ($message) ? $message : 'Ada yang tidak beres',
        'result' => $result
    ];
}

function JWT_extract($token) {
    $cache_key = sprintf('client_pub:%s', $token);
    if(Cache::has($cache_key) == false or Cache::get($cache_key) === null) {
        $client = new Client();

        $url = sprintf('%s%s', env('API_OAUTH_HOST'), '/v2/client');
        $request = $client->createRequest('GET', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ]
        ]);

        $res = $client->send($request);
        $data_pub_key = json_decode($res->getBody(), true);

        Cache::put($cache_key, $data_pub_key, 720);
    }

    $algorithms = ['RS256'];
    JWT::$leeway = 60;
    $pub_key = Cache::get($cache_key)['key_pairs']['public'];
    $data = JWT::decode($token, $pub_key, $algorithms);

    return $data;
}

function JWT_getAccess($link, $query = []) {
    try {
        $client = new Client();

        $url = sprintf('%s/api/v1/%s', env('URL_API_CORE'), $link);

        $params = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$_COOKIE['rll_acct_s']
            ],
            'query' => $query
        ];

        $request = new \GuzzleHttp\Psr7\Request('GET', $url, params);

        $res = $client->send($request);
        $data_pub = json_decode($res->getBody(), true);

        return JWT_setResponse('success', 'success', $data_pub['result']);
    } catch (Exception $e) {
        $log = ['Helper' => 'oauth_jwt_helpers', 'function' => 'JWT_getAccess'];
        logError($e, $log);
    }
}

function set_cookie($name, $value, $expire = 0, $path = '/', $domain = ''){
    // https is enabled
    $secure = !empty($_SERVER['HTTPS']) ? true : null;
    $secureMode = (env('APP_ENV') == 'local')? $secure : true;
    $expire = ($expire != 0) ? (time() + (3 * 365 * 24 * 60 * 60)) : (time() - (3 * 365 * 24 * 60 * 60));
    @setcookie($name, $value, $expire, $path, $domain, $secureMode);
}

function getDomain(){
    $output = '';
    $pieces = parse_url(env('WEB_URL'));
    $domain = isset($pieces['host']) ? $pieces['host'] : $output;
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        $output = $regs['domain'];
    }
    return $output;
}

function JWT_setTokenToCookie($token, $refresh_token) {
    # [INFO] unset the past
    set_cookie('rll_acct_s', null,  time() - 3600, '/');
    set_cookie('rll_reft_s', null,  time() - 3600, '/');

    # [INFO] create cookie for access_token and refresh_token
    set_cookie('rll_acct_s', $token, (86400 * 30), '/', '.'.getDomain());
    set_cookie('rll_reft_s', $refresh_token, (86400 * 30), '/', '.'.getDomain());
}

function JWT_refreshToken($refresh_token) {
    $client = new Client();

    $url = sprintf('%s%s', env('API_OAUTH_HOST'), '/v2/token');
    $request = $client->createRequest('POST', $url, [
        'headers' => [
            'Content-Type' => 'application/json'
        ],
        'body' => json_encode([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token
        ])
    ]);

    $res = $client->send($request);
    $data_pub = json_decode($res->getBody(), true);

    $token = $data_pub['access_token'];
    $refresh_token = $data_pub['refresh_token'];

    if(strtolower($data_pub['token_type']) == 'bearer') {
        $data = JWT_extract($token);

        $user = userModel::find($data->user_id);
        if($user->id && $user->type == 'V' && $user->vendor_status != 'R' && $user->vendor_status != 'D'){
            JWT_setTokenToCookie($token, $refresh_token);

            return JWT_setResponse('success', 'success', $data);
        }else{
            $message = 'Invalid Username and Password';
            return JWT_setResponse(null,$message);
        }

    }
}

function JWT_accessToken($email, $password) {
    try {
        $client = new Client();

        $url = sprintf('%s%s', env('API_OAUTH_HOST'), '/v2/token');
        $request = $client->createRequest('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$_COOKIE['rll_grnt_s']
            ],
            'body' => json_encode([
                'grant_type' => 'password',
                'email' => $email,
                'password' => $password
            ])
        ]);

        $res = $client->send($request);
        $data_pub = json_decode($res->getBody(), true);

        $token = $data_pub['access_token'];
        $refresh_token = $data_pub['refresh_token'];

        if(strtolower($data_pub['token_type']) == 'bearer') {
            $data = JWT_extract($token);

            $user = userModel::find($data->user_id);
            if($user->id && $user->type == 'V' && $user->vendor_status != 'R' && $user->vendor_status != 'D'){
                JWT_setTokenToCookie($token, $refresh_token);
                JWT_refreshToken($refresh_token);

                return JWT_setResponse('success', 'success', $data);
            }else{
                $message = 'Invalid Username and Password';
                return JWT_setResponse(null,$message);
            }
        }
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        return ['status' => 'failed', 'message' => null, 'status_code' => $e->getCode()];
    }
}

function accessTokenWebsite($email, $password, $byVerify = 0){
    $message      = null;
    $url          = Config::get('constants.ACCESS_URL');
    $api_user     = Config::get('constants.API_USERNAME');
    $api_password = Config::get('constants.API_PASSWORD');

    $fields       = "email=$email&";
    $fields       .= "passwd=$password&";
    $fields       .= "verify=$byVerify&";
    rtrim($fields, '&');
    $post = curl_init();
    curl_setopt($post, CURLOPT_URL, $url);
    curl_setopt($post, CURLOPT_TIMEOUT,10);
    curl_setopt($post, CURLOPT_POST, 1);
    curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($post, CURLOPT_HEADER, 1);
    curl_setopt($post, CURLOPT_USERPWD, "$api_user:$api_password");
    curl_setopt($post, CURLOPT_HTTPHEADER, array(
        'X-Grant-Code: '.getHeaderGrantWebsite() // need grant_code
    ));
    $response = curl_exec($post);
    // Then, after your curl_exec call:
    $header_size = curl_getinfo($post, CURLINFO_HEADER_SIZE);
    $header      = substr($response, 0, $header_size);
    $body        = substr($response, $header_size);
    $http_code   = curl_getinfo($post, CURLINFO_HTTP_CODE);
    $out['header']    = $header;
    $out['http_code'] = $http_code;
    $out['body']      = $body;
    curl_close($post);
    if($out['http_code'] == 200){
        $headers = null;
        // collecting headers on response
        foreach (explode("\r\n",$header) as $hdr){
            $key = explode(": ", $hdr);
            if(count($key) > 1) $headers[$key[0]] = $key[1];
        }
        $response_request = json_decode($out['body']);
        if($response_request->success->code == 14){
            #setup access token cookie website
            $encrypt_access = encrypt($headers['x-access-token'], Config::get('constants.ENCRYPTION_KEY'));
            $encrypt_refresh = encrypt($headers['x-refresh-token'], Config::get('constants.ENCRYPTION_KEY'));
            //unset the past
            set_cookie('rll_acct', null, 0, '/', '.'.getDomain() );
            set_cookie('rll_reft', null, 0, '/', '.'.getDomain() );
            // recreate them
            set_cookie('rll_acct', $encrypt_access, 1, '/', '.'.getDomain() );
            set_cookie('rll_reft', $encrypt_refresh, 1, '/', '.'.getDomain() );
            $return['website_encrypt_access'] = encode($headers['x-access-token']);
            $return['website_encrypt_refresh'] = encode($headers['x-refresh-token']);
            return $return;
        }
    }else{
        $return['website_encrypt_access']  = 0;
        $return['website_encrypt_refresh'] = 0;
        return $return;
    }
}

function smartContractSerialToOriginal($smartContractSerialAlias) {
    return str_replace('---', '/', $smartContractSerialAlias);
}

function smartContractSerialToAlias($smartContractSerial) {
    return str_replace('/', '---', $smartContractSerial);
}

function getOrderDateByOrderSerial($orderSerial) {
    $orderDate = substr($orderSerial, strpos($orderSerial, 'ORD/') + 4);
    return str_replace('/', '-', $orderDate);
}