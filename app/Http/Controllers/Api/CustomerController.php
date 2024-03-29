<?php

namespace App\Http\Controllers\Api;

use App\Models\Communes;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CustomerResource;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function store(Request $request){
        Log::channel('api')->info('New customer registration request from: '.$request->ip());
        $request->validate([
            'dni'=>'required|unique:customers',
            'id_reg'=>'required|integer',
            'id_com'=>'required|integer',
            'email'=>'required|email|unique:customers',
            'name'=>'required',
            'last_name'=>'required',
            'address'=>'required',
        ]);

        $commune=Communes::findOrFail($request->id_com);

        if($commune->id_reg != $request->id_reg){
            return response()->json([
                'message'=> 'The indicated commune does not belong to that region'
            ], Response::HTTP_OK);
        }

        $customer= new Customer();
        $customer->dni=$request->dni;
        $customer->id_reg=$request->id_reg;
        $customer->id_com=$request->id_com;
        $customer->email=$request->email;
        $customer->name=$request->name;
        $customer->last_name=$request->last_name;
        $customer->address=$request->address;
        $customer->date_reg=now();
        $customer->save();

        log::channel('api')->debug('Server response', [
            'Status_code'=>Response::HTTP_CREATED,
            'Content'=>$customer
        ]);
    

        return response()->json([
            "message"=>"The customer has been successfully registered"
        ], Response::HTTP_CREATED);
    }

    public function show(Request $request)  {
        Log::channel('api')->info('New customer show request from: '.$request->ip());
        $request->validate([
            'search'=> 'required'
        ]);

        $query=Customer::with(['regions','communes'])
        ->where('status', 'A')
        ->where('dni', $request->search)
        ->orWhere('email', $request->search)
        ->get();

        $customer=CustomerResource::collection($query);

        log::channel('api')->debug('Server response', [
            'Status_code'=>Response::HTTP_OK,
            'Content'=>$customer
        ]);

        return response()->json([
            "customerData"=>$customer
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request){
        Log::channel('api')->info('New customer destroy request from: '.$request->ip());
        
        $request->validate([
            'dni'=> 'required'
        ]);

        $customer=Customer::findOrFail($request->dni);

        $customer->status='Trash';
        $customer->save();

        log::channel('api')->debug('Server response', [
            'Status_code'=>Response::HTTP_OK,
            'Content'=>$customer
        ]);

        return response()->json([
            "customerData"=>$customer
        ], Response::HTTP_OK);
    }

}
