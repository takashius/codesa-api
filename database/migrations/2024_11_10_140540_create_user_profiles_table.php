<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('person_id');
            $table->smallInteger('doc_tipo')->unsigned();
            $table->integer('doc_pais')->unsigned();
            $table->char('doc_num', 20);
            $table->string('nombre', 90);
            $table->string('apellido', 90);
            $table->smallInteger('sexo')->unsigned();
            $table->date('f_nacimiento');
            $table->integer('nacionalidad')->unsigned();
            $table->string('direccion', 45)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->integer('institucion')->unsigned()->nullable();
            $table->string('curso', 15)->nullable();
            $table->integer('desde')->unsigned()->nullable();
            $table->integer('hasta')->unsigned()->nullable();
            $table->smallInteger('categoria')->unsigned()->default(1);
            $table->integer('agencia')->unsigned()->nullable();
            $table->smallInteger('agente')->unsigned()->nullable();
            $table->date('f_constancia')->nullable();
            $table->tinyInteger('tipo_funcionario')->unsigned()->default(0);
            $table->integer('funcionario')->unsigned()->nullable();
            $table->date('f_licencia_conducir')->nullable();
            $table->string('cargo', 5)->nullable();
            $table->text('observaciones')->nullable();
            $table->string('kms', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
