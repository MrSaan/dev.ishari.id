<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudioListResource;
use App\Models\Audio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{

    public function getData($muhudId)
    {
        try {
            $data = Audio::where('arabic_text_id', '=', $muhudId)
                ->with('pimpinan')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => AudioListResource::collection($data)
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
        }
    }
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'personId' => 'required',
            'textId' => 'required',
            'audio' => 'required',
            'description' => 'nullable',
        ]);

        try {

            $filePath = null;

            if ($request->hasFile('audio')) {
                $file = $request->file('audio');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('public/uploads', $filename);
            }

            Audio::create([
                'arabic_text_id' => $validate['textId'],
                'person_id' => $validate['personId'],
                'path' => $filePath,
                'description' => $validate['description'],
            ]);

            return to_route('audio.index');

        } catch (\Exception $error) {
            return redirect()->back()->withErrors([
                'status' => 'error',
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {

            $data = Audio::find($id);

            Storage::delete($data->path);
            $data->delete();

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
}
