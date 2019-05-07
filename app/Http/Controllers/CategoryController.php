<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller {

    public function __construct() {
        $this->middleware('api.auth', ['except' => ['index', 'show']]);
    }

    public function pruebas(Request $request) {
        return "Acción de pruebas de CATEGORY-CONTROLLER";
    }

    public function index() {
        $categories = Category::all();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'categories' => $categories
        );
        return response()->json($data, $data['code']);
    }

    public function show($id) {
        $category = Category::find($id);
        if (is_object($category)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'categories' => $category
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'success',
                'message' => "La categoria no existe."
            );
        }
        return response()->json($data, $data['code']);
    }

    public function store(Request $request) {
        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if (!empty($params_array)) {
            // Validar los datos
            $validate = \Validator::make($params_array, [
                        'name' => 'required'
            ]);

            // Guardar la categoria
            if ($validate->fails()) {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha podido guardar la categoria.'
                );
            } else {
                $category = new Category();
                $category->name = $params_array['name'];
                $category->save();
                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'category' => $category
                );
            }
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'No se ha enviado ninguna categoria.'
            );
        }
        // Devolver resultado
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request) {
        // Recoger datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            // Validar los datos
            $validate = \Validator($params_array, [
                'name' => 'required'
            ]);

            // Quitar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['create_at']);

            if ($validate->fails()) {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha podido actualizar la categoria.'
                );
            } else {
                // Actualizar el registro(categoria)
                $category = Category::where('id', $id)->update($params_array);
                // Devolver respuesta
                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'category' => $params_array
                );
            }
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'No se ha enviado ninguna categoria.'
            );
        }
        return response()->json($data, $data['code']);
    }

}
