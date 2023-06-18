<?php


namespace App\Http\Controllers;

use App\Models\Chatbot;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected $languages = ['Arabic' , 'English']; // Available language options
    public function store(Request $request)
    {
        $Chatbot = Chatbot::create($request->all());
        return response()->json($Chatbot, 201);
    }
    public function getLanguageOptions()
    {
        return response()->json(['languages' => $this->languages]);
    }
    public function processLanguageSelection(Request $request)
    {
        $selectedLanguage = $request->language;
        if($request->language == $this->languages[0]) {
            $questions = Chatbot::select('chatbots.ar_question','chatbots.id')->get();
        }elseif ($request->language == $this->languages[1]){
            $questions = Chatbot::select('chatbots.en_question','chatbots.id')->get();
        }
        return response()->json(['questions' => $questions]);
    }
    public function processAnswer(Request $request)
    {
        $selectedLanguage = $request->language;
        $questionId = $request->questionId;
        if($request->language == $this->languages[0]) {
            $answer = Chatbot::select('ar_answer')->where('id', '=', $questionId)->get();
        }elseif ($request->language == $this->languages[1]){
            $answer = Chatbot::select('en_answer')->where('id', '=', $questionId)->get();
        }
        return response()->json(['answer' => $answer]);
    }
    public function closeChat(Request $request){
        if($request->language == $this->languages[0]) {
            $message = "على الرحب والسعة! إذا كان لديك أي أسئلة أخرى أو إذا كنت بحاجة إلى مساعدة إضافية، فلا تتردد في طرحها. أنا هنالمساعدتك!";
        }elseif ($request->language == $this->languages[1]){
            $message = "You're welcome! If you have any more questions or need further assistance, feel free to ask. I'm here to help!";
        }
        return response()->json(['message' => $message]);
    }

}

//
//namespace App\Http\Controllers;
//
//use App\Models\Chatbot;
//use Illuminate\Http\Request;
//
//class ChatbotController extends Controller
//{
//    protected language;
//    public function index()
//    {
//        $Chatbot = Chatbot::all();
//        return response()->json([ "chatbot" => $Chatbot ]);
//    }
//
//    public function store(Request $request)
//    {
//        $validatedData = $request->validate([
//            'question' => 'required',
//            'answer' => 'required',
//        ]);
//
//        $Chatbot = Chatbot::create($validatedData);
//        return response()->json($Chatbot, 201);
//    }
//
//
//
//
//    public function show(Request $request)
//    {
//        $ar_language=1;
//        $en_language=2;
//        if($request->language == $ar_language){
//            $chatbot="ar";
//        }else if($request->language == $en_language){
//            $chatbot="en";
//
//        }
//        return response()->json(["Chatbot"=>$chatbot]);
//    }
//
//
//
//
//
//
//
//
//
//    public function update(Request $request, $id)
//    {
//        $validatedData = $request->validate([
//            'question' => 'required',
//            'answer' => 'required',
//        ]);
//
//        $Chatbot = Chatbot::findOrFail($id);
//        $Chatbot->update($validatedData);
//        return response()->json($Chatbot, 200);
//    }
//
//    public function destroy($id)
//    {
//        $Chatbot = Chatbot::findOrFail($id);
//        $Chatbot->delete();
//        return response()->json(null, 204);
//    }
//}
//
////
////namespace App\Http\Controllers;
////
////use Illuminate\Http\Request;
////
////class ChatbotController extends Controller
////{
////    public function handle(Request $request)
////    {
////        $message = $request->input('message');
////        if($message=='Hello') {
////            return response()->json([
////                'message' => "Hello! How can I assist you today?",
////            ]);
////        }elseif ($message=="مرحبا") {
////            return response()->json([
////                'message' => "مرحبًا! كيف يمكنني مساعدتك اليوم؟",
////            ]);
////        }
////        elseif ($message=="مرحبا") {
////            return response()->json([
////                'message' => "مرحبًا! كيف يمكنني مساعدتك اليوم؟",
////            ]);
////        }
////        elseif ($message=="انا عندي مشكله"){
////            return response()->json([
////                'message' => "أنا هنا للمساعدة! يمكنك شرح المشكلة التي تواجهها بالتفصيل؟ سأبذل قصارى جهدي لمساعدتك في حلها.",
////            ]);
////        }
////        else{
////            return response()->json([
////                'message' => "",
////            ]);
////        }
////    }
////}
