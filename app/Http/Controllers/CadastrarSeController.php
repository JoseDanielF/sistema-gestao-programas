<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CadastreSeStoreFormRequest;
use App\Models\Curso;
use App\Models\Servidor;
use App\Models\Tipo_servidor;
use App\Models\Orientador;
use App\Models\Aluno;
use Illuminate\Support\Facades\Hash;

class CadastrarSeController extends Controller
{
    public function cadastrarSe(){
        $cursos = Curso::all();
        $tipo_servidores = Tipo_servidor::all();
        return view('CadastrarSe.cadastrarSe', compact('cursos', 'tipo_servidores'));
    }

    public function store(CadastreSeStoreFormRequest $request)
    {
        if ($request->tipoUser == "servidor"){
            $servidor = Servidor::Create([
                'cpf' => $request->input('cpf'),
                'id_tipo_servidor' => (int) $request->input('tipo_servidor')
            ]);

            if(
                $servidor->user()->create([
                    'name' => $request->input('nome'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('senha'))
                ])
            ){
                return redirect('/')->with('sucesso', 'Usuário cadastrado com sucesso.');

            } else {
                return redirect()->back()->withErrors( "Falha ao cadastrar Usuário. tente novamente mais tarde." );
            }

        } else if ($request->tipoUser == "orientador"){
            $orientador = new Orientador();
            $orientador->cpf = $request->cpf;
            $orientador->matricula = $request->matriculaOrientador;

            if ($orientador->save()){

                if (
                    $orientador->user()->create([
                        'name' => $request->nome,
                        'email' => $request->email,
                        'password' => Hash::make($request->senha)
                    ])->givePermissionTo('orientador')
                ){

                    return redirect('/')->with('sucesso', 'Usuário cadastrado com sucesso.');

                } else {
                    return redirect()->back()->withErrors( "Falha ao cadastrar usuário. tente novamente mais tarde." );
                }
            }else{
                return redirect()->back()->withErrors( "Falha ao cadastrar usuário. tente novamente mais tarde." );
            }

        } else {
            $aluno = new Aluno();
            $aluno->cpf = $request->cpf;
            $aluno->id_curso = $request->curso;
            $aluno->semestre_entrada = $request->sementreEntradaAluno;

            if ($aluno->save()){

                if (
                    $aluno->user()->create([
                        'name' => $request->nome,
                        'email' => $request->email,
                        'password' => Hash::make($request->senha)
                    ])->givePermissionTo('aluno')
                ){
                    return redirect('/')->with('sucesso', 'Usuário cadastrado com sucesso.');

                } else {
                    return redirect()->back()->withErrors( "Falha ao cadastrar usuário. tente novamente mais tarde." );
                }
            }else{
                return redirect()->back()->withErrors( "Falha ao cadastrar usuário. tente novamente mais tarde." );
            }
        }
    }
}