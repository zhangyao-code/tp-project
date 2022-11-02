<?php

// This file is auto-generated, don't edit it. Thanks.
namespace app\api\SMS;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;

class SMS {

    /**
     * 使用AK&SK初始化账号Client
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @return Dysmsapi Client
     */
    public static function createClient($accessKeyId, $accessKeySecret){
        $config = new Config([
            // 您的 AccessKey ID
            "accessKeyId" => $accessKeyId,
            // 您的 AccessKey Secret
            "accessKeySecret" => $accessKeySecret
        ]);
        // 访问的域名
        $config->endpoint = "dysmsapi.aliyuncs.com";
        return new Dysmsapi($config);
    }

    /**
     * @param string[] $args
     * @return void
     */
    public static function main($user, $service_name, $price){
        $client = self::createClient("LTAI5tQp7CFhwSmMEmnKUgyV", "F68iKHnoHJHGqWxYoxhMCB61izvopF");
        $sendSmsRequest = new SendSmsRequest([
            "signName" => "安禾陪诊",
            "templateCode" => "SMS_255260914",
            "phoneNumbers" => "13681528603",
            "templateParam" => json_encode([
                'user'=> $user,
                'service_name' => $service_name,
                'price' =>$price
            ])
        ]);
        file_put_contents(__DIR__.'/sms.txt', json_encode([
                'user'=> $user,
                'service_name' => $service_name,
                'price' =>$price
            ]).'-----------',FILE_APPEND);

        $runtime = new RuntimeOptions([]);
        try {
            // 复制代码运行请自行打印 API 的返回值
           return $client->sendSmsWithOptions($sendSmsRequest, $runtime);
        }
        catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            // 如有需要，请打印 error
            Utils::assertAsString($error->message);
        }
    }
}

