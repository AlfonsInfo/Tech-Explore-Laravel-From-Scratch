<?php

namespace Tests\Feature;

use App\Rules\RegistrationRule;
use App\Rules\UpperCase;
use Closure;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator as ValidationValidator;
use Illuminate\Validation\Rules\Password;

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

    public function testValidatorAdditionalValidation()
    {
        $data = [
            "username" => "bambang@gmail.com",
            "password" => "bambang@gmail.com",
        ];


        $rules = [
            "username" => "required|email|max:100",
            "password" => ["required","min:6","max:20"] 
        ];

        $validator = Validator::make($data,$rules);
        $validator->after(function(\Illuminate\Validation\Validator $validator){
            $data = $validator->getData();
            if($data['username'] == $data['password']){
                $validator->errors()->add("password", "Password tidak boleh sama dengan username");
            }
        });
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

    public function testValidationCustom()
    {
        $data = [
            "username" => "bambang@gmail.com",
            "password" => "password",
        ];


        $rules = [
            "username" => ["required", new UpperCase()],
            "password" => ["required","min:6","max:20",] 
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

    public function testMultipleColumnValidation()
    {
        $data = [
            "username" => "bambang@gmail.com",
            "password" => "bambang@gmail.com",
        ];


        $rules = [
            "username" => ["required", new UpperCase()],
            "password" => ["required","min:6","max:20",new RegistrationRule()] 
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


    public function testCustomFunctionRule()
    {
        $data = [
            "username" => "bambang@gmail.com",
            "password" => "bambangg",
        ];


        $rules = [
            "username" => ["required", new UpperCase()],
            "password" => ["required","min:6","max:20",function($attribute, $value, Closure $fail){
                if($value = "bambangg"){
                    $fail("Ga boleh bosqu");
                }
            }] 
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

    public function testValidatorRuleClasses()
    {
        $data = [
            "username" => "bambang@gmail.com",
            "password" => "bambangg",
        ];


        $rules = [
            "username" => ["required", new In(["Alfons", "Setiawan", "Jacub"])],
            "password" => ["required", Password::min(6)->letters()->numbers()->symbols()] 
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

    public function testNestedArrayValidation(){
        $data = ["address" => ["street" => ""]];

        $rules = ["address.street" => "required"];

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
}
