<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use Validator;

class BukuController extends Controller
{
    public function getAllBuku() {
        return Buku::all();
    }

    public function getBukuById($id) {
        $buku = Buku::find($id);
        return $buku;
    }

    // cara lain untuk get buku
    public function getBuku($id = null) {
        return $id ? Buku::find($id) : Buku::all();
    }

    public function searchBuku($keyword) {
        try {
            $buku = Buku::where('id', $keyword)
                ->orWhere('namaBuku', 'like', '%' . $keyword . '%')
                ->orWhere('penerbit', 'like', '%' . $keyword . '%')
                ->get();
            
            if (count($buku)) {
                return response()->json([
                    'buku ditemukan',
                    $buku
                ], 200);
            } else {
                return response()->json([
                    'buku tidak ditemukan'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'gagal mencari data buku',
                'error' => $e->print
            ], 400);
        }
    }

    public function addBuku(Request $request) {
        try {
            Buku::create([
                'namaBuku' => $request->namaBuku,
                'penerbit' => $request->penerbit
            ]);

            return response()->json([
                'status' => 'berhasil menambah data buku',
                'namaBuku' => $request->namaBuku,
                'penerbit' => $request->penerbit
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'gagal menambah data buku',
                'error' => $e->print
            ], 400);
        }
    }

    public function updateBuku(Request $request, $id) {
        try {
            // $idBuku = Buku::find($request->id);

            // $idBuku->namaBuku = $request->namaBuku;
            // $idBuku->penerbit = $request->penerbit;
            
            // $result = $idBuku->save();

            Buku::where('id', $id)
            ->update([
                'namaBuku' => $request->namaBuku,
                'penerbit' => $request->penerbit
            ]);

            return response()->json([
                'status' => 'berhasil mengubah data buku',
                'namaBuku' => $request->namaBuku,
                'penerbit' => $request->penerbit
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'gagal mengubah data buku',
                'error' => $e->print
            ], 400);
        }
    }

    public function deleteBuku($id) {
        $buku = Buku::find($id);

        try {
            if ($buku != null) {
                $buku->delete();
                return response()->json([
                    'status' => 'id buku: ' . $id . ' berhasil dihapus'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'id buku: ' . $id . ' tidak ditemukan'
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'gagal menghapus data buku',
                'error' => $e->print
            ], 400);
        }
    }

    public function validateBuku(Request $request) {
        $rules = [
            "namaBuku" => "required|min:2|max:10",
            "penerbit" => "required|min:2|max:10"
        ];

        $validator = Validator::make(
            $request->all(), $rules
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                401
            );
        } else {
            Buku::create([
                'namaBuku' => $request->namaBuku,
                'penerbit' => $request->penerbit
            ]);

            return response()->json([
                'status' => 'berhasil menambah data buku',
                'namaBuku' => $request->namaBuku,
                'penerbit' => $request->penerbit
            ], 200);
        }
    }

    public function uploadFoto(Request $request) {
        $foto = $request->file('foto');
        // $namaFoto = $request->file('foto')->getClientOriginalName();
        // Buku::create([
        //     'namaBuku' => $request->namaBuku,
        //     'penerbit' => $request->penerbit,
        //     'foto' => $namaFoto
        // ]);

        $namaFoto = $request->file('foto')->getClientOriginalName();
        $path = public_path() . '/fotoBuku/' . $namaFoto;
        Buku::create([
            'namaBuku' => $request->namaBuku,
            'penerbit' => $request->penerbit,
            'foto' => $path
        ]);

        $foto->move(public_path().'/fotoBuku', $namaFoto);
        // return $data;

        return response()->json([
            'status' => 'berhasil tambah data buku dengan foto',
            'namaBuku' => $request->namaBuku,
            'penerbit' => $request->penerbit,
            'foto' => $path
        ], 200);
    }
}
