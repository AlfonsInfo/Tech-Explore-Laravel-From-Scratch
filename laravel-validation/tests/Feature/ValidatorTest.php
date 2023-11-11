<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ValidatorTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testValidator(): void
    {
        $data = [
            "username" => "alfons",
            "password" => "password"
        ];


        $rules = [
            "username" => "required",
            "password" => "required" 
        ];

        $validator = Validator::make($data, $rules);

        self::assertNotNull($validator);
        self::assertTrue($validator->passes());
        self::assertFalse($validator->fails());
    }


    public function testValidatorInvalid(): void
    {
        $data = [
            "username" => "",
            "password" => ""
        ];


        $rules = [
            "username" => "required",
            "password" => "required" 
        ];

        $validator = Validator::make($data, $rules);

        self::assertNotNull($validator);
        self::assertFalse($validator->passes());
        self::assertTrue($validator->fails());
    }

    public function testMessageBag(){
        $data = [
            "username" => "",
            "password" => ""
        ];


        $rules = [
            "username" => "required",
            "password" => "required" 
        ];

        $validator = Validator::make($data, $rules);
        $message = $validator->getMessageBag();
        $message->get("username");
        $message->get("password");
        Log::info($message->toJson(JSON_PRETTY_PRINT));
        self::assertTrue($validator->fails());

    }


    public function testValidationException()
    {
        $data = [
            "username" => "",
            "password" => ""
        ];


        $rules = [
            "username" => "required",
            "password" => "required" 
        ];
        $validator = Validator::make($data, $rules);
        try{
            $validator->validate();
            self::fail("Validation Exception not thrown");
        }catch(ValidationException $exception)
        {
            self::assertNotNull($exception->validator);
            $message = $exception->validator->errors();
            Log::info($message->toJson(JSON_PRETTY_PRINT));
        }
    }

    public function testValidationRules(){
        $data = [
            "username" => "aaa@",
            "password" => "pass"
        ];


        $rules = [
            "username" => "required|email|max:100",
            "password" => ["required","min:6","max:20"] 
        ];

        $validator = Validator::make($data,$rules);
        self::assertNotNull($validator);
        $message = $validator->getMessageBag();

        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }


    public function testValidatorValidData()
    {
        $data = [
            "username" => "itsmealfons@gmail.com",
            "password" => "password",
            "admin" => true
        ];


        $rules = [
            "username" => "required|email|max:100",
            "password" => ["required","min:6","max:20"] 
        ];

        $validator = Validator::make($data,$rules);
        self::assertNotNull($validator);
        try{
            $valid = $validator->validate(); //* secure not validate data.
            Log::info(json_encode($valid,JSON_PRETTY_PRINT));
        }catch(ValidationException $exception)
        {
            self::assertNotNull($exception->validator);
            $message = $exception->validator->errors();
            Log::info($message->toJson(JSON_PRETTY_PRINT));

        }

    }

    public function testValidatorInlineMessage()
    {
        $data = [
            "username" => "bambang",
            "password" => "password",
            "admin" => true
        ];


        $rules = [
            "username" => "required|email|max:100",
            "password" => ["required","min:6","max:20"] 
        ];

        $messages = [
            "required" => "Inline Flag : :attribute harus diisi",
            "email" => "Inline Flag : :attribute harus berupa email",
            "min" => "Inline Flag : :attribute miniaml harus :min karakter",
        ];

        $validator = Validator::make($data,$rules, $messages);
        self::assertNotNull($validator);
        try{
            $valid = $validator->validate(); //* secure not validate data.
            Log::info(json_encode($valid,JSON_PRETTY_PRINT));
        }catch(ValidationException $exception)
        {
            self::assertNotNull($exception->validator);
            $message = $exception->validator->errors();
            Log::info($message->toJson(JSON_PRETTY_PRINT));

        }

    }
}
