<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoriesCreateRequest;
use App\category;
use App\categoryContent;

class CategoryController extends Controller
{
    //recuepra todas las categorias
    function getAllCategories()
    {
        $res = categoryContent::select("categories.id", "category_contents.name", "category_contents.url", "languages.name as idioma", "category_contents.orden")
            ->join('categories', 'categories.id', '=', 'category_contents.category_id')
            ->join('languages', 'languages.id', '=', 'category_contents.language_id')
            // ->where('categories.active', 1)
            // ->where('languages.id', 1)
            // ->orderBy('category_contents.name', 'ASC')
            ->orderBy('category_contents.orden', 'ASC')
            ->get();
        //->toSql();
        return $res;
    }

    //agrega nuevas categories
    function addCategories(CategoriesCreateRequest $request)
    {
        //valido si existe la clave enviada para recuperar el id o caso contrario insertar uno nuevo
        $res = category::where('clave', '=', $request->clave)->first();
        if ($res === null) {
            $categoria = new category;
            $categoria->clave = $request->clave;
            $categoria->save();
            //ultimo id insertado
            $ultimoID = $categoria->id;
        } else {
            $ultimoID = $res->id;
        }

        $catContenido = new categoryContent;
        $catContenido->name = $request->name;
        $catContenido->title = $request->title;
        $catContenido->url = $request->url;
        $catContenido->description = $request->description;
        $catContenido->description_footer = $request->description_footer;
        $catContenido->meta_title = $request->meta_title;
        $catContenido->meta_description = $request->meta_description;
        $catContenido->meta_keywords = $request->meta_keywords;
        $catContenido->orden = $request->order;
        $catContenido->category_id = $ultimoID;
        $catContenido->language_id = $request->idioma;
        $catContenido->save();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //update de categorias
    public function updateCategories(CategoriesCreateRequest $request)
    {
        //esto es por si por error solo dieron de alta un solo idioma y quieren agregar el nuevo registros
        //desde la ediicion del registro actual
        //se valida si existe el registro por medio del id de la categoria y el id del idioma
        //si no existe entonces se recupera la clave de la categoria y se pisa la clave que se manda desde vue
        //para mandarla a la funcion del agregado
        $res = categoryContent::select("*")
            ->where('category_id', $request->id)
            ->where('language_id', $request->idioma)
            ->get();
        if (count($res) === 0) {
            $info = category::where("id", $request->id)->first();
            $request->clave = $info->clave;
            $this->addCategories($request);
        } else {

            categoryContent::where('category_id', $request->id)
                ->where('language_id', $request->idioma)
                ->update([
                    "name" => $request->name,
                    "title" => $request->title,
                    "url" => $request->url,
                    "description" => $request->description,
                    "description_footer" => $request->description_footer,
                    "meta_title" => $request->meta_title,
                    "meta_description" => $request->meta_description,
                    "meta_keywords" => $request->meta_keywords,
                    "orden" => $request->order
                ]);
        }
        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //elimina categorias
    function deleteCategories(Request $request)
    {
        try {
            $cat = category::find($request->id);
            $cat->active = 0;
            $cat->save();

            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'categories not found or deleted'
            ], 404);
        }
    }

    //recupera la informacion de la categoria por idioma
    function getCategoriesInfo(Request $request)
    {
        $res = categoryContent::select("*")
            ->where('category_id', $request->id)
            ->where('language_id', $request->idioma)
            ->get();
        //->toSql();
        if (count($res) > 0) {
            return $res;
        } else {
            return response()->json([
                'message' => 'categories not found or deleted'
            ], 404);
        }
    }
}
