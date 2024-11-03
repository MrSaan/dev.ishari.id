<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShalawatListResource;
use App\Models\Shalawat;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShalawatController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function getData(): JsonResponse
    {
        try {

            $data = Shalawat::with('muhud')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => ShalawatListResource::collection($data)
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'text' => 'required|string',
            'muhudId' => 'required',
            'position' => 'required',
            'isDiwan' => 'required',
            'isDiba' => 'required',
            'translateId' => 'nullable',
            'transliteration' => 'nullable',
        ]);

        try {
            $user = auth()->user();

            Shalawat::create([
                'muhud_id' => $request->muhudId,
                'position' => $request->position,
                'text' => $request->text,
                'translate_id' => $request->translateId,
                'transliteration' => $request->transliteration,
                'is_diwan' => $request->isDiwan ? 'Y' : 'N',
                'is_diba' => $request->isDiba ? 'Y' : 'N',
                'created_by' => $user->name ?? null,
                'updated_by' => $user->name ?? null,
            ]);

            return to_route('shalawat.index');

        } catch (\Exception $error) {
            return redirect()->back()->withErrors([
                'status' => 'error',
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Shalawat $shalawat)
    {
        try {
            $requestValidate = $request->validate([
                'text' => 'required|string',
                'muhudId' => 'required',
                'position' => 'required',
                'isDiwan' => 'required',
                'isDiba' => 'required',
                'translateId' => 'nullable',
                'transliteration' => 'nullable',
            ]);

            $user = auth()->user();

            $shalawat->text = $requestValidate['text'];
            $shalawat->muhud_id = $requestValidate['muhudId'];
            $shalawat->position = $requestValidate['position'];
            $shalawat->is_diwan = $requestValidate['isDiwan'] ? 'Y' : 'N';
            $shalawat->is_diba = $requestValidate['isDiba'] ? 'Y' : 'N';

            $shalawat->translate_id = $requestValidate['translateId'];
            $shalawat->transliteration = $requestValidate['transliteration'];
            $shalawat->updated_by = $user->name ?? null;
            $shalawat->save();

            return to_route('shalawat.index');

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

            Shalawat::find($id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus record.'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus record, Hubungi Amar.. aplikasine error!'
            ], 500);
        }
    }

    /**
     * @param String $name
     * @return JsonResponse
     */
    public function getShalawatMuhud(string $name): JsonResponse
    {
        try {

            $data = Shalawat::whereHas('muhud', function (Builder $query) use ($name) {
                    $query->where('name', '=', $name);
                })
                ->with('audios')
                ->orderBy('position', 'asc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => ShalawatListResource::collection($data)
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan data shalawat, Hubungi Amar.. aplikasine error! ' . $error->getMessage()
            ], 500);
        }
    }
}
