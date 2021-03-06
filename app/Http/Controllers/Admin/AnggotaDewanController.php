<?php

namespace App\Http\Controllers\Admin;

use App\Anggota;
use App\City;
use App\Http\Controllers\Controller;
use App\Jenjang;
use App\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AnggotaDewanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anggotaDewan = Anggota::orderBy('created_at', 'desc')->paginate(15);
        // $jenjang = Jenjang::where('id', $anggotaDewan->jenjang_id)->get();
        // dd($anggotaDewan);
        $jenjang = Jenjang::all();
        $province = Province::all();
        $city = City::all();
        return view('admins.dewans.index')->with('anggotaDewan', $anggotaDewan)->with('jenjang', $jenjang)->with('province', $province)->with('city', $city);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = Province::all();
        $jenjang = Jenjang::all();
        return view('admins.dewans.create')->with('jenjang', $jenjang)->with('provinces', $provinces);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'nama' => 'required|max:255',
                'jenjang_id' => 'required|exists:jenjangs,id',
                'dapil' => 'nullable',
                'province_id' => 'nullable|required_if:jenjang_id,2',
                'city_id' => 'nullable|required_if:jenjang_id,3',
                'foto' => 'required|image|mimes:jpg,jpeg,png'
            ]
        );

        if ($request->hasFile('foto')) {
            $fileNameWithExtension = $request->file('foto')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('foto')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $path = $request->file('foto')->storeAs('public/photos', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $anggota = new Anggota();
        $anggota->nama = $request->input('nama');
        $anggota->jenjang_id = $request->input('jenjang_id');
        $anggota->province_id = $request->input('province_id');
        $anggota->city_id = $request->input('city_id');
        $anggota->foto = $fileNameToStore;
        if ($request->jenjang_id == 1) {
            $anggota->dapil = 0;
            $anggota->save();
        }

        if ($request->jenjang_id == 2) {
            $anggota->dapil = 1;
            $anggota->save();
        }

        if ($request->jenjang_id == 3) {
            $anggota->dapil = 2;
            $anggota->save();
        }
        // dd($anggota->dapil);
        $anggota->save();
        $jenjang = Jenjang::find($request->jenjang_id);
        $anggota->jenjangs()->attach($jenjang);

        Session::put('success', 'Data Berhasil Ditambah');
        return redirect(route('index-dewan'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = Anggota::find($id);
        $jenjang = Jenjang::all();
        $provinces = Province::all();
        return view('admins.dewans.edit')->with('edit', $edit)->with('jenjang', $jenjang)->with('provinces', $provinces);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'nama' => 'max:255',
                'jenjang_id' => 'exists:jenjangs,id',
                'dapil' => 'nullable',
                'province_id' => 'nullable|required_if:jenjang_id,2',
                'city_id' => 'nullable|required_if:jenjang_id,3',
                'foto' => 'image|mimes:jpg,jpeg,png'
            ]
        );
        $anggota = Anggota::find($id);
        $anggota->nama = $request->nama;
        $anggota->jenjang_id = $request->jenjang_id;
        $anggota->province_id = $request->province_id;
        $anggota->city_id = $request->city_id;
        $anggota->dapil = $request->dapil;
        if ($request->jenjang_id == 1) {
            $anggota->dapil = 0;
            $anggota->update();
        }

        if ($request->jenjang_id == 2) {
            $anggota->dapil = 1;
            $anggota->update();
        }

        if ($request->jenjang_id == 3) {
            $anggota->dapil = 2;
            $anggota->update();
        }
        
        if ($request->hasFile('foto')) {
            $fileNameWithExtension = $request->file('foto')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('foto')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $path = $request->file('foto')->storeAs('public/photos', $fileNameToStore);
            $anggota->foto = $fileNameToStore;
            
            $select_old_foto_name = DB::table('anggotas')->where('id', $request->id)->first();
            
            if ($select_old_foto_name != 'noimage.jpg') {
                Storage::delete('public/photos', $select_old_foto_name->foto);
            }
        }

        $anggota->update();
        $jenjang = Jenjang::find($request->jenjang_id);
        $anggota->jenjangs()->attach($jenjang);

        Session::put('success', 'Data Berhasil Diperbaharui');
        return redirect(route('index-dewan'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $anggota = Anggota::find($id);
        $anggota->delete();

        Session::put('success', 'Data Berhasil Dihapus');
        return redirect(route('index-dewan'));
    }
}
