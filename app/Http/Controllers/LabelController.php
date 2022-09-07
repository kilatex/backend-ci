<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Labelnote;
use App\Models\User;
use Illuminate\Http\Request;
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
            'content' => 'string|required|unique:labels',
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
    
    public function searchByLabel(Label $label){
        $search = Labelnote::where('label_id', $label->id)->with('note')->get(); 
        if($search){
            $data = array(
                'status' => 'success',
                'code' => '200',
                'message' => 'NOTES SEARCHED',
                'search' => $search
            );
        }else{
            $data = array(
                'status' => 'error',
                'code' => '400',
                'message' => 'ERROR TO SEARCH BY LAVEL',
                'search' => $search
            ); 
        }
        return response()->json($data);


    }

    public function setLabeltoNote(Request $request){
        $validator =  Validator::make($request->all(), [
            'note_id' => 'required',
            'label_id' => 'required'
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

        $labelNote = new Labelnote();
        $labelNote->note_id = $request->note_id;
        $labelNote->label_id = $request->label_id;

        $labelNote->save();
        $data = array(
            'status' => 'success',
            'code' => '400',
            'message' => 'labelNote',
            'labelNote' => $labelNote
        ); 
    
        return response()->json($data);
    }


}
