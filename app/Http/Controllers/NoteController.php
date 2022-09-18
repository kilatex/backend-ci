<?php

namespace App\Http\Controllers;

use App\Models\Labelnote;
use Illuminate\Http\Request;
use  App\Models\Note;
use  App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['cosas','multiplicacion']]);
    }

    public function index(){
  
        $notes = Note::with('labels')->where('user_id',auth()->user()->id)->get();

        return response()->json($notes);
    }

    public function trash(){
        
        $notes = Note::where('user_id',auth()->user()->id)->onlyTrashed()->get(); 

        return response()->json($notes);
    }


    public function update( Request $request, Note $note ){

        $validator = Validator::make($request->all(), [
            'title' => 'max:191',
            'string' => 'string',
            'html' => 'string',
        ]);


        if($validator->fails()){
            $data = array(
                'status' => 'error',
                'code' => '400',
                'message' => 'Error to update note',
                'error' => $validator->errors()
            );

        }else{

            if ($request->input('title')) {
                $note->title = $request->input('title');
            }
            if ($request->input('string')) {
                $note->string = $request->string;
            }
            if ($request->input('html')) {
                $note->html = $request->input('html');
            }
            $note->color = $request->input('color');


            $res = $note->save();
    
            if ($res) {
                $data = array(
                    'status' => 'success',
                    'code' => '200',
                    'message' => 'NOTA ACTUALIZADA',
                    'notes' => $note
                );
            }
        }

        return response()->json($data);

    }

    public function create(Request $request){
       $validator =  Validator::make($request->all(), [
            'title' => 'string|required',
            'html' => 'string',
            'string' => 'string',
            'color' => 'required'
        ]);
        

        if($validator->fails()){
            $data = array(
                'status' => 'error',
                'code' => '400',
                'message' => 'Error to create note',
                'error' => $validator->errors()
            );
            return response()->json($data, 500);

        }

        $note = New Note();
        $note->title = $request->title;
        $note->html = $request->html;
        $note->string = $request->string;
        $note->color = $request->color;
        $note->user_id = auth()->user()->id;

        $res = $note->save();
            
        if ($res) {
            $data = array(
                'status' => 'success',
                'code' => '200',
                'message' => 'Persona no registrada',
                'note' => $note
            );    
            return response()->json($data);
        }   
        return response()->json(['message' => 'Error to update post'], 500);

    }

    public function copy(int $id){
        $noteCopy = Note::where('id',$id)->first();
       

        $note = New Note();
        $note->title = $noteCopy->title." copy";
        $note->html = $noteCopy->html ;
        $note->string = $noteCopy->string;
        $note->color = $noteCopy->color;
        $note->user_id = auth()->user()->id;


        $res = $note->save();
            

        if ($res) {
            $data = array(
                'status' => 'success',
                'code' => '200',
                'message' => 'Nota copiada',
                'note' => $note
            );    
            return response()->json($data);
        }   
        return response()->json(['message' => 'Error to copy post'], 500);

    }

    public function destroy(Note $noteD){
        $res = $noteD->delete();
        return response()->json($res);
    }


    public function get(Note $note){
        return response()->json($note);
    }

    public function delete(){
        $notes = Note::where('user_id',auth()->user()->id)->onlyTrashed()->forceDelete(); 
        $data = array(
            'status' => 'success',
            'code' => '200',
            'message' => 'Notas borradas',
            'notas' => $notes 
        );
        
        return response()->json($data);
    }

    public function forceDelete(int $id){
        $note = Note::where('id',$id)->onlyTrashed()->first();
        if($note){
            $note->forceDelete(); 
            $data = array(
                'status' => 'success',
                'code' => '200',
                'message' => 'Nota borrada',
                'nota' => $note 
            );
        }
     
        
        return response()->json($data);
    }

    public function restore(int $id){
        
        $note = Note::where('id',$id)->onlyTrashed()->first()->restore();

        if($note){
            $data = array(
                'status' => 'success',
                'code' => '200',
                'message' => 'Nota borrada',
                'nota' => $note 
            );
        }
     
        
        return response()->json($data);
    }


    public function search(string $content){
        $texto = trim($content);
        $notes = Note::Where('string','LIKE', '%'.$texto.'%')
        ->orWhere('title','LIKE', '%'.$texto.'%')
        ->where([ ['user_id', '=', auth()->user()->id]])->get();

        return response()->json($notes);

    }
    
}
