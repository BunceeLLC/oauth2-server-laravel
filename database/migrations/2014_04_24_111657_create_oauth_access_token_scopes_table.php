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
 * This is the create oauth access token scopes table migration class.
 *
 * @author Luca Degasperi <packages@lucadegasperi.com>
 */
class CreateOauthAccessTokenScopesTable extends Migration
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

    public function migrate($connection)
    {
        Schema::connection($connection)->create('oauth_access_token_scopes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_token_id', 40);
            $table->string('scope_id', 40);

            $table->timestamps();

            $table->index('access_token_id');
            $table->index('scope_id');

            $table->foreign('access_token_id')
                  ->references('id')->on('oauth_access_tokens')
                  ->onDelete('cascade');

            $table->foreign('scope_id')
                  ->references('id')->on('oauth_scopes')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_access_token_scopes', function (Blueprint $table) {
            $table->dropForeign('oauth_access_token_scopes_scope_id_foreign');
            $table->dropForeign('oauth_access_token_scopes_access_token_id_foreign');
        });
        Schema::drop('oauth_access_token_scopes');
    }
}
