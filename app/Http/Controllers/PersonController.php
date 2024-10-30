<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'avatar' => 'nullable'
        ]);

        try {
            Person::create([
                'name' => $validated['name'],
                'description' => $validated['description']
            ]);

            return to_route('figure.index');

        } catch (\Exception $error) {
            return redirect()->back()->withErrors([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getData(): JsonResponse
    {
        try {

            $data = Person::get();

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan data.' . $error->getMessage()
            ], 500);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {

            Person::find($id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data.',
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan data.' . $error->getMessage()
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @param Person $person
     * @return RedirectResponse
     */
    public function update(Request $request, Person $person): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'avatar' => 'nullable',
        ]);

        try {

            $person->name = $validated['name'];
            $person->description = $validated['description'];
            //$person->avatar = $validated['avatar'];
            $person->save();

            return to_route('figure.index');

        } catch (\Exception $error) {
            return redirect()->back()->withErrors([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
        }
    }
}
