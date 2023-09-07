<?php

namespace App\Http\Controllers;

trait ApiResponse{

    public function apiResponse($data=null,$message=null,$status=null){
        $array=[
            'data'=>$data,
            'message'=>$message,
            'status'=>$status
        ];
        return response($array);
    }

    public function  successResponse($data ,$message,$code=200)  {
        return response()->json([
            'status'=>true,
            'message'=>$message,
            'data'=>$data
        ],$code);

    }
    public function  errorResponse($message,$code=400)  {
        return response()->json([
            'status'=>false,
            'message'=>$message,
        ],$code);

    }

    public function notfound($code=404){
        return $this->response()->json([
            'status'=>false,
            'message'=>'this page not found',
        ],$code=404);
    }
    public function unauthorized($code=401){
        return $this->response()->json([
            'status'=>false,
            'message'=>' unauthorized',
        ],$code);
    }
    public function requestTimeOut($code=406){
        return $this->response()->json([
            'status'=>false,
            'message'=>' requestTimeOut',
        ],$code);
    }
}
