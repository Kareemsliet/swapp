<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        "prefix" => "v1",
        "middleware" => ["setLocale:ar"]
    ],
    function () {
        Route::group(
            [
                "prefix" => "auth",
                "controller" => AuthController::class
            ],
            function () {

                Route::post("login", "login");
                Route::post("register", "register");
                Route::post("check", "check");
                Route::post("logout", "logout")->middleware("auth:api");

                Route::group(
                    [
                        "prefix" => "password",
                        "controller" => ResetPasswordController::class,
                    ],
                    function () {
                        Route::post("forget-email", "forgetPasswordByEmail");
                        Route::post("forget-phone", "forgetPasswordByPhone");
                        Route::post("reset", "resetPassword");
                    }
                );

                Route::group(
                    [
                        "middleware" => ["auth:api"],
                        "prefix" => "verification",
                        "controller" => VerificationController::class,
                    ],
                    function () {
                        Route::post("/{type}/resend-code", "resendVerification")->whereIn("type", ["email", "phone"]);
                        Route::post("/{type}/verify", "verify")->whereIn("type", ["email", "phone"]);
                    }
                );

            }
        );

        Route::group(
            [
                "middleware" => ["auth:api", "isVerified"],
            ],
            function () {
                Route::group(
                    [
                        "prefix" => "profile",
                        "controller" => ProfileController::class,
                    ],
                    function () {
                        Route::get("/", "show");
                        Route::match(["put", "patch"], "/", "update");
                        Route::post("/change-password","changePassword");
                    }
                );
            }
        );

    }
);