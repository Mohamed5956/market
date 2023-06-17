<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index()
    {
        $Chatbot = Chatbot::all();
        return response()->json($Chatbot);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $Chatbot = Chatbot::create($validatedData);
        return response()->json($Chatbot, 201);
    }

    public function show($id)
    {
        $Chatbot = Chatbot::findOrFail($id);
        return response()->json($Chatbot);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $Chatbot = Chatbot::findOrFail($id);
        $Chatbot->update($validatedData);
        return response()->json($Chatbot, 200);
    }

    public function destroy($id)
    {
        $Chatbot = Chatbot::findOrFail($id);
        $Chatbot->delete();
        return response()->json(null, 204);
    }
}

//
//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//
//class ChatbotController extends Controller
//{
//    public function handle(Request $request)
//    {
//        $message = $request->input('message');
//        if($message=='Hello') {
//            return response()->json([
//                'message' => "Hello! How can I assist you today?",
//            ]);
//        }elseif ($message=="مرحبا") {
//            return response()->json([
//                'message' => "مرحبًا! كيف يمكنني مساعدتك اليوم؟",
//            ]);
//        }
//        elseif ($message=="مرحبا") {
//            return response()->json([
//                'message' => "مرحبًا! كيف يمكنني مساعدتك اليوم؟",
//            ]);
//        }
//        elseif ($message=="انا عندي مشكله"){
//            return response()->json([
//                'message' => "أنا هنا للمساعدة! يمكنك شرح المشكلة التي تواجهها بالتفصيل؟ سأبذل قصارى جهدي لمساعدتك في حلها.",
//            ]);
//        }
//        else{
//            return response()->json([
//                'message' => "",
//            ]);
//        }
//    }
//}
