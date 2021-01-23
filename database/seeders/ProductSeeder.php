<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('products')->insert([
            'id' => 1,
            'product_name' => 'Curso de cakephp',
            'description' => 'Aprenda cakephp de cero a cien adquiriendo este curso virtual.',
            'cost' => 50000,
            'picture' => 'cakephp.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 2,
            'product_name' => 'Curso de codeigniter',
            'description' => 'Aprenda codeigniter de cero a cien adquiriendo este curso virtual.',
            'cost' => 135000,
            'picture' => 'codeigniter.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 3,
            'product_name' => 'Curso de css-3',
            'description' => 'Aprenda css-3 de cero a cien adquiriendo este curso virtual.',
            'cost' => 80000,
            'picture' => 'css-3.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 4,
            'product_name' => 'Curso de html-5',
            'description' => 'Aprenda html-5 de cero a cien adquiriendo este curso virtual.',
            'cost' => 50000,
            'picture' => 'html-5.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 5,
            'product_name' => 'Curso de javascript',
            'description' => 'Aprenda javascript de cero a cien adquiriendo este curso virtual.',
            'cost' => 50000,
            'picture' => 'javascript.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 6,
            'product_name' => 'Curso de jquery',
            'description' => 'Aprenda jquery de cero a cien adquiriendo este curso virtual.',
            'cost' => 255000,
            'picture' => 'jquery.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 7,
            'product_name' => 'Curso de laravel',
            'description' => 'Aprenda laravel de cero a cien adquiriendo este curso virtual.',
            'cost' => 180000,
            'picture' => 'laravel.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 8,
            'product_name' => 'Curso de linux',
            'description' => 'Aprenda linux de cero a cien adquiriendo este curso virtual.',
            'cost' => 920000,
            'picture' => 'linux.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 9,
            'product_name' => 'Curso de mysql',
            'description' => 'Aprenda mysql de cero a cien adquiriendo este curso virtual.',
            'cost' => 75000,
            'picture' => 'mysql.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 10,
            'product_name' => 'Curso de oracle',
            'description' => 'Aprenda oracle de cero a cien adquiriendo este curso virtual.',
            'cost' => 200000,
            'picture' => 'oracle.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 11,
            'product_name' => 'Curso de php',
            'description' => 'Aprenda php de cero a cien adquiriendo este curso virtual.',
            'cost' => 120000,
            'picture' => 'php.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 12,
            'product_name' => 'Curso de postgresql',
            'description' => 'Aprenda postgresql de cero a cien adquiriendo este curso virtual.',
            'cost' => 60000,
            'picture' => 'postgresql.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 13,
            'product_name' => 'Curso de react',
            'description' => 'Aprenda react de cero a cien adquiriendo este curso virtual.',
            'cost' => 135500,
            'picture' => 'react.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 14,
            'product_name' => 'Curso de redhat',
            'description' => 'Aprenda redhat de cero a cien adquiriendo este curso virtual.',
            'cost' => 140000,
            'picture' => 'redhat.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 15,
            'product_name' => 'Curso de vuejs',
            'description' => 'Aprenda vuejs de cero a cien adquiriendo este curso virtual.',
            'cost' => 100000,
            'picture' => 'vuejs.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        \DB::table('products')->insert([
            'id' => 16,
            'product_name' => 'Curso de yii',
            'description' => 'Aprenda yii de cero a cien adquiriendo este curso virtual.',
            'cost' => 20000,
            'picture' => 'yii.png',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }
}
