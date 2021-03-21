<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayStatus = ['pending','complete'];
        $arrayType = ['expense','income'];
        $arrayName = ['Pembelian Barang','Pengadaaan barang','Pembayaran dari klien'];

        for($x=1;$x<10;$x++){
            $name = \Str::random(4);
            DB::table('transactions')->insert([
                'name' => $arrayName[array_rand($arrayName)].' '.$name,
                'company_id'=> rand(1,2),
                'user_id'=> rand(1,2),
                'status'=> $arrayStatus[array_rand($arrayStatus)],
                'type'=> $arrayType[array_rand($arrayType)],
                'amount'=> rand (2000*10, 1000000*10) / 10,
                'comment'=> \Str::random(50),
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s')
            ]);
        }

    }
}
