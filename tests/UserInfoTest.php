<?php
namespace App\Tests;

use App\Services\CrudService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UserInfoTest extends WebTestCase
{

    public function testCreateUser()
    {
        $kernel = static::createKernel();
        static::bootKernel();
        $application = new Application($kernel);

        $service=self::$container->get(CrudService::class);

        $data= array(
            "username" => "bob",
            "password" =>"dsfdsff"
        );

        $result=$service->add("config","_users",$data);
    }
    public function testUserInfo()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/token',
            array(
            "client_id"=>"c0a71bf0379c66c46da3ed41a4f4aab2",
            "client_secret"=>"e8f9855c30bb9915e61bc093656e75c7e8e3bc3b221eca2be796790af96e6f347b738a28c84ffb9db61617e812b21c51c8b29d9d8d1c92d2df9b386a2404c394",
            "grant_type"=>"password",
            "username"=>"fdg",
            "password"=>"dfgf"),
            [],
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            null
        );

        $result= json_decode( $client->getResponse()->getContent(), true);

        $token= $result["access_token"];
        echo print_r( $client->getResponse()->getContent(),true);

        echo print_r("\n\n|".$token."|",true);

       
      $client->request(
            'GET',
            'http://localhost/userinfo',
            [],
            [],
            array('Authorization' => "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJjMGE3MWJmMDM3OWM2NmM0NmRhM2VkNDFhNGY0YWFiMiIsImp0aSI6Ijc4OGYyMWRjMWNhZGJjZjNjMGY2NmE1MDFiOWEyNjc3ZjMzNjc1ZDQyZmY0OWQ5MTZkYmRlNTA4ZjQ1MDc4NzM5MTNiMWI1OTY4ZTVmNjIwIiwiaWF0IjoiMTYxNTA1NDg4My40ODQyNTIiLCJuYmYiOiIxNjE1MDU0ODgzLjQ4NDI1NCIsImV4cCI6IjE2MTUwNTg0ODMuNDc5MzQ0Iiwic3ViIjoiIiwic2NvcGVzIjpbXX0.et6QKws6Q699y60VJZt2niqi6Lw8wmT0PTq7qroDmfqJpLRxAjG9uSsmsg7moMMDAwpLT_MfHvKbww7tjOnNZXEi-9ktmSVXygTwegV79TjtkTSwtRngv7fMazD0lVw_rllj0gPat18NaFfpuTOAZLWY-csoiOUavu8oD8IY07IShaTUvfK8BfHOrmmHMhKxfWmFFsXxwQ6Z3vOxEwQhloOor-ki3waOs9p5jglLX7M2tOzL9pnPUMqhupDBgKjHSp3et7cqyT9aBjWl-FVsUi0-X28XVFe2VMr5TYRzOH2-SMBAv8kry1BGpBcLcYqWBOTtYsRcLgWJcv8-JUelXg",
            'Accept' =>'*/*',
            'User-Agent' =>'TEST'
            ),
            null
        );

        $content=$client->getResponse()->getContent();
        echo "\n". print_r( $content,true);
        $result= json_decode(  $content, true);

        echo "\n". print_r($result,true);

    }
}