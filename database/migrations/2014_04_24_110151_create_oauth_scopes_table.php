<?php

/*
 * This file is part of OAuth 2.0 Laravel.
 *
 * (c) Luca Degasperi <packages@lucadegasperi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Models\ClientModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * This is the create oauth scopes table migration class.
 *
 * @author Luca Degasperi <packages@lucadegasperi.com>
 */
class CreateOauthScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $client = ClientModel::find(array_get(DB::select('select database() as database_name'), '0.database_name'));
        
        if(!isset($client)) {
            $this->migrate('tenant-utf8');
        } else {
            $this->migrate($client->canonical_name);
        }
    }

    public function migrate($connection) {
        Schema::connection($connection)->create('oauth_scopes', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('description');

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
        Schema::drop('oauth_scopes');
    }
}
