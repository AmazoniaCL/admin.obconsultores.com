<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactanosMailable;
use Illuminate\Support\Facades\Mail;


class ContactanosController extends Controller
{
    public function enviar(Request $request)
    {

        $response = ["status" => 0, "msg" => ""];

        // $request->validate([
        //     'name'=>'required',
        //     'phone_number'=>'required|email',
        //     'email'=>'required',
        //     'message'=>'required'
        // ]);

        $correo = new ContactanosMailable($request->all());
        if (!Mail::to('gerencia@obconsultores.com')->send($correo)) {
            $response = ["status" => 200, "msg" => "Mensaje enviado"];
        }

        return response()->json($response);
    }
}
