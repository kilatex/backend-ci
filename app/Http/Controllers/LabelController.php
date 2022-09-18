<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Labelnote;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['','']]);
    }

    public function index(){
        $id = auth()->user()->id;
        $notes = Label::where('user_id',$id)->get(); 
        return response()->json($notes);
    }

    public function create(Request $request){

        $validator =  Validator::make($request->all(), [
            'content' => 'string|required',
        ]);

        $id = auth()->user()->id;
        $exist = Label::where([
            ['user_id', '=', $id],
            ['content', 'LIKE', $request->content]
        ])->first();

      if($exist){
            $data = array(
                'status' => 'error',
                'code' => '400',
                'message' => 'Error to create label',
                'error' => 'label already exists'
            );
            return response()->json($data);
      }

        if($validator->fails()){
            $data = array(
                'status' => 'error',
                'code' => '400',
                'message' => 'Error to create label',
                'error' => $validator->errors()
            );
            return response()->json($data, 500);

        }

        $label = new Label();
        $label->content = $request->content;
        $label->user_id = auth()->user()->id;
        $label->save();

        $data = array(
            'status' => 'success',
            'code' => '200',
            'message' => 'Label created',
            'label' => $label
        ); 

        return response()->json($data);
    }

    public function update( Request $request, Label $label ){

        $validator = Validator::make($request->all(), [
            'content' => 'string|required',
        ]);


        if($validator->fails()){
            $data = array(
                'status' => 'error',
                'code' => '400',
                'message' => 'Error to update label',
                'error' => $validator->errors()
            );

        }else{

            if ($request->input('content')) {
                $label->content = $request->input('content');
            }
        
            $res = $label->save();
    
            if ($res) {
                $data = array(
                    'status' => 'success',
                    'code' => '200',
                    'message' => 'LABEL UPDATED',
                    'label' => $label
                );
            }
        }

        return response()->json($data);

    }

    public function destroy(Label $labelD){
        $res = $labelD->delete();
        return response()->json($res);
    }
    
    public function searchByLabel(int $id){

        $notes = Label::with('notes')->where('id',$id)->get();

    
        
        return response()->json($notes);


    }

    public function setLabelToNote(Request $request, int $note_id){
        
        $label_id = $request->label_id;
        $query = Labelnote::where([
            ['label_id', '=', $label_id],
            ['note_id', '=', $note_id]       
        ])->first();

        if($query){
            $data = array(
                'status' => 'error',
                'code' => '400',
                'message' => 'Label exists in this note',
                'labelNote' => $query
            ); 
    
        }else{
            $labelnote = new Labelnote();
            $labelnote->note_id =$note_id;
            $labelnote->label_id =$label_id;
            $labelnote->save();

            $data = array(
                'status' => 'success',
                'code' => '200',
                'message' => 'labelNote',
                'labelNote' => $query
            ); 
        }
             

    
        return response()->json($data);
    }


    public function getLabelsByNote(int $note_id){
        
        $labelsNote = Labelnote::where('note_id',$note_id)->with('label')->get();
        
        $data = array(
            'status' => 'success',
            'code' => '400',
            'message' => 'labelNote',
            'labelsNote' => $labelsNote
        ); 
    
        return response()->json($data);
    }

}


/*

SELECT * from notes inner join labels ON labels.user_id = notes.user_id
inner join labelnotes ON labelnotes.label_id = labels.id
*/