<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cobacontroller extends Controller
{
    public function index(){
        
        return view('about',[
            //"siswane" =>DB::table('siswa')->get(),
            "siswa" => Siswa::paginate(5),
            'nama' => "Kang Ishom"
        ]);
      
    }

    public function create(){
        return view('addform');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'namasiswa' => 'required',
            'nohp' => 'required|numeric',
        ]);

        Siswa::insert([
            'namasiswa' => $validated['namasiswa'],
            'nohp' => $validated['nohp']
        ]);
        return  redirect('/coba');
    }

    public function edit($id){
        $santri = Siswa::where('idsiswa',$id)->first();
       return view('editform',['santri' => $santri]);
    }

    public function update(Request $request){
        $validated = $request->validate([
            'namasiswa' => 'required',
            'nohp' => 'required|numeric',
        ]);

        Siswa::where('idsiswa',$request->id)
              ->update([
                'namasiswa' => $validated['namasiswa'],
                'nohp' => $validated['nohp']
        ]);

        return  redirect('/coba');
    }

    public function delete($id){
        Siswa::where('idsiswa',$id)->delete();
        return  redirect('/coba');
    }
}
