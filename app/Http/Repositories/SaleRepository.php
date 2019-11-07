<?php

namespace App\Http\Repositories;

use DB;
use Validator;

class SaleRepository
{



    public function store($request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id-product' => 'required',
                'prod-quantity' => 'required',
                'prod-price' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect('home')
                    ->withErrors($validator)
                    ->withInput();
            }

            //Arrays que se reciben desde la vista (ids y cantidades)
            $idProduct = $request->input('id-product');
            $prodQuantity = $request->input('prod-quantity');
            $prodPrice = $request->input('prod-price');

            //Variables que guardarán una cadena de texto de los ids y cantidades.
            $arrayId = '';
            $arrayQty = '';
            $arrayPrice = '';

            if (sizeof($idProduct) == sizeof($prodQuantity)) { //Si las tres cadenas son iguales 
                if (sizeof($idProduct) == sizeof($prodPrice)) {
                    if (sizeof($prodQuantity) == sizeof($prodPrice)) {
                        
                         //Me manda un array de ids y las paso a string concatenandolas (1;2;3;4)
                        foreach ($idProduct as $id) { //Recibo [1,2,3]
                            $arrayId = $arrayId . ';' . $id; //Se vuelve '1;2;3' (string)
                        }

                        
                        foreach ($prodQuantity as $prodQty) { //Recibo [20;30;40]
                            $arrayQty = $arrayQty . ';' . $prodQty;
                        }

                        foreach ($prodPrice as $prodPce) { //Recibo [40;50;60]
                            $arrayPrice = $arrayPrice . ';' . $prodPce; 
                        }

                        $response = DB::select("CALL demo_sp_insert_product_has_sale(?,?,?)", [
                            $arrayId,
                            $arrayQty,
                            $arrayPrice //En la db estos strings se separan segun el ';' y se insertan.
                        ]);

                        if ($response[0]->response) {
                            return redirect('store_house')->with('successMsg', 'Venta registrada.');
                        } else {
                            return redirect('store_house')->with('errorMsg', 'Error al registrar la venta.');
                        }
                    }
                }
            } else {

                return redirect('store_house')->with('errorMsg', 'Error al registrar la venta.');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            return view('error.internalServelError');
        }
    }


    public function info($request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'id-product' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect('home')
                    ->withErrors($validator)
                    ->withInput();
            }

            $idProduct = $request->input('id-product');

            $response = DB::select("CALL sp_get_sale(?)", [
                $idProduct
            ]);

            if (sizeof($response) > 0) {
                $adminData = array([
                    'name' => $response[0]->nameAdministrador
                    /* Trae los datos del administrador que se repiten en un join*/
                ]);

                return view('sale')->with([
                    'sale' => json_encode($response),
                    'admin' => json_encode($adminData)
                ]);
            } else {
                return view('sale')->with(['sale' => [], 'admin' => []]);
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            return view('error.internalServelError');
        }
    }


    /*
            1. Funcion para mostrar todas las ventas con sus productos respectivos por id de venta
            (idSale)

            //Jalar datos repetidos de la venta del join entre product_has_sale y sale, para mostrar 
            toda una venta //

            if(sizeof($response) > 0){
                    $obj = array([
                        "name" => $response[0]->nameAdministrator
                    ]);

                    return view()->with(['obj'=> json_encode($obj)], 'msg'); //retorna objeto
                }
            
            2.  


            */
}
