<?php

namespace App\Http\Controllers;

use App\Http\Resources\MuhudListResource;
use App\Models\Muhud;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MuhudController extends Controller
{

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string',
            'position' => 'required|integer',
            'nameArabic' => 'nullable|string',
            'translateId' => 'nullable',
            'transliteration' => 'nullable',
        ]);

        try {
            Muhud::create([
                'name' => $request->name,
                'position' => $request->position,
                'name_arabic' => $request->nameArabic,
                'translate_id' => $request->translateId,
                'transliteration' => $request->transliteration,
            ]);

            return to_route('muhud.index');

        } catch (\Exception $error) {
            return redirect()->back()->withErrors([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
        }
    }

    /**
     * @param Request $request
     * @param Muhud $muhud
     * @return RedirectResponse
     */
    public function update(Request $request, Muhud $muhud): RedirectResponse
    {
        try {
            $requestValidate = $request->validate([
                'id' => 'required',
                'name' => 'required',
                'position' => 'required',
                'nameArabic' => 'nullable',
                'translateId' => 'nullable',
                'transliteration' => 'nullable',
            ]);

            $muhud->name = $requestValidate['name'];
            $muhud->position = $requestValidate['position'];
            $muhud->name_arabic = $requestValidate['nameArabic'];
            $muhud->translate_id = $requestValidate['translateId'];
            $muhud->transliteration = $requestValidate['transliteration'];
            $muhud->save();

            return to_route('muhud.index');

        } catch (\Exception $error) {
            return redirect()->back()->withErrors([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {

            Muhud::find($id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus record.'
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus record, Hubungi Amar.. aplikasine error!'
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getData(): JsonResponse
    {
        try {
            $data = Muhud::get();
            return response()->json([
                'status' => 'success',
                'data' => MuhudListResource::collection($data),
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
        }
    }
}
