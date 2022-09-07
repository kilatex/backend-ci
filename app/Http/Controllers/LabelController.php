<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Labelnote;
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

        if($validator->fails()){
            $data = array(
                'status' => 'error',
                'code' => '400',
                'message' => 'Error to create note',
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
        $notes = DB::table('notes')
            ->join('labelnotes', 'labelnotes.note_id', '=', 'notes.id')
            ->where('labelnotes.label_id', $id)
            ->get();
    
        
        return response()->json($notes);


    }

    public function setLabelstoNote(Request $request, int $note_id){
        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json,true);

        for ($i=0; $i < count($params_array); $i++) { 
            $labelNote = new Labelnote();
            $labelNote->label_id =  $params_array[$i];
            $labelNote->note_id = $note_id;

            $labelNote->save();

        }
               

        $data = array(
            'status' => 'success',
            'code' => '400',
            'message' => 'labelNote',
            'labelNote' => $params_array
        ); 
    
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
